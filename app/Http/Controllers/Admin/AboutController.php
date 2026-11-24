<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\Demo\DemoContent;
use Inertia\Inertia;
use Inertia\Response;

class AboutController extends Controller
{
    public function edit(): Response
    {
        return Inertia::render('admin/about/Edit', [
            'about' => DemoContent::about(),
        ]);
    }
}
