<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['title' => 'Rate BI', 'deskripsi' => 'Rate BI, contoh : 3.50', 'key' => 'bi_rate', 'value' => '3.50'],
            ['title' => 'Teks Berjalan (Footer)', 'deskripsi' => 'Teks berjalan akan tampil di sisi bawah layar smart tv', 'key' => 'running_text', 'value' => ''],
            ['title' => 'Nomor Telpon', 'deskripsi' => 'Nomor kontak yang dapat dihubungi akan ditampilkan di sisi bawah layar smart tv', 'key' => 'no_telp', 'value' => ''],
        ];
        Setting::insert($data);
    }
}
