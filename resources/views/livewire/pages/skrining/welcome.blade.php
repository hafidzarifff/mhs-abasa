<?php

use Livewire\Volt\Component;
use App\Models\Event;
use Livewire\Attributes\Layout;

new #[Layout('layouts.skrining')] class extends Component {
    public Event $event;

    public function mount(Event $event)
    {
        $this->event = $event;
    }

    public function startScreening()
    {
        return redirect()->route('skrining.data-diri', ['event' => $this->event->id]);
    }
}; ?>

<div class="max-w-md mx-auto min-h-screen relative flex flex-col items-center justify-center p-5">
    <!-- Top Logo Image -->
    <img src="{{ asset('images/abasa-hr-logo.svg') }}" 
        alt="Abasa HR Consulting" 
        class="w-48 h-auto z-10">

    <!-- Content Card -->
    <div class="bg-white border-2 border-indigo-100 rounded-3xl shadow-sm p-6 w-full relative mt-5 z-20">
        <!-- Drag Handle -->
        <div class="w-12 h-1.5 bg-indigo-100 rounded-full mx-auto mb-6"></div>

        <!-- Title -->
        <h1 class="text-2xl font-bold text-indigo-900 text-center mb-3 leading-snug">
            Yuk, Kenali Kesehatan Mental Kamu!
        </h1>

        <!-- Subtitle -->
        <p class="text-slate-600 text-center text-sm mb-2 leading-relaxed">
            Screening ini dirancang untuk membantu mengenali kondisi emosional kamu dalam <span class="font-bold text-slate-800">30 hari terakhir</span>.
        </p>
        
        <!-- Dynamic Event Confirmation -->
        <div class="flex items-center justify-center my-5">
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-50 border border-indigo-100 rounded-full text-xs font-medium text-indigo-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                {{ $event->nama_event ?? 'Mental Health Awareness' }}
            </span>
        </div>

        <!-- Info Box -->
        <div class="bg-purple-50 rounded-2xl p-4 flex items-start gap-3">
            <!-- Outline Info Icon -->
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-purple-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="text-sm text-indigo-900/80 leading-relaxed">
                Ini <span class="font-bold">bukanlah ujian akademis</span>. Jawablah dengan santai dan jujur sesuai dengan apa yang kamu rasakan tanpa tekanan.
            </p>
        </div>
    </div>

    <!--Button -->
    <button wire:click="startScreening" class="w-full py-4 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-semibold rounded-full shadow-lg shadow-indigo-200 transition-transform active:scale-95 flex items-center justify-center gap-2 mt-12">
        Mulai Screening 
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
        </svg>
    </button>
</div>

