@php
    use App\Models\Respondent;
    use App\Models\Event;

    $latestEvent = Event::latest('tanggal')->first();
    $latestEventId = $latestEvent ? $latestEvent->id : null;

    $kemungkinanBesar = Respondent::where('event_id', $latestEventId)->where('kategori', 'Kemungkinan Besar')->count();
@endphp

<div style="background:linear-gradient(135deg,#fee2e2,#fecaca);border:1px solid #fca5a5;border-radius:12px;padding:16px 22px;margin-bottom:20px;display:flex;align-items:center;gap:14px;">
    <div style="width:38px;height:38px;background:#ef4444;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
        <svg width="18" height="18" fill="none" stroke="#fff" stroke-width="2.2" viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0zM12 9v4M12 17h.01"/></svg>
    </div>
    <div>
        <p style="font-size:0.85rem;font-weight:700;color:#991b1b;">{{ $kemungkinanBesar }} Responden Memerlukan Tindakan Lanjutan</p>
        <p style="font-size:0.75rem;color:#b91c1c;margin-top:2px;">Responden dengan kategori <strong>Kemungkinan Besar</strong> perlu dihubungi dan ditinjau ke profesional.</p>
    </div>
</div>