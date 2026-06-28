<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    // Tambahkan baris ini biar Laravel membaca tabel 'siswa' bukan 'siswas'
    protected $table = 'siswa'; 

    // Jika lu mengatur mass assignment, pastikan fillable-nya diisi juga ya
    protected $fillable = ['nisn', 'nama', 'kelas', 'jurusan'];

    public function keterlambatans()
    {
        return $this->hasMany(Keterlambatan::class, 'siswa_id');
    }
}