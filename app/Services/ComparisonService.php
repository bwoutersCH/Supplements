<?php

namespace App\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ComparisonService
{
    public function summaryForActive(string $lang, string $activeSlug): array
    {
        return Cache::remember("comparison-summary-{$lang}-{$activeSlug}", 300, function () use ($lang, $activeSlug) {
            $offers = $this->queryOffers($activeSlug, [], 5);

            return [
                'active' => $activeSlug,
                'highlights' => [
                    $lang === 'en'
                        ? 'Best value based on normalized dose where safe.'
                        : ($lang === 'de'
                            ? 'Bestes Preis-Leistungs-Verhältnis basierend auf sicherer Normalisierung.'
                            : 'Beste waarde op basis van veilige normalisatie.'),
                ],
                'offers' => $offers->items(),
            ];
        });
    }

    public function comparisonPage(string $lang, string $activeSlug, array $filters): array
    {
        $queryKey = md5(http_build_query($filters));

        return Cache::remember("comparison-page-{$lang}-{$activeSlug}-{$queryKey}", 300, function () use ($lang, $activeSlug, $filters) {
            $offers = $this->queryOffers($activeSlug, $filters, 20);

            return [
                'title' => ucfirst(str_replace('-', ' ', $activeSlug)),
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
                'offers' => $offers,
                'sponsored' => $this->sponsoredEntries($lang, $activeSlug),
                'updated_at' => now()->toDateString(),
            ];
        });
    }

    public function productPage(string $lang, string $productKey): array
    {
        $productsCols = $this->columns('products');
        $offersCols = $this->columns('offers');
        $paCols = $this->columns('product_actives');

        $productKeyCol = $this->firstExisting($productsCols, ['slug', 'product_key', 'key', 'id']);
        $productNameCol = $this->firstExisting($productsCols, ['name', 'title']);
        $productBrandCol = $this->firstExisting($productsCols, ['brand', 'brand_name']);

        $product = null;
        if ($productKeyCol) {
            $product = DB::table('products')
                ->where($productKeyCol, $productKey)
                ->first();
        }

        $productIdCol = $this->firstExisting($productsCols, ['id']);
        $paProductIdCol = $this->firstExisting($paCols, ['product_id']);
        $paActiveSlugCol = $this->firstExisting($paCols, ['active_slug', 'active', 'slug']);

        $actives = [];
        if ($product && $productIdCol && $paProductIdCol && $paActiveSlugCol) {
            $actives = DB::table('product_actives')
                ->where($paProductIdCol, $product->{$productIdCol})
                ->pluck($paActiveSlugCol)
                ->filter()
                ->values()
                ->all();
        }

        $offers = [];
        $offerProductIdCol = $this->firstExisting($offersCols, ['product_id']);
        if ($product && $productIdCol && $offerProductIdCol) {
            $offers = DB::table('offers')
                ->where($offerProductIdCol, $product->{$productIdCol})
                ->limit(20)
                ->get()
                ->map(fn ($row) => [
                    'brand' => $this->value($row, ['brand', 'brand_name']) ?? '-',
                    'shop' => $this->value($row, ['shop', 'shop_name']) ?? '-',
                    'form_factor' => $this->value($row, ['form_factor', 'form']) ?? '-',
                    'price' => (float)($this->value($row, ['price', 'offer_price']) ?? 0),
                    'unit_price' => null,
                    'normalized_price' => null,
                    'daily_price' => null,
                    'best_value' => false,
                ])
                ->all();
        }

        return [
            'title' => (string)($product?->{$productNameCol} ?? $productKey),
            'summary' => $lang === 'en'
                ? 'Product details and included actives.'
                : ($lang === 'de' ? 'Produktdetails und enthaltene Wirkstoffe.' : 'Productdetails en actieve stoffen.'),
            'meta_title' => (string)($product?->{$productNameCol} ?? $productKey),
            'meta_description' => $lang === 'en' ? 'Product detail page.' : ($lang === 'de' ? 'Produktdetailseite.' : 'Productdetailpagina.'),
            'product' => [
                'name' => (string)($product?->{$productNameCol} ?? $productKey),
                'brand' => (string)($product?->{$productBrandCol} ?? ''),
                'actives' => $actives,
            ],
            'offers' => $offers,
            'updated_at' => now()->toDateString(),
        ];
    }

    public function offerMetrics(): array
    {
        return [
            'normalized_unit' => 'active default unit',
            'unit_price' => '€ / unit',
            'daily_price' => '€ / day',
        ];
    }

    private function queryOffers(string $activeSlug, array $filters, int $perPage): LengthAwarePaginator
    {
        $cols = $this->columns('view_offer_active_metrics');

        // Candidate columns in your existing view (resolved dynamically).
        $activeCol = $this->firstExisting($cols, ['active_slug', 'active', 'slug']);
        $brandCol = $this->firstExisting($cols, ['brand', 'brand_name']);
        $shopCol = $this->firstExisting($cols, ['shop', 'shop_name', 'merchant']);
        $formCol = $this->firstExisting($cols, ['form_factor', 'form', 'delivery_form']);
        $doseCol = $this->firstExisting($cols, ['dose_value', 'active_amount', 'amount']);
        $unitPriceCol = $this->firstExisting($cols, ['unit_price_eur', 'unit_price', 'price_per_unit']);
        $normalizedCol = $this->firstExisting($cols, ['normalized_price_eur', 'normalized_price', 'price_per_normalized_unit']);
        $dailyCol = $this->firstExisting($cols, ['daily_price_eur', 'daily_price', 'price_per_day']);
        $totalPriceCol = $this->firstExisting($cols, ['price_eur', 'price', 'offer_price']);

        $query = DB::table('view_offer_active_metrics');

        if ($activeCol) {
            $query->where($activeCol, $activeSlug);
        }

        if (!empty($filters['brand']) && $brandCol) {
            $query->where($brandCol, $filters['brand']);
        }

        if (!empty($filters['shop']) && $shopCol) {
            $query->where($shopCol, $filters['shop']);
        }

        if (!empty($filters['form_factor']) && $formCol) {
            $query->where($formCol, $filters['form_factor']);
        }

        if (!empty($filters['dose_min']) && $doseCol) {
            $query->where($doseCol, '>=', (float)$filters['dose_min']);
        }

        if (!empty($filters['dose_max']) && $doseCol) {
            $query->where($doseCol, '<=', (float)$filters['dose_max']);
        }

        $sort = $filters['sort'] ?? 'normalized';
        if ($sort === 'unit' && $unitPriceCol) {
            $query->orderBy($unitPriceCol);
        } elseif ($sort === 'daily' && $dailyCol) {
            $query->orderBy($dailyCol);
        } elseif ($normalizedCol) {
            $query->orderBy($normalizedCol);
        } elseif ($unitPriceCol) {
            $query->orderBy($unitPriceCol);
        }

        $page = (int)request('page', 1);
        $rows = $query->forPage($page, $perPage)->get();
        $total = (clone $query)->count();

        $offers = $rows->map(function ($row) use ($brandCol, $shopCol, $formCol, $totalPriceCol, $unitPriceCol, $normalizedCol, $dailyCol) {
            return [
                'brand' => (string)($brandCol ? $row->{$brandCol} : '-'),
                'shop' => (string)($shopCol ? $row->{$shopCol} : '-'),
                'form_factor' => (string)($formCol ? $row->{$formCol} : '-'),
                'price' => (float)($totalPriceCol ? ($row->{$totalPriceCol} ?? 0) : 0),
                'unit_price' => $unitPriceCol ? (float)($row->{$unitPriceCol} ?? 0) : null,
                'normalized_price' => $normalizedCol ? (float)($row->{$normalizedCol} ?? 0) : null,
                'daily_price' => $dailyCol ? (float)($row->{$dailyCol} ?? 0) : null,
                'best_value' => false,
            ];
        })->values();

        // Best value marker.
        $bestIndex = null;
        $bestValue = INF;
        foreach ($offers as $idx => $offer) {
            $candidate = $offer['normalized_price'] ?? $offer['unit_price'] ?? INF;
            if ($candidate > 0 && $candidate < $bestValue) {
                $bestValue = $candidate;
                $bestIndex = $idx;
            }
        }
        if ($bestIndex !== null) {
            $offers[$bestIndex]['best_value'] = true;
        }

        return new LengthAwarePaginator(
            $offers->all(),
            $total,
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
    }

    private function sponsoredEntries(string $lang, string $activeSlug): array
    {
        if (!config('sponsored.enabled')) {
            return [];
        }

        $now = now();
        return DB::table('sponsored_entries')
            ->where('active_slug', $activeSlug)
            ->where('is_enabled', true)
            ->where(function ($q) use ($lang) {
                $q->whereNull('lang')->orWhere('lang', $lang);
            })
            ->where(function ($q) use ($now) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', $now);
            })
            ->where(function ($q) use ($now) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>=', $now);
            })
            ->orderByDesc('priority')
            ->limit(3)
            ->get(['title', 'description', 'target_url'])
            ->map(fn ($row) => [
                'title' => (string)$row->title,
                'description' => (string)($row->description ?? ''),
                'target_url' => (string)$row->target_url,
            ])
            ->all();
    }

    private function columns(string $table): array
    {
        return Cache::remember("schema-cols-{$table}", 3600, function () use ($table) {
            return DB::table('information_schema.columns')
                ->where('table_schema', 'public')
                ->where('table_name', $table)
                ->pluck('column_name')
                ->all();
        });
    }

    private function firstExisting(array $available, array $candidates): ?string
    {
        foreach ($candidates as $candidate) {
            if (in_array($candidate, $available, true)) {
                return $candidate;
            }
        }
        return null;
    }

    private function value(object $row, array $candidates): mixed
    {
        foreach ($candidates as $candidate) {
            if (property_exists($row, $candidate)) {
                return $row->{$candidate};
            }
        }
        return null;
    }
}