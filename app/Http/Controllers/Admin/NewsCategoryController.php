<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\Demo\DemoContent;
use Inertia\Inertia;
use Inertia\Response;

class NewsCategoryController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('admin/news-categories/Index', [
            'rows' => Inertia::defer(fn () => DemoContent::newsCategories(), rescue: true),
        ]);
    }
}
