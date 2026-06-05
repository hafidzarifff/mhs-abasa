<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'kode_pertanyaan',
        'pertanyaan',
        'template_pertanyaan',
    ];
}
