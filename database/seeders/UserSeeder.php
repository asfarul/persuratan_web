<?php

namespace Database\Seeders;

use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $superadmin = User::create([
            'nip' => '001',
            'email' => 'admin@super.com',
            'nama_ptgs' => 'Superadmin',
            'password' => Hash::make('123!@#123'),
        ]);

        // if($superadmin) {
        //     $pegawai = Pegawai::create([
        //         'nip' => $superadmin->nip,
        //         'nama_lengkap' => 'Superadmin',
        //     ]);
        // }

        $superadmin->assignRole('Superadmin');
    }
}
