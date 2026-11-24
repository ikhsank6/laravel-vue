<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\Demo\DemoContent;
use Inertia\Inertia;
use Inertia\Response;

class MenuController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('admin/menus/Index', [
            'rows' => Inertia::defer(fn () => DemoContent::menus(), rescue: true),
        ]);
    }
}
