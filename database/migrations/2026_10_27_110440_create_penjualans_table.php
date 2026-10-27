
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penjualan', function (Blueprint $table) {
            $table->id();

            $table->string('nomor_transaksi', 50)->unique();
            $table->date('tanggal');
            $table->string('nama_pembeli', 100)->nullable();
            $table->decimal('total', 12, 2)->default(0);
            $table->string('status', 20)->default('selesai');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penjualan');
    }
};
