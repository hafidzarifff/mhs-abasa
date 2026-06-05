<?php

use App\Models\Respondent;
use App\Models\Event;
use Livewire\Volt\Component;
use Livewire\WithPagination;
use function Livewire\Volt\layout;
use function Livewire\Volt\title;

layout('layouts.app');
title('Data Responden');

new class extends Component {
    use WithPagination;

    public $search = '';
    public $filter_event = '';
    public $filter_kategori = '';
    public $isModalOpen = false;
    public ?Respondent $selectedRespondent = null;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterEvent()
    {
        $this->resetPage();
    }

    public function updatingFilterKategori()
    {
        $this->resetPage();
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

    public function with(): array
    {
        return [
            'events' => Event::orderBy('id', 'desc')->get(),
            'questions' => \App\Models\Question::all()->keyBy('kode_pertanyaan'), // tambah ini
            'respondents' => Respondent::with('event')
                ->when($this->search, function ($query) {
                    $query->where(function ($q) {
                        $q->where('nama', 'like', '%' . $this->search . '%')
                          ->orWhere('email', 'like', '%' . $this->search . '%');
                    });
                })
                ->when($this->filter_event, function ($query) {
                    $query->where('event_id', $this->filter_event);
                })
                ->when($this->filter_kategori, function ($query) {
                    $query->where('kategori', $this->filter_kategori);
                })
                ->orderBy('id', 'desc')
                ->paginate(10),
        ];
    }
}; ?>

<div>
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-900">Data Responden</h1>
        <p class="mt-1 text-sm text-slate-500">Kelola dan lihat hasil skrining dari seluruh responden.</p>
    </div>

    <!-- Table Container Card -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <!-- Filters Header -->
        <div class="flex flex-col md:flex-row gap-4 p-4 border-b border-slate-100 bg-white">
            <input type="text" wire:model.live="search" placeholder="Cari nama atau email..." class="border border-slate-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500 flex-1">
            
            <select wire:model.live="filter_event" class="border border-slate-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500 flex-1">
                <option value="">Semua Event</option>
                @foreach($events as $event)
                    <option value="{{ $event->id }}">{{ $event->nama_event }}</option>
                @endforeach
            </select>

            <select wire:model.live="filter_kategori" class="border border-slate-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500 flex-1">
                <option value="">Semua Kategori</option>
                <option value="Kemungkinan Kecil">Kemungkinan Kecil</option>
                <option value="Perlu Perhatian">Perlu Perhatian</option>
                <option value="Kemungkinan Besar">Kemungkinan Besar</option>
            </select>

            <a href="{{ route('export.respondents') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2.5 bg-green-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-green-700 shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-1 shrink-0">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Export to Excel
            </a>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider">
                        <th class="px-4 py-4 font-medium border-b border-slate-200 w-10">#</th>
                        <th class="px-4 py-4 font-medium border-b border-slate-200 w-36">WAKTU</th>
                        <th class="px-4 py-4 font-medium border-b border-slate-200 w-48">NAMA</th>
                        <th class="px-4 py-4 font-medium border-b border-slate-200 w-36">EVENT</th>
                        <th class="px-4 py-4 font-medium border-b border-slate-200 w-16">USIA</th>
                        <th class="px-4 py-4 font-medium border-b border-slate-200 w-12">JK</th>
                        <th class="px-4 py-4 font-medium border-b border-slate-200">EMAIL</th>
                        <th class="px-4 py-4 font-medium border-b border-slate-200 w-24 text-center">FOLLOW</th>
                        <th class="px-4 py-4 font-medium border-b border-slate-200 w-16 text-center">SKOR</th>
                        <th class="px-4 py-4 font-medium border-b border-slate-200 w-36 text-center whitespace-nowrap">KATEGORI</th>
                        <th class="px-4 py-4 font-medium border-b border-slate-200 w-20 text-center">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($respondents as $index => $respondent)
                        <tr class="hover:bg-slate-50 transition-colors duration-150">
                            <td class="px-6 py-4 text-slate-500">{{ $respondents->firstItem() + $index }}</td>
                            <td class="px-6 py-4 text-slate-500">{{ $respondent->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-4 font-medium text-slate-900">{{ $respondent->nama }}</td>
                            <td class="px-6 py-4 text-slate-500">{{ $respondent->event->nama_event ?? '-' }}</td>
                            <td class="px-6 py-4 text-slate-500">{{ $respondent->usia }}</td>
                            <td class="px-6 py-4 text-slate-500">{{ $respondent->jenis_kelamin }}</td>
                            <td class="px-6 py-4 text-slate-500">{{ $respondent->email }}</td>
                            <td class="px-6 py-4 text-center">
                                @if(isset($respondent->sudah_follow_ig))
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider {{ $respondent->sudah_follow_ig ? 'bg-indigo-50 text-indigo-600 border border-indigo-100' : 'bg-slate-50 text-slate-600 border border-slate-200' }}">
                                        {{ $respondent->sudah_follow_ig ? 'Sudah' : 'Belum' }}
                                    </span>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-6 py-4 font-semibold text-slate-900 text-center">{{ $respondent->skor }}</td>
                            <td class="px-4 py-4 whitespace-nowrap text-center">
                                @if($respondent->kategori === 'Kemungkinan Kecil')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold bg-emerald-50 text-emerald-600 uppercase tracking-wider border border-emerald-100 whitespace-nowrap">Kemungkinan Kecil</span>
                                @elseif($respondent->kategori === 'Perlu Perhatian')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold bg-amber-50 text-amber-600 uppercase tracking-wider border border-amber-100 whitespace-nowrap">Perlu Perhatian</span>
                                @elseif($respondent->kategori === 'Kemungkinan Besar')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold bg-rose-50 text-rose-600 uppercase tracking-wider border border-rose-100 whitespace-nowrap">Kemungkinan Besar</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold bg-slate-100 text-slate-800 whitespace-nowrap">{{ $respondent->kategori }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button wire:click="lihatJawaban({{ $respondent->id }})" class="inline-flex items-center px-3 py-1.5 text-xs font-semibold text-purple-600 bg-purple-50 border border-purple-200 rounded-lg hover:bg-purple-100 transition-colors">
                                        <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        Lihat
                                    </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="px-6 py-12 text-center text-slate-500">
                                Tidak ada data responden ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($respondents->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50 flex justify-end">
                {{ $respondents->onEachSide(1)->links('vendor.pagination.custom') }}
            </div>
        @endif
    </div>

    <!-- Answers Modal (Pop-up) -->
    @if($isModalOpen && $selectedRespondent)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <!-- Background backdrop -->
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-slate-900 bg-opacity-50 transition-opacity" aria-hidden="true" wire:click="closeModal"></div>

                <!-- Modal panel -->
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-4 sm:align-middle sm:max-w-5xl w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 border-b border-slate-100">
                        <h3 class="text-lg leading-6 font-medium text-slate-900" id="modal-title">
                            Detail Jawaban Responden
                        </h3>
                        <p class="mt-1 text-sm text-slate-500">{{ $selectedRespondent->nama }}</p>
                    </div>
                    
                    <div class="px-4 py-5 sm:p-6 bg-slate-50 max-h-[75vh] overflow-y-auto">
                        <!-- Basic info -->
                        <div class="mb-6 flex gap-4">
                            <div class="bg-white px-4 py-3 rounded-lg border border-slate-200 shadow-sm flex-1">
                                <div class="text-xs text-slate-500 uppercase tracking-wide font-semibold mb-1">Skor Total</div>
                                <div class="text-2xl font-bold text-slate-900">{{ $selectedRespondent->skor }}</div>
                            </div>
                            <div class="bg-white px-4 py-3 rounded-lg border border-slate-200 shadow-sm flex-1">
                                <div class="text-xs text-slate-500 uppercase tracking-wide font-semibold mb-1">Kategori</div>
                                <div class="text-lg font-bold
                                    @if($selectedRespondent->kategori === 'Kemungkinan Kecil') text-green-600
                                    @elseif($selectedRespondent->kategori === 'Perlu Perhatian') text-yellow-600
                                    @elseif($selectedRespondent->kategori === 'Kemungkinan Besar') text-red-600
                                    @else text-slate-900 @endif
                                ">
                                    {{ $selectedRespondent->kategori }}
                                </div>
                            </div>
                        </div>

                        <!-- Answers Grid -->
                        <div>
                            <h4 class="text-sm font-semibold text-slate-900 mb-3">Rincian Jawaban:</h4>
                            @if(is_array($selectedRespondent->jawaban))
                                @php
                                    $jawabanSorted = collect($selectedRespondent->jawaban)->sortBy(function ($ans, $key) {
                                        return (int) filter_var($key, FILTER_SANITIZE_NUMBER_INT);
                                    })->toArray();
                                @endphp
                                <div class="columns-1 sm:columns-2 gap-2">
                                    @foreach($jawabanSorted as $q => $ans)
                                        <div class="break-inside-avoid mb-2 bg-white p-3 rounded-lg border border-slate-200 flex justify-between items-center shadow-sm gap-3">
                                            <div class="flex items-center gap-2 flex-1 min-w-0">
                                                <span class="text-xs font-bold text-purple-600 shrink-0">{{ $q }}</span>
                                                <span class="text-sm text-slate-700 truncate">
                                                    {{ is_array($ans) ? ($ans['pertanyaan'] ?? '-') : ($questions->get($q)->pertanyaan ?? '-') }}
                                                </span>
                                            </div>
                                            @php 
                                                $jawabanTeks = is_array($ans) ? ($ans['jawaban'] ?? '') : $ans;
                                                if (is_array($jawabanTeks)) $jawabanTeks = implode(', ', $jawabanTeks);
                                                $isYa = strtolower(trim((string)$jawabanTeks)) === 'ya';
                                            @endphp
                                            <span class="text-sm px-2 py-1 rounded font-semibold shrink-0 uppercase {{ $isYa ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600' }}">
                                                {{ (string)$jawabanTeks }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-sm text-slate-500">Tidak ada data jawaban.</p>
                            @endif
                        </div>
                    </div>
                    
                    <div class="bg-white px-4 py-3 border-t border-slate-100 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" wire:click="closeModal" class="w-full inline-flex justify-center rounded-lg border border-slate-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-150">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
