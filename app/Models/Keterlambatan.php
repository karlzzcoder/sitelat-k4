<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Keterlambatan extends Model
{
    use HasFactory;

    // KUNCI UTAMA: Ubah menjadi 'keterlambatan' sesuai nama tabel asli di database kamu!
    protected $table = 'keterlambatan';

    protected $fillable = [
        'siswa_id',
        'user_id',
        'tanggal',
        'alasan',
        'tahun_ajaran',
        'semester',
        'keterangan', // Tambahkan ini jika belum ada
        'penanganan',
    ];

    /**
     * Boot fungsi bawaan model untuk otomatisasi semester & tahun ajaran.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Ambil tanggal input, jika kosong gunakan tanggal hari ini
            $tanggal = $model->tanggal ? Carbon::parse($model->tanggal) : Carbon::now();
            
            $bulan = $tanggal->month; 
            $tahun = $tanggal->year;  

            // Menentukan Semester Sekolah di Indonesia secara otomatis
            if ($bulan >= 7 && $bulan <= 12) {
                $model->semester = 1; // Semester Ganjil
                $model->tahun_ajaran = $tahun . '/' . ($tahun + 1); 
            } else {
                $model->semester = 2; // Semester Genap
                $model->tahun_ajaran = ($tahun - 1) . '/' . $tahun; 
            }
        });
    }

    /**
     * Relasi balik ke Siswa
     */
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    // ⬇️ SEKARANG SUDAH DITAMBAHKAN ⬇️
    /**
     * Relasi balik ke User (Admin/OSIS yang menginput data)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}