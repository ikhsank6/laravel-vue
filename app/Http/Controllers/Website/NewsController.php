<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Support\Demo\DemoContent;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class NewsController extends Controller
{
    private const PER_PAGE = 6;

    public function index(Request $request): Response
    {
        $category = $request->string('category')->toString() ?: null;
        $search = $request->string('q')->trim()->toString();

        $items = collect(DemoContent::publishedNews())
            ->when($category, fn ($c) => $c->where('category_id', $this->categoryId($category)))
            ->when($search !== '', fn ($c) => $c->filter(
                fn (array $n) => str_contains(mb_strtolower($n['title'].' '.$n['excerpt']), mb_strtolower($search))
            ))
            ->values();

        $page = max(1, $request->integer('page', 1));

        return Inertia::render('website/news/Index', [
            'news' => $items->forPage($page, self::PER_PAGE)->values(),
            'categories' => DemoContent::newsCategories(),
            'filters' => ['category' => $category, 'q' => $search],
            'pagination' => [
                'page' => $page,
                'lastPage' => max(1, (int) ceil($items->count() / self::PER_PAGE)),
                'total' => $items->count(),
            ],
        ]);
    }

    public function show(string $slug): Response
    {
        $published = collect(DemoContent::publishedNews());
        $article = $published->firstWhere('slug', $slug) ?? abort(404);

        return Inertia::render('website/news/Show', [
            'article' => $article,
            'related' => $published
                ->where('category_id', $article['category_id'])
                ->where('id', '!=', $article['id'])
                ->take(3)
                ->values(),
        ]);
    }

    private function categoryId(string $slug): ?int
    {
        return collect(DemoContent::newsCategories())->firstWhere('slug', $slug)['id'] ?? null;
    }
}
