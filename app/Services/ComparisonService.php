<?php

namespace App\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;

class ComparisonService
{
    public function summaryForActive(string $lang, string $activeSlug): array
    {
        return Cache::remember("comparison-summary-{$lang}-{$activeSlug}", 600, function () use ($lang, $activeSlug) {
            return [
                'active' => $activeSlug,
                'highlights' => [
                    $lang === 'en' ? 'Best value based on normalized dose where safe.' : ($lang === 'de' ? 'Bestes Preis-Leistungs-Verhältnis basierend auf sicherer Normalisierung.' : 'Beste waarde op basis van veilige normalisatie.'),
                ],
                'offers' => $this->demoOffers(),
            ];
        });
    }

    public function comparisonPage(string $lang, string $activeSlug, array $filters): array
    {
        $queryKey = http_build_query($filters);

        return Cache::remember("comparison-page-{$lang}-{$activeSlug}-{$queryKey}", 600, function () use ($lang, $activeSlug, $filters) {
            $offers = $this->applyFilters($this->demoOffers(), $filters);

            return [
                'title' => ucfirst($activeSlug),
                'summary' => $lang === 'en'
                    ? 'Compare offers by normalized dose, unit price, and daily cost.'
                    : ($lang === 'de'
                        ? 'Vergleiche Angebote nach normalisierter Dosis, Stückpreis und Tageskosten.'
                        : 'Vergelijk aanbiedingen op genormaliseerde dosis, stukprijs en dagprijs.'),
                'meta_title' => ucfirst($activeSlug) . ' vergelijken',
                'meta_description' => $lang === 'en'
                    ? 'Price comparison with safe normalization and clear fallbacks.'
                    : ($lang === 'de'
                        ? 'Preisvergleich mit sicherer Normalisierung und klaren Alternativen.'
                        : 'Prijsvergelijking met veilige normalisatie en duidelijke alternatieven.'),
                'active' => $activeSlug,
                'filters' => $filters,
                'offers' => new LengthAwarePaginator($offers, count($offers), 10),
                'sponsored' => $this->sponsoredEntries($lang, $activeSlug),
                'updated_at' => now()->toDateString(),
            ];
        });
    }

    public function productPage(string $lang, string $productKey): array
    {
        return [
            'title' => $productKey,
            'summary' => $lang === 'en'
                ? 'Product details and included actives.'
                : ($lang === 'de' ? 'Produktdetails und enthaltene Wirkstoffe.' : 'Productdetails en actieve stoffen.'),
            'meta_title' => $productKey,
            'meta_description' => $lang === 'en' ? 'Product detail page.' : ($lang === 'de' ? 'Produktdetailseite.' : 'Productdetailpagina.'),
            'product' => [
                'name' => $productKey,
                'brand' => 'Example Brand',
                'actives' => ['vitamin-d', 'magnesium'],
            ],
            'offers' => $this->demoOffers(),
            'updated_at' => now()->toDateString(),
        ];
    }

    public function offerMetrics(): array
    {
        return [
            'normalized_unit' => 'mg',
            'unit_price' => '€ / capsule',
            'daily_price' => '€ / day',
        ];
    }

    private function demoOffers(): array
    {
        return [
            [
                'brand' => 'PureLab',
                'shop' => 'Example Shop',
                'form_factor' => 'capsule',
                'price' => 12.99,
                'unit_price' => 0.22,
                'normalized_price' => 0.05,
                'daily_price' => 0.30,
                'best_value' => true,
            ],
            [
                'brand' => 'ActiveFuel',
                'shop' => 'Health Store',
                'form_factor' => 'powder',
                'price' => 19.95,
                'unit_price' => 0.18,
                'normalized_price' => 0.06,
                'daily_price' => 0.33,
                'best_value' => false,
            ],
        ];
    }

    private function applyFilters(array $offers, array $filters): array
    {
        return array_values(array_filter($offers, function ($offer) use ($filters) {
            foreach (['brand', 'shop', 'form_factor'] as $field) {
                if (!empty($filters[$field]) && $offer[$field] !== $filters[$field]) {
                    return false;
                }
            }

            return true;
        }));
    }

    private function sponsoredEntries(string $lang, string $activeSlug): array
    {
        if (!config('sponsored.enabled')) {
            return [];
        }

        return [
            [
                'title' => $lang === 'en' ? 'Sponsored pick' : ($lang === 'de' ? 'Gesponsert' : 'Gesponsord'),
                'description' => $lang === 'en' ? 'Partner offer for ' . $activeSlug : ($lang === 'de' ? 'Partnerangebot für ' . $activeSlug : 'Partneraanbod voor ' . $activeSlug),
                'target_url' => 'https://example.com',
            ],
        ];
    }
}
