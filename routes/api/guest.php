<?php

use Illuminate\Support\Facades\Route;

Route::post('/visitor/logs', App\Http\Controllers\Guest\VisitorLog\StoreController::class);
Route::get('/config/site', App\Http\Controllers\Guest\Site\ConfigController::class);
