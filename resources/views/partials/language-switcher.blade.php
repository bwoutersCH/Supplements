@php
    $segments = explode('/', request()->path());
    $pathWithoutLang = implode('/', array_slice($segments, 1));
    $suffix = $pathWithoutLang ? '/' . $pathWithoutLang : '';
@endphp
<div class="flex items-center gap-1 text-xs">
    <a class="px-2 py-1 rounded border border-slate-200" href="/nl{{ $suffix }}">NL</a>
    <a class="px-2 py-1 rounded border border-slate-200" href="/en{{ $suffix }}">EN</a>
    <a class="px-2 py-1 rounded border border-slate-200" href="/de{{ $suffix }}">DE</a>
</div>
