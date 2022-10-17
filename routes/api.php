<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImagesController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::get('/categories', [CategoryController::class, 'api'])->name('categories');
Route::get('/categories/slug', [CategoryController::class, 'checkSlug'])->name('categories.slug.check');
Route::post('/categories/slug', [CategoryController::class, 'generateSlug'])->name('categories.slug.generate');
Route::get('/posts/load-district', [PostController::class, 'loadDistrict'])->name('posts.load-district');
Route::get('/posts/load-communes', [PostController::class, 'loadCommunes'])->name('posts.load-communes');
Route::post('/images/store', [ImagesController::class, 'store'])->name('images.store');
Route::post('/change-avatar', [DashboardController::class, 'changeAvatar'])->name('change-avatar');
Route::post('/change-image', [DashboardController::class, 'changeImageWebsite'])->name('change-image');