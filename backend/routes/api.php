<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AffiliateController;
use App\Http\Controllers\CommissionController;


Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('register', [AuthController::class, 'register'])->name('register');
Route::post('logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth:api');
Route::get('verify', [AuthController::class, 'verify'])->name('verify')->middleware('auth:api');

Route::prefix('user')->name('user.')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('index');
    Route::get('/{id}', [UserController::class, 'show'])->name('show');
    Route::put('/{id}', [UserController::class, 'update'])->name('update')->middleware('auth:api');
    Route::post('/{id}/status', [UserController::class, 'updateStatus'])->name('updateStatus')->middleware('auth:api');
});

Route::prefix('affiliate')->name('affiliate.')->group(function () {
    Route::get('/', [AffiliateController::class, 'index'])->name('index');
    Route::get('/{id}', [AffiliateController::class, 'show'])->name('show');
    Route::put('/{id}', [AffiliateController::class, 'update'])->name('update')->middleware('auth:api');
    Route::post('/{id}/status', [AffiliateController::class, 'updateStatus'])->name('updateStatus')->middleware('auth:api');
    Route::post('/{id}/commissions', [AffiliateController::class, 'addCommission'])->name('addCommission')->middleware('auth:api');
});

Route::prefix('commission')->name('commission.')->group(function () {
    Route::get('/', [CommissionController::class, 'index'])->name('commissions.index');
    Route::post('/', [CommissionController::class, 'store'])->name('commissions.store')->middleware('auth:api');
    Route::put('/{commission}', [CommissionController::class, 'update'])->name('commissions.update')->middleware('auth:api');
    Route::delete('/{commission}', [CommissionController::class, 'destroy'])->name('commissions.destroy')->middleware('auth:api');
});