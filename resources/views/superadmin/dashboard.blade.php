<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Super Admin - SiTelat K4</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col">

    <header class="bg-white border-b border-slate-200 sticky top-0 z-30 px-6 py-4 flex justify-between items-center shadow-sm">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-indigo-900 rounded-xl flex items-center justify-center text-white shadow-md">
                <i class="fa-solid fa-user-shield text-lg"></i>
            </div>
            <div>
                <h1 class="text-xl font-bold tracking-tight">SiTelat K4</h1>
                <p class="text-[11px] text-slate-400">Super Admin Console</p>
            </div>
        </div>
        <div class="flex items-center gap-4">
            <div class="text-right hidden sm:block">
                <p class="text-sm font-semibold text-slate-800">{{ Auth::user()->name }}</p>
                <p class="text-xs text-indigo-950 font-bold uppercase tracking-wider">Super Admin</p>
            </div>
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="w-10 h-10 rounded-xl border border-slate-200 flex items-center justify-center text-slate-400 hover:text-rose-600 hover:border-rose-100 hover:bg-rose-50 transition-all duration-200" title="Keluar">
                    <i class="fa-solid fa-right-from-bracket"></i>
                </button>
            </form>
        </div>
    </header>

    <div class="flex-grow flex">
        
        <aside class="w-64 bg-white border-r border-slate-200 hidden md:block p-6 space-y-6">
            <div class="space-y-1">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-2">Main Menu</span>
                <a href="{{ route('superadmin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-indigo-50 text-indigo-600 font-bold text-sm transition-all">
                    <i class="fa-solid fa-chart-pie text-lg"></i> Dashboard
                </a>
                <a href="{{ route('superadmin.users.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-500 hover:bg-slate-50 hover:text-slate-800 font-semibold text-sm transition-all">
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

        <main class="flex-grow p-8">
            <div class="max-w-5xl mx-auto space-y-8">
                
                <div class="bg-gradient-to-r from-slate-800 to-indigo-950 rounded-3xl p-8 text-white shadow-xl mb-8 relative overflow-hidden">
                    <div class="relative z-10 space-y-2 max-w-lg">
                        <span class="bg-indigo-500/20 text-indigo-300 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">Superadmin Console</span>
                        <h2 class="text-3xl font-extrabold tracking-tight">Selamat Datang, {{ Auth::user()->name }}!</h2>
                        <p class="text-slate-300 text-sm leading-relaxed">
                            Akses kendali penuh atas data master siswa SMKN 4 Bogor, registrasi/modifikasi akun OSIS dan Wali Kelas, serta seluruh riwayat pelanggaran keterlambatan.
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="bg-white border border-slate-200 p-6 rounded-2xl shadow-sm flex items-center gap-4">
                        <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center text-xl shadow-sm">
                            <i class="fa-solid fa-user-graduate"></i>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Total Seluruh Siswa</span>
                            <h3 class="text-2xl font-extrabold text-slate-800 mt-0.5">{{ $totalSiswa }}</h3>
                        </div>
                    </div>
                    <div class="bg-white border border-slate-200 p-6 rounded-2xl shadow-sm flex items-center gap-4">
                        <div class="w-12 h-12 bg-violet-50 text-violet-600 rounded-xl flex items-center justify-center text-xl shadow-sm">
                            <i class="fa-solid fa-user-group"></i>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Akun OSIS (Admin)</span>
                            <h3 class="text-2xl font-extrabold text-slate-800 mt-0.5">{{ $totalOsis }}</h3>
                        </div>
                    </div>
                    <div class="bg-white border border-slate-200 p-6 rounded-2xl shadow-sm flex items-center gap-4">
                        <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center text-xl shadow-sm">
                            <i class="fa-solid fa-chalkboard-user"></i>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Akun Walas (User)</span>
                            <h3 class="text-2xl font-extrabold text-slate-800 mt-0.5">{{ $totalWalas }}</h3>
                        </div>
                    </div>
                    <div class="bg-white border border-slate-200 p-6 rounded-2xl shadow-sm flex items-center gap-4">
                        <div class="w-12 h-12 bg-rose-50 text-rose-600 rounded-xl flex items-center justify-center text-xl shadow-sm">
                            <i class="fa-solid fa-calendar-xmark"></i>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Kasus Keterlambatan</span>
                            <h3 class="text-2xl font-extrabold text-slate-800 mt-0.5">{{ $totalKeterlambatan }}</h3>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-slate-200 rounded-3xl shadow-sm overflow-hidden">
                    <div class="px-8 py-5 border-b border-slate-100 bg-amber-50/30 flex items-center gap-3">
                        <div class="w-8 h-8 bg-amber-50 text-amber-600 rounded-lg flex items-center justify-center text-sm border border-amber-200">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </div>
                        <div>
                            <h3 class="text-md font-bold text-slate-800">Monitoring Kasus Belum Ditindak</h3>
                            <p class="text-[11px] text-slate-400">Daftar kelas dengan keterlambatan siswa yang belum diproses oleh Wali Kelas</p>
                        </div>
                    </div>
                    <div class="p-8">
                        @if(empty($monitoringBelumDitindak) || count($monitoringBelumDitindak) == 0)
                            <div class="text-center py-8 text-slate-400 text-sm">
                                <div class="w-12 h-12 bg-emerald-50 text-emerald-500 rounded-full flex items-center justify-center text-xl mx-auto mb-3 border border-emerald-100">
                                    <i class="fa-solid fa-circle-check"></i>
                                </div>
                                <p class="font-semibold text-slate-700 text-sm">Semua Aman!</p>
                                <p class="text-xs text-slate-400 mt-0.5">Seluruh Wali Kelas telah menindaklanjuti kasus keterlambatan murid mereka.</p>
                            </div>
                        @else
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($monitoringBelumDitindak as $item)
                                    <div class="flex justify-between items-center bg-slate-50/50 border border-slate-200/80 p-4 rounded-2xl hover:border-slate-300 transition-all shadow-xs">
                                        <div class="space-y-0.5">
                                            <span class="text-sm font-bold text-slate-800 block tracking-tight">{{ $item['nama_kelas'] }}</span>
                                            <span class="text-[11px] text-slate-400 font-medium flex items-center gap-1">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-400 block"></span> Perlu Tindakan Walas
                                            </span>
                                        </div>
                                        <span class="bg-amber-100/80 text-amber-900 border border-amber-200 text-xs font-extrabold px-3 py-1.5 rounded-xl flex items-center gap-1.5">
                                            <i class="fa-solid fa-circle-exclamation text-[10px]"></i> {{ $item['jumlah_cases'] ?? $item['jumlah_kasus'] }} Kasus
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <div class="bg-white border border-slate-200 rounded-3xl p-8 shadow-sm">
                    <h3 class="text-lg font-bold text-slate-800 mb-6">Menu Kontrol Cepat</h3>
                    
                    <div class="grid sm:grid-cols-3 gap-6">
                        <a href="{{ route('superadmin.users.index') }}" class="p-5 border border-slate-100 hover:border-indigo-500 rounded-2xl transition-all hover:shadow-md block">
                            <i class="fa-solid fa-users-gear text-indigo-600 text-2xl mb-3"></i>
                            <h4 class="font-bold text-slate-800 text-sm">Kelola Akun / Users</h4>
                            <p class="text-slate-400 text-xs mt-1">Tambah, edit password, & hapus akun OSIS / Wali Kelas.</p>
                        </a>
                        <a href="{{ route('superadmin.siswa.index') }}" class="p-5 border border-slate-100 hover:border-indigo-500 rounded-2xl transition-all hover:shadow-md block">
                            <i class="fa-solid fa-user-graduate text-emerald-600 text-2xl mb-3"></i>
                            <h4 class="font-bold text-slate-800 text-sm">Kelola Master Siswa</h4>
                            <p class="text-slate-400 text-xs mt-1">Kelola data murid terdaftar (NISN, Nama, Kelas).</p>
                        </a>
                        <a href="{{ route('superadmin.keterlambatan.index') }}" class="p-5 border border-slate-100 hover:border-indigo-500 rounded-2xl transition-all hover:shadow-md block">
                            <i class="fa-solid fa-clock-rotate-left text-rose-600 text-2xl mb-3"></i>
                            <h4 class="font-bold text-slate-800 text-sm">Semua Riwayat</h4>
                            <p class="text-slate-400 text-xs mt-1">Periksa riwayat tanpa filter, edit, & hapus catatan.</p>
                        </a>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <footer class="text-center text-xs text-slate-400 py-6 border-t border-slate-100 bg-white">
        &copy; 2026 Tim Pengembang SiTelat K4
    </footer>

</body>
</html>