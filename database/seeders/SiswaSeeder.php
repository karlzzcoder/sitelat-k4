<?php

namespace Database\Seeders;

use App\Models\Siswa;
use Illuminate\Database\Seeder;

class SiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Buka file CSV nya
        $fileCsv = fopen(database_path('seeders/siswa.csv'), 'r');

        // 2. LOMPATIN BARIS 1 (Judul Besar: Data Siswa Aktif 2025-2026...)
        fgetcsv($fileCsv, 1000, ";");

        // 3. LOMPATIN BARIS 2 (Header Kolom: No; NISN; NIS; Nama; JK; Kelas)
        fgetcsv($fileCsv, 1000, ";");

        // 4. MULAI LOOPING DARI BARIS 3 (Data Murid)
        while (($data = fgetcsv($fileCsv, 1000, ";")) !== FALSE) {
            
            // Antisipasi kalau ada baris kosong di paling bawah Excel biar gak error
            if (!isset($data[1]) || !isset($data[3]) || empty($data[1]) || empty($data[3])) {
                continue;
            }

            // Trik ambil Jurusan dari kolom Kelas (Contoh: "XI RPL 1" dipisah spasi, diambil kata kedua yaitu "RPL")
            $stringKelas = $data[5]; // Mengambil teks di Kolom F (Kelas)
            $pecahKelas = explode(' ', $stringKelas);
            $jurusanAsli = $pecahKelas[1] ?? 'Umum'; // Kalau polanya beda, default-nya jadi 'Umum'

            // Masukin data secara rapi ke tabel siswa
            Siswa::create([
                'nisn'    => $data[1],     // Kolom B (NISN)
                'nama'    => $data[3],     // Kolom D (Nama)
                'kelas'   => $data[5],     // Kolom F (Kelas)
                'jurusan' => $jurusanAsli, // Hasil pecahan otomatis di atas
            ]);
        }

        // 5. Tutup koneksi file CSV-nya
        fclose($fileCsv);
    }
}