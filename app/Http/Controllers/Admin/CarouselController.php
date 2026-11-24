<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\Demo\DemoContent;
use Inertia\Inertia;
use Inertia\Response;

class CarouselController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('admin/carousels/Index', [
            'rows' => Inertia::defer(fn () => DemoContent::carousels(), rescue: true),
        ]);
    }
}
