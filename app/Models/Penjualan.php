
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Penjualan extends Model
{
    protected $table = 'penjualan';

    protected $fillable = [
        'nomor_transaksi',
        'tanggal',
        'nama_pembeli',
        'total',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'total' => 'decimal:2',
        ];
    }

    public function detail(): HasMany
    {
        return $this->hasMany(
            DetailPenjualan::class,
            'penjualan_id'
        );
    }
}
