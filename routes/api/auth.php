<?php

use Illuminate\Support\Facades\Route;

Route::post('/auth/login', App\Http\Controllers\Auth\LoginController::class);
Route::delete('/auth/logout', App\Http\Controllers\Auth\LogoutController::class)->middleware('auth:sanctum');
