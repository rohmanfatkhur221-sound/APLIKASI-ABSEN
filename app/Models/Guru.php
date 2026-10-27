<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Guru extends Model
{
    use HasFactory;

    protected $table = 'gurus';

    protected $fillable = [
        'nip',
        'nama',
        'email',
        'no_telepon',
        'alamat',
    ];

    public function kelasWali(): HasMany
    {
        return $this->hasMany(Kelas::class, 'wali_guru_id');
    }
}