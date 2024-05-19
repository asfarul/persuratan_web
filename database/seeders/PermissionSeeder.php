<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'roles-read',
            'roles-create',
            'roles-edit',
            'roles-delete',
            'users-read',
            'users-create',
            'users-edit',
            'users-delete',
            'permissions-read',
            'permissions-create',
            'permissions-edit',
            'permissions-delete',
            'posts-read',
            'posts-create',
            'posts-edit',
            'posts-delete',
            'sukubunga-read',
            'sukubunga-create',
            'sukubunga-edit',
            'sukubunga-delete',
            'partners-read',
            'partners-create',
            'partners-edit',
            'partners-delete',
            'slideshows-read',
            'slideshows-create',
            'slideshows-edit',
            'slideshows-delete',
            'settings-read',
            'settings-edit',
        ];

        foreach ($permissions as $perm) {
            Permission::create(['name' => $perm, 'guard_name' => 'web']);
        }
    }
}
