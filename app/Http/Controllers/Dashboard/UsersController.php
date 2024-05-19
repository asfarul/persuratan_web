<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class UsersController extends Controller
{


    protected $prefix = 'dashboard.user-management.users.';
    protected $myProfilePrefix = 'dashboard.myprofile.';
    protected $profilePrefix = 'dashboard.profile.';

    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        abort_if(Gate::denies('users-read'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $users = User::query();

            return DataTables::of($users)
                ->addColumn('roles', function ($user) {
                    $roles =  $user->getRoleNames();
                    $output = '';
                    foreach ($roles as $role) {
                        $color = 'light-info';
                        if ($role == 'Superadmin') $color = 'light-danger';
                        if ($role == 'Admin') $color = 'light-success';
                        $output .= '<div class="badge badge-' . $color . ' fw-bold my-1 mx-1">' . $role . '</div> ';
                    }
                    return $output;
                })
                ->addColumn('user detail', function ($user) {
                    $avatar = '<div
                class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                <a href="' . route('dashboard.users.show', $user->nip) . '">
                    <div
                        class="symbol-label fs-3 bg-light-warning text-warning">
                        ' . substr($user->nama_ptgs, 0, 1) . '</div>
                </a>
            </div>';


                    $detail = '<div class="d-flex flex-column">
            <a href="' . route('dashboard.profile.index', $user->nip) . '"
                class="text-gray-800 text-hover-primary mb-1">' .  $user->nama_ptgs . '</a>
        </div>';

                    $output = '<div class="d-flex align-items-center">' . $avatar . $detail . '</div>';
                    return $output;
                })
                ->addColumn('action', function ($item) {
                    return view($this->prefix . 'actions', compact('item'));
                })
                ->rawColumns(['roles', 'user detail'])
                ->toJson();
        }
        return view($this->prefix . 'index');
    }

    public function json()
    {
        abort_if(Gate::denies('users-read'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $users = User::orderBy('nip')->query();
        return DataTables::of($users)
            ->addColumn('roles', function ($user) {
                $roles =  $user->getRoleNames();
                $output = '';
                foreach ($roles as $role) {
                    $color = 'light-info';
                    if ($role == 'Superadmin') $color = 'light-danger';
                    if ($role == 'Admin') $color = 'light-success';
                    $output .= '<div class="badge badge-' . $color . ' fw-bold my-1 mx-1">' . $role . '</div> ';
                }
                return $output;
            })
            ->addColumn('user detail', function ($user) {
                $avatar = '<div
                class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                <a href="' . route('dashboard.users.show', $user->nip) . '">
                    <div
                        class="symbol-label fs-3 bg-light-warning text-warning">
                        ' . substr($user->nama_ptgs, 0, 1) . '</div>
                </a>
            </div>';


                $detail = '<div class="d-flex flex-column">
            <a href="' . route('dashboard.profile.index', $user->nip) . '"
                class="text-gray-800 text-hover-primary mb-1">' .  $user->nama_ptgs . '</a>
        </div>';

                $output = '<div class="d-flex align-items-center">' . $avatar . $detail . '</div>';
                return $output;
            })
            ->rawColumns(['roles', 'user detail'])
            ->toJson();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(Gate::denies('users-create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user = User::find(auth()->id());

        if ($user)
            if ($user->hasRole('Superadmin')) {
                $roles = Role::all();
            } else {
                $roles = Role::whereNotIn('name', ['Superadmin', 'Admin'])->get();
            }
        return view($this->prefix . 'create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        abort_if(Gate::denies('users-create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $rules = [
            // 'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'nama_lengkap' => 'required',
            // 'jenis_kelamin' => 'required',
            'roles' => 'required',
            'nip' => 'required|unique:users,nip',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ];

        $messages = [
            'required' => 'Data tidak boleh kosong',
            'min' => 'Password tidak boleh kurang dari 6 karakter',
            'max' => 'Ukuran gambar tidak boleh lebih dari 2MB',
            'nip.unique' => 'NIP sudah terpakai!',
            'email.unique' => 'Email sudah terpakai!',
        ];


        $this->validate($request, $rules, $messages);


        // format tgl lahir
        // $time = strtotime($request->tgl_lahir);
        // $newformat = date('Y-m-d', $time);

        try {
            $user = User::create([
                'nip' => $request->nip,
                'email' => $request->email,
                'nama_ptgs' => $request->nama_lengkap,
                'status' => true,
                'password' => Hash::make($request->password),
            ]);

            $user->assignRole($request->roles ?? []);

            // if ($pegawai && $request->hasFile('foto')) {
            //     $image = $request->file('foto');
            //     $imageName = $pegawai->nip . '.' . $image->extension();
            //     $imagePath = 'images/pegawai/' . $imageName;
            //     $storedImage = Storage::putFileAs('public/images/pegawai', $image, $imageName);
            // } else {
            //     $imagePath = null;
            // }
            // if ($imagePath) {
            //     $user->pegawai()->update([
            //         'foto' => $imagePath,
            //     ]);
            // }


            return redirect()->route('dashboard.users.index')->with('success', 'Berhasil menambahkan pegawai');
        } catch (\Throwable $th) {
            Log::error($th);
            return redirect()->route('dashboard.users.index')->with('error', 'Gagal menambahkan pegawai');
        }

        // $user = new User();
        // $user->name = $request->name;
        // $user->email = $request->email;
        // $user->tgl_lahir = $newformat;
        // $user->alamat = $request->alamat;
        // $user->jenis_kelamin = $request->jenis_kelamin;
        // $user->no_hp = $request->no_hp;
        // $user->url_foto = $imagePath;
        // $user->password = Hash::make($request->password);
        // $user->assignRole($request->role);
        // $status = $user->save();


        // if ($status) {
        //     return redirect()->route('dashboard.users.index')->with('success', 'Berhasil menambahkan pegawai');
        // } else {
        //     return redirect()->route('dashboard.users.index')->with('error', 'Gagal menambahkan pegawai');
        // }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function userProfile($nip)
    {
        abort_if(Gate::denies('users-detail'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user = User::where('nip', $nip)->first();
        // TODO : statistik, pengajuan cuti, riwayat aktivitas, riwayat kehadiran
        // dd($user);
        if (!$user) {
            abort(404);
        }

        return view($this->profilePrefix . 'index', compact('user'));
    }

    public function show($nip)
    {
        abort_if(Gate::denies('users-read'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user = User::where('nip', $nip)->first();

        if (!$user) {
            abort(404);
        }
        // dd($user);
        return view($this->profilePrefix . 'index', compact('user'));
    }

    public function myProfile()
    {
        $user = User::where('id', auth()->id())->first();
        // dd($user);
        return view($this->myProfilePrefix . 'index', compact('user'));
    }

    public function settings()
    {
        $user = User::where('id', auth()->id())->first();
        return view($this->myProfilePrefix . 'edit', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = User::findOrFail(auth()->id());

        $rules = [
            // 'foto' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'min:6|nullable',
        ];

        $messages = [
            'required' => 'Data tidak boleh kosong',
            'min' => 'Password tidak boleh kurang dari 6 karakter',
            'max' => 'Ukuran gambar tidak boleh lebih dari 2MB',
            'unique' => 'email sudah terpakai!',
        ];

        $this->validate($request, $rules, $messages);

        try {


            $userData['email'] = $request->email;
            // jika ada password
            if (isset($request->change_password)) {
                $userData['password'] = Hash::make($request->change_password);
            }

            $user->update($userData);



            return redirect()->route('dashboard.myprofile.index')->with('success', 'Data berhasil diperbarui');
        } catch (\Throwable $th) {
            Log::error($th);
            return redirect()->route('dashboard.myprofile.index')->with('error', 'Gagal mengupdate data');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($nip)
    {
        abort_if(Gate::denies('users-edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user = User::where('nip', $nip)->first();
        $roles = Role::all();
        return view($this->prefix . 'edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $nip)
    {
        abort_if(Gate::denies('users-edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $user = User::where('nip', $nip)->first();

        $rules = [
            // 'foto' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
            'nama_lengkap' => 'required',
            // 'jenis_kelamin' => 'required',
            'roles' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'min:6|nullable',
        ];

        $messages = [
            'required' => 'Data tidak boleh kosong',
            'min' => 'Password tidak boleh kurang dari 6 karakter',
            'max' => 'Ukuran gambar tidak boleh lebih dari 2MB',
            'unique' => 'email sudah terpakai!',
        ];

        $this->validate($request, $rules, $messages);
        // dd($request->all());
        // if (isset($request->device_id)) {
        //     $isExist = User::where('device_id', $request->device_id)->first();
        //     if ($isExist) {
        //         if ($isExist->nip != $pegawai->nip) {
        //             return redirect()->route('dashboard.users.index')->with('error', 'Gagal mengupdate data pengguna, Perangkat ID sudah digunakan oleh pegawai dengan NIP ' . $isExist->nip . '.');
        //         }
        //     }
        //     $userData['device_id'] = $request->device_id;
        // }


        try {
            // jika ada foto
            // if ($request->hasFile('foto')) {
            //     if ($pegawai->foto && Storage::exists($pegawai->foto)) {
            //         Storage::delete($pegawai->foto);
            //     }

            //     $image = $request->file('foto');
            //     $imageName = $pegawai->nip . '.' . $image->extension();
            //     $imagePath = 'images/pegawai/' . $imageName;
            //     $storedImage = Storage::putFileAs('public/images/pegawai', $image, $imageName);
            // } else {
            //     $imagePath = $pegawai->foto;
            // }



            $userData['email'] = $request->email ?? $user->email;
            // jika ada password
            if (isset($request->change_password)) {
                $userData['password'] = Hash::make($request->change_password);
                // $user->update([
                //     'email' => $userData['email'],
                //     'password' => $userData['password'],
                //     'device_id' => $userData['device_id'] ?? $user->device_id,
                // ]);
            }

            $userData['nama_ptgs'] = $request->nama_lengkap ?? $user->nama_ptgs;

            $user->update($userData);

            // $pegawai->update([
            //     'nama_lengkap' => $request->nama_lengkap ?? $pegawai->nama_lengkap,
            //     'gelar_depan' => $request->gelar_depan ?? $pegawai->gelar_depan,
            //     'gelar_belakang' => $request->gelar_belakang ?? $pegawai->gelar_belakang,
            //     'no_hp' => $request->no_hp ?? $pegawai->no_hp,
            //     'cabang_id' => $request->cabang,
            //     'jabatan_id' => $request->jabatan,
            //     'foto' => $imagePath ?? $request->foto,
            // ]);

            // dd($request->roles);
            $user->assignRole($request->roles ?? []);

            return redirect()->route('dashboard.users.index')->with('success', 'Berhasil mengupdate data pengguna');
        } catch (\Throwable $th) {
            Log::error($th);
            return redirect()->route('dashboard.users.index')->with('error', 'Gagal mengupdate data pengguna');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($nip)
    {
        abort_if(Gate::denies('users-delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $user = User::where('nip', $nip)->first();
        if ($user) {
            if ($user->delete()) {
                return response()->json(['status' => 'success', 'message' => 'Pengguna berhasil dihapus']);
            }
        }
        return response()->json(['status' => 'error', 'message' => 'Pengguna gagal dihapus']);
    }

    public function bulkDelete(Request $request)
    {
        abort_if(Gate::denies('users-delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $ids = $request->ids;
        $users = User::whereIn('id', $ids);
        if ($users->delete()) {
            return response()->json(['status' => 'success', 'message' => 'Data yang dipilih berhasil dihapus']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Data yang dipilih gagal dihapus']);
        }
    }
}
