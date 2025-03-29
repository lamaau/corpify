<?php

use Illuminate\Support\Facades\Route;

Route::post('/settings/app', App\Http\Controllers\Setting\AppController::class);
Route::apiResource('/galleries', App\Http\Controllers\Gallery\GalleryController::class)->except('update');
Route::apiResource('/programs', App\Http\Controllers\Program\ProgramController::class);
Route::apiResource('/news', App\Http\Controllers\News\NewsController::class);
Route::apiResource('/faqs', App\Http\Controllers\Faq\FaqController::class);
Route::apiResource('/member-positions', App\Http\Controllers\Member\MemberPositionController::class);
Route::apiResource('/member-categories', App\Http\Controllers\Member\MemberCategoryController::class);
