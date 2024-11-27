<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\UserPasswordController;
use App\Http\Controllers\Api\V1\UserProfileController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->name('auth.')->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    });
});

Route::prefix('password')->name('password.')->group(function () {
    Route::post('/forgot', [UserPasswordController::class, 'forgotPassword'])->name('email');
    Route::post('/reset', [UserPasswordController::class, 'resetPassword'])->name('reset');

    Route::middleware('auth:sanctum')->group(function () {
        Route::put('/update', [UserPasswordController::class, 'updatePassword'])->name('update');
    });
});

Route::prefix('profile')->name('profile.')->middleware('auth:sanctum')->group(function () {
    Route::put('/', [UserProfileController::class, 'updateProfile'])->name('update');
    Route::put('/picture', [UserProfileController::class, 'updatePicture'])->name('picture.update');
    Route::delete('/', [UserProfileController::class, 'deleteAccount'])->name('delete');
});
