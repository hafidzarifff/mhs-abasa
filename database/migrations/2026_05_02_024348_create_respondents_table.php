<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('respondents', function (Blueprint $table) {
            $table->id();
            // Relasi ke tabel events (Jika event dihapus, respondennya ikut terhapus)
            $table->foreignId('event_id')->constrained('events')->cascadeOnDelete(); 
            
            // Data Diri
            $table->string('nama');
            $table->integer('usia');
            $table->string('jenis_kelamin'); // Misal: 'Laki-laki' / 'Perempuan'
            $table->string('email')->nullable();
            $table->string('no_hp')->nullable();
            $table->string('ig')->nullable();
            $table->boolean('sudah_follow_ig')->default(false);
            
            // Hasil Skrining
            $table->integer('skor')->default(0);
            $table->string('kategori'); // 'Kemungkinan Kecil', 'Perlu Perhatian', 'Kemungkinan Besar'
            
            // Menyimpan jawaban Q1-Q20 secara rapi dalam 1 kolom berformat JSON
            $table->json('jawaban'); 
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('respondents');
    }
};
