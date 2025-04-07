<?php

use Illuminate\Support\Facades\Route;

Route::get('/og/tree', App\Http\Controllers\Guest\Og\TreeController::class);
Route::get('/faq', App\Http\Controllers\Guest\Faq\FaqController::class);
Route::get('/programs', App\Http\Controllers\Guest\Program\ProgramsController::class);
Route::get('/posts', App\Http\Controllers\Guest\Post\PostsController::class);
Route::get('/posts/{post:slug}', App\Http\Controllers\Guest\Post\PostController::class);
Route::get('/regulations', App\Http\Controllers\Guest\Regulation\RegulationsController::class);
Route::get('/regulations/{regulation}', App\Http\Controllers\Guest\Regulation\RegulationController::class);
Route::post('/visitor/logs', App\Http\Controllers\Guest\VisitorLog\StoreController::class);
Route::get('/config/site', App\Http\Controllers\Guest\Site\ConfigController::class);
Route::get('/gallery/featured', App\Http\Controllers\Guest\Gallery\GalleryFeaturedController::class);
