<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DataController;

// Public routes
Route::post('/login', [AuthController::class, 'login']);
Route::get('/companies', [DataController::class, 'companies']);

// Protected routes (require Sanctum auth)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    Route::get('/modules', [DataController::class, 'modules']);
});