<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Support\Demo\DemoContent;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function __invoke(): Response
    {
        $slides = collect(DemoContent::carousels())
            ->where('is_active', true)
            ->sortBy('order')
            ->values();

        return Inertia::render('website/Home', [
            'slides' => $slides,
            'latestNews' => array_slice(DemoContent::publishedNews(), 0, 4),
            'about' => DemoContent::about(),
            'featuredSchemes' => array_slice(DemoContent::schemes(), 0, 5),
            'schemeFields' => DemoContent::schemeFields(),
            'levels' => DemoContent::schemeLevels(),
        ]);
    }
}
