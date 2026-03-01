<?php

namespace App\Services;

class SeoService
{
    public function pageSeo(array $page): array
    {
        return [
            'title' => $page['meta_title'] ?? $page['title'] ?? 'Supplements',
            'description' => $page['meta_description'] ?? $page['summary'] ?? '',
            'updated_at' => $page['updated_at'] ?? null,
        ];
    }

    public function robots(): string
    {
        return "User-agent: *\nAllow: /\nSitemap: " . url('/sitemap.xml') . "\n";
    }

    public function sitemapIndex(): string
    {
        $base = url('/');

        return <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <sitemap>
        <loc>{$base}/sitemap-nl.xml</loc>
    </sitemap>
    <sitemap>
        <loc>{$base}/sitemap-en.xml</loc>
    </sitemap>
    <sitemap>
        <loc>{$base}/sitemap-de.xml</loc>
    </sitemap>
</sitemapindex>
XML;
    }

    public function sitemapLang(string $lang): string
    {
        $base = url('/');
        $now = now()->toAtomString();

        return <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>{$base}/{$lang}</loc>
        <lastmod>{$now}</lastmod>
    </url>
    <url>
        <loc>{$base}/{$lang}/supplements</loc>
        <lastmod>{$now}</lastmod>
    </url>
</urlset>
XML;
    }

    public function activeJsonLd(array $page): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $page['title'] ?? '',
            'description' => $page['summary'] ?? '',
            'dateModified' => $page['updated_at'] ?? null,
        ];
    }

    public function comparisonJsonLd(array $page): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $page['title'] ?? '',
            'description' => $page['summary'] ?? '',
        ];
    }

    public function productJsonLd(array $page): array
    {
        $product = $page['product'] ?? [];

        return [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $product['name'] ?? '',
            'brand' => $product['brand'] ?? '',
        ];
    }
}
