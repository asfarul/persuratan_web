<?php

namespace App\Http\Controllers\Dashboard;

use App\Events\SmartTVEvent;
use App\Http\Controllers\Controller;
use App\Http\Resources\SettingsResource;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class SettingsController extends Controller
{

    protected $prefix = 'dashboard.settings.';

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if(Gate::denies('settings-read'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $settings = Setting::all();
        return view($this->prefix . 'index', compact('settings'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        abort_if(Gate::denies('settings-edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        // dd($request->except('_token'));
        try {
            $dataRequest = $request->except('_token');
            foreach ($dataRequest as $key => $value) {
                // dd($key, $value);
                Setting::where('key', $key)->update(['value' => $value ?? '']);
            }

            $settings = Setting::all();

            $settingCollection = collect();
            foreach ($settings as $setting) {
                $settingCollection->put($setting['key'], $setting['value']);
            }

            $messageData = [
                'category' => 'SETTINGS',
                'method' => 'UPDATE',
                'data' => $settingCollection->toArray(),
            ];

            broadcast(new SmartTVEvent($messageData))->toOthers();



            return redirect()->route('dashboard.settings.index')->with('success', 'Pengaturan berhasil diubah');
        } catch (\Throwable $th) {
            return redirect()->route('dashboard.settings.index')->with('error', 'Gagal mengubah pengaturan');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
