@extends('layouts.app')

@section('content')
    <section class="bg-white rounded-xl p-6 border border-slate-200">
        <h1 class="text-3xl font-semibold mb-2">Admin dashboard</h1>
        <p class="text-slate-600">Manage actives, forms, goals, dosing, conversions, and sponsored entries.</p>
        <div class="mt-4 grid md:grid-cols-3 gap-4 text-sm">
            <a class="bg-slate-50 border border-slate-200 rounded p-4" href="/admin/actives">Actives</a>
            <a class="bg-slate-50 border border-slate-200 rounded p-4" href="/admin/forms">Forms</a>
            <a class="bg-slate-50 border border-slate-200 rounded p-4" href="/admin/goals">Goals</a>
            <a class="bg-slate-50 border border-slate-200 rounded p-4" href="/admin/doses">Recommended doses</a>
            <a class="bg-slate-50 border border-slate-200 rounded p-4" href="/admin/conversions">Unit conversions</a>
            <a class="bg-slate-50 border border-slate-200 rounded p-4" href="/admin/sponsored">Sponsored entries</a>
        </div>
    </section>
@endsection
