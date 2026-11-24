<?php

use Illuminate\Support\Facades\Route;

require __DIR__.'/website.php';

Route::middleware(['auth', 'verified'])->group(function () {
    require __DIR__.'/admin.php';
});

require __DIR__.'/settings.php';
