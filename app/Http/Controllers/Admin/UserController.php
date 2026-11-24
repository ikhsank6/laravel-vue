<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\Demo\DemoContent;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('admin/users/Index', [
            'rows' => Inertia::defer(fn () => DemoContent::users(), rescue: true),
            'roles' => DemoContent::roles(),
        ]);
    }
}
