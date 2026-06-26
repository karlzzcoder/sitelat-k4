<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    // Tambahkan baris ini di dalam class
    protected $table = 'siswa'; // Memastikan model ini mengarah ke tabel 'siswa'
    protected $fillable = ['nisn', 'nama', 'kelas', 'jurusan']; 
}