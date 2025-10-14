<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

// SPA login route (session-based) for Sanctum cookie flow
Route::post('/login', [AuthController::class, 'login']);
