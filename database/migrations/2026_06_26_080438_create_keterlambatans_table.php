<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
{
    Schema::create('keterlambatan', function (Blueprint $table) {
        $table->id();
        
        // Relasi ke tabel siswa dan users
        $table->foreignId('siswa_id')->constrained('siswa')->onDelete('cascade');
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        
        $table->date('tanggal');
        $table->text('alasan');
        $table->string('tahun_ajaran');
        $table->string('semester');
        $table->text('penanganan')->nullable(); // Nullable karena awal input pasti kosong
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keterlambatan');
    }
};
