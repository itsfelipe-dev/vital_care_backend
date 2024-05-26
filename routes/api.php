<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\V1\View\AuxProfileController;
use App\Http\Controllers\TokenController;

Route::group(['prefix' => '/v1'], function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    
    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/home', [AuthController::class, 'home']);
        Route::post('/getAuxProfile', [AuxProfileController::class, 'getAuxProfile']);
        Route::post('/filterBySpecialty', [AuxProfileController::class, 'filterBySpecialty']);
        Route::post('/auxProfile/{id}', [AuxProfileController::class, 'getAux']);  
        Route::post('/completeAuxProfile', [AuthController::class, 'completeAuxProfile']);
    });
});