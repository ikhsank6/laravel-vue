<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Support\Demo\DemoContent;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

/** Sample certification schemes: reads DemoContent until a real scheme module exists. */
class SchemeController extends Controller
{
    public function index(Request $request): Response
    {
        $field = $request->string('field')->toString() ?: null;
        $level = $request->integer('level') ?: null;
        $search = $request->string('q')->trim()->toString();
        $fieldName = collect(DemoContent::schemeFields())->firstWhere('slug', $field)['name'] ?? null;

        $items = collect(DemoContent::schemes())
            ->when($field, fn ($c) => $c->where('field', $fieldName))
            ->when($level, fn ($c) => $c->where('level_no', $level))
            ->when($search !== '', fn ($c) => $c->filter(
                fn (array $s) => str_contains(
                    mb_strtolower($s['name'].' '.$s['field'].' '.$s['summary']),
                    mb_strtolower($search)
                )
            ))
            ->values();

        return Inertia::render('website/schemes/Index', [
            'schemes' => $items,
            'fields' => DemoContent::schemeFields(),
            'levels' => DemoContent::schemeLevels(),
            'filters' => ['field' => $field, 'level' => $level, 'q' => $search],
        ]);
    }

    public function show(string $slug): Response
    {
        $scheme = collect(DemoContent::schemes())->firstWhere('slug', $slug) ?? abort(404);

        return Inertia::render('website/schemes/Show', ['scheme' => $scheme]);
    }
}
