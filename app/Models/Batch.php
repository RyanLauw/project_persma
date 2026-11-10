<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    protected $fillable = ['nama_batch', 'tipe_batch', 'tanggal_mulai', 'tanggal_selesai', 'is_active', 'requires_cv'];

    protected $casts = [
        'is_active' => 'boolean',
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'tipe_batch' => 'string',
    ];
}