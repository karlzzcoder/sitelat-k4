<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Keterlambatan;
use App\Models\Siswa;

class SiswaController extends Controller
{
    // Halaman list keterlambatan versi Walas / Superadmin (Bisa Edit)
    public function listKeterlambatan()
    {
        $riwayats = Keterlambatan::with('siswa')->latest()->get();
        return view('superadmin.keterlambatan.index', compact('riwayats')); 
    }

    // Menampilkan halaman edit keterlambatan (Hanya Walas/Superadmin)
    public function editKeterlambatan($id)
    {
        $riwayat = Keterlambatan::findOrFail($id);
        return view('superadmin.keterlambatan.edit', compact('riwayat'));
    }

    // Memproses update data keterlambatan
    public function updateKeterlambatan(Request $request, $id)
    {
        $riwayat = Keterlambatan::findOrFail($id);
        
        $request->validate([
            'alasan' => 'required|string',
            'keterangan' => 'nullable|string',
        ]);

        $riwayat->update([
            'alasan' => $request->alasan,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('superadmin.history')->with('success', 'Data berhasil diperbarui oleh Admin!');
    }
}