@php
    $totalR = \App\Models\Respondent::count();
    $genderLaki = \App\Models\Respondent::where('jenis_kelamin', 'Laki-laki')->count();
    $genderPerempuan = \App\Models\Respondent::where('jenis_kelamin', 'Perempuan')->count();
    $pctLaki = $totalR > 0 ? round(($genderLaki / $totalR) * 100) : 0;
    $pctPerempuan = $totalR > 0 ? round(($genderPerempuan / $totalR) * 100) : 0;

    $usia1 = \App\Models\Respondent::where('usia', '<', 18)->count();
    $usia2 = \App\Models\Respondent::whereBetween('usia', [18, 24])->count();
    $usia3 = \App\Models\Respondent::whereBetween('usia', [25, 34])->count();
    $usia4 = \App\Models\Respondent::whereBetween('usia', [35, 44])->count();
    $usia5 = \App\Models\Respondent::where('usia', '>', 44)->count();
@endphp

<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-4">
    {{-- Bar: Distribusi Usia --}}
    <div style="background:#fff;border-radius:12px;padding:22px;box-shadow:0 1px 3px rgba(0,0,0,0.04);" class="lg:col-span-2">
        <p style="font-size:0.78rem;font-weight:700;color:#1e1b4b;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:16px;">Distribusi Usia</p>
        <div style="position:relative; height:180px;">
            <canvas id="ageChart"></canvas>
        </div>
    </div>

    {{-- Donut: Jenis Kelamin --}}
    <div style="background:#fff;border-radius:12px;padding:22px;box-shadow:0 1px 3px rgba(0,0,0,0.04);">
        <p style="font-size:0.78rem;font-weight:700;color:#1e1b4b;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:16px;">Distribusi Jenis Kelamin</p>
        <div style="max-width:200px;margin:0 auto;">
            <canvas id="genderChart" height="200"></canvas>
        </div>
        <div style="margin-top:14px;display:flex;flex-direction:column;gap:6px;">
            <div style="display:flex;align-items:center;justify-content:space-between;font-size:0.72rem;">
                <span style="display:flex;align-items:center;gap:6px;"><span style="width:10px;height:10px;border-radius:50%;background:#6366f1;display:inline-block;"></span><span style="color:#475569;">Laki-laki</span></span>
                <span style="font-weight:700;color:#1e1b4b;">{{ $pctLaki }}%</span>
            </div>
            <div style="display:flex;align-items:center;justify-content:space-between;font-size:0.72rem;">
                <span style="display:flex;align-items:center;gap:6px;"><span style="width:10px;height:10px;border-radius:50%;background:#a78bfa;display:inline-block;"></span><span style="color:#475569;">Perempuan</span></span>
                <span style="font-weight:700;color:#1e1b4b;">{{ $pctPerempuan }}%</span>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('livewire:navigated', function() {
    const ctx3 = document.getElementById('ageChart');
    if (ctx3) {
        new Chart(ctx3, {
            type: 'bar',
            data: {
                labels: ['< 18', '18-24', '25-34', '35-44', '> 45'],
                datasets: [{
                    label: 'Jumlah',
                    data: [{{ $usia1 }}, {{ $usia2 }}, {{ $usia3 }}, {{ $usia4 }}, {{ $usia5 }}],
                    backgroundColor: ['#c4b5fd','#a78bfa','#8b5cf6','#7c3aed','#6d28d9'],
                    borderRadius: 6,
                    barThickness: 36,
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 2, font: { size: 11 }, color: '#94a3b8' }, grid: { color: '#f1f5f9' } },
                    x: { ticks: { font: { size: 11 }, color: '#94a3b8' }, grid: { display: false } }
                }
            }
        });
    }

    const ctx4 = document.getElementById('genderChart');
    if (ctx4) {
        new Chart(ctx4, {
            type: 'doughnut',
            data: {
                labels: ['Laki-laki', 'Perempuan'],
                datasets: [{ data: [{{ $genderLaki }}, {{ $genderPerempuan }}], backgroundColor: ['#6366f1', '#a78bfa'], borderWidth: 0, spacing: 2 }]
            },
            options: { responsive: true, cutout: '65%', plugins: { legend: { display: false } } }
        });
    }
});
</script>
