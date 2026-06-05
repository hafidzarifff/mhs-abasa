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
}; ?>

<div class="max-w-md mx-auto min-h-screen relative flex flex-col p-5">

    <!-- Logo -->
    <div class="flex justify-center mb-8 mt-4">
        <img src="{{ asset('images/abasa-hr-logo.svg') }}" alt="Abasa Consulting" class="h-10 w-auto">
    </div>

    <!-- Konten Utama -->
    <div class="bg-white border border-indigo-100 rounded-3xl shadow-sm p-6 w-full">

        <!-- Terima Kasih -->
        <p class="text-sm font-bold text-indigo-900 mb-4 leading-relaxed">
            Terima kasih
        </p>

        <p class="text-sm font-bold text-indigo-900 mb-4 leading-relaxed">
            <span class="font-arabic text-base">جَزَاكَ اللّٰهُ خَيْرًا</span>, 
            {{ $respondent->nama }}!
        </p>

        <p class="text-sm text-slate-600 leading-relaxed text-justify mb-6">
            Kesehatan mental dan iman bukan untuk dibandingkan, melainkan saling menguatkan. Saat hati terasa lelah, mempertebal iman dapat menjadi salah satu jalan untuk kembali seimbang.
        </p>

        <!-- Ayat -->
        <div class="mb-6">
            <p class="text-sm font-bold text-indigo-700 mb-3">
                Allah berfirman dalam QS. Ar-Ra'd ayat 28:
            </p>
            <p class="text-sm text-slate-600 leading-relaxed italic text-justify">
                "(Yaitu) orang-orang yang beriman dan hati mereka menjadi tenteram dengan mengingat Allah. Ingatlah, hanya dengan mengingat Allah-lah hati menjadi tenteram.".
            </p>
        </div>

        <div class="w-full h-px bg-indigo-50 mb-6"></div>

        <p class="text-sm text-slate-600 leading-relaxed text-justify mb-4">
            Apa pun hasilnya, kamu telah melakukan langkah yang baik dan berani untuk peduli pada kesehatan diri sendiri.
        </p>

        <p class="text-sm text-slate-600 leading-relaxed text-justify mb-6">
            Yuk, jaga terus kesehatan mental dan fisikmu, dengan tetap terhubung bersama orang-orang yang kamu sayangi, melakukan hal-hal yang bermanfaat, menjaga pola hidup sehat, dan pastinya tetap bersyukur atas hal-hal kecil setiap harinya yang sudah ALLAH takdirkan.
        </p>

        <p class="text-sm font-bold text-indigo-900 mb-2">
            <span class="font-arabic">بَارَكَ اللّٰهُ فِيْكُمْ</span> 
        </p>

        <div class="w-full h-px bg-indigo-50 mb-6"></div>

        <!-- Kontak -->
        <div class="mb-8">
            <p class="text-sm font-bold text-indigo-900 mb-4">Kontak Abasa HR Consulting:</p>
            <div class="space-y-4">
                
                <!-- WhatsApp -->
                <a href="https://wa.me/6282121919296" target="_blank" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 rounded-full bg-green-50 flex items-center justify-center shrink-0 group-hover:bg-green-100 transition-colors">
                        <i class="fa-brands fa-whatsapp text-green-600 text-lg"></i>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 mb-0.5">WhatsApp</p>
                        <p class="text-sm font-semibold text-slate-700 group-hover:text-green-600 transition-colors">+62 821-2191-9296</p>
                    </div>
                </a>

                <!-- Instagram -->
                <a href="https://www.instagram.com/abasa.hrconsulting/" target="_blank" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 rounded-full bg-pink-50 flex items-center justify-center shrink-0 group-hover:bg-pink-100 transition-colors">
                        <i class="fa-brands fa-instagram text-pink-600 text-lg"></i>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 mb-0.5">Instagram</p>
                        <p class="text-sm font-semibold text-slate-700 group-hover:text-pink-600 transition-colors">abasa.hrconsulting</p>
                    </div>
                </a>

                <!-- Location -->
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-location-dot text-indigo-500 text-lg"></i>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 mb-0.5">ABASA SPACE</p>
                        <p class="text-sm font-semibold text-slate-700 leading-relaxed">
                            Jl. Ciremai Raya Blok AB.2 No.7 RT.04/07 Komp. SBS-Harapan Jaya | Bekasi Utara 17124
                        </p>
                    </div>
                </div>

            </div>
        </div>

        <a href="{{ route('skrining.welcome', ['event' => $event->id]) }}" 
            class="w-full py-3.5 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-semibold rounded-full shadow-lg shadow-indigo-100 transition-all active:scale-[0.98] flex items-center justify-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Awal
        </a>

    </div>

    <!-- Spacer -->
    <div class="pb-10"></div>

</div>