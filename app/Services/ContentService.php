<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ContentService
{
    public function homePage(string $lang): array
    {
        return Cache::remember("home-page-{$lang}", 300, function () use ($lang) {
            $popular = $this->activesByLang($lang, 6);

            return [
                'title' => $this->t($lang, 'home.title'),
                'summary' => $this->t($lang, 'home.summary'),
                'meta_title' => $this->t($lang, 'home.meta_title'),
                'meta_description' => $this->t($lang, 'home.meta_description'),
                'popular' => $popular,
                'disclaimer' => $this->t($lang, 'global.disclaimer'),
                'updated_at' => now()->toDateString(),
            ];
        });
    }

    public function supplementsIndex(string $lang, array $filters): array
    {
        $queryKey = md5(http_build_query($filters));

        return Cache::remember("supplements-index-{$lang}-{$queryKey}", 300, function () use ($lang, $filters) {
            $q = DB::table('actives as a')
                ->leftJoin('active_translations as t', function ($join) use ($lang) {
                    $join->on('t.active_id', '=', 'a.id')->where('t.lang', '=', $lang);
                })
                ->select(
                    'a.slug',
                    't.name',
                    DB::raw("coalesce(t.description_short, '') as summary"),
                    DB::raw("coalesce(t.meta_title, t.name, a.slug) as meta_title"),
                    DB::raw("coalesce(t.meta_description, '') as meta_description")
                );

            if (!empty($filters['query'])) {
                $needle = '%' . trim($filters['query']) . '%';
                $q->where(function ($sub) use ($needle) {
                    $sub->where('a.slug', 'ilike', $needle)
                        ->orWhere('t.name', 'ilike', $needle)
                        ->orWhere('t.description_short', 'ilike', $needle);
                });
            }

            if (!empty($filters['category'])) {
                $q->where('a.category', $filters['category']);
            }

            $actives = $q->orderBy('t.name')->get();

            $mapped = [];
            foreach ($actives as $row) {
                $mapped[$row->slug] = [
                    'name' => $row->name ?: ucfirst(str_replace('-', ' ', $row->slug)),
                    'summary' => $row->summary ?: $this->t($lang, 'active.summary'),
                    'meta_title' => $row->meta_title,
                    'meta_description' => $row->meta_description,
                ];
            }

            return [
                'title' => $this->t($lang, 'supplements.title'),
                'summary' => $this->t($lang, 'supplements.summary'),
                'meta_title' => $this->t($lang, 'supplements.meta_title'),
                'meta_description' => $this->t($lang, 'supplements.meta_description'),
                'actives' => $mapped,
                'updated_at' => now()->toDateString(),
            ];
        });
    }

    public function activePage(string $lang, string $activeSlug): array
    {
        return Cache::remember("active-page-{$lang}-{$activeSlug}", 300, function () use ($lang, $activeSlug) {
            $base = DB::table('actives as a')
                ->leftJoin('active_translations as t', function ($join) use ($lang) {
                    $join->on('t.active_id', '=', 'a.id')->where('t.lang', '=', $lang);
                })
                ->where('a.slug', $activeSlug)
                ->select(
                    'a.id',
                    'a.slug',
                    'a.default_unit',
                    'a.updated_at',
                    't.name',
                    't.description_short',
                    't.description_long',
                    't.meta_title',
                    't.meta_description'
                )
                ->first();

            if (!$base) {
                return [
                    'slug' => $activeSlug,
                    'title' => ucfirst(str_replace('-', ' ', $activeSlug)),
                    'summary' => $this->t($lang, 'active.summary'),
                    'meta_title' => $this->t($lang, 'active.meta_title'),
                    'meta_description' => $this->t($lang, 'active.meta_description'),
                    'tldr' => [],
                    'what_is' => [],
                    'benefits' => [],
                    'who_helps' => ['elderly' => '', 'sports' => ''],
                    'how_to_take' => [],
                    'dosing' => [],
                    'safety' => [],
                    'faq' => [],
                    'evidence' => [],
                    'disclaimer' => $this->t($lang, 'global.disclaimer'),
                    'updated_at' => now()->toDateString(),
                ];
            }

            // General content first
            $general = DB::table('active_content')
                ->where('active_id', $base->id)
                ->where('lang', $lang)
                ->where('audience', 'general')
                ->first();

            $generalSections = $general ? (array) json_decode((string) $general->sections, true) : [];
            $sources = $general ? (array) json_decode((string) $general->sources, true) : [];

            // Audience-specific content
            $elderly = DB::table('active_content')
                ->where('active_id', $base->id)
                ->where('lang', $lang)
                ->where('audience', 'elderly')
                ->first();

            $sports = DB::table('active_content')
                ->where('active_id', $base->id)
                ->where('lang', $lang)
                ->where('audience', 'sports')
                ->first();

            $elderlySections = $elderly ? (array) json_decode((string) $elderly->sections, true) : [];
            $sportsSections = $sports ? (array) json_decode((string) $sports->sections, true) : [];

            $doses = DB::table('recommended_doses')
                ->where('active_id', $base->id)
                ->orderByRaw("case audience when 'general' then 1 when 'elderly' then 2 when 'sports' then 3 else 4 end")
                ->get()
                ->map(fn ($d) => [
                    'min' => (float) $d->min_value,
                    'max' => (float) $d->max_value,
                    'unit' => (string) $d->unit,
                    'audience' => (string) $d->audience,
                    'notes' => (string) ($d->notes ?? ''),
                    'region' => (string) ($d->region ?? ''),
                ])
                ->all();

            return [
                'slug' => $base->slug,
                'title' => $base->name ?: ucfirst(str_replace('-', ' ', $base->slug)),
                'summary' => $base->description_short ?: $this->t($lang, 'active.summary'),
                'meta_title' => $base->meta_title ?: ($base->name ?: ucfirst(str_replace('-', ' ', $base->slug))),
                'meta_description' => $base->meta_description ?: $this->t($lang, 'active.meta_description'),

                'tldr' => $this->arr($generalSections['tldr'] ?? []),
                'what_is' => $this->arr($generalSections['what_is'] ?? [$base->description_long ?: '']),
                'benefits' => $this->arr($generalSections['benefits'] ?? []),

                'who_helps' => [
                    'elderly' => (string) ($elderlySections['who_helps'] ?? ''),
                    'sports' => (string) ($sportsSections['who_helps'] ?? ''),
                ],

                'how_to_take' => $this->arr($generalSections['how_to_take'] ?? []),
                'dosing' => $doses,
                'safety' => $this->arr($generalSections['safety'] ?? []),
                'faq' => $this->arr($generalSections['faq'] ?? []),
                'evidence' => $this->arr($generalSections['evidence'] ?? []),
                'sources' => $sources,

                'disclaimer' => $this->t($lang, 'global.disclaimer'),
                'updated_at' => optional($base->updated_at)->toDateString() ?? now()->toDateString(),
            ];
        });
    }

    public function goalPage(string $lang, string $goalSlug): array
    {
        return Cache::remember("goal-page-{$lang}-{$goalSlug}", 300, function () use ($lang, $goalSlug) {
            $goal = DB::table('goals as g')
                ->leftJoin('goal_translations as gt', function ($join) use ($lang) {
                    $join->on('gt.goal_id', '=', 'g.id')->where('gt.lang', '=', $lang);
                })
                ->where('g.slug', $goalSlug)
                ->select('g.id', 'g.slug', 'gt.name', 'gt.description')
                ->first();

            $actives = [];
            if ($goal) {
                $rows = DB::table('active_goals as ag')
                    ->join('actives as a', 'a.id', '=', 'ag.active_id')
                    ->leftJoin('active_translations as at', function ($join) use ($lang) {
                        $join->on('at.active_id', '=', 'a.id')->where('at.lang', '=', $lang);
                    })
                    ->where('ag.goal_id', $goal->id)
                    ->select('a.slug', 'at.name', 'at.description_short')
                    ->orderBy('at.name')
                    ->get();

                foreach ($rows as $row) {
                    $actives[$row->slug] = [
                        'name' => $row->name ?: ucfirst(str_replace('-', ' ', $row->slug)),
                        'summary' => $row->description_short ?: '',
                    ];
                }
            }

            return [
                'title' => $goal?->name ?: ucfirst(str_replace('-', ' ', $goalSlug)),
                'summary' => $goal?->description ?: $this->t($lang, 'goals.summary'),
                'meta_title' => $goal?->name ?: $this->t($lang, 'goals.meta_title'),
                'meta_description' => $goal?->description ?: $this->t($lang, 'goals.meta_description'),
                'actives' => $actives,
                'updated_at' => now()->toDateString(),
            ];
        });
    }

    public function staticPage(string $lang, string $slug): array
    {
        // Keep static/legal pages as static translations for now.
        return [
            'title' => $this->t($lang, "static.{$slug}.title"),
            'summary' => $this->t($lang, "static.{$slug}.summary"),
            'content' => $this->t($lang, "static.{$slug}.content"),
            'meta_title' => $this->t($lang, "static.{$slug}.meta_title"),
            'meta_description' => $this->t($lang, "static.{$slug}.meta_description"),
            'updated_at' => now()->toDateString(),
        ];
    }

    private function activesByLang(string $lang, int $limit = 6): array
    {
        $rows = DB::table('actives as a')
            ->leftJoin('active_translations as t', function ($join) use ($lang) {
                $join->on('t.active_id', '=', 'a.id')->where('t.lang', '=', $lang);
            })
            ->select(
                'a.slug',
                't.name',
                DB::raw("coalesce(t.description_short, '') as summary"),
                DB::raw("coalesce(t.meta_title, t.name, a.slug) as meta_title"),
                DB::raw("coalesce(t.meta_description, '') as meta_description")
            )
            ->orderBy('t.name')
            ->limit($limit)
            ->get();

        $mapped = [];
        foreach ($rows as $row) {
            $mapped[$row->slug] = [
                'name' => $row->name ?: ucfirst(str_replace('-', ' ', $row->slug)),
                'summary' => $row->summary ?: '',
                'meta_title' => $row->meta_title,
                'meta_description' => $row->meta_description,
            ];
        }

        return $mapped;
    }

    private function arr(mixed $value): array
    {
        if (is_array($value)) {
            return $value;
        }

        if (is_string($value) && $value !== '') {
            return [$value];
        }

        return [];
    }

    private function t(string $lang, string $key): string
    {
        $translations = $this->translations();
        return $translations[$lang][$key] ?? $translations['nl'][$key] ?? $key;
    }

    private function translations(): array
    {
        // Keep this small static translation set for UI labels/legal pages.
        return [
            'nl' => [
                'home.title' => 'Supplementen vergelijken & begrijpen',
                'home.summary' => 'Vergelijk prijzen per werkzame stof, ontdek veilige doseringen en lees duidelijke uitleg.',
                'home.meta_title' => 'Supplementen vergelijken in Nederland',
                'home.meta_description' => 'Vind betrouwbare informatie en vergelijk supplementen op prijs per werkzame stof.',
                'supplements.title' => 'Alle supplementen',
                'supplements.summary' => 'Zoek op vitamine, mineraal of sportsupplement en ontdek betrouwbare uitleg.',
                'supplements.meta_title' => 'Supplementen overzicht',
                'supplements.meta_description' => 'Alle actieve stoffen met uitleg, veiligheid en prijsvergelijking.',
                'active.summary' => 'Heldere uitleg, veiligheid en prijsvergelijking zonder medische claims.',
                'active.meta_title' => 'Supplement uitleg',
                'active.meta_description' => 'TL;DR, dosering, veiligheid en prijsvergelijking voor supplementen.',
                'goals.summary' => 'Ontdek supplementen die vaak gekozen worden bij dit doel.',
                'goals.meta_title' => 'Supplementen per doel',
                'goals.meta_description' => 'Vind actieve stoffen die passen bij een gezondheidsdoel.',
                'global.disclaimer' => 'Informatie is informatief en vervangt geen medisch advies.',
                'static.about.title' => 'Over Supplements',
                'static.about.summary' => 'Waarom wij supplementen helder en eerlijk vergelijken.',
                'static.about.content' => 'Wij bieden onafhankelijke uitleg en prijsvergelijkingen op basis van duidelijke meeteenheden.',
                'static.about.meta_title' => 'Over ons',
                'static.about.meta_description' => 'Lees hoe wij supplementen vergelijken.',
                'static.disclaimer.title' => 'Disclaimer',
                'static.disclaimer.summary' => 'Belangrijke veiligheidsinformatie.',
                'static.disclaimer.content' => 'Gebruik deze informatie niet als vervanging van medisch advies.',
                'static.disclaimer.meta_title' => 'Disclaimer',
                'static.disclaimer.meta_description' => 'Lees de disclaimer voor supplementen.',
                'static.privacy.title' => 'Privacy',
                'static.privacy.summary' => 'Hoe wij met gegevens omgaan.',
                'static.privacy.content' => 'Wij gebruiken minimale data en respecteren jouw privacy.',
                'static.privacy.meta_title' => 'Privacybeleid',
                'static.privacy.meta_description' => 'Lees het privacybeleid.',
                'static.how-we-compare.title' => 'Hoe wij vergelijken',
                'static.how-we-compare.summary' => 'Uitleg over normalisatie, eenheden en beperkingen.',
                'static.how-we-compare.content' => 'We rekenen om naar veilige, vergelijkbare eenheden en markeren beperkingen.',
                'static.how-we-compare.meta_title' => 'Vergelijkingsmethode',
                'static.how-we-compare.meta_description' => 'Lees hoe we supplementen vergelijken.',
            ],
            'en' => [
                'home.title' => 'Compare & understand supplements',
                'home.summary' => 'Compare prices by active ingredient, see safe dosing, and read clear explanations.',
                'home.meta_title' => 'Supplement comparison in the Netherlands',
                'home.meta_description' => 'Reliable information and price comparison by active ingredient.',
                'supplements.title' => 'All supplements',
                'supplements.summary' => 'Search vitamins, minerals, and sports supplements with clear guidance.',
                'supplements.meta_title' => 'Supplement overview',
                'supplements.meta_description' => 'Active ingredients with safety and price comparison.',
                'active.summary' => 'Clear explanations, safety notes, and price comparisons without medical claims.',
                'active.meta_title' => 'Supplement guide',
                'active.meta_description' => 'TL;DR, dosing, safety, and price comparisons.',
                'goals.summary' => 'Discover supplements commonly chosen for this goal.',
                'goals.meta_title' => 'Supplements by goal',
                'goals.meta_description' => 'Find actives aligned with health goals.',
                'global.disclaimer' => 'Information is educational and not medical advice.',
                'static.about.title' => 'About Supplements',
                'static.about.summary' => 'Why we compare supplements clearly and fairly.',
                'static.about.content' => 'We provide independent explanations and price comparisons with clear units.',
                'static.about.meta_title' => 'About us',
                'static.about.meta_description' => 'Learn how we compare supplements.',
                'static.disclaimer.title' => 'Disclaimer',
                'static.disclaimer.summary' => 'Important safety information.',
                'static.disclaimer.content' => 'Do not use this information as a substitute for medical advice.',
                'static.disclaimer.meta_title' => 'Disclaimer',
                'static.disclaimer.meta_description' => 'Read the supplement disclaimer.',
                'static.privacy.title' => 'Privacy',
                'static.privacy.summary' => 'How we handle data.',
                'static.privacy.content' => 'We collect minimal data and respect your privacy.',
                'static.privacy.meta_title' => 'Privacy policy',
                'static.privacy.meta_description' => 'Read the privacy policy.',
                'static.how-we-compare.title' => 'How we compare',
                'static.how-we-compare.summary' => 'Normalization, units, and limitations explained.',
                'static.how-we-compare.content' => 'We convert to safe comparable units and flag limitations.',
                'static.how-we-compare.meta_title' => 'Comparison methodology',
                'static.how-we-compare.meta_description' => 'Learn how we compare supplements.',
            ],
            'de' => [
                'home.title' => 'Nahrungsergänzung vergleichen & verstehen',
                'home.summary' => 'Preise pro Wirkstoff vergleichen, sichere Dosierungen sehen und klare Erklärungen lesen.',
                'home.meta_title' => 'Supplementvergleich in den Niederlanden',
                'home.meta_description' => 'Zuverlässige Informationen und Preisvergleich nach Wirkstoff.',
                'supplements.title' => 'Alle Supplemente',
                'supplements.summary' => 'Suche Vitamine, Mineralstoffe und Sport-Supplemente mit klaren Hinweisen.',
                'supplements.meta_title' => 'Supplement-Übersicht',
                'supplements.meta_description' => 'Wirkstoffe mit Sicherheit und Preisvergleich.',
                'active.summary' => 'Klare Erklärungen, Sicherheitshinweise und Preisvergleich ohne medizinische Versprechen.',
                'active.meta_title' => 'Supplement-Leitfaden',
                'active.meta_description' => 'TL;DR, Dosierung, Sicherheit und Preisvergleich.',
                'goals.summary' => 'Entdecke Supplemente, die häufig für dieses Ziel gewählt werden.',
                'goals.meta_title' => 'Supplemente nach Ziel',
                'goals.meta_description' => 'Finde Wirkstoffe passend zu Gesundheitszielen.',
                'global.disclaimer' => 'Informationen sind informativ und kein medizinischer Rat.',
                'static.about.title' => 'Über Supplements',
                'static.about.summary' => 'Warum wir Supplemente klar und fair vergleichen.',
                'static.about.content' => 'Wir bieten unabhängige Erklärungen und Preisvergleiche mit klaren Einheiten.',
                'static.about.meta_title' => 'Über uns',
                'static.about.meta_description' => 'Erfahre, wie wir Supplemente vergleichen.',
                'static.disclaimer.title' => 'Haftungsausschluss',
                'static.disclaimer.summary' => 'Wichtige Sicherheitshinweise.',
                'static.disclaimer.content' => 'Diese Informationen ersetzen keine medizinische Beratung.',
                'static.disclaimer.meta_title' => 'Haftungsausschluss',
                'static.disclaimer.meta_description' => 'Lies den Haftungsausschluss.',
                'static.privacy.title' => 'Datenschutz',
                'static.privacy.summary' => 'Wie wir mit Daten umgehen.',
                'static.privacy.content' => 'Wir sammeln minimale Daten und respektieren deine Privatsphäre.',
                'static.privacy.meta_title' => 'Datenschutzrichtlinie',
                'static.privacy.meta_description' => 'Lies die Datenschutzrichtlinie.',
                'static.how-we-compare.title' => 'So vergleichen wir',
                'static.how-we-compare.summary' => 'Normalisierung, Einheiten und Einschränkungen erklärt.',
                'static.how-we-compare.content' => 'Wir rechnen in sichere vergleichbare Einheiten um und kennzeichnen Grenzen.',
                'static.how-we-compare.meta_title' => 'Vergleichsmethode',
                'static.how-we-compare.meta_description' => 'Lerne, wie wir Supplemente vergleichen.',
            ],
        ];
    }
}