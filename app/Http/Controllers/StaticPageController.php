<?php

namespace App\Http\Controllers;

use App\Services\ContentService;
use App\Services\SeoService;

class StaticPageController extends Controller
{
    public function __construct(
        private ContentService $contentService,
        private SeoService $seoService
    ) {
    }

    public function about(string $lang)
    {
        $page = $this->contentService->staticPage($lang, 'about');

        return view('pages.static', [
            'lang' => $lang,
            'page' => $page,
            'seo' => $this->seoService->pageSeo($page),
        ]);
    }

    public function disclaimer(string $lang)
    {
        $page = $this->contentService->staticPage($lang, 'disclaimer');

        return view('pages.static', [
            'lang' => $lang,
            'page' => $page,
            'seo' => $this->seoService->pageSeo($page),
        ]);
    }

    public function privacy(string $lang)
    {
        $page = $this->contentService->staticPage($lang, 'privacy');

        return view('pages.static', [
            'lang' => $lang,
            'page' => $page,
            'seo' => $this->seoService->pageSeo($page),
        ]);
    }

    public function howWeCompare(string $lang)
    {
        $page = $this->contentService->staticPage($lang, 'how-we-compare');

        return view('pages.static', [
            'lang' => $lang,
            'page' => $page,
            'seo' => $this->seoService->pageSeo($page),
        ]);
    }
}
