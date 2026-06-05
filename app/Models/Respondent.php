<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Respondent extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id', 'nama', 'usia', 'jenis_kelamin', 'email', 
        'no_hp', 'ig', 'sudah_follow_ig', 'skor', 'kategori', 'jawaban'
    ];

    // Ini kunci agar JSON otomatis jadi Array
    protected $casts = [
        'jawaban' => 'array',
        'sudah_follow_ig' => 'boolean',
    ];

    // Relasi balik ke Event
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
