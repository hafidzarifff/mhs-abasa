<?php

use Livewire\Volt\Component;
use App\Models\Event;
use App\Models\Respondent;
use Livewire\Attributes\Layout;

new #[Layout('layouts.skrining')] class extends Component {
    public Event $event;
    public Respondent $respondent;
    public bool $sudah_paham = false;

    public function mount(Event $event, Respondent $respondent)
    {
        $this->event = $event;
        $this->respondent = $respondent;
    }

    public function lanjutkan()
    {
        $this->validate([
            'sudah_paham' => 'accepted',
        ], [
            'sudah_paham.accepted' => 'Kamu harus menyetujui pernyataan di atas untuk melanjutkan.',
        ]);

        return redirect()->route('skrining.pertanyaan', [
            'event'      => $this->event->id,
            'respondent' => $this->respondent->id,
        ]);
    }
}; ?>

<div class="max-w-md mx-auto min-h-screen relative flex flex-col p-5 pb-28">

    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-2">
            <img src="{{ asset('images/abasa-hr-logo.svg') }}" alt="Abasa Consulting" class="h-8 w-auto">
        </div>
    </div>

    <!-- Progress Bar -->
    <div class="w-full bg-indigo-100 rounded-full h-1.5 mb-8">
        <div class="bg-indigo-600 h-1.5 rounded-full transition-all duration-500" style="width: 100%"></div>
    </div>

    <!-- Title -->
    <h1 class="text-2xl font-extrabold text-indigo-900 mb-8 leading-snug">
        Sebelum lanjut, ada beberapa hal yang perlu kamu baca dan pahami ya. Yuk disimak!
    </h1>

    <!-- Info Cards -->
    <div class="flex flex-col gap-3 w-full mb-4">

        <!-- Item 1 -->
        <div class="bg-white border border-indigo-100 rounded-2xl p-4 flex items-start gap-4 shadow-sm">
            <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center shrink-0 mt-0.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 16v-4m0-4h.01"/>
                </svg>
            </div>
            <p class="text-sm text-slate-600 leading-relaxed">
                Hasil tes ini merupakan <span class="font-bold text-slate-800">skrining awal</span> dan tidak dapat dijadikan sebagai diagnosis pasti.
            </p>
        </div>

        <!-- Item 2 -->
        <div class="bg-white border border-indigo-100 rounded-2xl p-4 flex items-start gap-4 shadow-sm">
            <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center shrink-0 mt-0.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <p class="text-sm text-slate-600 leading-relaxed">
                Hanya profesional kesehatan mental (psikolog atau psikiater) yang memiliki kewenangan untuk memberikan diagnosis resmi.
            </p>
        </div>

        <!-- Item 3 -->
        <div class="bg-white border border-indigo-100 rounded-2xl p-4 flex items-start gap-4 shadow-sm">
            <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center shrink-0 mt-0.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <p class="text-sm text-slate-600 leading-relaxed">
                Hasil skrining mencerminkan kondisi Anda saat ini dan dapat berubah seiring berjalannya waktu.
            </p>
        </div>

        <!-- Item 4 -->
        <div class="bg-white border border-indigo-100 rounded-2xl p-4 flex items-start gap-4 shadow-sm">
            <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center shrink-0 mt-0.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                </svg>
            </div>
            <p class="text-sm text-slate-600 leading-relaxed">
                Kami sangat menyarankan Anda untuk berkonsultasi dengan profesional jika merasakan gejala yang mengganggu atau membutuhkan bantuan lebih lanjut.
            </p>
        </div>

    </div>

    <!-- Checkbox Persetujuan -->
    <div x-data>
        <label class="bg-white border border-indigo-100 rounded-2xl p-4 flex items-center gap-4 shadow-sm cursor-pointer"
               :class="$wire.sudah_paham ? 'border-indigo-400 bg-indigo-50/50' : ''">
            <div class="w-6 h-6 rounded-md border-2 flex items-center justify-center shrink-0 transition-all"
                 :class="$wire.sudah_paham ? 'bg-indigo-600 border-indigo-600' : 'border-slate-300 bg-white'"
                 @click="$wire.sudah_paham = !$wire.sudah_paham">
                <svg x-show="$wire.sudah_paham" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <p class="text-sm font-bold text-slate-800 leading-snug">
                Aku sudah paham dan menyetujui hal di atas
            </p>
        </label>
        @error('sudah_paham')
            <p class="text-xs text-red-400 mt-2 ml-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Fixed Bottom Button -->
    <div class="fixed bottom-0 left-0 right-0 max-w-md mx-auto px-5 pb-6 pt-4 bg-gradient-to-t from-[#F9F6FF] via-[#F9F6FF] to-transparent z-30">
        <button wire:click="lanjutkan"
            class="w-full py-4 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-semibold rounded-full shadow-lg shadow-indigo-200 transition-transform active:scale-95 flex items-center justify-center gap-2"
            :class="!$wire.sudah_paham ? 'opacity-60 cursor-not-allowed' : ''"
            x-data>
            Mulai Screening
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
            </svg>
        </button>
    </div>

</div>