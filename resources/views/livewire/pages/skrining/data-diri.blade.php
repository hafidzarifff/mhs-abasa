<?php

use Livewire\Volt\Component;
use App\Models\Event;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;

new #[Layout('layouts.skrining')] class extends Component {
    use WithFileUploads;

    public Event $event;
    public string $nama = '';
    public string $usia = '';
    public string $jenis_kelamin = '';
    public string $email = '';
    public string $no_handphone = '';
    public string $instagram = '';
    public string $sudah_follow = '';
    public $bukti_screenshot = null;

    public function mount(Event $event)
    {
        $this->event = $event;
    }

    public function simpan()
    {
        $this->validate([
            'nama'   => 'required|string|max:255',
            'usia'           => 'required|numeric|min:1|max:120',
            'jenis_kelamin'  => 'required|in:Laki-laki,Perempuan',
            'email'          => 'required|email',
            'no_handphone'   => 'required|string|max:20',
            'instagram'      => 'nullable|string|max:100',
            'sudah_follow'   => 'required|in:sudah,belum',
            'bukti_screenshot' => 'nullable|image|max:5120',
        ]);

        $path = null;
        if ($this->bukti_screenshot) {
            $path = $this->bukti_screenshot->store('screenshots', 'public');
        }

        // Simpan ke session, bukan ke DB
        session([
            'data_diri' => [
                'event_id'         => $this->event->id,
                'nama'     => $this->nama,
                'usia'             => $this->usia,
                'jenis_kelamin'    => $this->jenis_kelamin,
                'email'            => $this->email,
                'no_handphone'     => $this->no_handphone,
                'instagram'        => $this->instagram,
                'sudah_follow'     => $this->sudah_follow,
                'bukti_screenshot' => $path,
            ]
        ]);

        // Redirect tanpa respondent ID di URL
        return redirect()->route('skrining.petunjuk', ['event' => $this->event->id]);
    }
}; ?>

<form wire:submit="simpan" class="max-w-md mx-auto min-h-screen relative flex flex-col p-5 pb-28">

    <!-- Header -->
    <div class="flex items-center gap-2 mb-6">
        <img src="{{ asset('images/abasa-hr-logo.svg') }}" alt="Abasa HR Consulting" class="h-8 w-auto">
    </div>

    <!-- Progress Bar -->
    <div class="w-full bg-indigo-100 rounded-full h-1.5 mb-6">
        <div class="bg-indigo-600 h-1.5 rounded-full" style="width: 40%"></div>
    </div>

    <!-- Page Title -->
    <h1 class="text-2xl font-bold text-indigo-900 mb-1">Data Diri</h1>
    <p class="text-sm text-slate-500 mb-6 leading-relaxed">
        Mohon lengkapi informasi di bawah ini untuk melanjutkan proses screening.
    </p>

    <!-- Section: Informasi Pribadi -->
    <div class="bg-white border border-indigo-100 rounded-3xl shadow-sm p-5 w-full mb-4">
        <h2 class="text-base font-bold text-indigo-900 mb-4">Informasi Pribadi</h2>

        <!-- Nama Lengkap -->
        <div class="mb-4">
            <label class="block text-[10px] font-semibold text-slate-400 tracking-widest uppercase mb-1.5">Nama Lengkap</label>
            <input wire:model="nama"
                type="text"
                placeholder="Ketik nama lengkap Anda"
                class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm text-slate-700 placeholder-slate-300 focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-transparent transition">
            @error('nama') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Usia -->
        <div class="mb-4">
            <label class="block text-[10px] font-semibold text-slate-400 tracking-widest uppercase mb-1.5">Usia</label>
            <div class="relative">
                <input wire:model="usia"
                    type="number"
                    placeholder="00"
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm text-slate-700 placeholder-slate-300 focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-transparent transition pr-16">
                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-sm text-slate-400 font-medium">Tahun</span>
            </div>
            @error('usia') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Jenis Kelamin -->
        <!-- Jenis Kelamin -->
        <div class="mb-4" x-data>
            <label class="block text-[10px] font-semibold text-slate-400 tracking-widest uppercase mb-1.5">Jenis Kelamin</label>
            <div class="flex gap-3">
                <button type="button" @click="$wire.jenis_kelamin = 'Laki-laki'"
                    class="flex-1 py-3 rounded-xl text-sm font-semibold transition-all"
                    :class="$wire.jenis_kelamin === 'Laki-laki' 
                        ? 'bg-indigo-600 text-white shadow-md shadow-indigo-200' 
                        : 'bg-indigo-50 text-indigo-400 border border-indigo-100'">
                    Laki-laki
                </button>
                <button type="button" @click="$wire.jenis_kelamin = 'Perempuan'"
                    class="flex-1 py-3 rounded-xl text-sm font-semibold transition-all"
                    :class="$wire.jenis_kelamin === 'Perempuan' 
                        ? 'bg-indigo-600 text-white shadow-md shadow-indigo-200' 
                        : 'bg-indigo-50 text-indigo-400 border border-indigo-100'">
                    Perempuan
                </button>
            </div>
        </div>
    </div>

    <!-- Section: Kontak & Media Sosial -->
    <div class="bg-white border border-indigo-100 rounded-3xl shadow-sm p-5 w-full mb-4">
        <h2 class="text-base font-bold text-indigo-900 mb-4">Kontak & Media Sosial</h2>

        <!-- Email -->
        <div class="mb-4">
            <label class="block text-[10px] font-semibold text-slate-400 tracking-widest uppercase mb-1.5">Alamat Email</label>
            <input wire:model="email"
                type="email"
                placeholder="nama@email.com"
                class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm text-slate-700 placeholder-slate-300 focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-transparent transition">
            @error('email') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- No Handphone -->
        <div class="mb-4">
            <label class="block text-[10px] font-semibold text-slate-400 tracking-widest uppercase mb-1.5">No Handphone (WhatsApp)</label>
            <input wire:model="no_handphone"
                type="tel"
                placeholder="0812 3456 7890"
                class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm text-slate-700 placeholder-slate-300 focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-transparent transition">
            @error('no_handphone') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Instagram -->
        <div class="mb-2">
            <label class="block text-[10px] font-semibold text-slate-400 tracking-widest uppercase mb-1.5">Akun Instagram</label>
            <div class="relative">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm font-medium">@</span>
                <input wire:model="instagram"
                    type="text"
                    placeholder="username"
                    class="w-full pl-8 pr-4 py-3 rounded-xl border border-slate-200 text-sm text-slate-700 placeholder-slate-300 focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-transparent transition">
            </div>
            @error('instagram') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
        </div>
    </div>

    <!-- Section: Verifikasi Follower -->
    <div class="bg-white border border-indigo-100 rounded-3xl shadow-sm p-5 w-full mb-4">

        <!-- Header verifikasi -->
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                </svg>
            </div>
            <div>
                <p class="text-sm font-bold text-indigo-900">Verifikasi Follower</p>
                <p class="text-xs text-slate-400 leading-relaxed">Apakah Anda sudah mengikuti Instagram <a href="https://www.instagram.com/abasa.hrconsulting/" target="_blank" class="font-semibold text-indigo-600 underline">@abasa.hrconsulting</a>?</p>
            </div>
        </div>

        <!-- Toggle Sudah / Belum -->
        <div class="flex gap-3 mb-4">
            <button type="button" @click="$wire.sudah_follow = 'sudah'"
                class="flex-1 py-3 rounded-xl text-sm font-semibold transition-all"
                :class="$wire.sudah_follow === 'sudah' 
                    ? 'bg-indigo-600 text-white shadow-md shadow-indigo-200' 
                    : 'bg-indigo-50 text-indigo-400 border border-indigo-100'">
                Sudah
            </button>
            <button type="button" @click="$wire.sudah_follow = 'belum'"
                class="flex-1 py-3 rounded-xl text-sm font-semibold transition-all"
                :class="$wire.sudah_follow === 'belum' 
                    ? 'bg-indigo-600 text-white shadow-md shadow-indigo-200' 
                    : 'bg-indigo-50 text-indigo-400 border border-indigo-100'">
                Belum
            </button>
        </div>
        @error('sudah_follow') <p class="text-xs text-red-400 mb-3">{{ $message }}</p> @enderror

        <!-- Upload Bukti -->
        <div>
            <label class="block text-[10px] font-semibold text-slate-400 tracking-widest uppercase mb-1.5">Unggah Bukti Screenshot</label>
            <label for="bukti" class="flex flex-col items-center justify-center w-full py-6 border-2 border-dashed border-indigo-200 rounded-2xl cursor-pointer bg-indigo-50/50 hover:bg-indigo-50 transition">
                <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                    </svg>
                </div>
                @if ($bukti_screenshot)
                    <p class="text-xs text-indigo-600 font-semibold">{{ $bukti_screenshot->getClientOriginalName() }}</p>
                    <p class="text-[10px] text-slate-400 mt-0.5">Klik untuk ganti file</p>
                @else
                    <p class="text-xs text-slate-500">
                        <span class="text-indigo-500 font-semibold">Klik untuk unggah</span> atau seret file
                    </p>
                    <p class="text-[10px] text-slate-400 mt-0.5">PNG, JPG (MAKS. 5MB)</p>
                @endif
                <input id="bukti" wire:model="bukti_screenshot" type="file" accept="image/*" class="hidden">
            </label>
            @error('bukti_screenshot') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
        </div>
    </div>

    <!-- Fixed Bottom Button -->
    <div class="fixed bottom-0 left-0 right-0 max-w-md mx-auto px-5 pb-6 pt-4 bg-gradient-to-t from-[#F9F6FF] via-[#F9F6FF] to-transparent z-30">
        <button type="submit"
            class="w-full py-4 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-semibold rounded-full shadow-lg shadow-indigo-200 transition-transform active:scale-95 flex items-center justify-center gap-2">
            Simpan & Lanjutkan
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
            </svg>
        </button>
        <p class="text-center text-[10px] text-slate-400 mt-3 flex items-center justify-center gap-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
            DATA ANDA DIJAMIN KERAHASIAANNYA
        </p>
    </div>

    @if($errors->any())
        <div class="text-red-500 text-xs mt-2">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif
</form>