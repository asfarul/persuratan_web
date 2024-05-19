<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $superadmin = Role::create([
            'name' => 'Superadmin',
            'guard_name' => 'web'
        ]);

        $permissions = Permission::pluck('name', 'name')->all();
        $superadmin->syncPermissions($permissions);

        $admin = Role::create([
            'name' => 'Admin',
            'guard_name' => 'web'
        ]);

        $admin->givePermissionTo(['users-create', 'users-edit', 'users-read', 'users-delete', 'roles-read']);

        $pegawai = Role::create([
            'name' => 'Pegawai',
            'guard_name' => 'web'
        ]);
    }
}
