<?php

use App\Http\Controllers\Website\AboutController;
use App\Http\Controllers\Website\HomeController;
use App\Http\Controllers\Website\NewsController;
use App\Http\Controllers\Website\SchemeController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('news', [NewsController::class, 'index'])->name('news.index');
Route::get('news/{slug}', [NewsController::class, 'show'])->name('news.show');
Route::get('skema', [SchemeController::class, 'index'])->name('schemes.index');
Route::get('skema/{slug}', [SchemeController::class, 'show'])->name('schemes.show');
Route::get('about', AboutController::class)->name('about');
