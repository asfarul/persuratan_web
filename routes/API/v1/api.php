<?php

use App\Http\Controllers\Api\v1\AbsensiController;
use App\Http\Controllers\Api\v1\SmartTVController;
use App\Http\Controllers\Api\v1\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// User authentication
Route::prefix('/smart-tv-display')->group(function () {
    Route::get('/predata', [SmartTVController::class, 'getAllData']);
    Route::get('/cabang', [SmartTVController::class, 'getCabang']);
});

// Route::group([
//     'prefix' => 'user',
//     'middleware' => ['auth:sanctum'],
// ], function () {
//     Route::get('/', [UserController::class, 'myProfile']);
//     Route::get('/detail/{nip}', [UserController::class, 'profile']);
//     Route::get('/dashboard', [UserController::class, 'dashboard']);
// });

