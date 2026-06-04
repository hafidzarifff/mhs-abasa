@php
    $totalR = \App\Models\Respondent::count();
    $kecil = \App\Models\Respondent::where('kategori', 'Kemungkinan Kecil')->count();
    $perhatian = \App\Models\Respondent::where('kategori', 'Perlu Perhatian')->count();
    $besar = \App\Models\Respondent::where('kategori', 'Kemungkinan Besar')->count();
    $pctKecil = $totalR > 0 ? round(($kecil / $totalR) * 100) : 0;
    $pctPerhatian = $totalR > 0 ? round(($perhatian / $totalR) * 100) : 0;
    $pctBesar = $totalR > 0 ? round(($besar / $totalR) * 100) : 0;

    $trendLabels = [];
    $trendData = [];
    for ($i = 4; $i >= 0; $i--) {
        $d = now()->subMonths($i);
        $trendLabels[] = $d->translatedFormat('M');
        $trendData[] = \App\Models\Respondent::whereYear('created_at', $d->year)->whereMonth('created_at', $d->month)->count();
    }
@endphp

<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-4">
    {{-- Line Chart: Tren Responden --}}
    <div style="background:#fff;border-radius:12px;padding:22px;box-shadow:0 1px 3px rgba(0,0,0,0.04);" class="lg:col-span-2">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
            <p style="font-size:0.78rem;font-weight:700;color:#1e1b4b;text-transform:uppercase;letter-spacing:0.05em;">Tren Responden Per Bulan</p>
        </div>

        {{-- ✅ Tambahkan wrapper dengan position relative dan height tetap --}}
        <div style="position:relative; height:180px;">
            <canvas id="trendChart"></canvas>
        </div>
    </div>

    {{-- Donut: Distribusi Kategori --}}
    <div style="background:#fff;border-radius:12px;padding:22px;box-shadow:0 1px 3px rgba(0,0,0,0.04);">
        <p style="font-size:0.78rem;font-weight:700;color:#1e1b4b;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:16px;">Distribusi Kategori</p>
        <div style="position:relative;max-width:200px;margin:0 auto;">
            <canvas id="categoryChart"></canvas>
        </div>
        <div style="margin-top:14px;display:flex;flex-direction:column;gap:6px;">
            <div style="display:flex;align-items:center;justify-content:space-between;font-size:0.72rem;">
                <span style="display:flex;align-items:center;gap:6px;">
                    <span style="width:10px;height:10px;border-radius:50%;background:#24fb68ff;display:inline-block;"></span>
                    <span style="color:#475569;">Kemungkinan Kecil</span>
                </span>
                <span style="font-weight:700;color:#15803d;">{{ $pctKecil }}%</span>
            </div>
            <div style="display:flex;align-items:center;justify-content:space-between;font-size:0.72rem;">
                <span style="display:flex;align-items:center;gap:6px;">
                    <span style="width:10px;height:10px;border-radius:50%;background:#FBBF24;display:inline-block;"></span>
                    <span style="color:#475569;">Perlu Perhatian</span>
                </span>
                <span style="font-weight:700;color:#a16207;">{{ $pctPerhatian }}%</span>
            </div>
            <div style="display:flex;align-items:center;justify-content:space-between;font-size:0.72rem;">
                <span style="display:flex;align-items:center;gap:6px;">
                    <span style="width:10px;height:10px;border-radius:50%;background:#EF4444;display:inline-block;"></span>
                    <span style="color:#475569;">Kemungkinan Besar</span>
                </span>
                <span style="font-weight:700;color:#b91c1c;">{{ $pctBesar }}%</span>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('livewire:navigated', function() {
    // Line chart
    const ctx1 = document.getElementById('trendChart');
    if (ctx1) {
        new Chart(ctx1, {
            type: 'line',
            data: {
                labels: @json($trendLabels),
                datasets: [{
                    label: 'Responden',
                    data: @json($trendData),
                    borderColor: '#7c3aed',
                    backgroundColor: 'rgba(124,58,237,0.08)',
                    fill: true,
                    tension: 0.35,
                    pointBackgroundColor: '#7c3aed',
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    borderWidth: 2,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { font: { size: 11 }, color: '#94a3b8' }, grid: { color: '#f1f5f9' } },
                    x: { ticks: { font: { size: 11 }, color: '#94a3b8' }, grid: { display: false } }
                }
            }
        });
    }

    // Donut chart
    const ctx2 = document.getElementById('categoryChart');
    if (ctx2) {
        new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: ['Kemungkinan Kecil', 'Perlu Perhatian', 'Kemungkinan Besar'],
                datasets: [{
                    data: [{{ $kecil }}, {{ $perhatian }}, {{ $besar }}],
                    backgroundColor: ['#24fb68ff', '#FBBF24', '#EF4444'],
                    borderWidth: 0,
                    spacing: 2
                }]
            },
            options: {
                responsive: true,
                cutout: '65%',
                plugins: { legend: { display: false } }
            }
        });
    }
});
</script>
