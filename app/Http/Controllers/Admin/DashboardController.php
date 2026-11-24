<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\Demo\DemoContent;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __invoke(): Response
    {
        $news = collect(DemoContent::news());

        return Inertia::render('Dashboard', [
            'drafts' => $news->where('status', 'draft')->values(),
            'recentlyPublished' => collect(DemoContent::publishedNews())->take(3)->values(),
            'failedJobs' => DemoContent::failedJobs(),
            'byCategory' => $news->where('status', 'published')
                ->groupBy('category_name')
                ->map(fn ($items, $name) => ['name' => $name, 'count' => $items->count()])
                ->values(),
            'counts' => [
                'published' => $news->where('status', 'published')->count(),
                'drafts' => $news->where('status', 'draft')->count(),
                'activeSlides' => collect(DemoContent::carousels())->where('is_active', true)->count(),
                'users' => count(DemoContent::users()),
            ],
        ]);
    }
}
