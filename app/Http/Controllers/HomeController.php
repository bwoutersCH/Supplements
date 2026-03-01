<?php

namespace App\Http\Controllers;

use App\Services\ContentService;
use App\Services\SeoService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct(
        private ContentService $contentService,
        private SeoService $seoService
    ) {
    }

    public function index(Request $request, string $lang)
    {
        $page = $this->contentService->homePage($lang);

        return view('pages.home', [
            'lang' => $lang,
            'page' => $page,
            'seo' => $this->seoService->pageSeo($page),
        ]);
    }
}
