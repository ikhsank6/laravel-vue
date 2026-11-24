<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\Demo\DemoContent;
use Inertia\Inertia;
use Inertia\Response;

class NewsController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('admin/news/Index', [
            'rows' => Inertia::defer(fn () => DemoContent::news(), rescue: true),
            'categories' => DemoContent::newsCategories(),
        ]);
    }
}
