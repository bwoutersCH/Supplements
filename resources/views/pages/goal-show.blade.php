@extends('layouts.app')

@section('content')
    <section class="bg-white rounded-xl p-6 border border-slate-200">
        <h1 class="text-3xl font-semibold mb-2">{{ $page['title'] }}</h1>
        <p class="text-slate-600">{{ $page['summary'] }}</p>
        <p class="text-sm text-slate-500 mt-2">{{ $lang === 'en' ? 'Last updated' : ($lang === 'de' ? 'Zuletzt aktualisiert' : 'Laatst bijgewerkt') }}: {{ $page['updated_at'] }}</p>
    </section>

    <section class="mt-6 grid md:grid-cols-2 gap-4">
        @foreach($page['actives'] as $slug => $active)
            <a class="bg-white p-4 rounded-lg border border-slate-200" href="/{{ $lang }}/supplement/{{ $slug }}">
                <h2 class="font-semibold text-lg">{{ $active['name'] }}</h2>
                <p class="text-sm text-slate-600">{{ $active['summary'] }}</p>
            </a>
        @endforeach
    </section>
@endsection
