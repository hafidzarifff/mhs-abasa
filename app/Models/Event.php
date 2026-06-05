<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'nama_event',
        'deskripsi',
        'lokasi',
        'tanggal',
        'status',
    ];

    public function respondents()
    {
        return $this->hasMany(Respondent::class);
    }
}
