@extends('layouts.app')

@section('content')
    <section class="bg-white rounded-xl p-6 border border-slate-200">
        <h1 class="text-3xl font-semibold mb-2">{{ $page['title'] }} {{ $lang === 'en' ? 'comparison' : ($lang === 'de' ? 'Vergleich' : 'vergelijking') }}</h1>
        <p class="text-slate-600">{{ $page['summary'] }}</p>
        <p class="text-sm text-slate-500 mt-2">{{ $lang === 'en' ? 'Last updated' : ($lang === 'de' ? 'Zuletzt aktualisiert' : 'Laatst bijgewerkt') }}: {{ $page['updated_at'] }}</p>
    </section>

    <section class="mt-6 bg-white rounded-xl p-6 border border-slate-200">
        <h2 class="text-xl font-semibold mb-4">{{ $lang === 'en' ? 'Filters' : ($lang === 'de' ? 'Filter' : 'Filters') }}</h2>
        <form class="grid md:grid-cols-3 gap-4">
            <input class="border border-slate-300 rounded px-3 py-2" name="brand" placeholder="{{ $lang === 'en' ? 'Brand' : ($lang === 'de' ? 'Marke' : 'Merk') }}">
            <input class="border border-slate-300 rounded px-3 py-2" name="shop" placeholder="{{ $lang === 'en' ? 'Shop' : ($lang === 'de' ? 'Shop' : 'Winkel') }}">
            <select class="border border-slate-300 rounded px-3 py-2" name="form_factor">
                <option value="">{{ $lang === 'en' ? 'Form factor' : ($lang === 'de' ? 'Darreichung' : 'Vorm') }}</option>
                <option value="capsule">Capsule</option>
                <option value="tablet">Tablet</option>
                <option value="powder">Powder</option>
                <option value="liquid">Liquid</option>
            </select>
            <input class="border border-slate-300 rounded px-3 py-2" name="dose_min" placeholder="{{ $lang === 'en' ? 'Min dose' : ($lang === 'de' ? 'Min Dosis' : 'Min dosis') }}">
            <input class="border border-slate-300 rounded px-3 py-2" name="dose_max" placeholder="{{ $lang === 'en' ? 'Max dose' : ($lang === 'de' ? 'Max Dosis' : 'Max dosis') }}">
            <select class="border border-slate-300 rounded px-3 py-2" name="sort">
                <option value="normalized">{{ $lang === 'en' ? 'Price per normalized unit' : ($lang === 'de' ? 'Preis pro normalisierte Einheit' : 'Prijs per genormaliseerde eenheid') }}</option>
                <option value="unit">{{ $lang === 'en' ? 'Price per unit' : ($lang === 'de' ? 'Preis pro Einheit' : 'Prijs per stuk') }}</option>
                <option value="daily">{{ $lang === 'en' ? 'Price per day' : ($lang === 'de' ? 'Preis pro Tag' : 'Prijs per dag') }}</option>
            </select>
            <button class="bg-slate-900 text-white px-4 py-2 rounded">{{ $lang === 'en' ? 'Apply' : ($lang === 'de' ? 'Anwenden' : 'Toepassen') }}</button>
        </form>
    </section>

    @if(!empty($page['sponsored']))
        <section class="mt-6 bg-amber-50 border border-amber-200 rounded-xl p-6">
            <h2 class="text-xl font-semibold mb-2">{{ $lang === 'en' ? 'Sponsored' : ($lang === 'de' ? 'Gesponsert' : 'Gesponsord') }}</h2>
            @foreach($page['sponsored'] as $entry)
                <div class="border border-amber-200 bg-white rounded p-4 mb-3">
                    <p class="text-xs uppercase text-amber-700">Sponsored</p>
                    <p class="font-semibold">{{ $entry['title'] }}</p>
                    <p class="text-sm text-slate-600">{{ $entry['description'] }}</p>
                    <a class="text-sm text-slate-700 underline" href="{{ $entry['target_url'] }}">{{ $lang === 'en' ? 'View offer' : ($lang === 'de' ? 'Zum Angebot' : 'Bekijk aanbod') }}</a>
                </div>
            @endforeach
        </section>
    @else
        <section class="mt-6 bg-slate-100 border border-dashed border-slate-300 rounded-xl p-6">
            <p class="text-sm text-slate-600">{{ $lang === 'en' ? 'Sponsored placements are currently disabled.' : ($lang === 'de' ? 'Gesponserte Platzierungen sind derzeit deaktiviert.' : 'Gesponsorde plaatsingen zijn momenteel uitgeschakeld.') }}</p>
        </section>
    @endif

    <section class="mt-6 bg-white rounded-xl p-6 border border-slate-200">
        <h2 class="text-xl font-semibold mb-4">{{ $lang === 'en' ? 'Offers' : ($lang === 'de' ? 'Angebote' : 'Aanbiedingen') }}</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left">
                <thead class="text-xs uppercase text-slate-500">
                    <tr>
                        <th class="py-2">{{ $lang === 'en' ? 'Brand' : ($lang === 'de' ? 'Marke' : 'Merk') }}</th>
                        <th class="py-2">{{ $lang === 'en' ? 'Shop' : ($lang === 'de' ? 'Shop' : 'Winkel') }}</th>
                        <th class="py-2">{{ $lang === 'en' ? 'Form' : ($lang === 'de' ? 'Form' : 'Vorm') }}</th>
                        <th class="py-2">{{ $lang === 'en' ? '€ / normalized unit' : ($lang === 'de' ? '€ / normalisierte Einheit' : '€ / genormaliseerde eenheid') }}</th>
                        <th class="py-2">{{ $lang === 'en' ? '€ / unit' : ($lang === 'de' ? '€ / Einheit' : '€ / stuk') }}</th>
                        <th class="py-2">{{ $lang === 'en' ? '€ / day' : ($lang === 'de' ? '€ / Tag' : '€ / dag') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($page['offers'] as $offer)
                        <tr class="border-t border-slate-200">
                            <td class="py-2 font-semibold">{{ $offer['brand'] }}</td>
                            <td class="py-2">{{ $offer['shop'] }}</td>
                            <td class="py-2">{{ $offer['form_factor'] }}</td>
                            <td class="py-2">€ {{ number_format($offer['normalized_price'], 2) }}</td>
                            <td class="py-2">€ {{ number_format($offer['unit_price'], 2) }}</td>
                            <td class="py-2">€ {{ number_format($offer['daily_price'], 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <p class="text-xs text-slate-500 mt-4">{{ $lang === 'en' ? 'Normalized comparisons only when safe conversions exist.' : ($lang === 'de' ? 'Normalisierung nur bei sicheren Umrechnungen.' : 'Normalisatie alleen bij veilige conversies.') }}</p>
    </section>

    @if(!empty($jsonLd))
        <script type="application/ld+json">{!! json_encode($jsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    @endif
@endsection
