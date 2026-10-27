
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('absensi_murid', function (Blueprint $table) {
            $table->id();

            $table->foreignId('murid_id')
                ->constrained('murid')
                ->cascadeOnDelete();

            $table->date('tanggal');
            $table->string('status', 20);
            $table->text('keterangan')->nullable();

            $table->timestamps();

            $table->unique(['murid_id', 'tanggal']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('absensi_murid');
    }
};
