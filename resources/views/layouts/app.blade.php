@php
    $lang = $lang ?? 'nl';
@endphp
<!DOCTYPE html>
<html lang="{{ $lang }}" class="text-base" data-text-size="medium">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $seo['title'] ?? 'Supplements' }}</title>
    <meta name="description" content="{{ $seo['description'] ?? '' }}">
    <link rel="canonical" href="{{ url()->current() }}">
    @php
        $segments = explode('/', request()->path());
        $pathWithoutLang = implode('/', array_slice($segments, 1));
        $suffix = $pathWithoutLang ? '/' . $pathWithoutLang : '';
    @endphp
    <link rel="alternate" hreflang="nl" href="{{ url('/nl' . $suffix) }}">
    <link rel="alternate" hreflang="en" href="{{ url('/en' . $suffix) }}">
    <link rel="alternate" hreflang="de" href="{{ url('/de' . $suffix) }}">
    <link rel="alternate" hreflang="x-default" href="{{ url('/nl' . $suffix) }}">
    <meta property="og:title" content="{{ $seo['title'] ?? '' }}">
    <meta property="og:description" content="{{ $seo['description'] ?? '' }}">
    <meta property="og:type" content="website">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 text-slate-900">
    <header class="bg-white border-b border-slate-200">
        <div class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between">
            <a href="/{{ $lang }}" class="text-xl font-semibold">Supplements</a>
            <nav class="flex gap-4 text-sm">
                <a href="/{{ $lang }}/supplements" class="hover:text-slate-700">Supplements</a>
                <a href="/{{ $lang }}/how-we-compare" class="hover:text-slate-700">How we compare</a>
                <a href="/{{ $lang }}/disclaimer" class="hover:text-slate-700">Disclaimer</a>
            </nav>
            <div class="flex items-center gap-3">
                @include('partials.language-switcher')
                @include('partials.text-size-toggle')
            </div>
        </div>
    </header>

    <main class="max-w-6xl mx-auto px-6 py-8">
        @yield('content')
    </main>

    <footer class="bg-white border-t border-slate-200">
        <div class="max-w-6xl mx-auto px-6 py-6 text-sm text-slate-600">
            <p class="mb-2">{{ $page['disclaimer'] ?? 'Information only.' }}</p>
            <div class="flex gap-4">
                <a href="/{{ $lang }}/about">About</a>
                <a href="/{{ $lang }}/privacy">Privacy</a>
            </div>
        </div>
    </footer>

    <script>
        const sizeButtons = document.querySelectorAll('[data-text-size]');
        const root = document.documentElement;
        const saved = localStorage.getItem('text-size') || 'medium';
        root.dataset.textSize = saved;
        root.classList.remove('text-sm', 'text-base', 'text-lg');
        root.classList.add(saved === 'small' ? 'text-sm' : saved === 'large' ? 'text-lg' : 'text-base');
        sizeButtons.forEach(button => {
            button.addEventListener('click', () => {
                const size = button.dataset.textSize;
                localStorage.setItem('text-size', size);
                root.dataset.textSize = size;
                root.classList.remove('text-sm', 'text-base', 'text-lg');
                root.classList.add(size === 'small' ? 'text-sm' : size === 'large' ? 'text-lg' : 'text-base');
            });
        });
    </script>
</body>
</html>
