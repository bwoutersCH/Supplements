<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class ContentService
{
    public function homePage(string $lang): array
    {
        return Cache::remember("home-page-{$lang}", 900, function () use ($lang) {
            return [
                'title' => $this->t($lang, 'home.title'),
                'summary' => $this->t($lang, 'home.summary'),
                'meta_title' => $this->t($lang, 'home.meta_title'),
                'meta_description' => $this->t($lang, 'home.meta_description'),
                'popular' => $this->seedActives($lang),
                'disclaimer' => $this->t($lang, 'global.disclaimer'),
                'updated_at' => now()->toDateString(),
            ];
        });
    }

    public function supplementsIndex(string $lang, array $filters): array
    {
        $queryKey = http_build_query($filters);

        return Cache::remember("supplements-index-{$lang}-{$queryKey}", 600, function () use ($lang) {
            return [
                'title' => $this->t($lang, 'supplements.title'),
                'summary' => $this->t($lang, 'supplements.summary'),
                'meta_title' => $this->t($lang, 'supplements.meta_title'),
                'meta_description' => $this->t($lang, 'supplements.meta_description'),
                'actives' => $this->seedActives($lang),
                'updated_at' => now()->toDateString(),
            ];
        });
    }

    public function activePage(string $lang, string $activeSlug): array
    {
        return Cache::remember("active-page-{$lang}-{$activeSlug}", 900, function () use ($lang, $activeSlug) {
            $active = $this->seedActives($lang)[$activeSlug] ?? null;

            return [
                'slug' => $activeSlug,
                'title' => $active['name'] ?? ucfirst($activeSlug),
                'summary' => $active['summary'] ?? $this->t($lang, 'active.summary'),
                'meta_title' => $active['meta_title'] ?? $this->t($lang, 'active.meta_title'),
                'meta_description' => $active['meta_description'] ?? $this->t($lang, 'active.meta_description'),
                'tldr' => $active['tldr'] ?? [],
                'what_is' => $active['what_is'] ?? [],
                'benefits' => $active['benefits'] ?? [],
                'who_helps' => $active['who_helps'] ?? [],
                'how_to_take' => $active['how_to_take'] ?? [],
                'dosing' => $active['dosing'] ?? [],
                'safety' => $active['safety'] ?? [],
                'faq' => $active['faq'] ?? [],
                'evidence' => $active['evidence'] ?? [],
                'disclaimer' => $this->t($lang, 'global.disclaimer'),
                'updated_at' => now()->toDateString(),
            ];
        });
    }

    public function goalPage(string $lang, string $goalSlug): array
    {
        return [
            'title' => ucfirst($goalSlug),
            'summary' => $this->t($lang, 'goals.summary'),
            'meta_title' => $this->t($lang, 'goals.meta_title'),
            'meta_description' => $this->t($lang, 'goals.meta_description'),
            'actives' => $this->seedActives($lang),
            'updated_at' => now()->toDateString(),
        ];
    }

    public function staticPage(string $lang, string $slug): array
    {
        return [
            'title' => $this->t($lang, "static.{$slug}.title"),
            'summary' => $this->t($lang, "static.{$slug}.summary"),
            'content' => $this->t($lang, "static.{$slug}.content"),
            'meta_title' => $this->t($lang, "static.{$slug}.meta_title"),
            'meta_description' => $this->t($lang, "static.{$slug}.meta_description"),
            'updated_at' => now()->toDateString(),
        ];
    }

    private function t(string $lang, string $key): string
    {
        $translations = $this->translations();

        return $translations[$lang][$key] ?? $translations['nl'][$key] ?? $key;
    }

    private function translations(): array
    {
        return [
            'nl' => [
                'home.title' => 'Supplementen vergelijken & begrijpen',
                'home.summary' => 'Vergelijk prijzen per werkzame stof, ontdek veilige doseringen en lees duidelijke uitleg.',
                'home.meta_title' => 'Supplementen vergelijken in Nederland',
                'home.meta_description' => 'Vind betrouwbare informatie en vergelijk supplementen op prijs per werkzame stof.',
                'supplements.title' => 'Alle supplementen',
                'supplements.summary' => 'Zoek op vitamine, mineraal of sport supplement en ontdek betrouwbare uitleg.',
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

    private function seedActives(string $lang): array
    {
        return [
            'vitamin-d' => [
                'name' => $lang === 'de' ? 'Vitamin D' : 'Vitamin D',
                'summary' => $lang === 'en'
                    ? 'Vitamin D supports bone health and muscle function when used responsibly.'
                    : ($lang === 'de'
                        ? 'Vitamin D unterstützt bei verantwortungsvoller Anwendung Knochen und Muskeln.'
                        : 'Vitamine D ondersteunt bij verantwoord gebruik de botten en spieren.'),
                'meta_title' => 'Vitamin D',
                'meta_description' => 'Vitamin D uitleg, dosering en vergelijking.',
                'tldr' => [
                    $lang === 'en' ? 'Use D3 for most people; D2 for plant-based options.' : ($lang === 'de' ? 'D3 ist meist die Standardwahl; D2 für pflanzliche Optionen.' : 'D3 is meestal de standaard; D2 voor plantaardige opties.'),
                    $lang === 'en' ? 'Common daily range: 10–20 µg depending on age.' : ($lang === 'de' ? 'Übliche Tagesmenge: 10–20 µg je nach Alter.' : 'Gebruikelijk: 10–20 µg per dag afhankelijk van leeftijd.'),
                ],
                'what_is' => [
                    $lang === 'en' ? 'Fat-soluble vitamin important for calcium balance.' : ($lang === 'de' ? 'Fettlösliches Vitamin für den Calciumhaushalt.' : 'Vetoplosbare vitamine voor calciumhuishouding.'),
                ],
                'benefits' => [
                    $lang === 'en' ? 'Supports normal bone and muscle function.' : ($lang === 'de' ? 'Unterstützt normale Knochen- und Muskelfunktion.' : 'Ondersteunt normale bot- en spierfunctie.'),
                ],
                'who_helps' => [
                    'elderly' => $lang === 'en' ? 'Older adults with limited sun exposure.' : ($lang === 'de' ? 'Ältere Menschen mit wenig Sonne.' : 'Ouderen met weinig zon.'),
                    'sports' => $lang === 'en' ? 'Athletes training indoors or in winter.' : ($lang === 'de' ? 'Sportler, die viel drinnen trainieren.' : 'Sporters die veel binnen trainen.'),
                ],
                'how_to_take' => [
                    $lang === 'en' ? 'Take with a meal containing fat.' : ($lang === 'de' ? 'Mit einer Mahlzeit mit Fett einnehmen.' : 'Neem in met een maaltijd met vet.'),
                ],
                'dosing' => [
                    ['min' => 10, 'max' => 20, 'unit' => 'µg', 'audience' => 'general'],
                ],
                'safety' => [
                    $lang === 'en' ? 'Consult a clinician when using higher doses or with kidney issues.' : ($lang === 'de' ? 'Bei höheren Dosen oder Nierenproblemen ärztlich beraten lassen.' : 'Overleg bij hogere doseringen of nierproblemen.'),
                ],
                'faq' => [
                    ['q' => $lang === 'en' ? 'D2 or D3?' : ($lang === 'de' ? 'D2 oder D3?' : 'D2 of D3?'), 'a' => $lang === 'en' ? 'D3 is typically preferred, D2 is plant-based.' : ($lang === 'de' ? 'D3 wird meist bevorzugt, D2 ist pflanzlich.' : 'D3 heeft meestal voorkeur, D2 is plantaardig.')],
                    ['q' => $lang === 'en' ? 'When to take it?' : ($lang === 'de' ? 'Wann einnehmen?' : 'Wanneer innemen?'), 'a' => $lang === 'en' ? 'With food for better absorption.' : ($lang === 'de' ? 'Mit einer Mahlzeit einnehmen.' : 'Met een maaltijd innemen.')],
                    ['q' => $lang === 'en' ? 'Is it safe daily?' : ($lang === 'de' ? 'Ist es täglich sicher?' : 'Dagelijks veilig?'), 'a' => $lang === 'en' ? 'Stay within recommended ranges unless advised.' : ($lang === 'de' ? 'Im empfohlenen Bereich bleiben.' : 'Blijf binnen aanbevolen grenzen.')],
                ],
                'evidence' => [
                    $lang === 'en' ? 'Evidence supports bone health outcomes; effects depend on baseline levels.' : ($lang === 'de' ? 'Evidenz zeigt Nutzen für Knochen, abhängig vom Ausgangswert.' : 'Bewijs ondersteunt botgezondheid; effect hangt af van startwaarde.'),
                ],
            ],
            'magnesium' => [
                'name' => $lang === 'de' ? 'Magnesium' : 'Magnesium',
                'summary' => $lang === 'en'
                    ? 'Magnesium supports muscle and nerve function.'
                    : ($lang === 'de' ? 'Magnesium unterstützt Muskel- und Nervenfunktion.' : 'Magnesium ondersteunt spier- en zenuwfunctie.'),
                'meta_title' => 'Magnesium',
                'meta_description' => 'Magnesium uitleg, vormen en vergelijking.',
                'tldr' => [
                    $lang === 'en' ? 'Bisglycinate is gentle; citrate can be more noticeable.' : ($lang === 'de' ? 'Bisglycinat ist sanft; Citrat wirkt spürbarer.' : 'Bisglycinaat is mild; citraat kan meer merkbaar zijn.'),
                    $lang === 'en' ? 'Dose ranges depend on diet and activity.' : ($lang === 'de' ? 'Dosierung hängt von Ernährung und Aktivität ab.' : 'Dosering hangt af van voeding en activiteit.'),
                ],
                'what_is' => [
                    $lang === 'en' ? 'Essential mineral involved in energy metabolism.' : ($lang === 'de' ? 'Essentieller Mineralstoff für den Energiestoffwechsel.' : 'Essentieel mineraal voor energiestofwisseling.'),
                ],
                'benefits' => [
                    $lang === 'en' ? 'Supports normal muscle function and recovery.' : ($lang === 'de' ? 'Unterstützt normale Muskelfunktion und Erholung.' : 'Ondersteunt normale spierfunctie en herstel.'),
                ],
                'who_helps' => [
                    'elderly' => $lang === 'en' ? 'Older adults with lower dietary intake.' : ($lang === 'de' ? 'Ältere Menschen mit geringer Aufnahme.' : 'Ouderen met lage inname.'),
                    'sports' => $lang === 'en' ? 'Athletes with higher sweat losses.' : ($lang === 'de' ? 'Sportler mit höherem Schweißverlust.' : 'Sporters met meer zweetverlies.'),
                ],
                'how_to_take' => [
                    $lang === 'en' ? 'Split doses to reduce stomach upset.' : ($lang === 'de' ? 'Dosen aufteilen, um den Magen zu schonen.' : 'Verdeel doses om maagklachten te verminderen.'),
                ],
                'dosing' => [
                    ['min' => 200, 'max' => 350, 'unit' => 'mg', 'audience' => 'general'],
                ],
                'safety' => [
                    $lang === 'en' ? 'High doses may cause gastrointestinal discomfort.' : ($lang === 'de' ? 'Hohe Dosen können Magen-Darm-Beschwerden verursachen.' : 'Hoge doseringen kunnen maag-darmklachten geven.'),
                ],
                'faq' => [
                    ['q' => $lang === 'en' ? 'Citrate or bisglycinate?' : ($lang === 'de' ? 'Citrat oder Bisglycinat?' : 'Citraat of bisglycinaat?'), 'a' => $lang === 'en' ? 'Bisglycinate is gentle; citrate can be more noticeable.' : ($lang === 'de' ? 'Bisglycinat ist sanft; Citrat wirkt stärker.' : 'Bisglycinaat is mild; citraat kan sterker zijn.')],
                    ['q' => $lang === 'en' ? 'Best time to take?' : ($lang === 'de' ? 'Beste Einnahmezeit?' : 'Beste tijdstip?'), 'a' => $lang === 'en' ? 'With meals or split morning/evening.' : ($lang === 'de' ? 'Mit Mahlzeiten oder morgens/abends.' : 'Met maaltijden of gesplitst ochtend/avond.')],
                    ['q' => $lang === 'en' ? 'Can I combine forms?' : ($lang === 'de' ? 'Formen kombinieren?' : 'Vormen combineren?'), 'a' => $lang === 'en' ? 'Yes, as long as total intake stays reasonable.' : ($lang === 'de' ? 'Ja, solange die Gesamtdosis moderat bleibt.' : 'Ja, zolang totale dosis redelijk blijft.')],
                ],
                'evidence' => [
                    $lang === 'en' ? 'Evidence supports roles in muscle function and energy metabolism.' : ($lang === 'de' ? 'Evidenz für Rolle in Muskelfunktion und Energie.' : 'Bewijs voor rol in spierfunctie en energie.'),
                ],
            ],
            'creatine' => [
                'name' => $lang === 'de' ? 'Kreatin' : 'Creatine',
                'summary' => $lang === 'en'
                    ? 'Creatine monohydrate supports high-intensity performance.'
                    : ($lang === 'de' ? 'Kreatin-Monohydrat unterstützt hohe Leistungsintensität.' : 'Creatine monohydraat ondersteunt hoge intensiteit.'),
                'meta_title' => 'Creatine',
                'meta_description' => 'Creatine uitleg en vergelijking.',
                'tldr' => [
                    $lang === 'en' ? 'Monohydrate is the most studied form.' : ($lang === 'de' ? 'Monohydrat ist die am besten untersuchte Form.' : 'Monohydraat is het meest onderzochte.'),
                    $lang === 'en' ? 'Typical dose: 3–5 g per day.' : ($lang === 'de' ? 'Typische Dosis: 3–5 g pro Tag.' : 'Gebruikelijk: 3–5 g per dag.'),
                ],
                'what_is' => [
                    $lang === 'en' ? 'Naturally occurring compound stored in muscles.' : ($lang === 'de' ? 'Natürlich vorkommende Verbindung in Muskeln.' : 'Natuurlijk voorkomende stof in spieren.'),
                ],
                'benefits' => [
                    $lang === 'en' ? 'Supports short bursts of high power output.' : ($lang === 'de' ? 'Unterstützt kurze, intensive Belastungen.' : 'Ondersteunt korte, intensieve inspanningen.'),
                ],
                'who_helps' => [
                    'elderly' => $lang === 'en' ? 'Older adults focusing on strength maintenance.' : ($lang === 'de' ? 'Ältere zur Kraftunterstützung.' : 'Ouderen gericht op krachtbehoud.'),
                    'sports' => $lang === 'en' ? 'Athletes in strength and sprint sports.' : ($lang === 'de' ? 'Sportler in Kraft- und Sprintdisziplinen.' : 'Sporters in kracht- en sprintdisciplines.'),
                ],
                'how_to_take' => [
                    $lang === 'en' ? 'Take daily with water; consistency matters.' : ($lang === 'de' ? 'Täglich mit Wasser einnehmen; Regelmäßigkeit zählt.' : 'Neem dagelijks met water; consistentie telt.'),
                ],
                'dosing' => [
                    ['min' => 3, 'max' => 5, 'unit' => 'g', 'audience' => 'sports'],
                ],
                'safety' => [
                    $lang === 'en' ? 'Generally well-tolerated; consult if kidney concerns.' : ($lang === 'de' ? 'Meist gut verträglich; bei Nierenproblemen beraten lassen.' : 'Meestal goed verdragen; overleg bij nierproblemen.'),
                ],
                'faq' => [
                    ['q' => $lang === 'en' ? 'Is loading required?' : ($lang === 'de' ? 'Ladephase nötig?' : 'Laden nodig?'), 'a' => $lang === 'en' ? 'No, steady daily intake works well.' : ($lang === 'de' ? 'Nein, tägliche Einnahme reicht.' : 'Nee, dagelijks nemen werkt ook.')],
                    ['q' => $lang === 'en' ? 'When to take?' : ($lang === 'de' ? 'Wann einnehmen?' : 'Wanneer innemen?'), 'a' => $lang === 'en' ? 'Any time of day; with carbs can be convenient.' : ($lang === 'de' ? 'Beliebige Tageszeit; mit Kohlenhydraten praktisch.' : 'Elke tijd; met koolhydraten kan handig zijn.')],
                    ['q' => $lang === 'en' ? 'Does it cause water retention?' : ($lang === 'de' ? 'Wassereinlagerung?' : 'Waterretentie?'), 'a' => $lang === 'en' ? 'Some people notice a small increase in water weight.' : ($lang === 'de' ? 'Manche bemerken etwas mehr Wassergewicht.' : 'Sommigen merken iets meer watergewicht.')],
                ],
                'evidence' => [
                    $lang === 'en' ? 'Strong evidence for strength and power performance.' : ($lang === 'de' ? 'Starke Evidenz für Kraft und Leistung.' : 'Sterk bewijs voor kracht en prestatie.'),
                ],
            ],
        ];
    }
}
