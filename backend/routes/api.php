<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AffiliateController;



Route::prefix('user')->name('user.')->group(function () {
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('register', [AuthController::class, 'register'])->name('register');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth:api');
    Route::get('verify', [AuthController::class, 'verify'])->name('verify')->middleware('auth:api');
});

Route::prefix('affiliate')->name('affiliate.')->group(function () {
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('register', [AuthController::class, 'register'])->name('register');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth:api');
    Route::get('verify', [AuthController::class, 'verify'])->name('verify')->middleware('auth:api');
});