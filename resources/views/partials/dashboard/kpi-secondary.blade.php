@php
    $kategoriKecil = \App\Models\Respondent::where('kategori', 'Kemungkinan Kecil')->count();
    $kategoriPerhatian = \App\Models\Respondent::where('kategori', 'Perlu Perhatian')->count();
    $kategoriFollowUp = \App\Models\Respondent::where('kategori', 'Kemungkinan Besar')->count();
@endphp

<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
    {{-- Kemungkinan Kecil --}}
    <div style="background:#fff;border-radius:12px;padding:18px 22px;box-shadow:0 1px 3px rgba(0,0,0,0.04);display:flex;align-items:center;gap:16px;">
        <div style="width:42px;height:42px;background:#dcfce7;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <svg width="20" height="20" fill="none" stroke="#16a34a" stroke-width="1.8" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        </div>
        <div>
            <p style="font-size:0.65rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.06em;">Kemungkinan Kecil</p>
            <p style="font-size:1.35rem;font-weight:800;color:#15803d;margin-top:2px;">{{ $kategoriKecil ?? 0 }} <span style="font-size:0.75rem;font-weight:500;color:#94a3b8;">Responden</span></p>
        </div>
    </div>

    {{-- Perlu Perhatian --}}
    <div style="background:#fff;border-radius:12px;padding:18px 22px;box-shadow:0 1px 3px rgba(0,0,0,0.04);display:flex;align-items:center;gap:16px;">
        <div style="width:42px;height:42px;background:#fef9c3;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <svg width="20" height="20" fill="none" stroke="#ca8a04" stroke-width="1.8" viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0zM12 9v4M12 17h.01"/></svg>
        </div>
        <div>
            <p style="font-size:0.65rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.06em;">Perlu Perhatian</p>
            <p style="font-size:1.35rem;font-weight:800;color:#a16207;margin-top:2px;">{{ $kategoriPerhatian ?? 0 }} <span style="font-size:0.75rem;font-weight:500;color:#94a3b8;">Responden</span></p>
        </div>
    </div>

    {{-- Perlu Follow-Up --}}
    <div style="background:#fff;border-radius:12px;padding:18px 22px;box-shadow:0 1px 3px rgba(0,0,0,0.04);display:flex;align-items:center;gap:16px;">
        <div style="width:42px;height:42px;background:#fee2e2;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <svg width="20" height="20" fill="none" stroke="#dc2626" stroke-width="1.8" viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0zM12 9v4M12 17h.01"/></svg>
        </div>
        <div>
            <p style="font-size:0.65rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.06em;">Perlu Follow-Up</p>
            <p style="font-size:1.35rem;font-weight:800;color:#b91c1c;margin-top:2px;">{{ $kategoriFollowUp ?? 0 }} <span style="font-size:0.75rem;font-weight:500;color:#94a3b8;">Responden</span></p>
        </div>
    </div>
</div>