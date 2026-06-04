<?php

use App\Models\Event;
use App\Models\Respondent;
use Livewire\Volt\Component;
use function Livewire\Volt\layout;
use function Livewire\Volt\title;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;

layout('layouts.app');
title('Detail Event');

new class extends Component {
    public Event $event;

    public $edit_nama_event;
    public $edit_deskripsi;
    public $edit_lokasi;
    public $edit_tanggal;
    public $edit_status;
    public $isEditOpen = false;

    // Modal Jawaban
    public $isModalOpen = false;
    public ?Respondent $selectedRespondent = null;

    public $isQrModalOpen = false;

    public function mount(Event $event)
    {
        $this->event = $event;
    }

    public function edit()
    {
        $this->edit_nama_event = $this->event->nama_event;
        $this->edit_deskripsi = $this->event->deskripsi;
        $this->edit_lokasi = $this->event->lokasi;
        $this->edit_tanggal = $this->event->tanggal;
        $this->edit_status = $this->event->status;
        $this->isEditOpen = true;
    }

    public function update()
    {
        $this->validate([
            'edit_nama_event' => 'required|string|max:255',
            'edit_deskripsi' => 'nullable|string',
            'edit_lokasi' => 'required|string|max:255',
            'edit_tanggal' => 'required|date',
            'edit_status' => 'required|in:Akan Datang,Berjalan,Selesai',
        ]);

        $this->event->update([
            'nama_event' => $this->edit_nama_event,
            'deskripsi' => $this->edit_deskripsi,
            'lokasi' => $this->edit_lokasi,
            'tanggal' => $this->edit_tanggal,
            'status' => $this->edit_status,
        ]);

        $this->closeEditModal();
    }

    public function delete()
    {
        $this->event->delete();
        return redirect()->route('events');
    }

    public function closeEditModal()
    {
        $this->isEditOpen = false;
        $this->resetValidation();
    }

    public function lihatJawaban($id)
    {
        $this->selectedRespondent = Respondent::find($id);
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->selectedRespondent = null;
    }

    public function openQrModal()
    {
        $this->isQrModalOpen = true;
    }

    public function closeQrModal()
    {
        $this->isQrModalOpen = false;
    }

    public function downloadPdf()
    {
        $url = url('/skrining/' . $this->event->id);
        $qrcode = base64_encode(QrCode::format('svg')->size(300)->errorCorrection('H')->generate($url));

        $data = [
            'event' => $this->event,
            'url' => $url,
            'qrcode' => $qrcode
        ];

        $pdf = Pdf::loadView('pdf.event-qr', $data);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'QR-Code-' . $this->event->nama_event . '.pdf');
    }

    public function with(): array
    {
        $respondents = Respondent::where('event_id', $this->event->id)
            ->orderBy('id', 'desc')
            ->get();

        $kategoris = $respondents
            ->groupBy('kategori')
            ->map(fn($group) => (object)['total' => $group->count()]);

        return [
            'respondents' => $respondents,
            'totalRespondents' => $respondents->count(),
            'criticalCases' => $respondents->where('kategori', 'Kemungkinan Besar')->count(), // tambah ini
            'kategoris' => $kategoris,
            'questions' => \App\Models\Question::all()->keyBy('kode_pertanyaan'),
        ];
    }
}; ?>

<div class="py-6 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Header Area --}}
        <div class="mb-6 flex flex-col sm:flex-row sm:items-start justify-between gap-4">
            <div>
                {{-- Breadcrumbs --}}
                <nav class="flex text-sm text-slate-500 mb-2 space-x-2">
                    <a href="{{ route('events') }}" class="hover:text-purple-600 transition-colors inline-flex items-center font-medium" wire:navigate>
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Kembali ke Daftar Event
                    </a>
                </nav>
                <h1 class="text-3xl sm:text-4xl font-extrabold text-[#0B1238] tracking-tight">{{ $event->nama_event }}</h1>
            </div>
            
            {{-- Top Right Actions --}}
            <div class="flex items-center space-x-3 shrink-0 mt-2 sm:mt-0">
                <button wire:click="openQrModal" class="inline-flex items-center px-4 py-2.5 bg-white border border-slate-200 rounded-lg text-sm font-semibold text-slate-700 hover:bg-slate-50 shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-1">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                    QR Code
                </button>
                <button wire:click="edit" class="inline-flex items-center px-4 py-2.5 bg-white border border-slate-200 rounded-lg text-sm font-semibold text-slate-700 hover:bg-slate-50 shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-1">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                    Edit Event
                </button>
                <button wire:click="delete" wire:confirm="Yakin ingin menghapus event ini?" class="inline-flex items-center justify-center p-2.5 bg-red-50 border border-red-100 text-red-600 rounded-lg hover:bg-red-100 shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </button>
            </div>
        </div>

        {{-- Top Section (Grid Layout) --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            {{-- Left Col (Span 2) --}}
            <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden relative border-t-4 border-t-purple-600">
                <div class="p-6 md:p-8">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-8 mb-8">
                        <div>
                            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-3">Lokasi</p>
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-purple-500 mr-2.5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                <span class="text-base font-bold text-[#0B1238]">{{ $event->lokasi }}</span>
                            </div>
                        </div>
                        <div>
                            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-3">Tanggal Pelaksanaan</p>
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-purple-500 mr-2.5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <span class="text-base font-bold text-[#0B1238]">{{ \Carbon\Carbon::parse($event->tanggal)->format('d M Y') }}</span>
                            </div>
                        </div>
                        <div>
                            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-3">Status</p>
                            <div>
                                @if($event->status === 'Akan Datang')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-100">
                                        <span class="w-1.5 h-1.5 bg-blue-500 rounded-full mr-1.5"></span>
                                        Akan Datang
                                    </span>
                                @elseif($event->status === 'Berjalan')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-700 border border-yellow-100">
                                        <span class="w-1.5 h-1.5 bg-yellow-500 rounded-full mr-1.5"></span>
                                        Berjalan    
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full mr-1.5"></span>
                                        Selesai
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="border-t border-slate-100 pt-6">
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-3">Deskripsi Event</p>
                        <p class="text-sm text-slate-600 leading-relaxed max-w-3xl">
                            {{ $event->deskripsi ?? 'Belum ada deskripsi yang ditambahkan untuk event ini. Anda dapat menambahkan deskripsi melalui tombol Edit Event di pojok kanan atas.' }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Right Col (Span 1) --}}
            <div class="lg:col-span-1 flex flex-col gap-5 justify-between">
                {{-- Metric Card 1 --}}
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex items-center justify-between flex-grow">
                    <div>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-2">Total Responden</p>
                        <p class="text-4xl font-extrabold text-[#0B1238]">{{ number_format($totalRespondents) }}</p>
                    </div>
                    <div class="bg-purple-50 text-purple-600 p-4 rounded-xl shadow-sm border border-purple-100/50">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                </div>

                {{-- Metric Card 2 --}}
                <div class="bg-white rounded-xl shadow-sm border border-red-100 p-6 flex items-center justify-between flex-grow">
                    <div>
                        <div class="mb-2">
                            <p class="text-lg font-bold text-red-600 leading-tight">Perlu Follow-up</p>
                        </div>
                        <p class="text-4xl font-extrabold text-red-600">{{ number_format($criticalCases) }}</p>
                    </div>
                    <div class="bg-red-50 text-red-500 p-4 rounded-xl shadow-sm border border-red-100">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Bottom Section (Respondents Table) --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mb-12">
            
            {{-- Header + Summary Kategori --}}
            <div class="p-5 border-b border-slate-100 bg-white">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-4">
                    <h2 class="text-lg font-bold text-[#0B1238] tracking-wide">DATA RESPONDEN</h2>
                    <div class="flex flex-col sm:flex-row items-center space-y-3 sm:space-y-0 sm:space-x-3 w-full md:w-auto">
                        <div class="relative w-full sm:w-64">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                            <input type="text" class="block w-full pl-10 pr-3 py-2.5 border border-slate-200 rounded-lg text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors bg-slate-50 focus:bg-white" placeholder="Cari Responden...">
                        </div>
                        <a href="{{ route('export.event', $event) }}" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2.5 bg-green-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-green-700 shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-1 shrink-0">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Export to Excel
                        </a>
                    </div>
                </div>

                {{-- Group by Kategori Summary --}}
                <div class="grid grid-cols-3 gap-3">
                    <div class="flex items-center gap-3 bg-emerald-50 border border-emerald-100 rounded-lg px-4 py-3">
                        <div class="w-2 h-2 rounded-full bg-emerald-500 shrink-0"></div>
                        <div>
                            <p class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider">Kemungkinan Kecil</p>
                            <p class="text-xl font-bold text-emerald-700">
                                {{ $kategoris['Kemungkinan Kecil']->total ?? 0 }}
                                <span class="text-xs font-medium text-emerald-500">Responden</span>
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 bg-amber-50 border border-amber-100 rounded-lg px-4 py-3">
                        <div class="w-2 h-2 rounded-full bg-amber-500 shrink-0"></div>
                        <div>
                            <p class="text-[10px] font-bold text-amber-600 uppercase tracking-wider">Perlu Perhatian</p>
                            <p class="text-xl font-bold text-amber-700">
                                {{ $kategoris['Perlu Perhatian']->total ?? 0 }}
                                <span class="text-xs font-medium text-amber-500">Responden</span>
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 bg-rose-50 border border-rose-100 rounded-lg px-4 py-3">
                        <div class="w-2 h-2 rounded-full bg-rose-500 shrink-0"></div>
                        <div>
                            <p class="text-[10px] font-bold text-rose-600 uppercase tracking-wider">Kemungkinan Besar</p>
                            <p class="text-xl font-bold text-rose-700">
                                {{ $kategoris['Kemungkinan Besar']->total ?? 0 }}
                                <span class="text-xs font-medium text-rose-500">Responden</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead>
                        <tr class="bg-white text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100">
                            <th scope="col" class="px-6 py-5">#</th>
                            <th scope="col" class="px-6 py-5">WAKTU</th>
                            <th scope="col" class="px-6 py-5">NAMA</th>
                            <th scope="col" class="px-6 py-5">USIA</th>
                            <th scope="col" class="px-6 py-5">JK</th>
                            <th scope="col" class="px-6 py-5">EMAIL</th>
                            <th scope="col" class="px-6 py-5">NO HP</th>
                            <th scope="col" class="px-6 py-5">IG</th>
                            <th scope="col" class="px-6 py-5">FOLLOW</th>
                            <th scope="col" class="px-6 py-5 text-center">SKOR</th>
                            <th scope="col" class="px-6 py-5 text-center">KATEGORI</th>
                            <th scope="col" class="px-6 py-5 text-center">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($respondents as $index => $responden)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 text-sm text-slate-400">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 text-sm text-slate-600">{{ $responden->created_at ?? '-' }}</td>
                                <td class="px-6 py-4">
                                    <span class="text-sm font-bold text-[#0B1238]">{{ $responden->nama ?? '-' }}</span>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600">{{ $responden->usia ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-slate-600">{{ $responden->jenis_kelamin ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-slate-600">{{ $responden->email ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-slate-600">{{ $responden->no_hp ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-slate-600">{{ $responden->ig ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-slate-600">
                                    @if(isset($responden->sudah_follow_ig))
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider {{ $responden->sudah_follow_ig ? 'bg-indigo-50 text-indigo-600 border border-indigo-100' : 'bg-slate-50 text-slate-600 border border-slate-200' }}">
                                            {{ $responden->sudah_follow_ig ? 'Sudah' : 'Belum' }}
                                        </span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm font-bold text-[#0B1238] text-center">{{ $responden->skor ?? '-' }}</td>
                                <td class="px-6 py-4 text-center">
                                    @php $kategori = $responden->kategori ?? 'Kemungkinan Kecil'; @endphp
                                    @if($kategori === 'Kemungkinan Kecil')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold bg-emerald-50 text-emerald-600 uppercase tracking-wider border border-emerald-100">KEMUNGKINAN KECIL</span>
                                    @elseif($kategori === 'Perlu Perhatian')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold bg-amber-50 text-amber-600 uppercase tracking-wider border border-amber-100">PERLU PERHATIAN</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold bg-rose-50 text-rose-600 uppercase tracking-wider border border-rose-100">KEMUNGKINAN BESAR</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <button wire:click="lihatJawaban({{ $responden->id }})" class="inline-flex items-center px-3 py-1.5 text-xs font-semibold text-purple-600 bg-purple-50 border border-purple-200 rounded-lg hover:bg-purple-100 transition-colors">
                                        <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        Lihat
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="bg-slate-50 p-4 rounded-full mb-4 border border-slate-100">
                                            <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                        </div>
                                        <p class="text-base font-bold text-slate-800">Belum ada data responden.</p>
                                        <p class="text-sm text-slate-500 mt-1">Data responden yang mengisi skrining akan muncul di sini.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Modal Lihat Jawaban --}}
        @if($isModalOpen && $selectedRespondent)
            <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 bg-slate-900 bg-opacity-50 transition-opacity" aria-hidden="true" wire:click="closeModal"></div>
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                    <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-4 sm:align-middle sm:max-w-5xl w-full">
                        
                        {{-- Modal Header --}}
                        <div class="bg-white px-6 pt-5 pb-4 border-b border-slate-100 flex items-center justify-between">
                            <div>
                                <h3 class="text-lg leading-6 font-bold text-slate-900">Detail Jawaban Responden</h3>
                                <p class="mt-1 text-sm text-slate-500">{{ $selectedRespondent->nama }}</p>
                            </div>
                            <button wire:click="closeModal" class="text-slate-400 hover:text-slate-600 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>

                        {{-- Modal Body --}}
                        <div class="px-6 py-5 bg-slate-50 max-h-[75vh] overflow-y-auto">
                            {{-- Info Cards --}}
                            <div class="mb-5 flex gap-4">
                                <div class="bg-white px-4 py-3 rounded-lg border border-slate-200 shadow-sm flex-1">
                                    <div class="text-xs text-slate-500 uppercase tracking-wide font-semibold mb-1">Skor Total</div>
                                    <div class="text-2xl font-bold text-slate-900">{{ $selectedRespondent->skor }}</div>
                                </div>
                                <div class="bg-white px-4 py-3 rounded-lg border border-slate-200 shadow-sm flex-1">
                                    <div class="text-xs text-slate-500 uppercase tracking-wide font-semibold mb-1">Kategori</div>
                                    <div class="text-lg font-bold
                                        @if($selectedRespondent->kategori === 'Kemungkinan Kecil') text-emerald-600
                                        @elseif($selectedRespondent->kategori === 'Perlu Perhatian') text-amber-600
                                        @else text-rose-600 @endif">
                                        {{ $selectedRespondent->kategori }}
                                    </div>
                                </div>
                                <div class="bg-white px-4 py-3 rounded-lg border border-slate-200 shadow-sm flex-1">
                                    <div class="text-xs text-slate-500 uppercase tracking-wide font-semibold mb-1">Event</div>
                                    <div class="text-sm font-semibold text-slate-700">{{ $selectedRespondent->event->nama_event ?? '-' }}</div>
                                </div>
                            </div>

                            {{-- Answers Grid 2 Kolom --}}
                            <div>
                                <h4 class="text-sm font-semibold text-slate-900 mb-3">Rincian Jawaban:</h4>
                                @if(is_array($selectedRespondent->jawaban))
                                    <div class="columns-1 sm:columns-2 gap-2">
                                        @foreach($selectedRespondent->jawaban as $q => $ans)
                                            <div class="break-inside-avoid mb-2 bg-white p-3 rounded-lg border border-slate-200 flex justify-between items-center shadow-sm gap-3">
                                                <div class="flex items-center gap-2 flex-1 min-w-0">
                                                    <span class="text-xs font-bold text-purple-600 shrink-0">{{ $q }}</span>
                                                    <span class="text-sm text-slate-700 truncate">{{ is_array($ans) ? ($ans['pertanyaan'] ?? '-') : ($questions[$q]->pertanyaan ?? '-') }}</span>
                                                </div>
                                                @php $jawabanTeks = is_array($ans) ? ($ans['jawaban'] ?? '') : $ans; @endphp
                                                <span class="text-sm px-2 py-1 rounded font-semibold shrink-0 uppercase
                                                    {{ strtolower($jawabanTeks) === 'ya' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600' }}">
                                                    {{ $jawabanTeks }}
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-sm text-slate-500">Tidak ada data jawaban.</p>
                                @endif
                            </div>
                        </div>

                        {{-- Modal Footer --}}
                        <div class="bg-white px-6 py-3 border-t border-slate-100 flex justify-end">
                            <button type="button" wire:click="closeModal" class="inline-flex justify-center rounded-lg border border-slate-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-purple-500 transition-colors">
                                Tutup
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

    {{-- Edit Modal --}}
    @if($isEditOpen)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                
                {{-- Overlay --}}
                <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" aria-hidden="true" wire:click="closeEditModal"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                {{-- Modal Card --}}
                <div class="inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-2xl shadow-2xl sm:my-8 sm:align-middle sm:max-w-xl sm:w-full sm:p-6 border border-slate-100">
                    <form wire:submit.prevent="update">
                        <div>
                            <div class="flex justify-between items-center mb-5 pb-4 border-b border-slate-100">
                                <h3 class="text-xl font-bold text-[#0B1238]" id="modal-title">
                                    Edit Event
                                </h3>
                                <button type="button" wire:click="closeEditModal" class="text-slate-400 hover:text-slate-500 focus:outline-none bg-slate-50 hover:bg-slate-100 p-2 rounded-full transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>
                            
                            <div class="space-y-5">
                                <div>
                                    <label for="edit_nama_event" class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Event <span class="text-red-500">*</span></label>
                                    <input type="text" id="edit_nama_event" wire:model="edit_nama_event" class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm bg-slate-50 focus:bg-white transition-colors py-2.5 px-3 border @error('edit_nama_event') border-red-300 text-red-900 focus:border-red-500 focus:ring-red-500 @enderror">
                                    @error('edit_nama_event') <span class="text-red-500 text-xs mt-1.5 block font-medium">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label for="edit_deskripsi" class="block text-sm font-semibold text-slate-700 mb-1.5">Event Objective (Deskripsi)</label>
                                    <textarea id="edit_deskripsi" wire:model="edit_deskripsi" rows="3" class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm bg-slate-50 focus:bg-white transition-colors py-2.5 px-3 border @error('edit_deskripsi') border-red-300 text-red-900 focus:border-red-500 focus:ring-red-500 @enderror" placeholder="Jelaskan tujuan dari event skrining ini..."></textarea>
                                    @error('edit_deskripsi') <span class="text-red-500 text-xs mt-1.5 block font-medium">{{ $message }}</span> @enderror
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <div>
                                        <label for="edit_lokasi" class="block text-sm font-semibold text-slate-700 mb-1.5">Lokasi <span class="text-red-500">*</span></label>
                                        <input type="text" id="edit_lokasi" wire:model="edit_lokasi" class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm bg-slate-50 focus:bg-white transition-colors py-2.5 px-3 border @error('edit_lokasi') border-red-300 text-red-900 focus:border-red-500 focus:ring-red-500 @enderror">
                                        @error('edit_lokasi') <span class="text-red-500 text-xs mt-1.5 block font-medium">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label for="edit_tanggal" class="block text-sm font-semibold text-slate-700 mb-1.5">Tanggal <span class="text-red-500">*</span></label>
                                        <input type="date" id="edit_tanggal" wire:model="edit_tanggal" class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm bg-slate-50 focus:bg-white transition-colors py-2.5 px-3 border @error('edit_tanggal') border-red-300 text-red-900 focus:border-red-500 focus:ring-red-500 @enderror">
                                        @error('edit_tanggal') <span class="text-red-500 text-xs mt-1.5 block font-medium">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div>
                                    <label for="edit_status" class="block text-sm font-semibold text-slate-700 mb-1.5">Status <span class="text-red-500">*</span></label>
                                    <select id="edit_status" wire:model="edit_status" class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm bg-slate-50 focus:bg-white transition-colors py-2.5 px-3 border @error('edit_status') border-red-300 text-red-900 focus:border-red-500 focus:ring-red-500 @enderror">
                                        <option value="Akan Datang">Akan Datang</option>
                                        <option value="Berjalan">Berjalan</option>
                                        <option value="Selesai">Selesai</option>
                                    </select>
                                    @error('edit_status') <span class="text-red-500 text-xs mt-1.5 block font-medium">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        
                        {{-- Footer Buttons --}}
                        <div class="mt-8 pt-5 border-t border-slate-100 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                            <button type="button" wire:click="closeEditModal" class="inline-flex justify-center w-full sm:w-auto px-5 py-2.5 text-sm font-bold text-slate-700 bg-white border border-slate-300 rounded-lg shadow-sm hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500 transition-colors">
                                Batal
                            </button>
                            <button type="submit" class="inline-flex justify-center w-full sm:w-auto px-5 py-2.5 text-sm font-bold text-white bg-purple-600 border border-transparent rounded-lg shadow-sm hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-colors">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal QR Code --}}
    @if($isQrModalOpen)
        <div class="fixed inset-0 z-[60] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                
                {{-- Overlay --}}
                <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity" aria-hidden="true" wire:click="closeQrModal"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                {{-- Modal Card --}}
                <div class="inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-xl shadow-xl sm:my-8 sm:align-middle sm:max-w-sm sm:w-full sm:p-6">
                    <div class="text-center">
                        <h3 class="text-lg font-bold leading-6 text-slate-900 mb-2">
                            QR Code Event
                        </h3>
                        <p class="text-sm text-slate-500 mb-6">
                            Scan QR Code ini untuk langsung menuju halaman pengisian kuesioner.
                        </p>
                        
                        {{-- Menampilkan Gambar QR Secara Langsung di Layar --}}
                        <div class="flex justify-center mb-4">
                            <div class="p-4 bg-white border border-slate-100 rounded-xl shadow-sm inline-block">
                                {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(200)->generate(url('/skrining/'.$event->id)) !!}
                            </div>
                        </div>

                        <p class="text-xs text-slate-400 break-all mb-2 font-mono">
                            {{ url('/skrining/'.$event->id) }}
                        </p>
                    </div>
                    
                    {{-- Footer Buttons --}}
                    <div class="mt-5 sm:mt-6 sm:flex sm:flex-col gap-2">
                        <button type="button" wire:click="downloadPdf" class="inline-flex justify-center w-full px-4 py-2.5 text-sm font-semibold text-white bg-purple-600 border border-transparent rounded-lg shadow-sm hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-colors">
                            Download PDF
                        </button>
                        <button type="button" wire:click="closeQrModal" class="inline-flex justify-center w-full px-4 py-2.5 text-sm font-semibold text-slate-700 bg-white border border-slate-300 rounded-lg shadow-sm hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-colors">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>