<?php

use App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Route;

Route::get('dashboard', Admin\DashboardController::class)->name('dashboard');

Route::name('admin.')->group(function () {
    Route::prefix('master-data')->group(function () {
        Route::get('users', [Admin\UserController::class, 'index'])->name('users.index');
        Route::get('roles', [Admin\RoleController::class, 'index'])->name('roles.index');
        Route::get('menus', [Admin\MenuController::class, 'index'])->name('menus.index');
        Route::get('menu-access', [Admin\MenuAccessController::class, 'index'])->name('menu-access.index');
    });

    Route::prefix('cms')->group(function () {
        Route::get('carousels', [Admin\CarouselController::class, 'index'])->name('carousels.index');
        Route::get('news-categories', [Admin\NewsCategoryController::class, 'index'])->name('news-categories.index');
        Route::get('news', [Admin\NewsController::class, 'index'])->name('news.index');
        Route::get('about', [Admin\AboutController::class, 'edit'])->name('about.edit');
    });

    Route::prefix('system')->group(function () {
        Route::get('settings', [Admin\SystemSettingController::class, 'index'])->name('system-settings.index');
        Route::get('logs', [Admin\LogController::class, 'index'])->name('logs.index');
        Route::get('failed-jobs', [Admin\FailedJobController::class, 'index'])->name('failed-jobs.index');
    });
});
