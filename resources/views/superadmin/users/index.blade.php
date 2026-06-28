<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Akun - SiTelat K4</title>
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
            <a href="{{ route('superadmin.dashboard') }}" class="w-8 h-8 rounded-lg border border-slate-200 flex items-center justify-center text-slate-400 hover:text-slate-600 transition-colors">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-lg font-bold tracking-tight">Kelola Akun</h1>
                <p class="text-[11px] text-slate-400">Pengaturan akses sistem OSIS & Wali Kelas</p>
            </div>
        </div>
        <div>
            <a href="{{ route('superadmin.users.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs rounded-xl px-4 py-2.5 shadow-md shadow-indigo-600/10 transition-all">
                ＋ Buat Akun Baru
            </a>
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
            <div class="max-w-5xl mx-auto space-y-6">

                @if(session('success'))
                    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-600 rounded-xl text-sm font-semibold flex items-center gap-2">
                        <i class="fa-solid fa-circle-check text-emerald-500"></i>
                        {{ session('success') }}
                    </div>
                @endif

                <div class="bg-white border border-slate-200 rounded-3xl shadow-sm overflow-hidden">
                    <div class="px-8 py-6 border-b border-slate-100">
                        <h3 class="text-base font-bold text-slate-800">Daftar Akun Pengguna</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Daftar lengkap pengguna terdaftar di dalam sistem.</p>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 text-slate-400 text-[10px] font-bold uppercase tracking-wider border-b border-slate-100">
                                    <th class="py-4 px-8">Nama Lengkap</th>
                                    <th class="py-4 px-6">Email / Username</th>
                                    <th class="py-4 px-6">Hak Akses / Role</th>
                                    <th class="py-4 px-6">Wali Kelas Dari</th>
                                    <th class="py-4 px-8 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-sm">
                                @foreach($users as $u)
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="py-4 px-8 font-bold text-slate-800">{{ $u->name }}</td>
                                        <td class="py-4 px-6 text-slate-500 font-medium">{{ $u->email }}</td>
                                        <td class="py-4 px-6">
                                            @if($u->role === 'super_admin')
                                                <span class="bg-slate-900 text-white text-[9px] px-2.5 py-0.5 rounded-full font-bold uppercase">Super Admin</span>
                                            @elseif($u->role === 'admin')
                                                <span class="bg-indigo-50 text-indigo-600 text-[9px] px-2.5 py-0.5 rounded-full font-bold uppercase">OSIS Admin</span>
                                            @elseif($u->role === 'user')
                                                <span class="bg-emerald-50 text-emerald-600 text-[9px] px-2.5 py-0.5 rounded-full font-bold uppercase">Wali Kelas</span>
                                            @endif
                                        </td>
                                        <td class="py-4 px-6 text-slate-500 font-semibold">
                                            {{ $u->kelas ?? '-' }}
                                        </td>
                                        <td class="py-4 px-8 text-right">
                                            <div class="inline-flex gap-2">
                                                <a href="{{ route('superadmin.users.edit', $u->id) }}" class="bg-slate-100 hover:bg-indigo-600 text-slate-600 hover:text-white px-2.5 py-1.5 rounded-lg text-xs font-bold transition-all shadow-sm">
                                                    <i class="fa-regular fa-pen-to-square"></i> Edit
                                                </a>
                                                
                                                <!-- Delete button -->
                                                @if(Auth::id() !== $u->id)
                                                    <form action="{{ route('superadmin.users.destroy', $u->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="bg-rose-50 hover:bg-rose-600 text-rose-600 hover:text-white px-2.5 py-1.5 rounded-lg text-xs font-bold transition-all shadow-sm">
                                                            <i class="fa-regular fa-trash-can"></i> Hapus
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="text-slate-400 text-xs px-2.5 py-1.5">Aktif</span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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
