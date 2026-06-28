<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\User;
use App\Models\Keterlambatan;
use Illuminate\Support\Facades\Hash;

class SuperAdminController extends Controller
{
    /**
     * Dashboard Utama Super Admin
     */
    public function dashboard()
    {
        $totalSiswa = Siswa::count();
        $totalOsis = User::where('role', 'admin')->count();
        $totalWalas = User::where('role', 'user')->count();
        $totalKeterlambatan = Keterlambatan::count();

        // 1. Statistik grafik bulanan (MySQL Version)
        $chartData = Keterlambatan::selectRaw('MONTH(tanggal) as bulan, count(*) as total')
            ->groupBy('bulan')
            ->orderBy('bulan', 'asc')
            ->get();

        // 2. Monitoring Kelas yang memiliki kasus "Belum Ditindak" oleh Walas
        // Query ini menghitung jumlah data keterlambatan yang penangannya NULL, dikelompokkan per kelas siswa
        $monitoringBelumDitindak = Keterlambatan::with('siswa')
            ->whereNull('penanganan')
            ->whereHas('siswa') // Memastikan relasi siswa ada
            ->get()
            ->groupBy(function($item) {
                return $item->siswa->kelas; // Kelompokkan berdasarkan string nama kelas
            })
            ->map(function($group) {
                return [
                    'nama_kelas' => $group->first()->siswa->kelas,
                    'jumlah_kasus' => $group->count()
                ];
            })
            ->sortByDesc('jumlah_kasus'); // Urutkan dari kelas yang paling banyak numpuk kasusnya

        return view('superadmin.dashboard', compact(
            'totalSiswa', 
            'totalOsis', 
            'totalWalas', 
            'totalKeterlambatan', 
            'chartData',
            'monitoringBelumDitindak'
        ));
    }

    // ==========================================
    // KELOLA AKUN / USERS CRUD
    // ==========================================
    public function usersIndex()
    {
        $users = User::all();
        return view('superadmin.users.index', compact('users'));
    }

    public function usersCreate()
    {
        $kelasOptions = Siswa::select('kelas')->distinct()->orderBy('kelas', 'asc')->pluck('kelas');
        return view('superadmin.users.create', compact('kelasOptions'));
    }

    public function usersStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|in:super_admin,admin,user',
            'kelas' => 'nullable|string',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'kelas' => $request->role === 'user' ? $request->kelas : null,
        ]);

        return redirect('/superadmin/users')->with('success', 'Akun berhasil dibuat!');
    }

    public function usersEdit($id)
    {
        $user = User::findOrFail($id);
        $kelasOptions = Siswa::select('kelas')->distinct()->orderBy('kelas', 'asc')->pluck('kelas');
        return view('superadmin.users.edit', compact('user', 'kelasOptions'));
    }

    public function usersUpdate(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6',
            'role' => 'required|in:super_admin,admin,user',
            'kelas' => 'nullable|string',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'kelas' => $request->role === 'user' ? $request->kelas : null,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect('/superadmin/users')->with('success', 'Akun berhasil diperbarui!');
    }

    public function usersDestroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect('/superadmin/users')->with('success', 'Akun berhasil dihapus!');
    }

    // ==========================================
    // KELOLA SISWA CRUD
    // ==========================================
    public function siswaIndex(Request $request)
    {
        $query = Siswa::query();

        if ($request->filled('q')) {
            $query->where('nama', 'LIKE', '%' . $request->q . '%')
                  ->orWhere('nisn', 'LIKE', '%' . $request->q . '%');
        }

        $siswas = $query->paginate(25);
        return view('superadmin.siswa.index', compact('siswas'));
    }

    public function siswaCreate()
    {
        return view('superadmin.siswa.create');
    }

    public function siswaStore(Request $request)
    {
        $request->validate([
            'nisn' => 'required|string|unique:siswa,nisn',
            'nama' => 'required|string|max:255',
            'kelas' => 'required|string',
            'jurusan' => 'required|string',
        ]);

        Siswa::create($request->all());

        return redirect('/superadmin/siswa')->with('success', 'Data siswa berhasil ditambahkan!');
    }

    public function siswaEdit($id)
    {
        $siswa = Siswa::findOrFail($id);
        return view('superadmin.siswa.edit', compact('siswa'));
    }

    public function siswaUpdate(Request $request, $id)
    {
        $siswa = Siswa::findOrFail($id);

        $request->validate([
            'nisn' => 'required|string|unique:siswa,nisn,' . $id,
            'nama' => 'required|string|max:255',
            'kelas' => 'required|string',
            'jurusan' => 'required|string',
        ]);

        $siswa->update($request->all());

        return redirect('/superadmin/siswa')->with('success', 'Data siswa berhasil diperbarui!');
    }

    public function siswaDestroy($id)
    {
        $siswa = Siswa::findOrFail($id);
        $siswa->delete();
        return redirect('/superadmin/siswa')->with('success', 'Siswa berhasil dihapus!');
    }

    // ==========================================
    // SEMUA RIWAYAT KETERLAMBATAN
    // ==========================================
    public function keterlambatanIndex()
    {
        $riwayats = Keterlambatan::with(['siswa', 'user'])->latest()->get();
        return view('superadmin.keterlambatan', compact('riwayats'));
    }

    public function keterlambatanEdit($id)
    {
        $riwayat = Keterlambatan::with('siswa')->findOrFail($id);
        return view('superadmin.keterlambatan_edit', compact('riwayat'));
    }

    public function keterlambatanUpdate(Request $request, $id)
    {
        $riwayat = Keterlambatan::findOrFail($id);

        $request->validate([
            'tanggal' => 'required|date',
            'alasan' => 'required|string',
            'penanganan' => 'nullable|string',
        ]);

        $riwayat->update($request->all());

        return redirect('/superadmin/keterlambatan')->with('success', 'Catatan keterlambatan berhasil diperbarui!');
    }

    public function keterlambatanDestroy($id)
    {
        $riwayat = Keterlambatan::findOrFail($id);
        $riwayat->delete();
        return redirect('/superadmin/keterlambatan')->with('success', 'Catatan keterlambatan berhasil dihapus!');
    }
}