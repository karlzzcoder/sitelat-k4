<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Keterlambatan;
use Illuminate\Support\Facades\Auth;
// ⬇️ IMPORT DUA CLASS INI UNTUK EXCEL DOWNLOAD ⬇️
use App\Exports\KeterlambatanExport;
use Maatwebsite\Excel\Facades\Excel;

class WalasController extends Controller
{
    /**
     * Menampilkan Dashboard Utama Wali Kelas
     */
    public function dashboard()
    {
        $kelas = Auth::user()->kelas;

        if (!$kelas) {
            return redirect('/')->withErrors(['error' => 'Akun Anda tidak memiliki kelas wali kelas yang valid.']);
        }

        // Ambil semua siswa di kelas ini yang pernah telat
        $siswaPernahTelat = Siswa::where('kelas', $kelas)
            ->whereHas('keterlambatans')
            ->with(['keterlambatans' => function ($q) {
                $q->latest();
            }])
            ->withCount('keterlambatans')
            ->get();

        // Hitung total seluruh siswa di kelas
        $totalSiswa = Siswa::where('kelas', $kelas)->count();

        // Hitung total siswa yang pernah telat
        $totalPernahTelat = $siswaPernahTelat->count();

        // Hitung jumlah kasus keterlambatan yang belum ditindak (penanganan masih NULL)
        $belumDitindakCount = Keterlambatan::whereHas('siswa', function ($q) use ($kelas) {
                $q->where('kelas', $kelas);
            })
            ->whereNull('penanganan')
            ->count();

        // Masukkan 'belumDitindakCount' ke dalam compact
        return view('walas.dashboard', compact('kelas', 'siswaPernahTelat', 'totalSiswa', 'totalPernahTelat', 'belumDitindakCount'));
    }

    /**
     * Menampilkan Form Penanganan berdasarkan ID Keterlambatan spesifik
     * URL: /walas/penanganan/{id}
     */
    public function showPenanganan($id)
    {
        // Cari data keterlambatan spesifik beserta info siswanya
        $keterlambatan = Keterlambatan::with('siswa')->findOrFail($id);
        
        return view('walas.penanganan', compact('keterlambatan'));
    }

    /**
     * Menyimpan Tindakan Penanganan Wali Kelas (Update Data)
     * URL: /walas/penanganan/{id} (PUT)
     */
    public function updatePenanganan(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'penanganan' => 'required|string',
        ]);

        // Cari record keterlambatan berdasarkan ID-nya
        $keterlambatan = Keterlambatan::findOrFail($id);

        // Update kolom penanganan spesifik pada tanggal tersebut
        $keterlambatan->update([
            'penanganan' => $request->penanganan,
        ]);

        // Redirect kembali ke rute dashboard walas dengan pesan sukses
        return redirect()->route('walas.dashboard')->with('success', 'Tindakan penanganan berhasil disimpan!');
    }

    /**
     * Download Rekap Keterlambatan Kelas (Format Excel Rapi melalui Maatwebsite)
     */
public function downloadRekap()
{
    $kelas = Auth::user()->kelas;
    
    if (!$kelas) {
        return redirect()->back()->withErrors(['error' => 'Gagal mendownload, kelas tidak ditemukan.']);
    }

    // Ambil semua data riwayat keterlambatan siswa di kelas ini
    $riwayats = Keterlambatan::whereHas('siswa', function ($q) use ($kelas) {
            $q->where('kelas', $kelas);
        })
        ->with('siswa')
        ->orderBy('tanggal', 'desc')
        ->get();

    // Nama file otomatis rapi format .xls (Akan dibuka sebagai Excel asli oleh komputer)
    $filename = 'Rekap_Keterlambatan_' . str_replace(' ', '_', $kelas) . '.xls';

    // Set Header agar browser memaksa download sebagai file Microsoft Excel
    header("Content-Type: application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=\"$filename\"");
    header("Pragma: no-cache");
    header("Expires: 0");

    // Membuat struktur tabel Excel rapi menggunakan HTML + Styling CSS inline
    echo '
    <html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
    <head>
        <meta http-equiv="Content-type" content="text/html;charset=utf-8" />
        <style>
            .header {
                background-color: #1D7444; /* Hijau Excel Elegan */
                color: #FFFFFF;
                font-weight: bold;
                text-align: center;
                font-family: Arial, sans-serif;
                font-size: 11pt;
            }
            td {
                font-family: Arial, sans-serif;
                font-size: 10pt;
                vertical-align: middle;
            }
            .center {
                text-align: center;
            }
            .text {
                mso-number-format:"\@"; /* Memaksa NISN agar tetap teks dan angka 0 di depan tidak hilang */
            }
        </style>
    </head>
    <body>
        <table border="1" style="border-collapse:collapse;">
            <thead>
                <tr>
                    <th class="header" style="width:50px; height:30px;">No</th>
                    <th class="header" style="width:150px;">NISN</th>
                    <th class="header" style="width:250px;">Nama Siswa</th>
                    <th class="header" style="width:100px;">Kelas</th>
                    <th class="header" style="width:120px;">Tanggal Kejadian</th>
                    <th class="header" style="width:250px;">Alasan Utama (OSIS)</th>
                    <th class="header" style="width:350px;">Tindakan Wali Kelas (Pembinaan)</th>
                </tr>
            </thead>
            <tbody>';

            $no = 1;
            foreach ($riwayats as $r) {
                $tanggalFormat = date('d-m-Y', strtotime($r->tanggal));
                $penangananText = $r->penanganan ?? 'Belum ditindaklanjuti';

                echo '
                <tr style="height:25px;">
                    <td class="center">' . $no++ . '</td>
                    <td class="center text">' . ($r->siswa->nisn ?? '-') . '</td>
                    <td>' . ($r->siswa->nama ?? '-') . '</td>
                    <td class="center">' . ($r->siswa->kelas ?? '-') . '</td>
                    <td class="center">' . $tanggalFormat . '</td>
                    <td>' . ($r->alasan ?? 'Tidak ada alasan') . '</td>
                    <td>' . $penangananText . '</td>
                </tr>';
            }

    echo '
            </tbody>
        </table>
    </body>
    </html>';
    exit;
}
}