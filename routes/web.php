<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KeterlambatanController;
use App\Http\Controllers\WalasController;
use App\Http\Controllers\SuperAdminController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman utama: Mengalihkan user pintar berdasarkan status login & role
Route::get('/', function () {
    if (Auth::check()) {
        $role = Auth::user()->role;
        if ($role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($role === 'user') {
            return redirect()->route('walas.dashboard');
        } elseif ($role === 'super_admin') {
            return redirect()->route('superadmin.dashboard');
        }
    }
    return redirect()->route('login');
});

// --- RUTE GUEST (Belum Login) ---
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// --- RUTE PROTECTED (Wajib Login) ---
Route::middleware('auth')->group(function () {
    
    // Proses Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // ==========================================
    // ROLE: OSIS (kolom role di db: 'admin')
    // ==========================================
    Route::middleware('role:admin')->prefix('admin-osis')->group(function () {
        // Halaman utama Dashboard OSIS
        Route::get('/', [KeterlambatanController::class, 'dashboard'])->name('admin.dashboard');
        
        // Halaman Riwayat Keterlambatan versi OSIS
        Route::get('/history', [KeterlambatanController::class, 'history'])->name('admin.history');
        
        // Proses simpan data telat dari form modal dashboard
        Route::post('/store', [KeterlambatanController::class, 'store'])->name('keterlambatan.store');
        
        // Fitur edit & update data keterlambatan oleh OSIS
        Route::get('/keterlambatan/{id}/edit', [KeterlambatanController::class, 'edit'])->name('keterlambatan.edit');
        Route::put('/keterlambatan/{id}', [KeterlambatanController::class, 'update'])->name('keterlambatan.update');
    });

    // ==========================================
    // ROLE: Wali Kelas (kolom role di db: 'user')
    // ==========================================
    Route::middleware('role:user')->prefix('walas')->group(function () {
        // Halaman utama Dashboard Walas
        Route::get('/dashboard', [WalasController::class, 'dashboard'])->name('walas.dashboard');
        
        // Menampilkan form input penanganan walas per tanggal kejadian (Menggunakan ID Keterlambatan)
        Route::get('/penanganan/{id}', [WalasController::class, 'showPenanganan'])->name('walas.penanganan');
        
        // Memproses simpan penanganan data kolom 'penanganan' yang kosong
        Route::put('/penanganan/{id}', [WalasController::class, 'updatePenanganan'])->name('walas.penanganan.update');
        
        // Download Rekap Excel
        Route::get('/rekap/download', [WalasController::class, 'downloadRekap'])->name('walas.rekap.download');
    });

    // ==========================================
    // ROLE: Super Admin (kolom role di db: 'super_admin')
    // ==========================================
    Route::middleware('role:super_admin')->prefix('superadmin')->group(function () {
        // Dashboard utama Super Admin
        Route::get('/dashboard', [SuperAdminController::class, 'dashboard'])->name('superadmin.dashboard');

        // Kelola Akun Users (CRUD)
        Route::get('/users', [SuperAdminController::class, 'usersIndex'])->name('superadmin.users.index');
        Route::get('/users/create', [SuperAdminController::class, 'usersCreate'])->name('superadmin.users.create');
        Route::post('/users', [SuperAdminController::class, 'usersStore'])->name('superadmin.users.store');
        Route::get('/users/{id}/edit', [SuperAdminController::class, 'usersEdit'])->name('superadmin.users.edit');
        Route::put('/users/{id}', [SuperAdminController::class, 'usersUpdate'])->name('superadmin.users.update');
        Route::delete('/users/{id}', [SuperAdminController::class, 'usersDestroy'])->name('superadmin.users.destroy');

        // Kelola Data Master Siswa (CRUD)
        Route::get('/siswa', [SuperAdminController::class, 'siswaIndex'])->name('superadmin.siswa.index');
        Route::get('/siswa/create', [SuperAdminController::class, 'siswaCreate'])->name('superadmin.siswa.create');
        Route::post('/siswa', [SuperAdminController::class, 'siswaStore'])->name('superadmin.siswa.store');
        Route::get('/siswa/{id}/edit', [SuperAdminController::class, 'siswaEdit'])->name('superadmin.siswa.edit');
        Route::put('/siswa/{id}', [SuperAdminController::class, 'siswaUpdate'])->name('superadmin.siswa.update');
        Route::delete('/siswa/{id}', [SuperAdminController::class, 'siswaDestroy'])->name('superadmin.siswa.destroy');

        // Kelola Semua Riwayat Keterlambatan (CRUD Admin)
        Route::get('/keterlambatan', [SuperAdminController::class, 'keterlambatanIndex'])->name('superadmin.keterlambatan.index');
        Route::get('/keterlambatan/{id}/edit', [SuperAdminController::class, 'keterlambatanEdit'])->name('superadmin.keterlambatan.edit');
        Route::put('/keterlambatan/{id}', [SuperAdminController::class, 'keterlambatanUpdate'])->name('superadmin.keterlambatan.update');
        Route::delete('/keterlambatan/{id}', [SuperAdminController::class, 'keterlambatanDestroy'])->name('superadmin.keterlambatan.destroy');
    });
});