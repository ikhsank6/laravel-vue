<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\Demo\DemoContent;
use Inertia\Inertia;
use Inertia\Response;

class MenuAccessController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('admin/menu-access/Index', [
            'roles' => DemoContent::roles(),
            'menus' => DemoContent::menus(),
            'access' => DemoContent::menuAccess(),
        ]);
    }
}
