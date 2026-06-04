<?php

use App\Models\Event;
use Livewire\Volt\Component;
use Livewire\WithPagination;
use function Livewire\Volt\layout;
use function Livewire\Volt\title;

layout('layouts.app');
title('Data Event');

new class extends Component {
    use WithPagination;

    public $nama_event = '';
    public $deskripsi = '';
    public $lokasi = '';
    public $tanggal = '';
    public $status = 'Akan Datang';
    public $search = '';
    public $isOpen = false;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function create()
    {
        $this->resetFields();
        $this->isOpen = true;
    }

    public function store()
    {
        $this->validate([
            'nama_event' => 'required|string|max:255',
            'deskripsi'  => 'nullable|string',
            'lokasi'     => 'required|string|max:255',
            'tanggal'    => 'required|date',
            'status'     => 'required|in:Akan Datang,Berjalan,Selesai',
        ]);

        Event::create([
            'nama_event' => $this->nama_event,
            'deskripsi'  => $this->deskripsi,
            'lokasi'     => $this->lokasi,
            'tanggal'    => $this->tanggal,
            'status'     => $this->status,
        ]);

        $this->closeModal();
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->resetFields();
        $this->resetValidation();
    }

    private function resetFields()
    {
        $this->nama_event = '';
        $this->deskripsi  = '';
        $this->lokasi     = '';
        $this->tanggal    = '';
        $this->status     = 'Akan Datang';
    }

    public function with(): array
    {
        return [
            'events' => Event::withCount('respondents')
                ->where('nama_event', 'like', '%' . $this->search . '%')
                ->orderBy('id', 'desc')
                ->paginate(10)
        ];
    }
}; ?>

<div>
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-slate-800 tracking-tight">Data Event</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola daftar event kegiatan skrining kesehatan mental.</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <button wire:click="create" class="inline-flex items-center justify-center px-4 py-2 bg-purple-700 hover:bg-purple-800 transition-colors border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Tambah Event
            </button>
        </div>
    </div>

    {{-- Table Container --}}
    <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-slate-200">

        {{-- Search Bar --}}
        <div class="p-4 border-b border-slate-100 flex justify-end bg-white">
            <div class="relative w-full md:w-1/3">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                    </svg>
                </div>
                <input type="text" wire:model.live="search" class="block w-full p-2 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500" placeholder="Cari Event...">
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th scope="col" class="px-6 py-4 text-xs font-semibold text-slate-600 uppercase tracking-wider">Nama Event</th>
                        <th scope="col" class="px-6 py-4 text-xs font-semibold text-slate-600 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-4 text-xs font-semibold text-slate-600 uppercase tracking-wider">Lokasi</th>
                        <th scope="col" class="px-6 py-4 text-xs font-semibold text-slate-600 uppercase tracking-wider">Tanggal</th>
                        <th scope="col" class="px-6 py-4 text-xs font-semibold text-slate-600 uppercase tracking-wider text-center">Responden</th>
                        <th scope="col" class="px-6 py-4 text-xs font-semibold text-slate-600 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($events as $event)
                        <tr class="hover:bg-slate-50 transition-colors" wire:key="event-{{ $event->id }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">{{ $event->nama_event }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($event->status === 'Akan Datang')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Akan Datang</span>
                                @elseif($event->status === 'Berjalan')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Berjalan</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Selesai</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">{{ $event->lokasi }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">{{ \Carbon\Carbon::parse($event->tanggal)->format('d M Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900 text-center">{{ $event->respondents_count }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-right">
                                <a href="{{ route('events.detail', $event->id) }}" class="text-purple-600 hover:text-purple-900 transition-colors inline-flex items-center">
                                    Lihat Detail
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-sm text-slate-500 text-center">Tidak ada data event ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($events->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50 flex justify-end">
                {{ $events->onEachSide(1)->links('vendor.pagination.custom') }}
            </div>
        @endif
    </div>
    
    {{-- Modal Create --}}
    @if($isOpen)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">

                {{-- Overlay --}}
                <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" aria-hidden="true" wire:click="closeModal"></div>

                {{-- Modal Card --}}
                <div class="relative inline-block text-left align-middle transition-all transform bg-white rounded-2xl shadow-2xl w-full sm:max-w-xl sm:w-full border border-slate-100 z-10">

                    {{-- Modal Header --}}
                    <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100">
                        <h3 class="text-xl font-bold text-slate-800" id="modal-title">Tambah Event Baru</h3>
                        <button type="button" wire:click="closeModal" class="text-slate-400 hover:text-slate-600 bg-slate-100 hover:bg-slate-200 p-1.5 rounded-full transition-colors focus:outline-none">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    {{-- Modal Body --}}
                    <form wire:submit.prevent="store">
                        <div class="px-6 py-5 space-y-5">

                            {{-- Nama Event --}}
                            <div>
                                <label for="nama_event" class="block text-sm font-semibold text-slate-700 mb-1.5">
                                    Nama Event <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    id="nama_event"
                                    wire:model="nama_event"
                                    placeholder="Contoh: Event Kajian Anak Muda"
                                    class="block w-full rounded-lg border border-slate-200 bg-slate-50 focus:bg-white px-3 py-2.5 text-sm text-slate-800 placeholder-slate-400 shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors @error('nama_event') border-red-300 focus:ring-red-400 focus:border-red-400 @enderror"
                                >
                                @error('nama_event') <span class="text-red-500 text-xs mt-1.5 block font-medium">{{ $message }}</span> @enderror
                            </div>

                            {{-- Deskripsi --}}
                            <div>
                                <label for="deskripsi" class="block text-sm font-semibold text-slate-700 mb-1.5">
                                    Deskripsi
                                </label>
                                <textarea
                                    id="deskripsi"
                                    wire:model="deskripsi"
                                    rows="3"
                                    placeholder="Jelaskan tujuan dari event skrining ini..."
                                    class="block w-full rounded-lg border border-slate-200 bg-slate-50 focus:bg-white px-3 py-2.5 text-sm text-slate-800 placeholder-slate-400 shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors resize-none @error('deskripsi') border-red-300 focus:ring-red-400 focus:border-red-400 @enderror"
                                ></textarea>
                                @error('deskripsi') <span class="text-red-500 text-xs mt-1.5 block font-medium">{{ $message }}</span> @enderror
                            </div>

                            {{-- Lokasi & Tanggal (2 col) --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="lokasi" class="block text-sm font-semibold text-slate-700 mb-1.5">
                                        Lokasi <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        id="lokasi"
                                        wire:model="lokasi"
                                        placeholder="Contoh: Jakarta"
                                        class="block w-full rounded-lg border border-slate-200 bg-slate-50 focus:bg-white px-3 py-2.5 text-sm text-slate-800 placeholder-slate-400 shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors @error('lokasi') border-red-300 focus:ring-red-400 focus:border-red-400 @enderror"
                                    >
                                    @error('lokasi') <span class="text-red-500 text-xs mt-1.5 block font-medium">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="tanggal" class="block text-sm font-semibold text-slate-700 mb-1.5">
                                        Tanggal <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        type="date"
                                        id="tanggal"
                                        wire:model="tanggal"
                                        class="block w-full rounded-lg border border-slate-200 bg-slate-50 focus:bg-white px-3 py-2.5 text-sm text-slate-800 shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors @error('tanggal') border-red-300 focus:ring-red-400 focus:border-red-400 @enderror"
                                    >
                                    @error('tanggal') <span class="text-red-500 text-xs mt-1.5 block font-medium">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            {{-- Status --}}
                            <div>
                                <label for="status" class="block text-sm font-semibold text-slate-700 mb-1.5">
                                    Status <span class="text-red-500">*</span>
                                </label>
                                <select
                                    id="status"
                                    wire:model="status"
                                    class="block w-full rounded-lg border border-slate-200 bg-slate-50 focus:bg-white px-3 py-2.5 text-sm text-slate-800 shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors @error('status') border-red-300 focus:ring-red-400 focus:border-red-400 @enderror"
                                >
                                    <option value="Akan Datang">Akan Datang</option>
                                    <option value="Berjalan">Berjalan</option>
                                    <option value="Selesai">Selesai</option>
                                </select>
                                @error('status') <span class="text-red-500 text-xs mt-1.5 block font-medium">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        {{-- Modal Footer --}}
                        <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-slate-100 bg-slate-50 rounded-b-2xl">
                            <button
                                type="button"
                                wire:click="closeModal"
                                class="inline-flex justify-center px-5 py-2.5 text-sm font-bold text-slate-700 bg-white border border-slate-300 rounded-lg shadow-sm hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-400 transition-colors"
                            >
                                Batal
                            </button>
                            <button
                                type="submit"
                                class="inline-flex justify-center px-5 py-2.5 text-sm font-bold text-white bg-purple-600 border border-transparent rounded-lg shadow-sm hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-colors"
                            >
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>