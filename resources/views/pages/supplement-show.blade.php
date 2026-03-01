@extends('layouts.app')

@section('content')
    <article class="bg-white rounded-xl p-6 border border-slate-200">
        <h1 class="text-3xl font-semibold mb-2">{{ $page['title'] }}</h1>
        <p class="text-slate-600 mb-4">{{ $page['summary'] }}</p>
        <p class="text-sm text-slate-500">{{ $lang === 'en' ? 'Last updated' : ($lang === 'de' ? 'Zuletzt aktualisiert' : 'Laatst bijgewerkt') }}: {{ $page['updated_at'] }}</p>

        <section class="mt-6">
            <h2 class="text-xl font-semibold">TL;DR</h2>
            <ul class="list-disc pl-5 text-slate-600">
                @foreach($page['tldr'] as $item)
                    <li>{{ $item }}</li>
                @endforeach
            </ul>
        </section>

        <section class="mt-6">
            <h2 class="text-xl font-semibold">{{ $lang === 'en' ? 'What it is' : ($lang === 'de' ? 'Was ist das?' : 'Wat is het?') }}</h2>
            <ul class="list-disc pl-5 text-slate-600">
                @foreach($page['what_is'] as $item)
                    <li>{{ $item }}</li>
                @endforeach
            </ul>
        </section>

        <section class="mt-6">
            <h2 class="text-xl font-semibold">{{ $lang === 'en' ? 'What it does' : ($lang === 'de' ? 'Wofür ist es?' : 'Wat doet het?') }}</h2>
            <ul class="list-disc pl-5 text-slate-600">
                @foreach($page['benefits'] as $item)
                    <li>{{ $item }}</li>
                @endforeach
            </ul>
        </section>

        <section class="mt-6">
            <h2 class="text-xl font-semibold">{{ $lang === 'en' ? 'Who it may help' : ($lang === 'de' ? 'Für wen geeignet?' : 'Voor wie kan het helpen?') }}</h2>
            <div class="grid md:grid-cols-2 gap-4">
                <div class="bg-slate-50 p-4 rounded">
                    <h3 class="font-semibold">{{ $lang === 'en' ? 'Older adults' : ($lang === 'de' ? 'Ältere' : 'Ouderen') }}</h3>
                    <p class="text-sm text-slate-600">{{ $page['who_helps']['elderly'] ?? '' }}</p>
                </div>
                <div class="bg-slate-50 p-4 rounded">
                    <h3 class="font-semibold">{{ $lang === 'en' ? 'Sports' : ($lang === 'de' ? 'Sport' : 'Sporters') }}</h3>
                    <p class="text-sm text-slate-600">{{ $page['who_helps']['sports'] ?? '' }}</p>
                </div>
            </div>
        </section>

        <section class="mt-6">
            <h2 class="text-xl font-semibold">{{ $lang === 'en' ? 'How to take it' : ($lang === 'de' ? 'Einnahme' : 'Inname') }}</h2>
            <ul class="list-disc pl-5 text-slate-600">
                @foreach($page['how_to_take'] as $item)
                    <li>{{ $item }}</li>
                @endforeach
            </ul>
        </section>

        <section class="mt-6">
            <h2 class="text-xl font-semibold">{{ $lang === 'en' ? 'Dosing ranges' : ($lang === 'de' ? 'Dosierung' : 'Dosering') }}</h2>
            <div class="grid md:grid-cols-2 gap-4">
                @foreach($page['dosing'] as $dose)
                    <div class="border border-slate-200 rounded p-4">
                        <p class="text-sm text-slate-600">{{ ucfirst($dose['audience']) }}</p>
                        <p class="text-lg font-semibold">{{ $dose['min'] }}–{{ $dose['max'] }} {{ $dose['unit'] }}</p>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="mt-6">
            <h2 class="text-xl font-semibold">{{ $lang === 'en' ? 'Safety & interactions' : ($lang === 'de' ? 'Sicherheit & Wechselwirkungen' : 'Veiligheid & interacties') }}</h2>
            <ul class="list-disc pl-5 text-slate-600">
                @foreach($page['safety'] as $item)
                    <li>{{ $item }}</li>
                @endforeach
            </ul>
        </section>

        <section class="mt-6">
            <h2 class="text-xl font-semibold">FAQ</h2>
            <div class="space-y-3">
                @foreach($page['faq'] as $faq)
                    <div class="border border-slate-200 rounded p-4">
                        <p class="font-semibold">{{ $faq['q'] }}</p>
                        <p class="text-sm text-slate-600">{{ $faq['a'] }}</p>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="mt-6">
            <h2 class="text-xl font-semibold">{{ $lang === 'en' ? 'Evidence notes' : ($lang === 'de' ? 'Evidenz' : 'Bewijs') }}</h2>
            <ul class="list-disc pl-5 text-slate-600">
                @foreach($page['evidence'] as $item)
                    <li>{{ $item }}</li>
                @endforeach
            </ul>
        </section>
    </article>

    <section class="mt-8">
        <h2 class="text-2xl font-semibold mb-4">{{ $lang === 'en' ? 'Price snapshot' : ($lang === 'de' ? 'Preisübersicht' : 'Prijs overzicht') }}</h2>
        <div class="grid md:grid-cols-2 gap-4">
            @foreach($comparison['offers'] as $offer)
                <div class="bg-white border border-slate-200 rounded p-4">
                    <div class="flex items-center justify-between">
                        <h3 class="font-semibold">{{ $offer['brand'] }}</h3>
                        @if($offer['best_value'])
                            <span class="text-xs bg-emerald-100 text-emerald-700 px-2 py-1 rounded">{{ $lang === 'en' ? 'Best value' : ($lang === 'de' ? 'Bester Wert' : 'Beste waarde') }}</span>
                        @endif
                    </div>
                    <p class="text-sm text-slate-600">{{ $offer['shop'] }} · {{ $offer['form_factor'] }}</p>
                    <p class="mt-2 text-sm">€ {{ number_format($offer['normalized_price'], 2) }} / {{ $lang === 'en' ? 'normalized unit' : ($lang === 'de' ? 'Normalisierte Einheit' : 'genormaliseerde eenheid') }}</p>
                </div>
            @endforeach
        </div>
        <a class="inline-block mt-4 text-slate-700 underline" href="/{{ $lang }}/compare/{{ $page['slug'] }}">{{ $lang === 'en' ? 'View full comparison' : ($lang === 'de' ? 'Vollständigen Vergleich' : 'Volledige vergelijking') }}</a>
    </section>

    @if(!empty($jsonLd))
        <script type="application/ld+json">{!! json_encode($jsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    @endif
@endsection
