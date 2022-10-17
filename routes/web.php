<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomePageController;
use App\Models\Category;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomePageController::class, 'index'])->name('home');

$categories = Category::active()->get();

foreach ($categories as $key => $category) {
    Route::get('/' . $category->slug, [HomePageController::class, 'category', $category->slug])->name($category->slug);
}

Route::get('/tim-kiem', [HomePageController::class, 'search'])->name('search');
Route::get('/lien-he', [ContactController::class, 'contact'])->name('contact');
Route::post('/lien-he', [ContactController::class, 'sendContact'])->name('send_contact');
Route::get('/tin-tuc', [HomePageController::class, 'news'])->name('news');
Route::get('/tin-tuc/{slug}', [HomePageController::class, 'newDetail'])->name('new-detail');
Route::get('/{post}', [HomePageController::class, 'post'])->name('post');