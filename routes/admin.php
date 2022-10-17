<?php

use App\Http\Controllers\AdministratorController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::match(
    ['get', 'post'],
    '/login',
    [LoginController::class, 'login']
)->name('login');

Route::middleware('auth:admin')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/logout', [LogoutController::class, 'logout'])->name('logout');
    Route::get('/account', [DashboardController::class, 'show'])->name('account');
    Route::get('/account/change-password', [DashboardController::class, 'changePassword'])->name('change-password');
    Route::patch('/account/edit/{admin}', [DashboardController::class, 'edit'])->name('account-edit');
    Route::patch('/account/update-password', [DashboardController::class, 'updatePassword'])->name('update-password');
    Route::get('/setting-website', [DashboardController::class, 'settingWebsite'])->name('setting-website')->middleware('checkMaster');
    Route::patch('/setting-website/update-info', [DashboardController::class, 'updateInfo'])->name('update-info');
    
    // Route administrator group
    Route::group([
        'as'     => 'administrators.',
        'middleware' => ['checkMaster'],
        'prefix' => 'administrators',
    ], static function () {
        Route::get('/', [AdministratorController::class, 'index'])->name('index');
        Route::get('/api', [AdministratorController::class, 'api'])->name('api');
        Route::get('/trash', [AdministratorController::class, 'admin_trash'])->name('trash');
        Route::get('/create', [AdministratorController::class, 'create'])->name('create');
        Route::post('/store', [AdministratorController::class, 'store'])->name('store');
        Route::get('/{admin}', [AdministratorController::class, 'show'])->name('show');
        Route::get('/edit/{admin}', [AdministratorController::class, 'edit'])->name('edit');
        Route::patch('/update/{admin}', [AdministratorController::class, 'update'])->name('update');
        Route::delete('/{admin}', [AdministratorController::class, 'destroy'])->name('destroy');
        Route::post('/restore/{admin}', [AdministratorController::class, 'restore'])->name('restore');
        Route::post('/restore-all', [AdministratorController::class, 'restoreAll'])->name('restore-all');
        Route::post('/change-active', [AdministratorController::class, 'changeActive'])->middleware('throttle:60,1')->name('change-active');
        Route::delete('/force-delete/{admin}', [AdministratorController::class, 'forceDelete'])->name('force-delete');
    });

    // Route categories group
    Route::group([
        'as'     => 'categories.',
        'middleware' => ['checkMaster'],
        'prefix' => 'categories',
    ], static function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::get('/trash', [CategoryController::class, 'trash'])->name('trash');
        Route::post('/store', [CategoryController::class, 'store'])->name('store');
        Route::get('/edit/{category}', [CategoryController::class, 'edit'])->name('edit');
        Route::patch('/update', [CategoryController::class, 'update'])->name('update');
        Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('destroy');
        Route::post('/restore/{category}', [CategoryController::class, 'restore'])->name('restore');
        Route::post('/restore-all', [CategoryController::class, 'restoreAll'])->name('restore-all');
        Route::post('/change-active', [CategoryController::class, 'changeActive'])->middleware('throttle:60,1')->name('change-active');
        Route::delete('/force-delete/{category}', [CategoryController::class, 'force_delete'])->name('force-delete');
    });

    // Route posts group
    Route::group([
        'as'     => 'posts.',
        'prefix' => 'posts',
    ], static function () {
        Route::get('/', [PostController::class, 'index'])->name('index');
        Route::get('/trash', [PostController::class, 'trash'])->name('trash');
        Route::get('/create', [PostController::class, 'create'])->name('create');
        Route::post('/store', [PostController::class, 'store'])->name('store');
        Route::get('/edit/{post}', [PostController::class, 'edit'])->name('edit');
        Route::patch('/update/{post}', [PostController::class, 'update'])->name('update');
        Route::delete('/{post}', [PostController::class, 'destroy'])->name('destroy');
        Route::post('/restore/{post}', [PostController::class, 'restore'])->name('restore');
        Route::post('/restore-all', [PostController::class, 'restoreAll'])->name('restore-all');
        Route::post('/change-active', [PostController::class, 'changeActive'])->middleware('throttle:60,1')->name('change-active');
        Route::delete('/force-delete/{post}', [PostController::class, 'forceDelete'])->name('force-delete');
        Route::post('/refresh-post', [PostController::class, 'refreshPost'])->name('refresh-post');
    });

    // Route news group
    Route::group([
        'as'     => 'news.',
        'prefix' => 'news', 
    ], static function () {
        Route::get('/', [NewsController::class, 'index'])->name('index');
        Route::get('/trash', [NewsController::class, 'trash'])->name('trash');
        Route::get('/create', [NewsController::class, 'create'])->name('create');
        Route::post('/store', [NewsController::class, 'store'])->name('store');
        Route::get('/edit/{new}', [NewsController::class, 'edit'])->name('edit');
        Route::patch('/update/{new}', [NewsController::class, 'update'])->name('update');
        Route::delete('/{new}', [NewsController::class, 'destroy'])->name('destroy');
        Route::post('/restore/{new}', [NewsController::class, 'restore'])->name('restore');
        Route::post('/restore-all', [NewsController::class, 'restoreAll'])->name('restore-all');
        Route::post('/change-active', [NewsController::class, 'changeActive'])->middleware('throttle:60,1')->name('change-active');
        Route::delete('/force-delete/{news}', [NewsController::class, 'forceDelete'])->name('force-delete');
        Route::post('/refresh-new', [NewsController::class, 'refreshNew'])->name('refresh-new');
    });
});
