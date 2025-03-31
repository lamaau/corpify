<?php

use Illuminate\Support\Facades\Route;

Route::apiResource('/users', App\Http\Controllers\User\UserController::class);
Route::get('/settings/app', App\Http\Controllers\Setting\GetSettingController::class);
Route::post('/settings/app', App\Http\Controllers\Setting\StoreSettingController::class);
Route::apiResource('/galleries', App\Http\Controllers\Gallery\GalleryController::class);
Route::apiResource('/programs', App\Http\Controllers\Program\ProgramController::class);
Route::apiResource('/news', App\Http\Controllers\News\NewsController::class);
Route::apiResource('/faqs', App\Http\Controllers\Faq\FaqController::class);
Route::apiResource('/regulations', App\Http\Controllers\Regulation\RegulationController::class);
Route::apiResource('/positions', App\Http\Controllers\Position\PositionController::class);
Route::apiResource('/position-categories', App\Http\Controllers\PositionCategory\PositionCategoryController::class);
Route::apiResource('/og', App\Http\Controllers\Og\OgController::class);
