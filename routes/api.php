<?php

use App\Http\Controllers\AlertController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;



    Route::prefix('auth')->group(function () {
        
    Route::post('/register', [AuthController::class, 'register']);

    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);


    });

        Route::get('/alertas', [AlertController::class, 'index'])->name('alertas.index');
        Route::post('/alertas', [AlertController::class, 'store']);


});


