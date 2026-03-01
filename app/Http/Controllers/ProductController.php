<?php

namespace App\Http\Controllers;

use App\Services\ComparisonService;
use App\Services\SeoService;

class ProductController extends Controller
{
    public function __construct(
        private ComparisonService $comparisonService,
        private SeoService $seoService
    ) {
    }

    public function show(string $lang, string $productKey)
    {
        $page = $this->comparisonService->productPage($lang, $productKey);

        return view('pages.product-show', [
            'lang' => $lang,
            'page' => $page,
            'seo' => $this->seoService->pageSeo($page),
            'jsonLd' => $this->seoService->productJsonLd($page),
        ]);
    }
}
