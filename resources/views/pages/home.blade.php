@extends('layouts.app')

@section('content')
    <section class="bg-white rounded-xl p-6 shadow-sm border border-slate-200">
        <h1 class="text-3xl font-semibold mb-2">{{ $page['title'] }}</h1>
        <p class="text-slate-600 mb-6">{{ $page['summary'] }}</p>
        <form class="flex gap-2" method="get" action="/{{ $lang }}/supplements">
            <input name="query" class="flex-1 border border-slate-300 rounded px-3 py-2" placeholder="{{ $lang === 'en' ? 'Search supplements' : ($lang === 'de' ? 'Supplemente suchen' : 'Zoek supplementen') }}">
            <button class="bg-slate-900 text-white px-4 py-2 rounded">{{ $lang === 'en' ? 'Search' : ($lang === 'de' ? 'Suchen' : 'Zoeken') }}</button>
        </form>
    </section>

    <section class="mt-8">
        <h2 class="text-2xl font-semibold mb-4">{{ $lang === 'en' ? 'Popular comparisons' : ($lang === 'de' ? 'Beliebte Vergleiche' : 'Populaire vergelijkingen') }}</h2>
        <div class="grid md:grid-cols-3 gap-4">
            @foreach($page['popular'] as $slug => $active)
                <a class="bg-white p-4 rounded-lg border border-slate-200" href="/{{ $lang }}/supplement/{{ $slug }}">
                    <h3 class="font-semibold">{{ $active['name'] }}</h3>
                    <p class="text-sm text-slate-600">{{ $active['summary'] }}</p>
                </a>
            @endforeach
        </div>
    </section>

    <section class="mt-8 grid md:grid-cols-2 gap-6">
        <div class="bg-white p-6 rounded-lg border border-slate-200">
            <h2 class="text-xl font-semibold">{{ $lang === 'en' ? 'How comparisons work' : ($lang === 'de' ? 'So funktionieren Vergleiche' : 'Hoe vergelijken we') }}</h2>
            <p class="text-slate-600 mt-2">{{ $lang === 'en' ? 'We normalize doses when safe and show clear fallbacks.' : ($lang === 'de' ? 'Wir normalisieren Dosen, wenn es sicher ist, und zeigen Alternativen.' : 'We normaliseren doseringen waar veilig en tonen duidelijke alternatieven.') }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg border border-slate-200">
            <h2 class="text-xl font-semibold">{{ $lang === 'en' ? 'Safety disclaimer' : ($lang === 'de' ? 'Sicherheitshinweis' : 'Veiligheidsdisclaimer') }}</h2>
            <p class="text-slate-600 mt-2">{{ $page['disclaimer'] }}</p>
        </div>
    </section>
@endsection
