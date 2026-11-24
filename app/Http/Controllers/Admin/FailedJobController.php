<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\Demo\DemoContent;
use Inertia\Inertia;
use Inertia\Response;

class FailedJobController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('admin/failed-jobs/Index', [
            'rows' => Inertia::defer(fn () => DemoContent::failedJobs(), rescue: true),
        ]);
    }
}
