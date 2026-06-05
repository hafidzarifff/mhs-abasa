<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Respondent;
use App\Models\Event;
use Illuminate\Support\Arr;

class RespondentSeeder extends Seeder
{
    public function run(): void
    {
        $events = Event::all();

        if ($events->isEmpty()) {
            $this->command->info('Tidak ada data Event. Jalankan EventSeeder terlebih dahulu!');
            return;
        }

        $names = [
            'Budi Santoso', 'Siti Aminah', 'Andi Wijaya', 'Dewi Lestari', 'Rian Hidayat',
            'Lani Putri', 'Eko Prasetyo', 'Maya Sari', 'Fajar Nugraha', 'Rina Permata',
            'Hadi Saputra', 'Nina Marlina', 'Gilang Ramadhan', 'Tari Utami', 'Bayu Segara'
        ];

        foreach ($events as $event) {
            // Kita buat antara 3 sampai 7 responden per event agar bervariasi
            $jumlahResponden = rand(3, 7);

            for ($i = 0; $i < $jumlahResponden; $i++) {
                
                // 1. Generate 20 Jawaban Acak (Ya/Tidak)
                $jawaban = [];
                $skor = 0;
                for ($q = 1; $q <= 20; $q++) {
                    $isi = Arr::random(['Ya', 'Tidak']);
                    $jawaban["Q$q"] = $isi;
                    if ($isi === 'Ya') $skor++; // Hitung skor jika jawabannya "Ya"
                }

                // 2. Tentukan Kategori Berdasarkan Skor
                if ($skor <= 5) {
                    $kategori = 'Kemungkinan Kecil';
                } elseif ($skor <= 10) {
                    $kategori = 'Perlu Perhatian';
                } else {
                    $kategori = 'Kemungkinan Besar';
                }

                // 3. Simpan ke Database
                Respondent::create([
                    'event_id' => $event->id,
                    'nama' => Arr::random($names) . ' ' . ($i + 1),
                    'usia' => rand(18, 55),
                    'jenis_kelamin' => Arr::random(['Laki-laki', 'Perempuan']),
                    'email' => strtolower(str_replace(' ', '', Arr::random($names))) . rand(10, 99) . '@example.com',
                    'no_hp' => '08' . rand(1111111111, 9999999999),
                    'ig' => '@' . strtolower(explode(' ', Arr::random($names))[0]) . rand(1, 100),
                    'sudah_follow_ig' => Arr::random([true, false]),
                    'skor' => $skor,
                    'kategori' => $kategori,
                    'jawaban' => $jawaban, // Akan otomatis ter-cast jadi JSON oleh Model
                ]);
            }
        }
    }
}