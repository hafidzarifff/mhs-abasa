<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use Carbon\Carbon;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $events = [
            [
                'nama_event' => 'Kajian Anak Muda',
                'deskripsi' => 'Skrining kesehatan mental awal (PHQ-9 dan GAD-7) untuk peserta rutin kajian guna mendeteksi tingkat stres.',
                'lokasi' => 'Harris Hotel Jakarta',
                'tanggal' => Carbon::now()->format('Y-m-d'), // Tanggal hari ini
                'status' => 'Berjalan',
            ],
            [
                'nama_event' => 'Skrining Karyawan PT XYZ',
                'deskripsi' => 'Evaluasi tingkat stres dan kecemasan karyawan kuartal pertama tahun ini.',
                'lokasi' => 'Gedung Perkantoran Sudirman',
                'tanggal' => Carbon::now()->subDays(15)->format('Y-m-d'), // 15 hari yang lalu
                'status' => 'Selesai',
            ],
            [
                'nama_event' => 'Sosialisasi Mental Health Kampus',
                'deskripsi' => 'Deteksi dini gejala depresi pada mahasiswa baru tingkat awal sebagai syarat orientasi.',
                'lokasi' => 'Aula Universitas BCD',
                'tanggal' => Carbon::now()->addDays(20)->format('Y-m-d'), // 20 hari ke depan
                'status' => 'Akan Datang',
            ],
            [
                'nama_event' => 'Pemeriksaan Guru se-Kecamatan',
                'deskripsi' => 'Skrining kesejahteraan psikologis tenaga pendidik pasca ujian nasional.',
                'lokasi' => 'Gedung Serbaguna',
                'tanggal' => Carbon::now()->subMonths(2)->format('Y-m-d'), // 2 bulan yang lalu
                'status' => 'Selesai',
            ],
            [
                'nama_event' => 'Mental Health Awareness Week',
                'deskripsi' => 'Rangkaian tes kesehatan mental terbuka untuk publik di area *car free day*.',
                'lokasi' => 'Bundaran HI',
                'tanggal' => Carbon::now()->addDays(5)->format('Y-m-d'), // 5 hari ke depan
                'status' => 'Akan Datang',
            ],
            [
                'nama_event' => 'Evaluasi Psikologis Atlet Daerah',
                'deskripsi' => 'Skrining tingkat kecemasan (GAD-7) para atlet sebelum menghadapi pekan olahraga nasional.',
                'lokasi' => 'Gelanggang Olahraga Provinsi',
                'tanggal' => Carbon::now()->subDays(45)->format('Y-m-d'), 
                'status' => 'Selesai',
            ],
            [
                'nama_event' => 'Bootcamp Developer Mental Health',
                'deskripsi' => 'Pemeriksaan tingkat stres pada peserta bootcamp programming intensif di minggu ke-4.',
                'lokasi' => 'Tech Hub Jakarta (Virtual)',
                'tanggal' => Carbon::now()->format('Y-m-d'), 
                'status' => 'Berjalan',
            ],
            [
                'nama_event' => 'Skrining Rutin PNS Pemkot',
                'deskripsi' => 'Pemeriksaan kesehatan mental tahunan bagi seluruh Aparatur Sipil Negara di lingkungan pemerintah kota.',
                'lokasi' => 'Balai Kota',
                'tanggal' => Carbon::now()->subMonths(1)->format('Y-m-d'), 
                'status' => 'Selesai',
            ],
            [
                'nama_event' => 'Workshop Self-Care Ibu Hamil',
                'deskripsi' => 'Deteksi dini *postpartum depression* dan kecemasan pada kelompok ibu hamil trimester ketiga.',
                'lokasi' => 'Klinik Bersalin Kasih Ibu',
                'tanggal' => Carbon::now()->addDays(12)->format('Y-m-d'), 
                'status' => 'Akan Datang',
            ],
            [
                'nama_event' => 'Skrining Relawan Bencana Alam',
                'deskripsi' => 'Evaluasi psikologis (PTSD dan Depresi) bagi para relawan yang baru pulang dari lokasi bencana.',
                'lokasi' => 'Kantor Pusat PMI',
                'tanggal' => Carbon::now()->subDays(2)->format('Y-m-d'), 
                'status' => 'Berjalan',
            ],
            [
                'nama_event' => 'Program Kesejahteraan Lansia',
                'deskripsi' => 'Pemeriksaan rasa kesepian dan depresi ringan pada warga lanjut usia di panti jompo.',
                'lokasi' => 'Panti Wreda Harapan',
                'tanggal' => Carbon::now()->subMonths(3)->format('Y-m-d'), 
                'status' => 'Selesai',
            ],
            [
                'nama_event' => 'Klinik Kesehatan Mental Desa',
                'deskripsi' => 'Program jemput bola skrining kesehatan mental gratis untuk masyarakat pedesaan.',
                'lokasi' => 'Balai Desa Suka Maju',
                'tanggal' => Carbon::now()->addMonths(1)->format('Y-m-d'), 
                'status' => 'Akan Datang',
            ],
            [
                'nama_event' => 'Tes Psikologi Calon Karyawan',
                'deskripsi' => 'Bagian dari proses rekrutmen tahap akhir untuk posisi manajerial di perusahaan retail.',
                'lokasi' => 'Kantor Pusat Retailindo',
                'tanggal' => Carbon::now()->addDays(1)->format('Y-m-d'), 
                'status' => 'Akan Datang',
            ],
        ];

        foreach ($events as $event) {
            Event::create($event);
        }
    }
}