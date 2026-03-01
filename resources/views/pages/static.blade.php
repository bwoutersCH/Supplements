@extends('layouts.app')

@section('content')
    <section class="bg-white rounded-xl p-6 border border-slate-200">
        <h1 class="text-3xl font-semibold mb-2">{{ $page['title'] }}</h1>
        <p class="text-slate-600 mb-4">{{ $page['summary'] }}</p>
        <div class="prose prose-slate max-w-none">
            {{ $page['content'] }}
        </div>
        <p class="text-sm text-slate-500 mt-4">{{ $lang === 'en' ? 'Last updated' : ($lang === 'de' ? 'Zuletzt aktualisiert' : 'Laatst bijgewerkt') }}: {{ $page['updated_at'] }}</p>
    </section>
@endsection
