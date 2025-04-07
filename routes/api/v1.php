<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['ability:manage site setting'])->group(function () {
    Route::apiResource('/faqs', App\Http\Controllers\Faq\FaqController::class);
    Route::get('/settings/site', App\Http\Controllers\Setting\GetSiteController::class);
    Route::post('/settings/site', App\Http\Controllers\Setting\StoreSiteController::class);
});

Route::apiResource('/users', App\Http\Controllers\User\UserController::class)->middleware('ability:manage user');
Route::middleware(['ability:manage user ability'])->group(function () {
    Route::apiResource('/roles', App\Http\Controllers\Ability\RoleController::class);
    Route::get('/abilities', App\Http\Controllers\Ability\AbilityController::class);
});

Route::middleware(['ability:manage gallery'])->group(function () {
    Route::apiResource('/galleries', App\Http\Controllers\Gallery\GalleryController::class);
    Route::post('/gallery/sort', App\Http\Controllers\Gallery\GallerySortController::class);
});

Route::apiResource('/programs', App\Http\Controllers\Program\ProgramController::class)->middleware('ability:manage program');
Route::apiResource('/work-programs', App\Http\Controllers\WorkProgram\WorkProgramController::class)->middleware('ability:manage work program');

Route::middleware(['ability:manage post'])->group(function () {
    Route::apiResource('/posts', App\Http\Controllers\Post\PostController::class);
    Route::get('/constant/posts', App\Http\Controllers\Constant\PostStatusController::class);
    Route::apiResource('/post-categories', App\Http\Controllers\Post\PostCategoryController::class);
});

Route::middleware(['ability:manage organization'])->group(function () {
    Route::apiResource('/og', App\Http\Controllers\Og\OgController::class);
    Route::apiResource('/positions', App\Http\Controllers\Position\PositionController::class);
    Route::apiResource('/position-categories', App\Http\Controllers\PositionCategory\PositionCategoryController::class);
});

Route::apiResource('/regulations', App\Http\Controllers\Regulation\RegulationController::class)->middleware('ability:manage regulation');
Route::get('/visitor/logs', App\Http\Controllers\VisitorLog\VisitorLogController::class)->middleware('ability:manage visitor log');
