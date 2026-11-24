<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\Demo\DemoContent;
use Inertia\Inertia;
use Inertia\Response;

class SystemSettingController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('admin/system-settings/Index', [
            'rows' => Inertia::defer(fn () => DemoContent::systemSettings(), rescue: true),
        ]);
    }
}
