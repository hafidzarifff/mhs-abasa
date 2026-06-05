@php
    $totalResponden = \App\Models\Respondent::count();
    $avgSkor = $totalResponden > 0 ? round(\App\Models\Respondent::avg('skor'), 1) : 0;
    $avgUsia = $totalResponden > 0 ? round(\App\Models\Respondent::avg('usia')) : 0;
    $sudahFollow = \App\Models\Respondent::where('sudah_follow_ig', true)->count();
    $totalPertanyaan = \App\Models\Question::count();
@endphp

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
    {{-- Card 1: Total Responden --}}
    <div style="background:#fff;border-radius:12px;padding:20px 22px;border-top:3px solid #7c3aed;box-shadow:0 1px 3px rgba(0,0,0,0.04);">
        <p style="font-size:0.65rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.08em;margin-bottom:10px;">Total Responden</p>
        <div style="display:flex;align-items:baseline;gap:12px;">
            <span style="font-size:2rem;font-weight:800;color:#1e1b4b;">{{ $totalResponden }}</span>
        </div>
        <p style="font-size:0.7rem;color:#94a3b8;margin-top:4px;">Semua data masuk</p>
    </div>

    {{-- Card 2: Rata-rata Skor --}}
    <div style="background:#fff;border-radius:12px;padding:20px 22px;border-top:3px solid #6366f1;box-shadow:0 1px 3px rgba(0,0,0,0.04);">
        <p style="font-size:0.65rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.08em;margin-bottom:10px;">Rata-rata Skor</p>
        <div style="display:flex;align-items:baseline;gap:12px;">
            <span style="font-size:2rem;font-weight:800;color:#1e1b4b;">{{ $avgSkor }}</span>
        </div>
        <p style="font-size:0.7rem;color:#94a3b8;margin-top:4px;">Rata-rata skor dari {{ $totalPertanyaan }} pertanyaan</p>
    </div>

    {{-- Card 3: Rata-rata Usia --}}
    <div style="background:#fff;border-radius:12px;padding:20px 22px;border-top:3px solid #a78bfa;box-shadow:0 1px 3px rgba(0,0,0,0.04);">
        <p style="font-size:0.65rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.08em;margin-bottom:10px;">Rata-rata Usia</p>
        <div style="display:flex;align-items:baseline;gap:12px;">
            <span style="font-size:2rem;font-weight:800;color:#1e1b4b;">{{ $avgUsia }}</span>
            <span style="font-size:0.72rem;font-weight:500;color:#94a3b8;">tahun</span>
        </div>
        <p style="font-size:0.7rem;color:#94a3b8;margin-top:4px;">Dari seluruh responden</p>
    </div>

    {{-- Card 4: Followed Instagram --}}
    <div style="background:#fff;border-radius:12px;padding:20px 22px;border-top:3px solid #ec4899;box-shadow:0 1px 3px rgba(0,0,0,0.04);">
        <p style="font-size:0.65rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.08em;margin-bottom:10px;">Sudah Follow Instagram</p>
        <div style="display:flex;align-items:baseline;gap:12px;">
            <span style="font-size:2rem;font-weight:800;color:#1e1b4b;">{{ $sudahFollow }}</span>
            <span style="font-size:0.72rem;font-weight:500;color:#94a3b8;">orang</span>
        </div>
        <p style="font-size:0.7rem;color:#94a3b8;margin-top:4px;">Dari total {{ $totalResponden }} responden</p>
    </div>
</div>