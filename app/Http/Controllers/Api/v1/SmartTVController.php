<?php

namespace App\Http\Controllers\Api\v1;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Resources\CabangResource;
use App\Http\Resources\PartnerResource;
use App\Http\Resources\SettingsResource;
use App\Http\Resources\SlideshowResource;
use App\Http\Resources\SukuBungaResource;
use App\Models\Cabang;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Slideshow;
use App\Models\SukuBunga;
use App\Models\Partner;

class SmartTVController extends Controller
{
    public function getAllData()
    {
        $settings = Setting::all();

        $settingCollection = collect();
        foreach ($settings as $setting) {
            $settingCollection->put($setting['key'], $setting['value']);
        }
        $slideshow = Slideshow::where('is_active', true)->get();
        $partners = Partner::where('is_active', true)->get();
        $sukuBunga = SukuBunga::where('is_active', true)->get();



        return ResponseFormatter::success([
            'slideshow' => SlideshowResource::collection($slideshow),
            'partners' => PartnerResource::collection($partners),
            'settings' => $settingCollection->toArray(),
            'sukuBunga' => SukuBungaResource::collection($sukuBunga),
        ], 'Berhasil mengambil data');
    }

    public function getCabang()
    {
        $cabang = Cabang::all();
        return ResponseFormatter::success([
            'cabang' => CabangResource::collection($cabang),
        ], 'Berhasil mengambil data kantor cabang');
    }
}
