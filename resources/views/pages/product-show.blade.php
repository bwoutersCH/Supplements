@extends('layouts.app')

@section('content')
    <section class="bg-white rounded-xl p-6 border border-slate-200">
        <h1 class="text-3xl font-semibold mb-2">{{ $page['title'] }}</h1>
        <p class="text-slate-600">{{ $page['summary'] }}</p>
        <p class="text-sm text-slate-500 mt-2">{{ $lang === 'en' ? 'Last updated' : ($lang === 'de' ? 'Zuletzt aktualisiert' : 'Laatst bijgewerkt') }}: {{ $page['updated_at'] }}</p>
    </section>

    <section class="mt-6 grid md:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl p-6 border border-slate-200">
            <h2 class="text-xl font-semibold mb-2">{{ $lang === 'en' ? 'Included actives' : ($lang === 'de' ? 'Enthaltene Wirkstoffe' : 'Actieve stoffen') }}</h2>
            <ul class="list-disc pl-5 text-slate-600">
                @foreach($page['product']['actives'] as $active)
                    <li><a class="underline" href="/{{ $lang }}/supplement/{{ $active }}">{{ ucfirst($active) }}</a></li>
                @endforeach
            </ul>
        </div>
        <div class="bg-white rounded-xl p-6 border border-slate-200">
            <h2 class="text-xl font-semibold mb-2">{{ $lang === 'en' ? 'Available offers' : ($lang === 'de' ? 'Verfügbare Angebote' : 'Beschikbare aanbiedingen') }}</h2>
            <ul class="text-sm text-slate-600">
                @foreach($page['offers'] as $offer)
                    <li>{{ $offer['brand'] }} · € {{ number_format($offer['price'], 2) }}</li>
                @endforeach
            </ul>
        </div>
    </section>

    @if(!empty($jsonLd))
        <script type="application/ld+json">{!! json_encode($jsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    @endif
@endsection
