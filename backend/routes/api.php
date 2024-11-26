<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::prefix('user')->name('user.')->group(function () {
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('register', [AuthController::class, 'register'])->name('register');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth:sanctum');
    Route::get('verify', [AuthController::class, 'verify'])->name('verify')->middleware('auth:sanctum');
});
