@php
use App\Models\Question;
use App\Models\Respondent;

$totalRespondents = Respondent::count();
$allRespondents = Respondent::whereNotNull('jawaban')->get();

$chartData = Question::select('kode_pertanyaan', 'pertanyaan')
    ->get()
    ->map(function ($q) use ($totalRespondents, $allRespondents) {
        $yaCount = 0;
        foreach ($allRespondents as $r) {
            $jawaban = is_array($r->jawaban) ? $r->jawaban : [];
            if (isset($jawaban[$q->kode_pertanyaan])) {
                $ans = $jawaban[$q->kode_pertanyaan];
                $val = is_array($ans) ? ($ans['jawaban'] ?? '') : $ans;
                if (strtolower($val) === 'ya') $yaCount++;
            }
        }
        $q->percentage = $totalRespondents > 0 ? round(($yaCount / $totalRespondents) * 100) : 0;
        return $q;
    })
    ->sortBy(function ($q) {
        return (int) filter_var($q->kode_pertanyaan, FILTER_SANITIZE_NUMBER_INT);
    })
    ->values();
@endphp

<div style="background:#fff;border-radius:12px;padding:22px;box-shadow:0 1px 3px rgba(0,0,0,0.04);margin-bottom:20px;">
    <p style="font-size:0.78rem;font-weight:700;color:#1e1b4b;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:18px;">
        Persentase Jawaban "Ya" Per Pertanyaan
    </p>
    <div style="display:flex;flex-direction:column;gap:10px;">
        @foreach ($chartData as $data)
        @php
            $pct = $data->percentage;
            if ($pct >= 70) {
                $barClass = 'bg-red-500';
                $textColor = '#b91c1c';
            } elseif ($pct >= 30) {
                $barClass = 'bg-amber-400';
                $textColor = '#b45309';
            } else {
                $barClass = 'bg-emerald-500';
                $textColor = '#15803d';
            }
        @endphp
        <div style="display:flex;align-items:center;gap:12px;">
            <span style="font-size:0.7rem;color:#94a3b8;width:28px;text-align:right;flex-shrink:0;">
                {{ $data->kode_pertanyaan }}.
            </span>
            <span style="font-size:0.72rem;color:#475569;width:190px;flex-shrink:0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                {{ $data->pertanyaan }}
            </span>
            <div class="flex-1 bg-slate-100 rounded-full" style="height:10px;overflow:hidden;">
                <div class="{{ $barClass }} rounded-full" style="width:{{ $pct }}%;height:100%;transition:width 0.5s ease;"></div>
            </div>
            <span style="font-size:0.72rem;font-weight:700;color:{{ $textColor }};width:36px;text-align:right;flex-shrink:0;">
                {{ $pct }}%
            </span>
        </div>
        @endforeach
    </div>
</div>