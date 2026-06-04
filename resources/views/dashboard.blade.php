<x-app-layout>
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-slate-800 tracking-tight">Dashboard Analitik Responden</h1>
            <p style="font-size:0.82rem;color:#64748b;margin-top:2px;">Data skrining kesehatan mental — Mental Health Screening by Abasa HR</p>
        </div>
    </div>

    {{-- Primary KPI Cards --}}
    @include('partials.dashboard.kpi-primary')

    {{-- Secondary KPI Cards --}}
    @include('partials.dashboard.kpi-secondary')

    {{-- Charts Row 1: Line + Donut Kategori --}}
    @include('partials.dashboard.charts-row1')

    {{-- Charts Row 2: Bar Usia + Donut Gender --}}
    @include('partials.dashboard.charts-row2')

    {{-- Horizontal Bar: Jawaban per Pertanyaan --}}
    @include('partials.dashboard.chart-questions')

    {{-- Alert Box --}}
    @include('partials.dashboard.alert-followup')

    {{-- Data Table --}}
    @include('partials.dashboard.data-table')
</x-app-layout>
