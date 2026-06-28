<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Menampilkan halaman login
    public function showLogin()
    {
        // Jika user sudah login, langsung lempar ke dashboard masing-masing
        if (Auth::check()) {
            return $this->redirectByUserRole(Auth::user());
        }
        
        return view('auth.login');
    }

    // Memproses data autentikasi login menggunakan Name
    public function login(Request $request)
    {
        // 1. Validasi Input (Menggunakan 'name' sebagai identitas login)
        $credentials = $request->validate([
            'name' => 'required|string',
            'password' => 'required|string',
        ]);

        // 2. Debugging Otomatis berdasarkan Nama Pengguna
        $userCheck = User::where('name', $request->name)->first();
        
        if (!$userCheck) {
            return back()->withErrors([
                'name' => 'Nama pengguna tersebut tidak terdaftar di sistem kami.',
            ])->onlyInput('name');
        }

        if (!Hash::check($request->password, $userCheck->password)) {
            return back()->withErrors([
                'name' => 'Kata sandi salah! Periksa kembali.',
            ])->onlyInput('name');
        }

        // 3. Proses Login Resmi Laravel jika password & nama sinkron
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return $this->redirectByUserRole(Auth::user());
        }

        return back()->withErrors([
            'name' => 'Gagal masuk ke sistem. Silakan coba beberapa saat lagi.',
        ])->onlyInput('name');
    }

    // Fungsi pembantu untuk mengarahkan rute sesuai hak akses (role)
    protected function redirectByUserRole($user)
    {
        if ($user->role === 'super_admin') {
            return redirect('/superadmin/dashboard');
        } elseif ($user->role === 'admin') {
            return redirect('/admin-osis');
        } elseif ($user->role === 'user') {
            return redirect('/walas/dashboard');
        }

        // Jalur alternatif jika role tidak dikenali
        return redirect('/login');
    }

    // Memproses fungsi keluar (Logout)
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}