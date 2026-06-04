<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Question;

class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        $questions = [
            ['kode_pertanyaan' => 'Q1', 'pertanyaan' => 'Sakit kepala'],
            ['kode_pertanyaan' => 'Q2', 'pertanyaan' => 'Kurang nafsu makan'],
            ['kode_pertanyaan' => 'Q3', 'pertanyaan' => 'Sulit tidur'],
            ['kode_pertanyaan' => 'Q4', 'pertanyaan' => 'Mudah takut'],
            ['kode_pertanyaan' => 'Q5', 'pertanyaan' => 'Tangan gemetar'],
            ['kode_pertanyaan' => 'Q6', 'pertanyaan' => 'Gugup/khawatir'],
            ['kode_pertanyaan' => 'Q7', 'pertanyaan' => 'Sering sedih'],
            ['kode_pertanyaan' => 'Q8', 'pertanyaan' => 'Sulit menikmati hari'],
            ['kode_pertanyaan' => 'Q9', 'pertanyaan' => 'Sulit ambil keputusan'],
            ['kode_pertanyaan' => 'Q10', 'pertanyaan' => 'Kegiatan terganggu'],
            ['kode_pertanyaan' => 'Q11', 'pertanyaan' => 'Merasa tidak mampu'],
            ['kode_pertanyaan' => 'Q12', 'pertanyaan' => 'Hilang minat'],
            ['kode_pertanyaan' => 'Q13', 'pertanyaan' => 'Merasa tidak berguna'],
            ['kode_pertanyaan' => 'Q14', 'pertanyaan' => 'Pikiran mengakhiri hidup'],
            ['kode_pertanyaan' => 'Q15', 'pertanyaan' => 'Lelah sepanjang waktu'],
            ['kode_pertanyaan' => 'Q16', 'pertanyaan' => 'Tidak enak di perut'],
            ['kode_pertanyaan' => 'Q17', 'pertanyaan' => 'Mudah lelah'],
            ['kode_pertanyaan' => 'Q18', 'pertanyaan' => 'Sulit berpikir jernih'],
            ['kode_pertanyaan' => 'Q19', 'pertanyaan' => 'Tidak bahagia'],
            ['kode_pertanyaan' => 'Q20', 'pertanyaan' => 'Sering menangis'],
        ];

        foreach ($questions as $q) {
            // Menggunakan updateOrCreate agar tidak terjadi error duplikat 
            // jika kode_pertanyaan sudah ada di database
            Question::updateOrCreate(
                ['kode_pertanyaan' => $q['kode_pertanyaan']], 
                ['pertanyaan' => $q['pertanyaan']]
            );
        }
    }
}