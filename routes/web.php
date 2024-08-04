<?php

use App\Http\Controllers\Dashboard\CabangController;
use App\Http\Controllers\Dashboard\HomeController;
use App\Http\Controllers\Dashboard\JabatanController;
use App\Http\Controllers\Dashboard\KepalaSuratController;
use App\Http\Controllers\Dashboard\PermissionsController;
use App\Http\Controllers\Dashboard\RolesController;
use App\Http\Controllers\Dashboard\SlideshowsController;
use App\Http\Controllers\Dashboard\PartnersController;
use App\Http\Controllers\Dashboard\SettingsController;
use App\Http\Controllers\Dashboard\SukuBungaController;
use App\Http\Controllers\Dashboard\SuratKeluarController;
use App\Http\Controllers\Dashboard\SuratMasukController;
use App\Http\Controllers\Dashboard\TandaTanganController;
use App\Http\Controllers\Dashboard\UsersController;
use App\Models\SuratKeluar;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::group(['middleware' => ['auth', 'verified'], 'prefix' => 'dashboard', 'as' => 'dashboard.'], function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');

    Route::delete('permissions/bulk-delete', [PermissionsController::class, 'bulkDelete'])->name('permissions.bulkdelete');
    Route::get('permissions/json', [PermissionsController::class, 'json'])->name('permissions.json');
    Route::resource('permissions', PermissionsController::class);

    Route::post('roles/{id}', [RolesController::class, 'update'])->name('roles.update');
    Route::delete('roles/bulk-delete', [RolesController::class, 'bulkDelete'])->name('roles.bulkdelete');
    Route::get('roles/json', [RolesController::class, 'json'])->name('roles.json');
    Route::resource('roles', RolesController::class);


    Route::post('users/{id}', [UsersController::class, 'update'])->name('users.update');
    Route::delete('users/bulk-delete', [UsersController::class, 'bulkDelete'])->name('users.bulkdelete');
    Route::get('users/json', [UsersController::class, 'json'])->name('users.json');
    Route::get('myprofile', [UsersController::class, 'myProfile'])->name('myprofile.index');
    Route::get('myprofile/settings', [UsersController::class, 'settings'])->name('myprofile.edit');
    Route::post('myprofile/settings', [UsersController::class, 'updateProfile'])->name('myprofile.update');
    Route::get('profile/{nip}', [UsersController::class, 'userProfile'])->name('profile.index');
    Route::resource('users', UsersController::class);

    Route::resource('kepalasurat', KepalaSuratController::class);
    Route::resource('tandatangan', TandaTanganController::class);
    Route::resource('suratkeluar', SuratKeluarController::class);
    Route::resource('suratmasuk', SuratMasukController::class);

});

// Route::get('/linkstorage', function () {
//     Artisan::call('storage:link');
//     echo 'storage linked!';
// });

// Route::get('/dashboard', function () {
//     return view('dashboard.home');
// })->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__ . '/auth.php';
