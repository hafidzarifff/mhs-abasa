<?php

use Livewire\Volt\Component;
use App\Models\Event;
use App\Models\Respondent;
use Livewire\Attributes\Layout;

new #[Layout('layouts.skrining')] class extends Component {
    public Event $event;
    public Respondent $respondent;
    public string $kategori = '';
    public string $judul = '';
    public string $deskripsi = '';
    public string $warna = '';
    public int $jumlahYa = 0;
    public float $persentase = 0;

    private const THRESHOLD_KECIL = 33;
    private const THRESHOLD_SEDANG = 66;

    public function mount(Event $event, Respondent $respondent)
    {
        $this->event = $event;
        $this->respondent = $respondent;

        $jawaban = collect($respondent->jawaban);
        $total = $jawaban->count();
        $skor = $jawaban->filter(fn($item) =>
            is_array($item)
                ? strtolower($item['jawaban']) === 'ya'
                : strtolower($item) === 'ya'
        )->count();

        $this->jumlahYa = $skor;
        $this->persentase = $total > 0 ? round(($skor / $total) * 100) : 0;

        $this->tentukanKategori();
    }

    private function tentukanKategori(): void
    {
        $kategori = $this->respondent->kategori;

        if (empty($kategori)) {
            $kategori = match(true) {
                $this->persentase <= self::THRESHOLD_KECIL  => 'Kemungkinan Kecil',
                $this->persentase <= self::THRESHOLD_SEDANG => 'Perlu Perhatian',
                default                                      => 'Kemungkinan Besar',
            };
        }

        match($kategori) {
            'Kemungkinan Kecil' => $this->setKategori(
                'Kemungkinan Kecil Masalah Kesehatan Mental',
                'green',
                'Hasil skrining menunjukkan kemungkinan kecil adanya masalah kesehatan mental. Tapi tetap penting untuk menjaga kesehatan pikiran dan perasaan ' . $this->respondent->nama . '. Terus lakukan hal-hal positif, jaga tidur dan makanmu, dan jangan ragu cerita ke orang yang kamu percaya.'
            ),
            'Perlu Perhatian' => $this->setKategori(
                'Perlu Perhatian Lebih pada Kesehatan Mental',
                'yellow',
                'Hasil skrining menunjukkan beberapa tanda yang perlu ' . $this->respondent->nama . ' perhatikan. Kondisi ini bisa membaik dengan langkah yang tepat. Cobalah berbicara dengan orang yang kamu percaya dan pertimbangkan untuk berkonsultasi dengan profesional.'
            ),
            default => $this->setKategori(
                'Kemungkinan Besar Adanya Masalah Kesehatan Mental',
                'red',
                $this->respondent->nama . ', kamu tidak sendirian. Hasil skrining menunjukkan kemungkinan besar adanya masalah yang perlu segera ditangani. Kami sangat menyarankan untuk segera berkonsultasi dengan psikolog atau psikiater profesional.'
            ),
        };
    }

    private function setKategori(string $judul, string $warna, string $deskripsi): void
    {
        $this->judul     = $judul;
        $this->warna     = $warna;
        $this->deskripsi = $deskripsi;
    }

    public function lanjutkan()
    {
        return redirect()->route('skrining.penutup', [
            'event'      => $this->event->id,
            'respondent' => $this->respondent->id,
        ]);
    }
}; ?>

<div class="max-w-md mx-auto min-h-screen relative flex flex-col p-5 pb-28">

    <!-- Logo -->
    <div class="flex justify-center mb-6">
        <img src="{{ asset('images/abasa-hr-logo.svg') }}" alt="Abasa Consulting" class="h-10 w-auto">
    </div>

    <!-- Title -->
    <h1 class="text-xl font-extrabold text-indigo-900 text-center mb-6 uppercase tracking-wide">
        HASIL SKRINING {{ strtoupper($respondent->nama) }}
    </h1>

    <!-- Hasil Card -->
    <div class="rounded-2xl border-2 p-5 mb-6 text-center
        {{ $warna === 'green' ? 'bg-green-50 border-green-400' : '' }}
        {{ $warna === 'yellow' ? 'bg-yellow-50 border-yellow-400' : '' }}
        {{ $warna === 'red' ? 'bg-red-50 border-red-400' : '' }}">

        <p class="font-bold text-base underline mb-3
            {{ $warna === 'green' ? 'text-green-700' : '' }}
            {{ $warna === 'yellow' ? 'text-yellow-700' : '' }}
            {{ $warna === 'red' ? 'text-red-700' : '' }}">
            {{ $judul }}
        </p>
        <p class="text-sm leading-relaxed
            {{ $warna === 'green' ? 'text-green-800' : '' }}
            {{ $warna === 'yellow' ? 'text-yellow-800' : '' }}
            {{ $warna === 'red' ? 'text-red-800' : '' }}">
            {{ str_replace(':nama', $respondent->nama, $deskripsi) }}
        </p>
    </div>

    <!-- Terima Kasih -->
    <div class="bg-white border border-indigo-100 rounded-2xl shadow-sm p-5 mb-4">
        <p class="text-sm font-bold text-indigo-700 mb-3">Terima kasih sudah mengisi skrining ini!</p>
        <p class="text-sm text-slate-600 leading-relaxed mb-1">
            <span class="font-bold">Catatan:</span> Hasil skrining ini <span class="font-bold">bukan hasil diagnosis</span> dan <span class="font-bold">tidak bisa dijadikan landasan untuk mendiagnosis diri sendiri</span>.
        </p>
        <p class="text-sm text-slate-600 leading-relaxed mt-3">
            Jika ingin mendapatkan informasi lebih lanjut, atau akses layanan pemeriksaan psikologis dan konseling, silahkan hubungi kami di
            <a href="https://wa.me/6282100000000" target="_blank"
                class="text-indigo-500 font-semibold underline">WhatsApp Abasa</a>.
        </p>
    </div>

    <!-- Fixed Bottom Button -->
    <div class="fixed bottom-0 left-0 right-0 max-w-md mx-auto px-5 pb-6 pt-4 bg-gradient-to-t from-[#F9F6FF] via-[#F9F6FF] to-transparent z-30">
        <button wire:click="lanjutkan"
            class="w-full py-4 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-semibold rounded-full shadow-lg shadow-indigo-200 transition-transform active:scale-95 flex items-center justify-center gap-2">
            Lanjutkan
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
            </svg>
        </button>
    </div>

</div>