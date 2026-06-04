<?php

use Livewire\Volt\Component;
use App\Models\Event;
use App\Models\Question;
use App\Models\Respondent;
use Livewire\Attributes\Layout;

new #[Layout('layouts.skrining')] class extends Component {
    public int $total = 0;
    public float $progress = 0;
    public bool $isLast = false;
    public string $kalimat = '';
    public int $currentIndex = 0;
    public array $jawaban = [];
    public string $nama = '';
    public $event;
    public $questions;

    public function mount(Event $event)
    {
        $this->event = $event;
        $this->nama = session('data_diri.nama', 'Kamu');
        $this->questions = Question::all()->sortBy(function ($q) {
            return (int) filter_var($q->kode_pertanyaan, FILTER_SANITIZE_NUMBER_INT);
        })->values();
        $this->total = $this->questions->count();

        if ($this->questions->isEmpty()) {
            abort(404, 'Tidak ada pertanyaan tersedia.');
        }

        $this->hitungState();
    }

    private function hitungState(): void
    {
        $this->progress = (($this->currentIndex + 1) / $this->total) * 100;
        $this->isLast   = $this->currentIndex === $this->total - 1;
        $this->kalimat  = str_replace(':nama', $this->nama, $this->questions[$this->currentIndex]->template_pertanyaan);
    }

    public function jawab(string $nilai): void
    {
        $this->jawaban[$this->currentIndex] = $nilai;
    }

    public function next(): void
    {
        if (!isset($this->jawaban[$this->currentIndex])) return;

        if ($this->currentIndex < $this->total - 1) {
            $this->currentIndex++;
            $this->hitungState();
        } else {
            $this->selesai();
        }
    }

    public function prev(): void
    {
        if ($this->currentIndex > 0) {
            $this->currentIndex--;
            $this->hitungState();
        }
    }

    public function selesai()
    {
        $dataDiri = session('data_diri');

        if (!$dataDiri) {
            return redirect()->route('skrining.welcome', $this->event->id);
        }

        // Hitung skor dan kategori secara dinamis
        $total = $this->questions->count();
        $skor = collect($this->jawaban)->filter(fn($j) => $j === 'ya')->count();
        $persentase = $total > 0 ? round(($skor / $total) * 100) : 0;

        $kategori = match(true) {
            $persentase <= 33  => 'Kemungkinan Kecil',
            $persentase <= 66  => 'Perlu Perhatian',
            default            => 'Kemungkinan Besar',
        };

        // Susun jawaban per question_id dalam format JSON
        $jawabanJson = [];
        foreach ($this->jawaban as $index => $jawaban) {
            $question = $this->questions[$index];
            $jawabanJson[$question->kode_pertanyaan] = [
                'pertanyaan' => $question->pertanyaan,
                'jawaban'    => $jawaban,
            ];
        }

        $respondent = Respondent::create([
            'event_id'        => $dataDiri['event_id'],
            'nama'            => $dataDiri['nama'],
            'usia'            => $dataDiri['usia'],
            'jenis_kelamin'   => $dataDiri['jenis_kelamin'],
            'email'           => $dataDiri['email'],
            'no_hp'           => $dataDiri['no_handphone'],
            'ig'              => $dataDiri['instagram'],
            'sudah_follow_ig' => $dataDiri['sudah_follow'] === 'sudah',
            'skor'            => $skor,
            'kategori'        => $kategori,
            'jawaban'         => $jawabanJson,
        ]);

        session()->forget('data_diri');

        return redirect()->route('skrining.hasil', [
            'event'      => $this->event->id,
            'respondent' => $respondent->id,
        ]);
    }
}; ?>

<div class="max-w-md mx-auto min-h-screen relative flex flex-col bg-[#F9F6FF]"
     x-data="{ jawaban: @entangle('jawaban') }">

    <!-- Header -->
    <div class="flex items-center justify-between px-5 pt-5 pb-3">
        <button wire:click="prev"
            class="w-10 h-10 rounded-full bg-white border border-indigo-100 flex items-center justify-center shadow-sm text-slate-400 hover:text-indigo-600 transition
                {{ $currentIndex === 0 ? 'opacity-30 pointer-events-none' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
        </button>

        <span class="text-sm font-semibold text-indigo-400">
            {{ $currentIndex + 1 }} / {{ $this->total }}
        </span>

        <a href="{{ route('skrining.welcome', $event->id) }}"
            class="w-10 h-10 rounded-full bg-white border border-indigo-100 flex items-center justify-center shadow-sm text-slate-400 hover:text-red-400 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </a>
    </div>

    <!-- Progress Bar -->
    <div class="px-5 mb-8">
        <div class="w-full bg-indigo-100 rounded-full h-1.5">
            <div class="bg-indigo-600 h-1.5 rounded-full transition-all duration-500"
                 style="width: {{ $progress }}%"></div>
        </div>
    </div>

    <!-- Question -->
    <div class="flex-1 px-5">
        <h1 class="text-2xl font-extrabold text-indigo-950 leading-snug mb-10">
            {{ $kalimat }}
        </h1>
    </div>

    <!-- Answer Buttons + Nav -->
    <div class="w-full">

        <!-- YA / TIDAK -->
        <div class="grid grid-cols-2 border-t border-indigo-100"
            x-data="{ pilihan: '' }"
            x-init="
                pilihan = $wire.jawaban[$wire.currentIndex] ?? '';
                $watch('$wire.currentIndex', (val) => {
                    pilihan = $wire.jawaban[val] ?? '';
                })
            ">

            <!-- YA -->
            <button @click="pilihan = 'ya'; $wire.jawab('ya')"
                class="flex flex-col items-center justify-center py-8 gap-3 border-r border-indigo-100 transition-all"
                :class="pilihan === 'ya' ? 'bg-green-50' : 'bg-white hover:bg-green-50/50'">
                <div class="w-16 h-16 rounded-full flex items-center justify-center transition-all"
                    :class="pilihan === 'ya' ? 'bg-green-200' : 'bg-green-100'">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <span class="text-sm font-bold tracking-widest"
                    :class="pilihan === 'ya' ? 'text-green-700' : 'text-slate-500'">
                    YA
                </span>
            </button>

            <!-- TIDAK -->
            <button @click="pilihan = 'tidak'; $wire.jawab('tidak')"
                class="flex flex-col items-center justify-center py-8 gap-3 transition-all"
                :class="pilihan === 'tidak' ? 'bg-red-50' : 'bg-white hover:bg-red-50/50'">
                <div class="w-16 h-16 rounded-full flex items-center justify-center transition-all"
                    :class="pilihan === 'tidak' ? 'bg-red-200' : 'bg-red-100'">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
                <span class="text-sm font-bold tracking-widest"
                    :class="pilihan === 'tidak' ? 'text-red-600' : 'text-slate-500'">
                    TIDAK
                </span>
            </button>
        </div>

        <!-- Bottom Nav -->
        <div class="flex items-center justify-between px-5 py-4 bg-white border-t border-indigo-100">
            <button wire:click="prev"
                class="w-12 h-12 rounded-full bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-400 hover:text-indigo-600 transition
                    {{ $currentIndex === 0 ? 'opacity-30 pointer-events-none' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </button>

            <button wire:click="next"
                class="px-8 py-3 rounded-full text-white font-semibold text-sm flex items-center gap-2 transition-all shadow-lg
                    {{ isset($jawaban[$currentIndex])
                        ? 'bg-gradient-to-r from-indigo-500 to-purple-600 shadow-indigo-200 active:scale-95'
                        : 'bg-indigo-200 cursor-not-allowed shadow-none' }}"
                {{ !isset($jawaban[$currentIndex]) ? 'disabled' : '' }}>
                {{ $isLast ? 'Selesai' : 'Next' }}
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                </svg>
            </button>
        </div>
    </div>

</div>