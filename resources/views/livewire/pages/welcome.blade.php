<?php
use Livewire\Volt\Component;
use App\Models\Event;
use Livewire\Attributes\Layout;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

new #[Layout('layouts.skrining')] class extends Component {
    public $isQrModalOpen = false;
    public ?Event $selectedEvent = null;

    // Menarik semua event yang statusnya "Berjalan"
    public function with()
    {
        return [
            'activeEvents' => Event::where('status', 'Berjalan')
                                ->orderBy('created_at', 'desc')
                                ->get()
        ];
    }

    public function openQrModal(Event $event)
    {
        $this->selectedEvent = $event;
        $this->isQrModalOpen = true;
    }

    public function closeQrModal()
    {
        $this->isQrModalOpen = false;
        $this->selectedEvent = null;
    }
}; ?>

<div class="max-w-md mx-auto min-h-screen bg-[#f8f9ff] relative pb-10 flex flex-col items-center pt-8 px-4">
    <img src="{{ asset('images/abasa-hr-logo.svg') }}" 
        alt="Abasa HR Consulting" 
        class="w-48 h-auto z-10 mb-6 drop-shadow-md">

    <div class="bg-white border border-indigo-50 rounded-3xl shadow-lg shadow-indigo-100/50 p-6 w-full relative z-20">
        <div class="w-12 h-1.5 bg-indigo-100 rounded-full mx-auto mb-6"></div>
        
        <h1 class="text-xl font-bold text-indigo-900 text-center mb-2 leading-snug">
            Mental Health Screening
        </h1>
        
        <p class="text-slate-500 text-center text-sm mb-6 leading-relaxed">
            Pilih event skrining yang sedang berlangsung di bawah ini.
        </p>
        
        <div class="space-y-4 mt-2">
            @forelse($activeEvents as $event)
                <div class="p-4 border border-slate-100 rounded-2xl bg-slate-50 hover:bg-indigo-50 transition-colors group">
                    <h3 class="font-bold text-indigo-900 text-base mb-1">{{ $event->nama_event }}</h3>
                    <div class="flex items-center text-sm text-slate-500 mb-2">
                        {{ $event->deskripsi }}
                    </div>
                    <div class="flex items-center text-xs text-indigo-900 mb-4">
                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        {{ $event->lokasi }}
                    </div>

                    <div class="flex gap-2">
                        <a href="{{ route('skrining.welcome', $event->id) }}" class="flex-1 flex items-center justify-center bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white text-sm font-semibold py-2.5 px-4 rounded-xl shadow-sm shadow-indigo-200 transition-all active:scale-95">
                            Mulai Screening
                        </a>
                        
                        <button wire:click="openQrModal({{ $event->id }})" class="bg-white border border-slate-200 hover:border-indigo-300 text-slate-600 text-sm font-semibold py-2.5 px-3 rounded-xl shadow-sm transition-all active:scale-95 flex items-center justify-center" title="Tampilkan QR Code">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                        </button>
                    </div>
                </div>
            @empty
                <div class="text-center py-8">
                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-slate-100 mb-3">
                        <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                    </div>
                    <p class="text-slate-500 text-sm">Saat ini belum ada event skrining yang sedang berjalan.</p>
                </div>
            @endforelse
        </div>
    </div>

    <a href="{{ route('login') }}" class="mt-8 text-xs text-slate-400 hover:text-indigo-600 font-medium transition-colors">
        Login sebagai Admin
    </a>

    @if($isQrModalOpen && $selectedEvent)
        <div class="fixed inset-0 z-50 flex items-center justify-center px-4">
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" wire:click="closeQrModal"></div>
            
            <div class="bg-white rounded-3xl p-6 w-full max-w-sm relative z-10 shadow-2xl transform transition-all">
                <div class="text-center">
                    <h3 class="font-bold text-lg text-indigo-900 mb-1">QR Code Event</h3>
                    <p class="text-sm text-slate-500 mb-6 line-clamp-1">{{ $selectedEvent->nama_event }}</p>
                    
                    <div class="flex justify-center mb-6">
                        <div class="p-4 bg-white border-2 border-indigo-50 rounded-2xl shadow-sm inline-block">
                            {!! QrCode::size(200)->generate(route('skrining.welcome', $selectedEvent->id)) !!}
                        </div>
                    </div>
                    
                    <p class="text-xs text-slate-400 mb-6">Minta responden memindai QR Code ini menggunakan kamera HP mereka.</p>
                    
                    <button wire:click="closeQrModal" class="w-full bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold py-3 px-4 rounded-xl transition-colors active:scale-95">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>