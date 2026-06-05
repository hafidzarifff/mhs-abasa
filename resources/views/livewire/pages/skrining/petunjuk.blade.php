<?php

use Livewire\Volt\Component;
use App\Models\Event;
use App\Models\Respondent;
use Livewire\Attributes\Layout;

new #[Layout('layouts.skrining')] class extends Component {
    public Event $event;
    public Respondent $respondent;

    public function mount(Event $event, Respondent $respondent)
    {
        $this->event = $event;
        $this->respondent = $respondent;
    }

    public function mulaiSkrining()
    {
        return redirect()->route('skrining.disclaimer', [
            'event'      => $this->event->id,
            'respondent' => $this->respondent->id,
        ]);
    }
}; ?>

<div class="max-w-md mx-auto min-h-screen relative flex flex-col p-5 pb-28">

    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-2">
            <img src="{{ asset('images/abasa-hr-logo.svg') }}" alt="Abasa HR Consulting" class="h-8 w-auto">
        </div>
    </div>

    <!-- Progress Bar -->
    <div class="w-full bg-indigo-100 rounded-full h-1.5 mb-6">
        <div class="bg-indigo-600 h-1.5 rounded-full transition-all duration-500" style="width: 70%"></div>
    </div>

    <!-- Icon -->
    <div class="flex justify-center mb-6">
        <div class="w-16 h-16 rounded-full bg-indigo-100 flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-9 h-9 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
        </div>
    </div>

    <!-- Title -->
    <h1 class="text-3xl font-extrabold text-indigo-900 text-center mb-8 leading-tight">
        Petunjuk Pengisian
    </h1>

    <!-- Instruction Cards -->
    <div class="flex flex-col gap-3 w-full">

        <!-- Item 1 -->
        <div class="bg-white border border-indigo-100 rounded-2xl p-4 flex items-center gap-4 shadow-sm">
            <div class="w-10 h-10 rounded-xl bg-indigo-100 flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <p class="text-sm text-slate-600 leading-relaxed">
                Jawablah berdasarkan kondisimu dalam <span class="font-bold text-slate-800">30 hari terakhir</span>.
            </p>
        </div>

        <!-- Item 2 -->
        <div class="bg-white border border-indigo-100 rounded-2xl p-4 flex items-center gap-4 shadow-sm">
            <div class="w-10 h-10 rounded-xl bg-indigo-100 flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
            </div>
            <p class="text-sm text-slate-600 leading-relaxed">
                Pilih jawaban <span class="font-bold text-slate-800">YA</span> atau <span class="font-bold text-slate-800">TIDAK</span> yang paling sesuai dengan apa yang dirasakan.
            </p>
        </div>

        <!-- Item 3 -->
        <div class="bg-white border border-indigo-100 rounded-2xl p-4 flex items-center gap-4 shadow-sm">
            <div class="w-10 h-10 rounded-xl bg-indigo-100 flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                </svg>
            </div>
            <p class="text-sm text-slate-600 leading-relaxed">
                Jangan ragu bertanya kepada <span class="font-bold text-slate-800">kakak-kakak Abasa</span> jika ada pertanyaan yang kurang jelas.
            </p>
        </div>

    </div>

    <!-- Fixed Bottom Button -->
    <div class="fixed bottom-0 left-0 right-0 max-w-md mx-auto px-5 pb-6 pt-4 bg-gradient-to-t from-[#F9F6FF] via-[#F9F6FF] to-transparent z-30">
        <button wire:click="mulaiSkrining"
            class="w-full py-4 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-semibold rounded-full shadow-lg shadow-indigo-200 transition-transform active:scale-95 flex items-center justify-center gap-2">
            Lanjutkan
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
            </svg>
        </button>
    </div>

</div>