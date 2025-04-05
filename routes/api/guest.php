<?php

use Illuminate\Support\Facades\Route;

Route::post('/visitor/logs', App\Http\Controllers\VisitorLog\StoreController::class);
