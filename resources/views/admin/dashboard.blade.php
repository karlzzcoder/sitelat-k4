<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Dashboard OSIS - SiTelat K4</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; -webkit-tap-highlight-color: transparent; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased" x-data="{ 
    searchQuery: '', 
    showModal: false,
    
    // State Filter Navigasi Aktif
    selectedAngkatan: 'Semua',
    selectedJurusan: 'Semua',
    selectedNomor: 'Semua',
    
    angkatans: ['Semua', 'X', 'XI', 'XII'],
    jurusans: ['Semua', 'PPLG', 'TJKT', 'TO', 'TPFL'],
    nomors: ['Semua', '1', '2', '3'],

    selectedSiswa: { id: '', name: '', kelas: '' },
    siswas: {{ json_encode($siswas) }},

    // LOGIK PURE FILTER UPDATE
    get filteredSiswas() {
        return this.siswas.filter(s => {
            let kelasStr = String(s.kelas).toUpperCase().trim();

            // 1. Filter Ketik Nama
            const matchSearch = s.name.toLowerCase().includes(this.searchQuery.toLowerCase());
            
            // 2. Filter Angkatan (Mengecek apakah mengandung X, XI, atau XII)
            let targetAngkatan = this.selectedAngkatan;
            if (targetAngkatan === 'X' && !kelasStr.includes('X')) {
                if (!kelasStr.startsWith('10')) return false;
            } else if (targetAngkatan === 'XI' && !kelasStr.includes('XI')) {
                if (!kelasStr.startsWith('11')) return false;
            } else if (targetAngkatan === 'XII' && !kelasStr.includes('XII')) {
                if (!kelasStr.startsWith('12')) return false;
            } else if (targetAngkatan !== 'Semua' && !kelasStr.includes(targetAngkatan)) {
                return false;
            }
            
            // 3. Filter Jurusan (PPLG, TJKT, dll)
            const matchJurusan = this.selectedJurusan === 'Semua' || kelasStr.includes(this.selectedJurusan);
            
            // 4. Filter Nomor Kelas (1, 2, 3) di akhir string kelas
            const matchNomor = this.selectedNomor === 'Semua' || kelasStr.endsWith(this.selectedNomor);

            return matchSearch && matchJurusan && matchNomor;
        });
    }
}">

    <div class="max-w-md mx-auto min-h-screen bg-white shadow-2xl relative pb-28 flex flex-col justify-between">
        <div>
            <div class="p-4 flex justify-between items-center bg-white border-b border-slate-100 sticky top-0 z-30 shadow-sm">
                <div class="flex items-center gap-3">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=4F46E5&color=fff" alt="Avatar" class="w-9 h-9 rounded-full object-cover">
                    <div>
                        <h4 class="text-[10px] text-indigo-600 font-bold uppercase tracking-wider">Petugas OSIS</h4>
                        <h2 class="text-xs font-bold text-slate-800 tracking-tight">{{ Auth::user()->name }}</h2>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="w-8 h-8 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-center text-slate-400 hover:text-rose-600 transition-all">
                        <i class="fa-solid fa-right-from-bracket text-xs"></i>
                    </button>
                </form>
            </div>

            <div class="p-4 space-y-4">
                
                @if(session('success'))
                    <div class="p-3 bg-emerald-50 border border-emerald-150 text-emerald-700 rounded-xl text-xs font-semibold flex items-center gap-2 shadow-sm">
                        <i class="fa-solid fa-circle-check text-emerald-500 text-sm"></i>
                        {{ session('success') }}
                    </div>
                @endif

                <div class="relative">
                    <input type="text" x-model="searchQuery" placeholder="Cari nama siswa langsung..." 
                        class="w-full px-4 py-3 pl-10 bg-slate-100 border border-transparent rounded-xl text-xs font-medium focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none text-slate-700 transition-all placeholder:text-slate-400">
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </div>
                </div>

                <div class="bg-slate-50 p-3.5 rounded-2xl border border-slate-100 space-y-3.5 shadow-sm">
                    
                    <div class="space-y-1.5">
                        <span class="text-[9px] font-extrabold text-slate-400 uppercase tracking-widest block px-0.5">Pilih Tingkat</span>
                        <div class="flex gap-1.5 overflow-x-auto no-scrollbar py-0.5">
                            <template x-for="ang in angkatans" :key="ang">
                                <button type="button" @click="selectedAngkatan = ang" 
                                    :class="selectedAngkatan === ang ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-200 border-indigo-600' : 'bg-white text-slate-600 border-slate-200/80 hover:bg-slate-100'"
                                    class="px-4 py-1.5 rounded-xl text-[10px] font-bold border transition-all whitespace-nowrap min-w-[55px]"
                                    x-text="ang">
                                </button>
                            </template>
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <span class="text-[9px] font-extrabold text-slate-400 uppercase tracking-widest block px-0.5">Pilih Jurusan</span>
                        <div class="flex gap-1.5 overflow-x-auto no-scrollbar py-0.5">
                            <template x-for="jur in jurusans" :key="jur">
                                <button type="button" @click="selectedJurusan = jur" 
                                    :class="selectedJurusan === jur ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-200 border-indigo-600' : 'bg-white text-slate-600 border-slate-200/80 hover:bg-slate-100'"
                                    class="px-4 py-1.5 rounded-xl text-[10px] font-extrabold border transition-all whitespace-nowrap" 
                                    x-text="jur">
                                </button>
                            </template>
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <span class="text-[9px] font-extrabold text-slate-400 uppercase tracking-widest block px-0.5">Pilih No Kelas</span>
                        <div class="flex gap-1.5 overflow-x-auto no-scrollbar py-0.5">
                            <template x-for="num in nomors" :key="num">
                                <button type="button" @click="selectedNomor = num" 
                                    :class="selectedNomor === num ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-200 border-indigo-600' : 'bg-white text-slate-600 border-slate-200/80 hover:bg-slate-100'"
                                    class="px-4 py-1.5 rounded-xl text-[10px] font-bold border transition-all whitespace-nowrap min-w-[55px]" 
                                    x-text="num">
                                </button>
                            </template>
                        </div>
                    </div>

                </div>

                <div class="space-y-2">
                    <div class="flex justify-between items-center px-1">
                        <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Daftar Siswa</span>
                        <span class="text-[10px] bg-indigo-50 text-indigo-600 font-bold px-2.5 py-0.5 rounded-full" x-text="filteredSiswas.length + ' Orang'"></span>
                    </div>

                    <div class="space-y-2 max-h-[360px] overflow-y-auto pr-1 no-scrollbar">
                        <template x-for="siswa in filteredSiswas" :key="siswa.id">
                            <div class="p-3 bg-white border border-slate-150 rounded-xl flex justify-between items-center shadow-sm hover:border-indigo-200 transition-all">
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <div class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 font-bold text-xs flex items-center justify-center shrink-0 uppercase" x-text="siswa.name.charAt(0)"></div>
                                    <div class="min-w-0">
                                        <h4 class="text-xs font-bold text-slate-800 truncate" x-text="siswa.name"></h4>
                                        <div class="mt-0.5">
                                            <span class="inline-block text-[9px] bg-indigo-50 text-indigo-700 font-extrabold px-2 py-0.5 rounded-md border border-indigo-100/70 uppercase tracking-wide" x-text="siswa.kelas"></span>
                                        </div>
                                    </div>
                                </div>
                                
                                <button type="button" @click="
                                    selectedSiswa = { id: siswa.id, name: siswa.name, kelas: siswa.kelas };
                                    showModal = true;
                                " class="px-3 py-2 bg-indigo-600 hover:bg-indigo-700 active:scale-[0.96] text-white font-bold text-[10px] rounded-xl shadow-md shadow-indigo-100 shrink-0 transition-all">
                                    <i class="fa-solid fa-plus mr-1"></i> Telat
                                </button>
                            </div>
                        </template>

                        <div x-show="filteredSiswas.length === 0" class="text-center py-12 text-slate-400 bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                            <i class="fa-solid fa-user-slash text-xl mb-1.5 text-slate-300 block"></i>
                            <span class="text-xs font-medium text-slate-400">Siswa tidak ditemukan</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="fixed bottom-0 left-0 right-0 max-w-md mx-auto h-20 bg-white border-t border-slate-100 px-14 flex justify-between items-center z-40 shadow-[0_-4px_12px_rgba(0,0,0,0.03)]">
            <div class="flex flex-col items-center gap-1 text-indigo-600 cursor-pointer">
                <i class="fa-solid fa-square-plus text-xl"></i>
                <span class="text-[10px] font-bold">Beranda</span>
            </div>
            <a href="/admin-osis/history" class="flex flex-col items-center gap-1 text-slate-300 hover:text-indigo-600 cursor-pointer transition-all">
                <i class="fa-solid fa-clock-rotate-left text-xl"></i>
                <span class="text-[10px] font-bold">Riwayat</span>
            </a>
        </div>

        <div x-show="showModal" class="fixed inset-0 z-50 overflow-hidden" style="display: none;" x-cloak>
            <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" @click="showModal = false"></div>
            <div class="absolute inset-x-0 bottom-0 max-w-md mx-auto bg-white rounded-t-[32px] shadow-2xl p-6 space-y-4 border-t border-slate-100">
                <div class="w-12 h-1 bg-slate-200 rounded-full mx-auto -mt-2 mb-1"></div>
                <div class="flex justify-between items-center">
                    <h3 class="text-sm font-bold text-slate-800">Catat Keterlambatan</h3>
                    <button type="button" @click="showModal = false" class="w-6 h-6 rounded-full bg-slate-100 flex items-center justify-center text-slate-400"><i class="fa-solid fa-xmark text-xs"></i></button>
                </div>

                <form action="/admin-osis/store" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="siswa_id" :value="selectedSiswa.id">

                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 flex items-center gap-3">
                        <div class="w-8 h-8 rounded-xl bg-indigo-600 text-white font-bold text-xs flex items-center justify-center uppercase" x-text="selectedSiswa.name.charAt(0)"></div>
                        <div class="min-w-0">
                            <h4 class="text-xs font-bold text-slate-800 truncate" x-text="selectedSiswa.name"></h4>
                            <div class="mt-0.5">
                                <span class="inline-block text-[9px] bg-indigo-100/80 text-indigo-700 font-extrabold px-2 py-0.5 rounded-md uppercase" x-text="selectedSiswa.kelas"></span>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col gap-1">
                        <label class="text-[10px] font-bold text-slate-500">Tanggal Keterlambatan</label>
                        <input type="date" 
                               name="tanggal" 
                               value="{{ \Carbon\Carbon::now('Asia/Jakarta')->format('Y-m-d') }}" 
                               class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 focus:outline-none focus:border-indigo-500 focus:bg-white transition-all">
                    </div>

                    <div class="space-y-1">
                        <label class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block">Alasan</label>
                        <div class="relative">
                            <select name="alasan" class="w-full px-4 py-2.5 bg-slate-100 border-none rounded-xl text-xs font-semibold outline-none text-slate-700 focus:ring-2 focus:ring-indigo-600 appearance-none" required>
                                <option value="">-- Pilih Alasan --</option>
                                <option value="Kesiangan">Kesiangan / Bangun Lambat</option>
                                <option value="Macet">Kendaraan / Macet di Jalan</option>
                                <option value="Ban Bocor">Kendaraan Rusak / Ban Bocor</option>
                                <option value="Urusan Keluarga">Urusan Keluarga</option>
                                <option value="Lainnya">Lainnya (Tulis di Catatan)</option>
                            </select>
                            <div class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                                <i class="fa-solid fa-chevron-down text-[10px]"></i>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block">Catatan Tambahan</label>
                        <textarea name="keterangan" placeholder="Keterangan opsional jika diperlukan..." class="w-full px-4 py-2.5 bg-slate-100 border-none rounded-xl text-xs text-slate-700 outline-none focus:ring-2 focus:ring-indigo-600 h-16 resize-none"></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-3 pt-2">
                        <button type="button" @click="showModal = false" class="py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs rounded-xl transition-all">
                            Batal
                        </button>
                        <button type="submit" class="py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-md transition-all">
                            Simpan Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
</html>