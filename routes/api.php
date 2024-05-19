<?php

use App\Http\Controllers\Dashboard\PermissionsController;
use App\Http\Controllers\Dashboard\RolesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// Route::get('permissions/json', [PermissionsController::class, 'json'])->name('permissions.json');
// Route::get('roles/json', [RolesController::class, 'json'])->name('roles.json');
