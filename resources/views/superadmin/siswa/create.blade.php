<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Siswa - SiTelat K4</title>
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
            <a href="{{ route('superadmin.siswa.index') }}" class="w-8 h-8 rounded-lg border border-slate-200 flex items-center justify-center text-slate-400 hover:text-slate-600 transition-colors">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-lg font-bold tracking-tight">Tambah Siswa</h1>
                <p class="text-[11px] text-slate-400">Pendaftaran murid baru secara manual</p>
            </div>
        </div>
        <div>
            <span class="text-xs text-indigo-955 font-bold uppercase bg-indigo-50 px-3 py-1 rounded-full">Super Admin</span>
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
                <a href="{{ route('superadmin.users.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-500 hover:bg-slate-50 hover:text-slate-800 font-semibold text-sm transition-all">
                    <i class="fa-solid fa-users-gear text-lg"></i> Kelola Akun
                </a>
                <a href="{{ route('superadmin.siswa.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-indigo-50 text-indigo-600 font-bold text-sm transition-all">
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
                        <h2 class="text-xl font-bold text-slate-800">Form Siswa Baru</h2>
                        <p class="text-xs text-slate-400 mt-1">Harap isi biodata lengkap siswa di bawah ini.</p>
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

                    <form action="{{ route('superadmin.siswa.store') }}" method="POST" class="space-y-5">
                        @csrf
                        
                        <!-- NISN -->
                        <div>
                            <label for="nisn" class="block text-sm font-semibold text-slate-600 mb-1.5">NISN <span class="text-rose-500">*</span></label>
                            <input type="text" id="nisn" name="nisn" required value="{{ old('nisn') }}" placeholder="Contoh: 0098231221"
                                class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-sm text-slate-800 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 focus:outline-none transition-all duration-200">
                        </div>

                        <!-- Nama -->
                        <div>
                            <label for="nama" class="block text-sm font-semibold text-slate-600 mb-1.5">Nama Lengkap <span class="text-rose-500">*</span></label>
                            <input type="text" id="nama" name="nama" required value="{{ old('nama') }}" placeholder="Contoh: Ahmad Fauzi"
                                class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-sm text-slate-800 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 focus:outline-none transition-all duration-200">
                        </div>

                        <!-- Kelas -->
                        <div>
                            <label for="kelas" class="block text-sm font-semibold text-slate-600 mb-1.5">Kelas <span class="text-rose-500">*</span></label>
                            <input type="text" id="kelas" name="kelas" required value="{{ old('kelas') }}" placeholder="Contoh: XI RPL 1"
                                class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-sm text-slate-800 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 focus:outline-none transition-all duration-200">
                        </div>

                        <!-- Jurusan -->
                        <div>
                            <label for="jurusan" class="block text-sm font-semibold text-slate-600 mb-1.5">Jurusan <span class="text-rose-500">*</span></label>
                            <select id="jurusan" name="jurusan" required
                                class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-sm text-slate-800 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 focus:outline-none transition-all duration-200">
                                <option value="" disabled selected>Pilih jurusan...</option>
                                <option value="PPLG" {{ old('jurusan') === 'PPLG' ? 'selected' : '' }}>PPLG (RPL)</option>
                                <option value="TJKT" {{ old('jurusan') === 'TJKT' ? 'selected' : '' }}>TJKT</option>
                                <option value="TO" {{ old('jurusan') === 'TO' ? 'selected' : '' }}>TO</option>
                                <option value="TPFL" {{ old('jurusan') === 'TPFL' ? 'selected' : '' }}>TPFL</option>
                            </select>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex gap-4 pt-2">
                            <a href="{{ route('superadmin.siswa.index') }}" 
                               class="flex-1 bg-slate-100 hover:bg-slate-200 active:scale-[0.99] text-slate-600 text-center font-semibold text-sm rounded-xl py-3 px-4 transition-all duration-200">
                                Batal
                            </a>
                            <button type="submit" 
                                class="flex-1 bg-indigo-600 hover:bg-indigo-700 active:scale-[0.99] text-white font-semibold text-sm rounded-xl py-3 px-4 shadow-lg shadow-indigo-600/10 hover:shadow-indigo-600/20 transition-all duration-200">
                                Simpan Siswa
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

</body>
</html>
