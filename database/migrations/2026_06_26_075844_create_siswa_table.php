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
    Schema::create('siswa', function (Blueprint $table) {
        $table->id(); // Ini otomatis jadi Primary Key & bakal jadi siswa_id
        $table->string('nisn')->unique();
        $table->string('nama');
        $table->string('kelas');
        $table->string('jurusan');
        $table->timestamps(); // Ini otomatis bikin kolom created_at & updated_at
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswa');
    }
};
