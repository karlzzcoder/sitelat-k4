<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Riwayat Keterlambatan - SiTelat K4</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; -webkit-tap-highlight-color: transparent; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased">

    <div x-data="{ 
            activeTab: '{{ date('n') >= 7 ? '1' : '2' }}',
            searchQuery: '',
            showModal: false,
            selectedData: {}
         }" 
         class="max-w-md mx-auto min-h-screen bg-white shadow-2xl relative pb-24 flex flex-col justify-between">
        <div>
            <div class="p-4 bg-white border-b border-slate-100 sticky top-0 z-30 shadow-sm flex items-center gap-3">
                <a href="/admin-osis" class="w-8 h-8 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-center text-slate-500">
                    <i class="fa-solid fa-arrow-left text-xs"></i>
                </a>
                <div>
                    <h2 class="text-xs font-bold text-slate-800 tracking-tight">Riwayat Log Catatan</h2>
                    <p class="text-[10px] text-slate-400 font-medium">Data terkunci & tidak dapat diubah</p>
                </div>
            </div>

            <div class="p-4 pb-1">
                <div class="flex bg-slate-100 p-1 rounded-xl gap-1">
                    <button @click="activeTab = '1'" 
                            :class="activeTab === '1' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-500 hover:text-slate-800'"
                            class="flex-1 py-2 text-[11px] font-bold rounded-lg transition-all text-center">
                        <i class="fa-solid fa-leaf mr-1 text-[10px]"></i> Ganjil <span class="text-[9px] font-medium text-slate-400">(Jul - Des)</span>
                    </button>
                    <button @click="activeTab = '2'" 
                            :class="activeTab === '2' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-500 hover:text-slate-800'"
                            class="flex-1 py-2 text-[11px] font-bold rounded-lg transition-all text-center">
                        <i class="fa-solid fa-snowflake mr-1 text-[10px]"></i> Genap <span class="text-[9px] font-medium text-slate-400">(Jan - Jun)</span>
                    </button>
                </div>
            </div>

            <div class="px-4 py-2">
                <div class="relative flex items-center">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fa-solid fa-magnifying-glass text-slate-400 text-xs"></i>
                    </div>
                    <input type="text" 
                           x-model="searchQuery" 
                           class="w-full pl-9 pr-8 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 focus:outline-none focus:border-indigo-500 focus:bg-white transition-all"
                           placeholder="Ketik tanggal (Contoh: 2026-06-28 atau 28 Juni)...">
                    
                    <button x-show="searchQuery !== ''" 
                            @click="searchQuery = ''" 
                            type="button" 
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600">
                        <i class="fa-solid fa-circle-xmark text-xs"></i>
                    </button>
                </div>
            </div>

            <div class="p-4 space-y-3 pt-1 flex-1">
                <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block px-1">Rekap Rekaman Log</span>

                <div class="space-y-3 max-h-[500px] overflow-y-auto no-scrollbar pr-0.5">
                    @php
                        $groupedByMonth = [];
                        foreach($riwayatPerTanggal as $item) {
                            $timestamp = strtotime($item['tanggal']);
                            $bulanData = (int) date('n', $timestamp);
                            $semesterData = ($bulanData >= 7 && $bulanData <= 12) ? '1' : '2';
                            
                            $namaBulanTahun = \Carbon\Carbon::parse($item['tanggal'])->translatedFormat('F Y');
                            $groupedByMonth[$semesterData][$namaBulanTahun][] = $item;
                        }
                    @endphp

                    @forelse(['1', '2'] as $sem)
                        <div x-show="activeTab === '{{ $sem }}'" class="space-y-3">
                            @if(isset($groupedByMonth[$sem]) && count($groupedByMonth[$sem]) > 0)
                                @foreach($groupedByMonth[$sem] as $bulanTahun => $daftarTanggal)
                                    
                                    <div x-data="{ openBulan: false }" class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
                                        
                                        <button @click="openBulan = !openBulan" type="button" 
                                                class="w-full p-4 flex justify-between items-center bg-slate-50 hover:bg-slate-100/70 transition-all outline-none border-b border-slate-100">
                                            <div class="flex items-center gap-2">
                                                <i class="fa-regular fa-folder-open text-indigo-500 text-xs"></i>
                                                <span class="text-xs font-extrabold text-slate-700 tracking-tight">{{ $bulanTahun }}</span>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <span class="text-[9px] bg-indigo-50 text-indigo-600 font-extrabold px-2 py-0.5 rounded-full">
                                                    {{ count($daftarTanggal) }} Hari Log
                                                </span>
                                                <i class="fa-solid text-slate-400 text-[9px] transition-transform duration-250" :class="openBulan ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                                            </div>
                                        </button>

                                        <div x-show="openBulan || searchQuery !== ''" x-collapse class="p-3 space-y-2.5 bg-white">
                                            @foreach($daftarTanggal as $item)
                                                @php
                                                    $tanggalFormatted = \Carbon\Carbon::parse($item['tanggal'])->translatedFormat('d F Y');
                                                @endphp

                                                <div x-show="searchQuery === '' || '{{ $item['tanggal'] }}'.includes(searchQuery) || '{{ strtolower($tanggalFormatted) }}'.includes(searchQuery.toLowerCase())" 
                                                     x-data="{ openTanggal: false }" 
                                                     class="bg-slate-50 border border-slate-150 rounded-xl overflow-hidden transition-all">
                                                    
                                                    <button @click="openTanggal = !openTanggal" type="button" class="w-full p-3 flex justify-between items-center bg-white border-b border-slate-100 outline-none text-left">
                                                        <div class="flex items-center gap-2.5">
                                                            <div class="w-7 h-7 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                                                                <i class="fa-solid fa-calendar-day text-[10px]"></i>
                                                            </div>
                                                            <div>
                                                                <h4 class="text-[11px] font-bold text-slate-800">{{ \Carbon\Carbon::parse($item['tanggal'])->translatedFormat('d F') }}</h4>
                                                            </div>
                                                        </div>
                                                        <div class="flex items-center gap-2">
                                                            <span class="text-[9px] bg-rose-50 text-rose-600 font-extrabold px-1.5 py-0.5 rounded-full border border-rose-100">
                                                                {{ $item['total_telat'] }} Siswa
                                                            </span>
                                                            <i class="fa-solid text-slate-400 text-[9px] transition-transform duration-200" :class="openTanggal ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                                                        </div>
                                                    </button>

                                                    <div x-show="openTanggal" x-collapse class="p-2 bg-slate-50/50 space-y-1.5 border-t border-slate-100">
                                                        @foreach($item['detail'] as $detail)
                                                            <div @click="
                                                                    selectedData = {
                                                                        nama: '{{ $detail->siswa->nama ?? 'Siswa Terhapus' }}',
                                                                        kelas: '{{ $detail->siswa->kelas ?? '-' }}',
                                                                        tanggal: '{{ $tanggalFormatted }}',
                                                                        alasan: '{{ $detail->alasan }}',
                                                                        tahun_ajaran: '{{ $detail->tahun_ajaran ?? '-' }}',
                                                                        semester: '{{ $detail->semester == '1' ? 'Ganjil' : 'Genap' }}',
                                                                        penanganan: '{{ $detail->penanganan ?? '' }}'
                                                                    };
                                                                    showModal = true;
                                                                 "
                                                                 class="p-2.5 bg-white border border-slate-150 rounded-lg flex justify-between items-center shadow-sm cursor-pointer hover:border-indigo-300 active:scale-[0.99] transition-all">
                                                                
                                                                <div class="flex items-center gap-2 min-w-0">
                                                                    <div class="w-6 h-6 rounded-md bg-slate-100 text-slate-600 font-bold text-[10px] flex items-center justify-center shrink-0 uppercase">
                                                                        {{ substr($detail->siswa->nama ?? 'S', 0, 1) }}
                                                                    </div>
                                                                    <div class="min-w-0">
                                                                        <h5 class="text-[11px] font-bold text-slate-800 truncate">{{ $detail->siswa->nama ?? 'Siswa Terhapus' }}</h5>
                                                                        <p class="text-[8px] text-slate-400 font-semibold">Kelas: {{ $detail->siswa->kelas ?? '-' }}</p>
                                                                    </div>
                                                                </div>
                                                                <div class="text-right shrink-0">
                                                                    <span class="text-[8px] bg-slate-100 text-slate-600 font-bold px-1.5 py-0.5 rounded mb-0.5 inline-block">
                                                                        {{ $detail->alasan }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                    </div>
                                @endforeach
                            @else
                                <div class="text-center py-16 text-slate-400 bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                                    <i class="fa-solid fa-clock-rotate-left text-2xl mb-2 text-slate-300 block"></i>
                                    <span class="text-xs font-medium">Belum ada riwayat keterlambatan di semester ini</span>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-16 text-slate-400 bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                            <i class="fa-solid fa-clock-rotate-left text-2xl mb-2 text-slate-300 block"></i>
                            <span class="text-xs font-medium">Belum ada rekaman log sama sekali</span>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div x-show="showModal" 
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click.self="showModal = false"
             style="display: none;">
            
            <div class="bg-white rounded-3xl w-full max-w-sm overflow-hidden shadow-2xl border border-slate-100 flex flex-col"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95">
                
                <div class="p-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-[10px]">
                            <i class="fa-solid fa-circle-info"></i>
                        </div>
                        <h3 class="text-xs font-extrabold text-slate-800 tracking-tight">Detail Log Keterlambatan</h3>
                    </div>
                    <button @click="showModal = false" class="w-6 h-6 rounded-full bg-slate-200/70 hover:bg-slate-200 text-slate-500 flex items-center justify-center text-[10px]">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <div class="p-5 space-y-4 max-h-[400px] overflow-y-auto no-scrollbar">
                    <div class="flex items-center gap-3 p-3 bg-indigo-50/50 rounded-2xl border border-indigo-100/50">
                        <div class="w-10 h-10 rounded-xl bg-indigo-600 text-white font-extrabold text-sm flex items-center justify-center uppercase" x-text="selectedData.nama ? selectedData.nama.substring(0,1) : 'S'">
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-slate-800" x-text="selectedData.nama"></h4>
                            <p class="text-[10px] text-indigo-600 font-extrabold mt-0.5" x-text="'Kelas ' + selectedData.kelas"></p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2.5">
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                            <span class="text-[9px] font-bold text-slate-400 block mb-0.5">Tanggal Catat</span>
                            <span class="text-[11px] font-bold text-slate-700" x-text="selectedData.tanggal"></span>
                        </div>
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                            <span class="text-[9px] font-bold text-slate-400 block mb-0.5">Alasan Utama</span>
                            <span class="text-[11px] font-bold text-rose-600" x-text="selectedData.alasan"></span>
                        </div>
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                            <span class="text-[9px] font-bold text-slate-400 block mb-0.5">Tahun Ajaran</span>
                            <span class="text-[11px] font-bold text-slate-700" x-text="selectedData.tahun_ajaran"></span>
                        </div>
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                            <span class="text-[9px] font-bold text-slate-400 block mb-0.5">Periode Semester</span>
                            <span class="text-[11px] font-bold text-slate-700" x-text="selectedData.semester"></span>
                        </div>
                    </div>

                    <div class="p-3.5 bg-amber-50/60 rounded-xl border border-amber-200/70">
                        <span class="text-[9px] font-extrabold text-amber-800 uppercase tracking-wider block mb-1.5 flex items-center gap-1">
                            <i class="fa-solid fa-comment-dots text-[10px]"></i> Catatan Tambahan / Penanganan
                        </span>
                        <p class="text-xs font-semibold leading-relaxed" 
                           :class="selectedData.penanganan ? 'text-slate-700 font-medium' : 'text-slate-400 italic font-normal'" 
                           x-text="selectedData.penanganan ? selectedData.penanganan : 'Tidak ada catatan tambahan atau penanganan khusus yang dimasukkan.'">
                        </p>
                    </div>
                </div>

                <div class="p-4 border-t border-slate-100 bg-slate-50 text-center">
                    <button @click="showModal = false" class="w-full py-2.5 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-xs font-bold shadow-sm transition-all">
                        Tutup Detail
                    </button>
                </div>
            </div>
        </div>

        <div class="absolute bottom-0 left-0 right-0 h-20 bg-white border-t border-slate-100 px-14 flex justify-between items-center z-30 shadow-[0_-4px_12px_rgba(0,0,0,0.03)]">
            <a href="/admin-osis" class="flex flex-col items-center gap-1 text-slate-400 hover:text-indigo-600 cursor-pointer transition-all">
                <i class="fa-solid fa-house-chimney text-lg"></i>
                <span class="text-[10px] font-bold">Beranda</span>
            </a>
            <div class="flex flex-col items-center gap-1 text-indigo-600 cursor-pointer">
                <i class="fa-solid fa-clock-rotate-left text-lg"></i>
                <span class="text-[10px] font-bold">Riwayat</span>
            </div>
        </div>
    </div>

</body>
</html>