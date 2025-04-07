<?php

use Illuminate\Support\Facades\Route;

Route::get('/settings/site', App\Http\Controllers\Setting\GetSiteController::class);
Route::post('/settings/site', App\Http\Controllers\Setting\StoreSiteController::class);
Route::apiResource('/users', App\Http\Controllers\User\UserController::class);
Route::apiResource('/galleries', App\Http\Controllers\Gallery\GalleryController::class);
Route::post('/gallery/sort', App\Http\Controllers\Gallery\GallerySortController::class);
Route::apiResource('/programs', App\Http\Controllers\Program\ProgramController::class);
Route::apiResource('/work-programs', App\Http\Controllers\WorkProgram\WorkProgramController::class);
Route::apiResource('/posts', App\Http\Controllers\Post\PostController::class);
Route::apiResource('/post-categories', App\Http\Controllers\Post\PostCategoryController::class);
Route::apiResource('/faqs', App\Http\Controllers\Faq\FaqController::class);
Route::apiResource('/regulations', App\Http\Controllers\Regulation\RegulationController::class);
Route::apiResource('/positions', App\Http\Controllers\Position\PositionController::class);
Route::apiResource('/position-categories', App\Http\Controllers\PositionCategory\PositionCategoryController::class);
Route::apiResource('/og', App\Http\Controllers\Og\OgController::class);
Route::get('/visitor/logs', App\Http\Controllers\VisitorLog\VisitorLogController::class);
Route::get('/abilities', App\Http\Controllers\Ability\AbilityController::class);
Route::apiResource('/roles', App\Http\Controllers\Ability\RoleController::class);

Route::prefix('/constant')->group(function () {
    Route::get('/posts', App\Http\Controllers\Constant\PostStatusController::class);
});
