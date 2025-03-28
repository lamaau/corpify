<?php

use Illuminate\Support\Facades\Route;

Route::post('/settings/app', App\Http\Controllers\Setting\AppController::class);
