<?php

namespace App\Http\Controllers\Api\v1;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Resources\AbsensiResource;
use App\Http\Resources\UserResource;
use App\Models\Absensi;
use App\Models\AbsensiDetail;
use App\Models\Cuti;
use App\Models\HariLibur;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $rules = [
            'nip' => 'required',
            'password' => 'required',
        ];

        $messages = [
            'required' => 'Silahkan isi NIP dan Password anda',
        ];

        try {
            $validation = Validator::make($request->all(), $rules, $messages);

            if ($validation->fails()) {
                return ResponseFormatter::error([
                    'message' => $validation->errors()->first(),
                ], 'Authentication Failed', 400);
            }

            if (!Auth::attempt(['nip' => $request->nip, 'password' => $request->password])) {
                return ResponseFormatter::error([
                    'message' => 'NIP atau password yang anda masukkan tidak cocok'
                ], 'Authentication Failed', 400);
            }



            $user = User::where('nip', $request->nip)->first();
            if (!Hash::check($request->password, $user->password, [])) {
                throw new \Exception('Invalid Credentials');
            }


            if ($request->device_id != $user->device_id) {
                return ResponseFormatter::success([
                    'access_token' => null,
                    'token_type' => 'Bearer',
                    'user' => null,
                ], 'Device id tidak sesuai, silahkan hubungi admin');
            }

            $tokenResult = $user->createToken('authToken')->plainTextToken;
            return ResponseFormatter::success([
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => new UserResource($user),
            ], 'Authentication Success');
        } catch (\Throwable $th) {
            Log::error($th);
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $th,
            ], 'Authentication Failed', 500);
        }
    }

    public function myProfile()
    {

        $user = User::where('nip', auth()->user()->nip)->first();

        if ($user) {
            return ResponseFormatter::success([
                'user' => new UserResource($user),
            ], 'User Profile retrieved');
        }
        return ResponseFormatter::error([
            'message' => 'Invalid NIP',
        ], 'User Not Found', 404);
    }


    public function profile($nip)
    {
        $user = User::where('nip', $nip)->first();

        if ($user) {
            return ResponseFormatter::success([
                'user' => new UserResource($user),
            ], 'User Profile retrieved');
        }
        return ResponseFormatter::error([
            'message' => 'Invalid NIP',
        ], 'User Not Found', 404);
    }


    public function dashboard(Request $request)
    {
        $user = User::where('nip', auth()->user()->nip)->first();
        $now = Carbon::now();


        if (isset($request->device_id)) {
            if ($user->device_id != $request->device_id) {
                return ResponseFormatter::error([
                    'message' => 'ID perangkat tidak cocok'
                ], 'ID Perangkat tidak cocok', 401);
            }
        }


        return ResponseFormatter::success([
            'user' => new UserResource($user),
            // 'absensi' => $todayAttendance == null ? null :  new AbsensiResource($todayAttendance),
            'persentase_kehadiran' => $user->pegawai->persenKehadiran(),
        ], 'berhasil menarik data dashboard');
    }
}
