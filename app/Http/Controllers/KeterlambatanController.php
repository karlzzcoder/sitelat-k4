<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Keterlambatan;
use Illuminate\Support\Facades\DB;

class KeterlambatanController extends Controller
{
    /**
     * Menampilkan Halaman Dashboard OSIS (Input Cepat)
     * URL: /admin-osis
     */
    public function dashboard()
    {
        // Ambil semua data siswa langsung dari tabel 'siswa'
        $dataSiswa = Siswa::all();

        // Kita mapping murni tanpa menggabungkan string biar gak dobel
        $siswas = $dataSiswa->map(function ($siswa) {
            return [
                'id'    => $siswa->id,
                'name'  => $siswa->nama, // kolom nama siswa
                'nisn'  => $siswa->nisn ?? '-',
                // Murni ambil kolom kelas saja (misal isinya: "XI PPLG 1")
                'kelas' => trim($siswa->kelas ?? '-') 
            ];
        });

        return view('admin.dashboard', compact('siswas'));
    }

    /**
     * Menampilkan Halaman Tabel Riwayat Keterlambatan
     * URL: /admin-osis/history
     */
    public function history()
    {
        // Ambil data keterlambatan, kelompokkan berdasarkan tanggal, dan hitung jumlahnya
        // Kita juga panggil relasi 'siswa' untuk menampilkan daftar nama nantinya
        $historyData = Keterlambatan::with('siswa')
            ->select('tanggal', DB::raw('count(*) as total_telat'))
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'desc')
            ->get();

        // Untuk setiap tanggal, kita ambil detail siswa siapa saja yang terlambat di hari itu
        $riwayatPerTanggal = $historyData->map(function ($item) {
            return [
                'tanggal'     => $item->tanggal,
                'total_telat' => $item->total_telat,
                'detail'      => Keterlambatan::with('siswa')
                                    ->where('tanggal', $item->tanggal)
                                    ->get()
            ];
        });

       return view('admin.history', compact('riwayatPerTanggal'));
    }

    /**
     * Memproses Penyimpanan Data Keterlambatan Baru dari Modal Form
     * URL: /admin-osis/store (POST)
     */
    /**
     * Memproses Penyimpanan Data Keterlambatan Baru dari Modal Form
     * URL: /admin-osis/store (POST)
     */
   public function store(Request $request)
{
    // 1. Validasi input form dari modal
    $request->validate([
        'siswa_id'   => 'required|exists:siswa,id',
        'tanggal'    => 'required|date',
        'alasan'     => 'required|string',
        'keterangan' => 'nullable|string', // Ini catatan lain dari OSIS
    ]);

    // 2. Menentukan semester secara otomatis
    $bulan = date('n', strtotime($request->tanggal));
    $semester = ($bulan >= 7 && $bulan <= 12) ? '1' : '2';

    // 3. Menentukan tahun ajaran otomatis
    $tahun = date('Y', strtotime($request->tanggal));
    if ($bulan >= 7) {
        $tahunAjaran = $tahun . '/' . ($tahun + 1);
    } else {
        $tahunAjaran = ($tahun - 1) . '/' . $tahun;
    }

    // 4. Simpan data ke database
    Keterlambatan::create([
        'siswa_id'     => $request->siswa_id,
        'user_id'      => auth()->id() ?? 1, 
        'tanggal'      => $request->tanggal,
        'alasan'       => $request->alasan,
        'tahun_ajaran' => $tahunAjaran,      
        'semester'     => $semester,
        
        // ⬇️ PERBAIKAN DI SINI ⬇️
        'keterangan'   => $request->keterangan, // Masuk ke kolom keterangan OSIS (Pastikan kolom ini ada di database)
        'penanganan'   => null,                 // Set NULL/kosong agar nanti tombol "Tindak" muncul di dashboard Walas
    ]);

    return redirect()->route('admin.dashboard')->with('success', 'Data keterlambatan siswa berhasil dicatat!');
}

    /**
     * Menampilkan Form Edit Data Keterlambatan
     * URL: /admin-osis/keterlambatan/{id}/edit
     */
    public function edit($id)
    {
        $keterlambatan = Keterlambatan::findOrFail($id);
        $siswas = Siswa::all();
        
        return view('admin.edit', compact('keterlambatan', 'siswas'));
    }

    /**
     * Memproses Update Data Keterlambatan
     * URL: /admin-osis/keterlambatan/{id} (PUT)
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal'    => 'required|date',
            'alasan'     => 'required|string',
            'keterangan' => 'nullable|string',
        ]);

        $keterlambatan = Keterlambatan::findOrFail($id);
        
        $keterlambatan->update([
            'tanggal'    => $request->tanggal,
            'alasan'     => $request->alasan,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('admin.history')->with('success', 'Data keterlambatan berhasil diperbarui!');
    }
}