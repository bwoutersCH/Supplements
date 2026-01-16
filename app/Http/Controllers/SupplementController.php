<?php

namespace App\Http\Controllers;

use App\Services\ComparisonService;
use App\Services\ContentService;
use App\Services\SeoService;
use Illuminate\Http\Request;

class SupplementController extends Controller
{
    public function __construct(
        private ContentService $contentService,
        private ComparisonService $comparisonService,
        private SeoService $seoService
    ) {
    }

    public function index(Request $request, string $lang)
    {
        $filters = $request->only(['query', 'category']);
        $page = $this->contentService->supplementsIndex($lang, $filters);

        return view('pages.supplements-index', [
            'lang' => $lang,
            'page' => $page,
            'filters' => $filters,
            'seo' => $this->seoService->pageSeo($page),
        ]);
    }

    public function show(Request $request, string $lang, string $activeSlug)
    {
        $page = $this->contentService->activePage($lang, $activeSlug);
        $comparison = $this->comparisonService->summaryForActive($lang, $activeSlug);

        return view('pages.supplement-show', [
            'lang' => $lang,
            'page' => $page,
            'comparison' => $comparison,
            'seo' => $this->seoService->pageSeo($page),
            'jsonLd' => $this->seoService->activeJsonLd($page),
        ]);
    }
}
