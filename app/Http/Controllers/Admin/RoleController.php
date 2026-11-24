<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\Demo\DemoContent;
use Inertia\Inertia;
use Inertia\Response;

class RoleController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('admin/roles/Index', [
            'rows' => Inertia::defer(fn () => DemoContent::roles(), rescue: true),
        ]);
    }
}
