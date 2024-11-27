<?php

use Illuminate\Support\Facades\Route;

Route::name('api.')
    ->group(function () {
        Route::name('v1.')->prefix('v1')->group(base_path('routes/api/v1.php'));
    });
