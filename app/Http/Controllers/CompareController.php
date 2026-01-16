<?php

namespace App\Http\Controllers;

use App\Services\ComparisonService;
use App\Services\SeoService;
use Illuminate\Http\Request;

class CompareController extends Controller
{
    public function __construct(
        private ComparisonService $comparisonService,
        private SeoService $seoService
    ) {
    }

    public function show(Request $request, string $lang, string $activeSlug)
    {
        $filters = $request->only([
            'brand',
            'shop',
            'form_factor',
            'dose_min',
            'dose_max',
            'sort',
        ]);
        $page = $this->comparisonService->comparisonPage($lang, $activeSlug, $filters);

        return view('pages.compare-show', [
            'lang' => $lang,
            'page' => $page,
            'filters' => $filters,
            'seo' => $this->seoService->pageSeo($page),
            'jsonLd' => $this->seoService->comparisonJsonLd($page),
        ]);
    }
}
