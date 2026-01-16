<?php

namespace App\Http\Controllers;

use App\Services\ContentService;
use App\Services\SeoService;

class GoalController extends Controller
{
    public function __construct(
        private ContentService $contentService,
        private SeoService $seoService
    ) {
    }

    public function show(string $lang, string $goalSlug)
    {
        $page = $this->contentService->goalPage($lang, $goalSlug);

        return view('pages.goal-show', [
            'lang' => $lang,
            'page' => $page,
            'seo' => $this->seoService->pageSeo($page),
        ]);
    }
}
