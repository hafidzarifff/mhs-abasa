@php
    // Ambil event terakhir (berdasarkan tanggal terbaru)
    $latestEvent = \App\Models\Event::latest('tanggal')->first();
    $respondents = $latestEvent
        ? \App\Models\Respondent::with('event')->where('event_id', $latestEvent->id)->latest()->get()
        : collect();
    $totalData = $respondents->count();
@endphp

<div style="background:#fff;border-radius:12px;padding:22px;box-shadow:0 1px 3px rgba(0,0,0,0.04);"
     x-data="{
        search: '',
        kategori: '',
        gender: '',
        get filtered() {
            return this.$refs.rows ? true : true;
        },
        isVisible(nama, email, kat, jk) {
            const s = this.search.toLowerCase();
            const matchSearch = !s || nama.toLowerCase().includes(s) || email.toLowerCase().includes(s);
            const matchKat = !this.kategori || kat === this.kategori;
            const matchGender = !this.gender || jk === this.gender;
            return matchSearch && matchKat && matchGender;
        }
     }">

    {{-- Table Header --}}
    <div style="display:flex;flex-wrap:wrap;justify-content:space-between;align-items:center;margin-bottom:16px;gap:10px;">
        <div>
            <p style="font-size:0.85rem;font-weight:700;color:#1e1b4b;text-transform:uppercase;letter-spacing:0.05em;">
                Data Responden — Kegiatan Terakhir
            </p>
            @if($latestEvent)
                <p style="font-size:0.72rem;color:#94a3b8;margin-top:2px;">
                    {{ $latestEvent->nama_event }} · {{ \Carbon\Carbon::parse($latestEvent->tanggal)->format('d M Y') }}
                </p>
            @endif
        </div>
        <div style="display:flex;flex-wrap:wrap;gap:8px;align-items:center;">
            {{-- Search --}}
            <div style="position:relative;">
                <svg style="position:absolute;left:10px;top:50%;transform:translateY(-50%);width:14px;height:14px;color:#94a3b8;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
                <input type="text" x-model="search" placeholder="Cari nama / email..."
                    style="padding:7px 12px 7px 32px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;font-size:0.75rem;color:#334155;width:190px;outline:none;">
            </div>

            {{-- Filter Kategori --}}
            <select x-model="kategori" style="padding:7px 12px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;font-size:0.75rem;color:#334155;outline:none;cursor:pointer;">
                <option value="">Semua Kategori</option>
                <option value="Kemungkinan Kecil">Kemungkinan Kecil</option>
                <option value="Perlu Perhatian">Perlu Perhatian</option>
                <option value="Kemungkinan Besar">Kemungkinan Besar</option>
            </select>

            {{-- Filter Jenis Kelamin --}}
            <select x-model="gender" style="padding:7px 12px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;font-size:0.75rem;color:#334155;outline:none;cursor:pointer;">
                <option value="">Semua JK</option>
                <option value="Laki-laki">Laki-laki</option>
                <option value="Perempuan">Perempuan</option>
            </select>

            {{-- Reset --}}
            <button @click="search = ''; kategori = ''; gender = '';"
                style="padding: 10px 14px;background: #fcf8f8ff;border:1px solid #e2e8f0;border-radius:8px;font-size:0.72rem;font-weight:600;color:#64748b;cursor:pointer;">
                Reset
            </button>

            {{-- Export Excel --}}
            <a href="{{ route('export.dashboard') }}"
                class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2.5 bg-green-600 border border-transparent rounded-lg font-semibold text-xs text-white hover:bg-green-700 shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-1 shrink-0">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Export to Excel
            </a>
        </div>
    </div>

    {{-- Table --}}
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;font-size:0.75rem;">
            <thead>
                <tr style="border-bottom:2px solid #f1f5f9;">
                    <th style="padding:10px 8px;text-align:left;font-weight:700;color:#64748b;font-size:0.68rem;text-transform:uppercase;letter-spacing:0.04em;">#</th>
                    <th style="padding:10px 8px;text-align:left;font-weight:700;color:#64748b;font-size:0.68rem;text-transform:uppercase;letter-spacing:0.04em;">Waktu</th>
                    <th style="padding:10px 8px;text-align:left;font-weight:700;color:#64748b;font-size:0.68rem;text-transform:uppercase;letter-spacing:0.04em;">Nama</th>
                    <th style="padding:10px 8px;text-align:left;font-weight:700;color:#64748b;font-size:0.68rem;text-transform:uppercase;letter-spacing:0.04em;">Usia</th>
                    <th style="padding:10px 8px;text-align:left;font-weight:700;color:#64748b;font-size:0.68rem;text-transform:uppercase;letter-spacing:0.04em;">JK</th>
                    <th style="padding:10px 8px;text-align:left;font-weight:700;color:#64748b;font-size:0.68rem;text-transform:uppercase;letter-spacing:0.04em;">Email</th>
                    <th style="padding:10px 8px;text-align:left;font-weight:700;color:#64748b;font-size:0.68rem;text-transform:uppercase;letter-spacing:0.04em;">No HP</th>
                    <th style="padding:10px 8px;text-align:left;font-weight:700;color:#64748b;font-size:0.68rem;text-transform:uppercase;letter-spacing:0.04em;">IG</th>
                    <th style="padding:10px 8px;text-align:center;font-weight:700;color:#64748b;font-size:0.68rem;text-transform:uppercase;letter-spacing:0.04em;">Follow</th>
                    <th style="padding:10px 8px;text-align:center;font-weight:700;color:#64748b;font-size:0.68rem;text-transform:uppercase;letter-spacing:0.04em;">Skor</th>
                    <th style="padding:10px 8px;text-align:center;font-weight:700;color:#64748b;font-size:0.68rem;text-transform:uppercase;letter-spacing:0.04em;">Kategori</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($respondents as $i => $r)
                <tr x-show="isVisible('{{ addslashes($r->nama) }}', '{{ addslashes($r->email) }}', '{{ $r->kategori }}', '{{ $r->jenis_kelamin }}')"
                    x-transition
                    style="border-bottom:1px solid #f8fafc;transition:background 0.1s;"
                    onmouseover="this.style.background='#faf8ff'" onmouseout="this.style.background='transparent'">
                    <td style="padding:10px 8px;color:#94a3b8;font-weight:600;">{{ $i + 1 }}</td>
                    <td style="padding:10px 8px;color:#475569;white-space:nowrap;">{{ $r->created_at ? $r->created_at->format('Y-m-d H:i') : '-' }}</td>
                    <td style="padding:10px 8px;color:#1e1b4b;font-weight:600;">{{ $r->nama }}</td>
                    <td style="padding:10px 8px;color:#475569;">{{ $r->usia }}</td>
                    <td style="padding:10px 8px;color:#475569;">{{ $r->jenis_kelamin }}</td>
                    <td style="padding:10px 8px;color:#475569;">{{ $r->email }}</td>
                    <td style="padding:10px 8px;color:#475569;white-space:nowrap;">{{ $r->no_hp ?: '-' }}</td>
                    <td style="padding:10px 8px;color:#475569;">{{ $r->ig ?: '-' }}</td>
                    <td style="padding:10px 8px;color:#475569;" class="text-center">
                        @if(isset($r->sudah_follow_ig))
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider {{ $r->sudah_follow_ig ? 'bg-indigo-50 text-indigo-600 border border-indigo-100' : 'bg-slate-50 text-slate-600 border border-slate-200' }}">
                                {{ $r->sudah_follow_ig ? 'Sudah' : 'Belum' }}
                            </span>
                        @else
                            -
                        @endif
                    </td>
                    <td style="padding:10px 8px;font-weight:700;color:#1e1b4b;" class="text-center">{{ $r->skor }}</td>
                    <td style="padding:10px 8px;" class="text-center">
                        @php $kategori = $r->kategori ?? 'Kemungkinan Kecil'; @endphp
                        @if($kategori === 'Kemungkinan Kecil')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold bg-emerald-50 text-emerald-600 uppercase tracking-wider border border-emerald-100">KEMUNGKINAN KECIL</span>
                        @elseif($kategori === 'Perlu Perhatian')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold bg-amber-50 text-amber-600 uppercase tracking-wider border border-amber-100">PERLU PERHATIAN</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold bg-rose-50 text-rose-600 uppercase tracking-wider border border-rose-100">KEMUNGKINAN BESAR</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="11" style="padding:20px 8px;text-align:center;color:#94a3b8;font-size:0.75rem;">Tidak ada data responden pada kegiatan terakhir.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Footer --}}
    <div style="display:flex;justify-content:space-between;align-items:center;margin-top:16px;padding-top:12px;border-top:1px solid #f1f5f9;">
        <p style="font-size:0.72rem;color:#94a3b8;">Menampilkan {{ $totalData }} data</p>
    </div>
</div>
