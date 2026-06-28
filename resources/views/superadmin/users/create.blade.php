<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Akun - SiTelat K4</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col">

    <!-- Header Navigation -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-30 px-6 py-4 flex justify-between items-center shadow-sm">
        <div class="flex items-center gap-3">
            <a href="{{ route('superadmin.users.index') }}" class="w-8 h-8 rounded-lg border border-slate-200 flex items-center justify-center text-slate-400 hover:text-slate-600 transition-colors">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-lg font-bold tracking-tight">Buat Akun Baru</h1>
                <p class="text-[11px] text-slate-400">Registrasi akses OSIS atau Wali Kelas</p>
            </div>
        </div>
        <div>
            <span class="text-xs text-indigo-950 font-bold uppercase bg-indigo-50 px-3 py-1 rounded-full">Super Admin</span>
        </div>
    </header>

    <div class="flex-grow flex">
        
        <!-- Sidebar Navigation -->
        <aside class="w-64 bg-white border-r border-slate-200 hidden md:block p-6 space-y-6">
            <div class="space-y-1">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-2">Main Menu</span>
                <a href="{{ route('superadmin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-500 hover:bg-slate-50 hover:text-slate-800 font-semibold text-sm transition-all">
                    <i class="fa-solid fa-chart-pie text-lg"></i> Dashboard
                </a>
                <a href="{{ route('superadmin.users.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-indigo-50 text-indigo-600 font-bold text-sm transition-all">
                    <i class="fa-solid fa-users-gear text-lg"></i> Kelola Akun
                </a>
                <a href="{{ route('superadmin.siswa.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-500 hover:bg-slate-50 hover:text-slate-800 font-semibold text-sm transition-all">
                    <i class="fa-solid fa-user-graduate text-lg"></i> Kelola Siswa
                </a>
                <a href="{{ route('superadmin.keterlambatan.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-500 hover:bg-slate-50 hover:text-slate-800 font-semibold text-sm transition-all">
                    <i class="fa-solid fa-clock-rotate-left text-lg"></i> Riwayat Keterlambatan
                </a>
            </div>
        </aside>

        <!-- Content Area -->
        <main class="flex-grow p-8">
            <div class="max-w-2xl mx-auto">
                <div class="bg-white border border-slate-200 rounded-3xl p-8 shadow-xl shadow-slate-100/50">
                    
                    <div class="mb-6">
                        <h2 class="text-xl font-bold text-slate-800">Form Pembuatan Akun</h2>
                        <p class="text-xs text-slate-400 mt-1">Harap isi form di bawah ini dengan lengkap untuk mendaftarkan akun baru.</p>
                    </div>

                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-600 rounded-xl text-sm font-medium">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('superadmin.users.store') }}" method="POST" class="space-y-5">
                        @csrf
                        
                        <!-- Nama -->
                        <div>
                            <label for="name" class="block text-sm font-semibold text-slate-600 mb-1.5">Nama Lengkap <span class="text-rose-500">*</span></label>
                            <input type="text" id="name" name="name" required value="{{ old('name') }}" placeholder="Contoh: Budi Santoso"
                                class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-sm text-slate-800 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 focus:outline-none transition-all duration-200">
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-semibold text-slate-600 mb-1.5">Alamat Email / Username <span class="text-rose-500">*</span></label>
                            <input type="email" id="email" name="email" required value="{{ old('email') }}" placeholder="Contoh: budi@sitelat.com"
                                class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-sm text-slate-800 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 focus:outline-none transition-all duration-200">
                        </div>

                        <!-- Role -->
                        <div>
                            <label for="role" class="block text-sm font-semibold text-slate-600 mb-1.5">Hak Akses / Role <span class="text-rose-500">*</span></label>
                            <select id="role" name="role" required onchange="toggleKelasField()"
                                class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-sm text-slate-800 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 focus:outline-none transition-all duration-200">
                                <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>OSIS (Admin)</option>
                                <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>Wali Kelas (User)</option>
                                <option value="super_admin" {{ old('role') === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                            </select>
                        </div>

                        <!-- Kelas (Khusus Walas) -->
                        <div id="kelas_container" class="{{ old('role') === 'user' ? '' : 'hidden' }}">
                            <label for="kelas" class="block text-sm font-semibold text-slate-600 mb-1.5">Wali Kelas Dari Kelas <span class="text-rose-500">*</span></label>
                            <select id="kelas" name="kelas"
                                class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-sm text-slate-800 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 focus:outline-none transition-all duration-200">
                                <option value="" disabled selected>Pilih kelas...</option>
                                @foreach($kelasOptions as $kls)
                                    <option value="{{ $kls }}" {{ old('kelas') === $kls ? 'selected' : '' }}>{{ $kls }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-semibold text-slate-600 mb-1.5">Kata Sandi <span class="text-rose-500">*</span></label>
                            <input type="password" id="password" name="password" required placeholder="Minimal 6 karakter"
                                class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-sm text-slate-800 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 focus:outline-none transition-all duration-200">
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex gap-4 pt-2">
                            <a href="{{ route('superadmin.users.index') }}" 
                               class="flex-1 bg-slate-100 hover:bg-slate-200 active:scale-[0.99] text-slate-600 text-center font-semibold text-sm rounded-xl py-3 px-4 transition-all duration-200">
                                Batal
                            </a>
                            <button type="submit" 
                                class="flex-1 bg-indigo-600 hover:bg-indigo-700 active:scale-[0.99] text-white font-semibold text-sm rounded-xl py-3 px-4 shadow-lg shadow-indigo-600/10 hover:shadow-indigo-600/20 transition-all duration-200">
                                Simpan Akun
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <footer class="text-center text-xs text-slate-400 py-6 border-t border-slate-100 bg-white">
        &copy; 2026 Tim Pengembang SiTelat K4
    </footer>

    <script>
        function toggleKelasField() {
            const roleSelect = document.getElementById('role');
            const kelasContainer = document.getElementById('kelas_container');
            const kelasSelect = document.getElementById('kelas');

            if (roleSelect.value === 'user') {
                kelasContainer.classList.remove('hidden');
                kelasSelect.setAttribute('required', 'required');
            } else {
                kelasContainer.classList.add('hidden');
                kelasSelect.removeAttribute('required');
            }
        }
        
        // Panggil saat load pertama kali
        document.addEventListener('DOMContentLoaded', toggleKelasField);
    </script>

</body>
</html>
