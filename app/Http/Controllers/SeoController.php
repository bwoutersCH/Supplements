<?php

namespace App\Http\Controllers;

use App\Services\SeoService;
use Illuminate\Http\Response;

class SeoController extends Controller
{
    public function __construct(private SeoService $seoService)
    {
    }

    public function robots(): Response
    {
        $content = $this->seoService->robots();

        return response($content, 200)->header('Content-Type', 'text/plain');
    }

    public function sitemapIndex(): Response
    {
        $content = $this->seoService->sitemapIndex();

        return response($content, 200)->header('Content-Type', 'application/xml');
    }

    public function sitemapLang(string $lang): Response
    {
        $content = $this->seoService->sitemapLang($lang);

        return response($content, 200)->header('Content-Type', 'application/xml');
    }
}
