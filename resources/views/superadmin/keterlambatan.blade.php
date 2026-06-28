<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Master Keterlambatan - SiTelat K4</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col"
      x-data="{ 
        searchKelas: '', 
        globalFilterType: 'date',
        globalFilterValue: '{{ date('Y-m-d') }}', // Default memfilter kelas yang telat hari ini
        showModal: false,
        modalData: { nama: '', nisn: '', kelas: '', histori: [], filterType: 'all', filterValue: '' }
      }">

    <header class="bg-white border-b border-slate-200 sticky top-0 z-30 px-6 py-4 flex justify-between items-center shadow-sm">
        <div class="flex items-center gap-4">
            <a href="{{ route('superadmin.dashboard') }}" class="w-9 h-9 rounded-xl border border-slate-200 flex items-center justify-center text-slate-500 hover:text-slate-800 transition-colors bg-slate-50">
                <i class="fa-solid fa-arrow-left text-sm"></i>
            </a>
            <div>
                <h1 class="text-lg font-bold tracking-tight">Semua Riwayat Keterlambatan</h1>
                <p class="text-xs text-slate-400 mt-0.5">Master logs data pelanggaran dikelompokkan per kelas</p>
            </div>
        </div>
        <div>
            <span class="text-xs text-indigo-950 font-semibold uppercase bg-indigo-50 px-3 py-1 rounded-full border border-indigo-100">Super Admin</span>
        </div>
    </header>

    <div class="flex-grow flex">
        
        <aside class="w-64 bg-white border-r border-slate-200 hidden md:block p-6 space-y-6">
            <div class="space-y-1.5">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-widest block mb-2 pl-3">Main Menu</span>
                <a href="{{ route('superadmin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-600 hover:bg-slate-50 hover:text-slate-900 font-semibold text-sm transition-all">
                    <i class="fa-solid fa-chart-pie text-lg"></i> Dashboard
                </a>
                <a href="{{ route('superadmin.users.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-600 hover:bg-slate-50 hover:text-slate-900 font-semibold text-sm transition-all">
                    <i class="fa-solid fa-users-gear text-lg"></i> Kelola Akun
                </a>
                <a href="{{ route('superadmin.siswa.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-600 hover:bg-slate-50 hover:text-slate-900 font-semibold text-sm transition-all">
                    <i class="fa-solid fa-user-graduate text-lg"></i> Kelola Siswa
                </a>
                <a href="{{ route('superadmin.keterlambatan.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-indigo-50 text-indigo-600 font-bold text-sm transition-all border-l-4 border-indigo-600 pl-3">
                    <i class="fa-solid fa-clock-rotate-left text-lg"></i> Riwayat Keterlambatan
                </a>
            </div>
        </aside>

        <main class="flex-grow p-8">
            <div class="max-w-4xl mx-auto space-y-6">

                @if(session('success'))
                    <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-sm font-semibold flex items-center gap-2 shadow-sm">
                        <i class="fa-solid fa-circle-check text-emerald-500 text-sm"></i>
                        {{ session('success') }}
                    </div>
                @endif

                <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm space-y-3">
                    <div class="flex flex-col lg:flex-row gap-4 items-center justify-between">
                        <div class="relative w-full lg:w-4/12">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                                <i class="fa-solid fa-school text-xs"></i>
                            </span>
                            <input x-model="searchKelas" type="text" placeholder="Cari Nama Kelas (contoh: X PPLG 1)..." 
                                   class="w-full text-sm pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-indigo-500 transition-colors">
                        </div>

                        <div class="flex flex-wrap items-center gap-1 w-full lg:w-auto justify-start lg:justify-end text-[11px]">
                            <span class="text-slate-400 font-bold mr-1 uppercase">Filter Monitoring Kelas:</span>
                            <div class="flex flex-wrap gap-1">
                                <button @click="globalFilterType = 'all'; globalFilterValue = ''" :class="globalFilterType === 'all' ? 'bg-indigo-600 text-white font-bold' : 'bg-slate-100 text-slate-600'" class="px-2.5 py-1.5 rounded-md transition-all">Semua</button>
                                <button @click="globalFilterType = 'date'; globalFilterValue = '{{ date('Y-m-d') }}'" :class="globalFilterType === 'date' ? 'bg-indigo-600 text-white font-bold' : 'bg-slate-100 text-slate-600'" class="px-2.5 py-1.5 rounded-md transition-all">Hari Ini</button>
                                <button @click="globalFilterType = 'month'; globalFilterValue = '{{ date('Y-m') }}'" :class="globalFilterType === 'month' ? 'bg-indigo-600 text-white font-bold' : 'bg-slate-100 text-slate-600'" class="px-2.5 py-1.5 rounded-md transition-all">Bulan Ini</button>
                                <button @click="globalFilterType = 'semester'; globalFilterValue = '1'" :class="globalFilterType === 'semester' && globalFilterValue === '1' ? 'bg-indigo-600 text-white font-bold' : 'bg-slate-100 text-slate-600'" class="px-2.5 py-1.5 rounded-md transition-all">Smstr 1</button>
                                <button @click="globalFilterType = 'semester'; globalFilterValue = '2'" :class="globalFilterType === 'semester' && globalFilterValue === '2' ? 'bg-indigo-600 text-white font-bold' : 'bg-slate-100 text-slate-600'" class="px-2.5 py-1.5 rounded-md transition-all">Smstr 2</button>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 pt-2 border-t border-slate-100 text-[11px]" x-show="globalFilterType === 'date' || globalFilterType === 'month'">
                        <span class="text-slate-500 font-bold uppercase" x-text="globalFilterType === 'date' ? 'Pilih Tanggal Kejadian Kelas:' : 'Pilih Bulan Kejadian Kelas:'"></span>
                        <input x-model="globalFilterValue" :type="globalFilterType === 'date' ? 'date' : 'month'" 
                               class="bg-slate-50 border border-slate-300 rounded-md p-1 px-2 text-xs font-semibold focus:outline-none focus:border-indigo-500">
                    </div>
                </div>

                <div class="space-y-3">
                    @php
                        $groupedRiwayats = isset($riwayats) ? $riwayats->filter(function($r) { return isset($r->siswa); })->groupBy('siswa.kelas') : collect();
                    @endphp

                    @forelse($groupedRiwayats as $namaKelas => $listKeterlambatan)
                        @php
                            $allClassLogsJson = $listKeterlambatan->map(function($item) {
                                return [
                                    'siswa_id' => $item->siswa_id,
                                    'raw_date' => $item->tanggal,
                                    'semester' => $item->semester
                                ];
                            })->values()->toJson();
                        @endphp

                        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden" 
                             x-data="{ 
                                open: false, 
                                searchSiswa: '',
                                localFilterType: 'all',
                                localFilterValue: '',
                                
                                // Fungsi filter global (Luar) untuk mendeteksi visibilitas baris kartu kelas
                                checkGlobalFilter(dateStr, semesterStr) {
                                    if (globalFilterType === 'all') return true;
                                    if (globalFilterType === 'date') return dateStr === globalFilterValue;
                                    if (globalFilterType === 'month') return dateStr.substring(0, 7) === globalFilterValue;
                                    if (globalFilterType === 'semester') return semesterStr == globalFilterValue;
                                    return true;
                                },
                                hasGlobalViolation() {
                                    let logs = {{ $allClassLogsJson }};
                                    return logs.some(log => this.checkGlobalFilter(log.raw_date, log.semester));
                                },
                                countGlobalStudents() {
                                    let logs = {{ $allClassLogsJson }};
                                    let filtered = logs.filter(log => this.checkGlobalFilter(log.raw_date, log.semester));
                                    return [...new Set(filtered.map(l => l.siswa_id))].length;
                                },

                                // Fungsi filter lokal (Dalam Dropdown) khusus menyaring nama siswa langsung
                                checkLocalFilter(dateStr, semesterStr) {
                                    if (this.localFilterType === 'all') return true;
                                    if (this.localFilterType === 'date') return dateStr === this.localFilterValue;
                                    if (this.localFilterType === 'month') return dateStr.substring(0, 7) === this.localFilterValue;
                                    if (this.localFilterType === 'semester') return semesterStr == this.localFilterValue;
                                    return true;
                                },
                                countStudentsInLocalFilter() {
                                    let logs = {{ $allClassLogsJson }};
                                    let filteredLogs = logs.filter(log => this.checkLocalFilter(log.raw_date, log.semester));
                                    return [...new Set(filteredLogs.map(l => l.siswa_id))].length;
                                }
                             }"
                             x-show="(searchKelas === '' || '{{ strtolower($namaKelas) }}'.includes(searchKelas.toLowerCase())) && hasGlobalViolation()"
                             x-transition>
                            
                            <button @click="open = !open" class="w-full px-6 py-4 flex items-center justify-between hover:bg-slate-50/60 transition-colors text-left">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 bg-indigo-50 rounded-xl text-indigo-600 flex items-center justify-center text-base font-bold">
                                        <i class="fa-solid fa-folder"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-slate-800 tracking-tight">{{ $namaKelas }}</h4>
                                        <p class="text-xs text-slate-400 mt-0.5" x-text="'Total riwayat kelas: {{ $listKeterlambatan->groupBy('siswa_id')->count() }} siswa'"></p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3">
                                    <span class="bg-rose-500 text-white text-[11px] font-bold px-2.5 py-1 rounded-lg shadow-xs flex items-center gap-1.5 animate-pulse">
                                        <i class="fa-solid fa-triangle-exclamation text-[10px]"></i>
                                        <span x-text="countGlobalStudents() + ' Terdeteksi'"></span>
                                    </span>
                                    <i class="fa-solid text-xs text-slate-400 transition-transform duration-200 mr-1" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                                </div>
                            </button>

                            <div x-show="open" x-collapse class="border-t border-slate-100 bg-slate-50/30 space-y-3 p-4">
                                
                                <div class="bg-white border border-slate-200/80 rounded-xl p-3.5 shadow-2xs space-y-3">
                                    <div class="flex flex-col lg:flex-row gap-3 items-center justify-between">
                                        <div class="relative w-full lg:w-4/12">
                                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                                <i class="fa-solid fa-user text-xs"></i>
                                            </span>
                                            <input x-model="searchSiswa" type="text" placeholder="Cari nama atau NISN..." 
                                                   class="w-full text-xs pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-indigo-500 font-medium">
                                        </div>

                                        <div class="flex flex-wrap items-center gap-1 w-full lg:w-auto justify-start lg:justify-end text-[11px]">
                                            <span class="text-slate-400 font-bold mr-1 uppercase">Filter Monitor Siswa:</span>
                                            <div class="flex flex-wrap gap-1">
                                                <button @click="localFilterType = 'all'; localFilterValue = ''" :class="localFilterType === 'all' ? 'bg-indigo-600 text-white font-bold' : 'bg-slate-100 text-slate-600'" class="px-2.5 py-1.5 rounded-md transition-all">Semua</button>
                                                <button @click="localFilterType = 'date'; localFilterValue = '{{ date('Y-m-d') }}'" :class="localFilterType === 'date' ? 'bg-indigo-600 text-white font-bold' : 'bg-slate-100 text-slate-600'" class="px-2.5 py-1.5 rounded-md transition-all">Hari Ini</button>
                                                <button @click="localFilterType = 'month'; localFilterValue = '{{ date('Y-m') }}'" :class="localFilterType === 'month' ? 'bg-indigo-600 text-white font-bold' : 'bg-slate-100 text-slate-600'" class="px-2.5 py-1.5 rounded-md transition-all">Bulan Ini</button>
                                                <button @click="localFilterType = 'semester'; localFilterValue = '1'" :class="localFilterType === 'semester' && localFilterValue === '1' ? 'bg-indigo-600 text-white font-bold' : 'bg-slate-100 text-slate-600'" class="px-2.5 py-1.5 rounded-md transition-all">Smstr 1</button>
                                                <button @click="localFilterType = 'semester'; localFilterValue = '2'" :class="localFilterType === 'semester' && localFilterValue === '2' ? 'bg-indigo-600 text-white font-bold' : 'bg-slate-100 text-slate-600'" class="px-2.5 py-1.5 rounded-md transition-all">Smstr 2</button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-2 pt-2 border-t border-slate-100 text-[11px]" x-show="localFilterType === 'date' || localFilterType === 'month'">
                                        <span class="text-slate-500 font-bold uppercase" x-text="localFilterType === 'date' ? 'Target Tanggal Siswa:' : 'Target Bulan Siswa:'"></span>
                                        <input x-model="localFilterValue" :type="localFilterType === 'date' ? 'date' : 'month'" 
                                               class="bg-slate-50 border border-slate-300 rounded-md p-1 px-2 text-xs font-semibold focus:outline-none focus:border-indigo-500">
                                    </div>
                                </div>

                                <div class="overflow-x-auto rounded-xl border border-slate-100 bg-white shadow-2xs">
                                    <table class="w-full text-left border-collapse">
                                        <thead>
                                            <tr class="bg-slate-50 text-slate-400 text-xs font-semibold uppercase tracking-wider border-b border-slate-100">
                                                <th class="py-3 px-5">Nama Lengkap Siswa</th>
                                                <th class="py-3 px-5">NISN</th>
                                                <th class="py-3 px-5 text-center">Total Kasus (Filter Monitor)</th>
                                                <th class="py-3 px-5 text-right">Informasi</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100 text-sm font-medium">
                                            @foreach($listKeterlambatan->groupBy('siswa_id') as $siswaId => $logs)
                                                @php 
                                                    $siswaData = $logs->first()->siswa; 
                                                    
                                                    $jsonHistori = $logs->map(function($item) {
                                                        return [
                                                            'id' => $item->id,
                                                            'tanggal' => date('d-m-Y', strtotime($item->tanggal)),
                                                            'raw_date' => $item->tanggal,
                                                            'semester' => $item->semester,
                                                            'alasan' => $item->alasan,
                                                            'petugas' => $item->user->name ?? 'Petugas',
                                                            'penanganan' => $item->penanganan ? $item->penanganan : null,
                                                            'route_edit' => route('superadmin.keterlambatan.edit', $item->id),
                                                            'route_delete' => route('superadmin.keterlambatan.destroy', $item->id)
                                                        ];
                                                    })->values()->toJson();
                                                @endphp

                                                <tr x-data="{
                                                        countValidLogs() {
                                                            return {{ $jsonHistori }}.filter(log => checkLocalFilter(log.raw_date, log.semester)).length;
                                                        }
                                                    }"
                                                    x-show="(searchSiswa === '' || '{{ strtolower($siswaData->nama) }}'.includes(searchSiswa.toLowerCase()) || '{{ $siswaData->nisn }}'.includes(searchSiswa)) && countValidLogs() > 0"
                                                    @click="
                                                        modalData = {
                                                            nama: '{{ $siswaData->nama }}',
                                                            nisn: '{{ $siswaData->nisn }}',
                                                            kelas: '{{ $namaKelas }}',
                                                            filterType: localFilterType,
                                                            filterValue: localFilterValue,
                                                            histori: {{ $jsonHistori }}
                                                        };
                                                        showModal = true;
                                                    "
                                                    class="hover:bg-indigo-50/40 cursor-pointer transition-colors group">
                                                    
                                                    <td class="py-3 px-5 font-bold text-slate-900 group-hover:text-indigo-600 text-sm tracking-tight transition-colors">
                                                        {{ $siswaData->nama }}
                                                    </td>
                                                    <td class="py-3 px-5 text-slate-500 font-mono text-xs tracking-wide">
                                                        {{ $siswaData->nisn }}
                                                    </td>
                                                    <td class="py-3 px-5 text-center">
                                                        <span class="inline-block px-3 py-0.5 rounded-xl text-xs font-bold font-mono bg-rose-50 text-rose-600 border border-rose-100 shadow-xs" x-text="countValidLogs() + 'x Telat'">
                                                        </span>
                                                    </td>
                                                    <td class="py-3 px-5 text-right text-indigo-500 font-bold text-xs group-hover:translate-x-1 transition-transform">
                                                        Detail Kasus <i class="fa-solid fa-arrow-right-to-bracket ml-1 text-[10px]"></i>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="bg-white border border-slate-200 rounded-3xl py-12 text-center text-slate-400 font-medium shadow-sm text-sm">
                            <i class="fa-solid fa-box-open text-2xl block mb-2 text-slate-300"></i>
                            Belum ada catatan keterlambatan di dalam database.
                        </div>
                    @endforelse
                </div>

            </div>
        </main>
    </div>

    <div class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-950/40 backdrop-blur-xs"
         x-show="showModal"
         x-transition
         style="display: none;">
        
        <div class="bg-white border border-slate-200 rounded-2xl max-w-xl w-full shadow-2xl overflow-hidden transform transition-all"
             @click.away="showModal = false">
            
            <div class="px-6 py-4 bg-slate-900 text-white flex justify-between items-center">
                <div>
                    <h3 class="text-base font-bold tracking-tight" x-text="modalData.nama"></h3>
                    <p class="text-xs text-slate-300 font-medium mt-0.5">
                        Kelas: <span class="font-semibold text-white" x-text="modalData.kelas"></span> &bull; NISN: <span x-text="modalData.nisn" class="font-mono text-white"></span>
                    </p>
                </div>
                <button @click="showModal = false" class="w-7 h-7 flex items-center justify-center bg-white/10 hover:bg-white/20 text-white rounded-lg transition-colors">
                    <i class="fa-solid fa-xmark text-xs"></i>
                </button>
            </div>

            <div class="p-6 space-y-4 max-h-[420px] overflow-y-auto bg-slate-50/60">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Daftar Tanggal Terlambat Terfilter</span>
                
                <div class="space-y-3.5">
                    <template x-for="(log, index) in modalData.histori" :key="log.id">
                        <div class="bg-white border border-slate-200 p-4 rounded-xl shadow-xs space-y-3"
                             x-show="
                                modalData.filterType === 'all' ||
                                (modalData.filterType === 'date' && log.raw_date === modalData.filterValue) ||
                                (modalData.filterType === 'month' && log.raw_date.substring(0, 7) === modalData.filterValue) ||
                                (modalData.filterType === 'semester' && log.semester == modalData.filterValue)
                             ">
                            
                            <div class="flex flex-wrap justify-between items-center gap-2 border-b border-slate-100 pb-2">
                                <div>
                                    <span class="text-base font-bold text-slate-900 block" x-text="log.tanggal"></span>
                                    <span class="text-[10px] font-bold bg-indigo-50 border border-indigo-100 text-indigo-700 px-1.5 py-0.5 rounded uppercase mt-0.5 inline-block">Semester <span x-text="log.semester"></span></span>
                                </div>
                                
                                <div>
                                    <template x-if="log.penanganan">
                                        <span class="inline-flex items-center gap-1 bg-emerald-50 border border-emerald-100 text-emerald-700 text-[10px] px-2 py-0.5 rounded-full font-bold uppercase">
                                            <i class="fa-solid fa-check-double text-[9px]"></i> Ditangani
                                        </span>
                                    </template>
                                    <template x-if="!log.penanganan">
                                        <span class="inline-flex items-center gap-1 bg-amber-50 border border-amber-200 text-amber-800 text-[10px] px-2 py-0.5 rounded-full font-bold uppercase">
                                            <i class="fa-solid fa-clock text-[9px]"></i> Pending
                                        </span>
                                    </template>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-slate-700 text-sm">
                                <div>
                                    <span class="font-bold block text-slate-400 text-[10px] uppercase tracking-wider">Alasan Pelanggaran:</span>
                                    <p class="font-semibold text-slate-800 mt-0.5 bg-slate-50/80 p-2 rounded-lg border border-slate-100" x-text="log.alasan"></p>
                                </div>
                                <div>
                                    <span class="font-bold block text-slate-400 text-[10px] uppercase tracking-wider">Petugas Catat:</span>
                                    <p class="font-semibold text-slate-800 mt-0.5 bg-slate-50/80 p-2 rounded-lg border border-slate-100" x-text="log.petugas"></p>
                                </div>
                            </div>

                            <template x-if="log.penanganan">
                                <div class="bg-emerald-50/40 border border-emerald-200 p-3 rounded-lg text-sm">
                                    <span class="font-bold block text-emerald-800 text-[10px] uppercase tracking-wider">Tindakan Wali Kelas:</span>
                                    <p class="font-semibold text-emerald-950 mt-1" x-text="log.penanganan"></p>
                                </div>
                            </template>

                            <div class="flex justify-end gap-1.5 pt-1.5">
                                <a :href="log.route_edit" class="bg-slate-50 hover:bg-indigo-600 border border-slate-200 text-slate-600 hover:text-white px-3 py-1.5 rounded-lg text-xs font-bold transition-all flex items-center gap-1">
                                    <i class="fa-regular fa-pen-to-square text-[10px]"></i> Edit
                                </a>
                                
                                <form :action="log.route_delete" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus catatan keterlambatan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-rose-50 hover:bg-rose-600 border border-rose-100 text-rose-600 hover:text-white px-3 py-1.5 rounded-lg text-xs font-bold transition-all flex items-center gap-1">
                                        <i class="fa-regular fa-trash-can text-[10px]"></i> Hapus
                                    </button>
                                </form>
                            </div>

                        </div>
                    </template>
                </div>
            </div>

            <div class="px-6 py-3.5 bg-slate-50 border-t border-slate-100 flex justify-end">
                <button @click="showModal = false" class="px-4 py-1.5 bg-slate-200 text-slate-800 hover:bg-slate-300 font-bold text-xs rounded-xl transition-all">
                    Tutup Riwayat
                </button>
            </div>

        </div>
    </div>

    <footer class="text-center text-xs text-slate-400 py-6 border-t border-slate-100 bg-white mt-auto">
        &copy; 2026 Tim Pengembang SiTelat K4
    </footer>

</body>
</html>