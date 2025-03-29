<?php

use Illuminate\Support\Facades\Route;

Route::post('/settings/app', App\Http\Controllers\Setting\AppController::class);
Route::apiResource('/galleries', App\Http\Controllers\Gallery\GalleryController::class)->except('update');
Route::apiResource('/programs', App\Http\Controllers\Program\ProgramController::class);
Route::apiResource('/news', App\Http\Controllers\News\NewsController::class);
