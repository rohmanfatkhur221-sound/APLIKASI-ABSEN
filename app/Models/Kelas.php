<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';

    protected $fillable = [
        'nama_kelas',
        'tingkat',
        'wali_guru_id',
    ];

    public function waliGuru(): BelongsTo
    {
        return $this->belongsTo(Guru::class, 'wali_guru_id');
    }

    public function murid(): HasMany
    {
        return $this->hasMany(Murid::class, 'kelas_id');
    }
}