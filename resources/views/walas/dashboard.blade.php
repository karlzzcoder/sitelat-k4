<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Dashboard Wali Kelas - SiTelat K4</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; -webkit-tap-highlight-color: transparent; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col">

    <header class="bg-white border-b border-slate-100 sticky top-0 z-30 px-4 md:px-8 py-3.5 flex justify-between items-center shadow-sm">
        <div class="flex items-center gap-2.5">
            <div class="w-9 h-9 bg-emerald-600 rounded-xl flex items-center justify-center text-white shadow-sm">
                <i class="fa-solid fa-graduation-cap text-base"></i>
            </div>
            <div>
                <h1 class="text-sm md:text-base font-extrabold tracking-tight leading-none">SiTelat K4</h1>
                <span class="text-[10px] text-emerald-600 font-bold uppercase tracking-wider block mt-1">Kelas {{ $kelas }}</span>
            </div>
        </div>
        
        <div class="flex items-center gap-3">
            <div class="text-right">
                <p class="text-xs md:text-sm font-bold text-slate-700 leading-none truncate max-w-[120px] md:max-w-xs">{{ Auth::user()->name }}</p>
                <p class="text-[9px] md:text-xs text-slate-400 font-medium mt-1">Wali Kelas</p>
            </div>
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="w-9 h-9 rounded-xl border border-slate-200 flex items-center justify-center text-slate-400 hover:text-rose-600 active:bg-rose-50 transition-all">
                    <i class="fa-solid fa-right-from-bracket text-xs"></i>
                </button>
            </form>
        </div>
    </header>

    <main class="flex-grow max-w-6xl mx-auto w-full px-4 md:px-6 py-5 space-y-4">

        @if(session('success'))
            <div class="p-3 bg-emerald-50 border border-emerald-150 text-emerald-700 rounded-xl text-xs font-semibold flex items-center gap-2 shadow-sm">
                <i class="fa-solid fa-circle-check text-emerald-500 text-sm"></i>
                {{ session('success') }}
            </div>
        @endif

        @if(isset($belumDitindakCount) && $belumDitindakCount > 0)
            <div class="p-4 bg-amber-50 border border-amber-200 text-amber-800 rounded-xl text-xs font-semibold flex items-center justify-between gap-3 shadow-sm animate-pulse">
                <div class="flex items-center gap-2.5">
                    <div class="w-7 h-7 bg-amber-500 rounded-lg flex items-center justify-center text-white shrink-0">
                        <i class="fa-solid fa-bell text-xs"></i>
                    </div>
                    <div>
                        <p class="font-extrabold text-amber-950">Perhatian, Halo Guru!</p>
                        <p class="text-amber-700 font-medium mt-0.5">Ada <span class="font-black text-rose-600 underline text-sm">{{ $belumDitindakCount }}</span> kasus keterlambatan siswa yang **belum Anda berikan tindakan/pembinaan**. Silakan cek daftar di bawah.</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="bg-gradient-to-br from-emerald-600 to-teal-700 rounded-2xl p-5 md:p-6 text-white shadow-lg shadow-emerald-100/50 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
            <div>
                <span class="bg-white/20 text-white text-[9px] md:text-xs font-extrabold px-2.5 py-0.5 rounded-full uppercase tracking-wider inline-block mb-1.5">Monitoring Dashboard</span>
                <h2 class="text-lg md:text-xl font-extrabold tracking-tight">Evaluasi Keterlambatan Kelas</h2>
                <p class="text-emerald-100 text-[11px] md:text-xs leading-relaxed font-medium mt-1 max-w-xl">
                    Ketuk/Klik tombol **"Lihat Riwayat & Tindak"** untuk melihat detail tanggal absen dan memberikan pembinaan per hari kejadian.
                </p>
            </div>
            <a href="{{ route('walas.rekap.download') }}" 
               class="w-full md:w-auto bg-[#1D7444] hover:bg-[#155A33] text-white font-bold text-xs rounded-xl py-3 px-4 shadow-md transition-all flex items-center justify-center gap-2 shrink-0">
                <i class="fa-regular fa-file-excel text-sm"></i>
                Download Rekap Excel (.xlsx)
            </a>
        </div>

        <div>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                <div class="bg-white border border-slate-150 p-3.5 rounded-xl shadow-sm">
                    <span class="text-[9px] font-bold text-slate-400 uppercase block">Total Murid</span>
                    <h3 class="text-lg font-extrabold text-slate-800 mt-0.5">{{ $totalSiswa }}</h3>
                </div>
                <div class="bg-white border border-slate-150 p-3.5 rounded-xl shadow-sm">
                    <span class="text-[9px] font-bold text-slate-400 uppercase block">Pernah Telat</span>
                    <h3 class="text-lg font-extrabold text-amber-600 mt-0.5">{{ $totalPernahTelat }}</h3>
                </div>
                <div class="bg-white border border-rose-100 p-3.5 rounded-xl shadow-sm col-span-2 md:col-span-1 flex justify-between items-center bg-gradient-to-r from-white to-rose-50/20">
                    <div>
                        <span class="text-[9px] font-bold text-rose-500 uppercase block">Peringatan (>= 3x Telat)</span>
                        <h3 class="text-lg font-extrabold text-rose-600 mt-0.5">
                            {{ $siswaPernahTelat->filter(fn($s) => $s->keterlambatans_count >= 3)->count() }}
                        </h3>
                    </div>
                    <i class="fa-solid fa-triangle-exclamation text-rose-500 animate-pulse"></i>
                </div>
            </div>
        </div>

        <div class="hidden md:block bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100">
                <h3 class="text-sm font-bold text-slate-800">Daftar Pelanggar Kelas</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50 text-slate-400 font-bold uppercase tracking-wider border-b border-slate-100">
                            <th class="py-3.5 px-5 text-center w-12">No</th>
                            <th class="py-3.5 px-5">Nama Siswa</th>
                            <th class="py-3.5 px-5">NISN</th>
                            <th class="py-3.5 px-5">Total Telat</th>
                            <th class="py-3.5 px-5">Kasus Terakhir</th>
                            <th class="py-3.5 px-5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($siswaPernahTelat as $idx => $s)
                            @php
                                $isAlert = $s->keterlambatans_count >= 3;
                                $latestTelat = $s->keterlambatans->first();
                                
                                // Deteksi apakah siswa ini memiliki setidaknya satu kasus keterlambatan yang belum ditangani
                                $adaYangBelumDitindak = $s->keterlambatans->contains(fn($k) => is_null($k->penanganan));

                                $historyData = $s->keterlambatans->map(fn($k) => [
                                    'id' => $k->id,
                                    'tanggal' => date('d-m-Y', strtotime($k->tanggal)),
                                    'raw_date' => $k->tanggal,
                                    'alasan' => $k->alasan ?? 'Tidak ada alasan',
                                    'keterangan' => $k->keterangan ?? '-', 
                                    'penanganan' => $k->penanganan
                                ]);
                            @endphp
                            <tr class="transition-colors {{ $isAlert ? 'bg-rose-50/70 hover:bg-rose-100/60' : 'hover:bg-slate-50/60' }}">
                                <td class="py-4 px-5 text-center font-bold {{ $isAlert ? 'text-rose-400' : 'text-slate-400' }}">{{ $idx + 1 }}</td>
                                <td class="py-4 px-5">
                                    <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                                        <span class="font-extrabold {{ $isAlert ? 'text-rose-900' : 'text-slate-900' }}">{{ $s->nama }}</span>
                                        
                                        @if($adaYangBelumDitindak)
                                            <span class="inline-flex items-center gap-1 bg-amber-100 text-amber-800 text-[9px] font-black px-2 py-0.5 rounded-md border border-amber-200/60 max-w-max">
                                                <span class="w-1.5 h-1.5 bg-amber-500 rounded-full animate-ping"></span> Belum Ditindak
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 bg-emerald-100 text-emerald-800 text-[9px] font-extrabold px-2 py-0.5 rounded-md max-w-max">
                                                <i class="fa-solid fa-check text-[8px]"></i> Bersih
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="py-4 px-5 font-semibold {{ $isAlert ? 'text-rose-600/80' : 'text-slate-500' }}">{{ $s->nisn }}</td>
                                <td class="py-4 px-5">
                                    <span class="font-extrabold px-2 py-0.5 rounded-md text-[11px] {{ $isAlert ? 'bg-rose-200 text-rose-800' : 'bg-slate-100 text-slate-600' }}">
                                        {{ $s->keterlambatans_count }}x
                                    </span>
                                </td>
                                <td class="py-4 px-5 font-semibold {{ $isAlert ? 'text-rose-700/80' : 'text-slate-600' }}">{{ $latestTelat ? date('d-m-Y', strtotime($latestTelat->tanggal)) : '-' }}</td>
                                <td class="py-4 px-5 text-right">
                                    <button type="button" onclick="openHistoryModal('{{ addslashes($s->nama) }}', '{{ $s->nisn }}', {{ json_encode($historyData) }}, '{{ $s->id }}')" 
                                            class="inline-flex items-center px-3 py-1.5 rounded-lg font-bold text-[11px] transition-all shadow-xs text-white
                                            {{ $isAlert ? 'bg-rose-600 hover:bg-rose-700' : 'bg-slate-700 hover:bg-slate-800' }}">
                                        <i class="fa-solid fa-folder-open mr-1.5 text-[10px]"></i> Lihat & Tindak
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center py-12 text-slate-400">Tidak ada data siswa terlambat.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="block md:hidden space-y-3.5">
            @forelse($siswaPernahTelat as $idx => $s)
                @php
                    $isAlert = $s->keterlambatans_count >= 3;
                    $latestTelat = $s->keterlambatans->first();
                    
                    // Deteksi apakah siswa ini memiliki setidaknya satu kasus keterlambatan yang belum ditangani
                    $adaYangBelumDitindak = $s->keterlambatans->contains(fn($k) => is_null($k->penanganan));

                    $historyData = $s->keterlambatans->map(fn($k) => [
                        'id' => $k->id,
                        'tanggal' => date('d-m-Y', strtotime($k->tanggal)),
                        'raw_date' => $k->tanggal,
                        'alasan' => $k->alasan ?? 'Tidak ada alasan',
                        'keterangan' => $k->keterangan ?? '-', 
                        'penanganan' => $k->penanganan
                    ]);
                @endphp
                <div class="bg-white rounded-xl border p-3.5 {{ $isAlert ? 'border-rose-300 bg-gradient-to-b from-rose-50/40 to-rose-50/10 shadow-sm' : 'border-slate-200' }}">
                    <div class="flex justify-between items-start gap-3">
                        <div class="min-w-0 text-left flex-grow space-y-1">
                            @if($adaYangBelumDitindak)
                                <span class="inline-flex items-center gap-1 bg-amber-100 text-amber-800 text-[8px] font-black px-1.5 py-0.5 rounded-md border border-amber-200/60">
                                    <span class="w-1 h-1 bg-amber-500 rounded-full animate-ping"></span> Belum Ditindak
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 bg-emerald-100 text-emerald-800 text-[8px] font-extrabold px-1.5 py-0.5 rounded-md">
                                    <i class="fa-solid fa-check text-[7px]"></i> Bersih
                                </span>
                            @endif

                            <h4 class="text-sm font-extrabold truncate {{ $isAlert ? 'text-rose-800' : 'text-slate-900' }}">
                                {{ $s->nama }}
                            </h4>
                            <p class="text-[9px] font-medium {{ $isAlert ? 'text-rose-500/70' : 'text-slate-400' }}">NISN: {{ $s->nisn }}</p>
                        </div>
                        <span class="text-[10px] font-extrabold px-2.5 py-1 rounded-lg shrink-0 {{ $isAlert ? 'bg-rose-600 text-white' : 'bg-slate-100 text-slate-700' }}">
                            {{ $s->keterlambatans_count }}x
                        </span>
                    </div>
                    <div class="mt-3">
                        <button type="button" onclick="openHistoryModal('{{ addslashes($s->nama) }}', '{{ $s->nisn }}', {{ json_encode($historyData) }}, '{{ $s->id }}')" 
                           class="w-full h-9 text-[11px] font-extrabold rounded-lg flex items-center justify-center shadow-sm transition-all text-white
                           {{ $isAlert ? 'bg-rose-600 hover:bg-rose-700' : 'bg-slate-700 hover:bg-slate-800' }}">
                            <i class="fa-solid fa-folder-open mr-1.5 text-xs"></i> Lihat Riwayat & Tindak
                        </button>
                    </div>
                </div>
            @empty
                <div class="text-center py-10 bg-white rounded-xl border border-dashed border-slate-200">
                    <h5 class="text-xs font-bold text-slate-400">Kelas Aman, Tidak ada siswa yang terlambat.</h5>
                </div>
            @endforelse
        </div>
    </main>

    <div id="historyModal" class="hidden fixed inset-0 z-50 flex items-end sm:items-center justify-center p-0 sm:p-4 bg-slate-900/60 backdrop-blur-sm">
        <div class="bg-white w-full sm:max-w-md rounded-t-2xl sm:rounded-2xl max-h-[90vh] flex flex-col shadow-2xl overflow-hidden">
            
            <div class="px-5 py-4 border-b border-slate-100 flex justify-between items-start bg-slate-50">
                <div>
                    <h3 id="modalStudentName" class="text-sm font-extrabold text-slate-900 leading-tight">Nama Siswa</h3>
                    <p id="modalStudentNisn" class="text-[10px] text-slate-400 font-bold mt-0.5">NISN: -</p>
                </div>
                <button type="button" onclick="closeHistoryModal()" class="w-7 h-7 bg-white rounded-full border border-slate-200 text-slate-400 flex items-center justify-center active:bg-slate-100">
                    <i class="fa-solid fa-xmark text-xs"></i>
                </button>
            </div>

            <div class="p-3 bg-slate-100 border-b border-slate-200/60 flex gap-2">
                <button type="button" id="semesterGanjilTab" onclick="changeSemester('ganjil')" class="flex-1 py-2 px-3 rounded-xl text-xs font-extrabold transition-all bg-white text-emerald-700 shadow-sm border border-emerald-500/20">
                    <i class="fa-solid fa-calendar-days mr-1 text-[10px]"></i> Sem. Ganjil
                </button>
                <button type="button" id="semesterGenapTab" onclick="changeSemester('genap')" class="flex-1 py-2 px-3 rounded-xl text-xs font-bold transition-all bg-slate-50 text-slate-500 hover:bg-white border border-slate-200">
                    <i class="fa-solid fa-calendar-days mr-1 text-[10px]"></i> Sem. Genap
                </button>
            </div>

            <div id="monthsNavbar" class="px-3 py-2 bg-slate-50/50 border-b border-slate-150 flex gap-1.5 overflow-x-auto no-scrollbar">
            </div>

            <div id="modalHistoryContainer" class="p-4 overflow-y-auto space-y-4 no-scrollbar flex-grow max-h-[45vh]">
            </div>

            <div class="p-3 border-t border-slate-100 bg-slate-50">
                <button type="button" onclick="closeHistoryModal()" class="w-full h-9 bg-slate-200 text-slate-700 font-bold rounded-xl text-xs active:bg-slate-300 transition-colors">Tutup</button>
            </div>
        </div>
    </div>

    <footer class="text-center text-[9px] text-slate-400 py-4 border-t border-slate-100 bg-white">
        &copy; 2026 Tim Pengembang SiTelat K4
    </footer>

    <script>
        let currentRawHistories = [];
        let currentSiswaId = null;
        let activeSemester = 'ganjil'; 
        let activeMonthFilter = 'all';  

        const namaBulan = [
            "Januari", "Februari", "Maret", "April", "Mei", "Juni",
            "Juli", "Agustus", "September", "Oktober", "November", "Desember"
        ];

        const semesterMonths = {
            ganjil: [6, 7, 8, 9, 10, 11], 
            genap: [0, 1, 2, 3, 4, 5]     
        };

        function openHistoryModal(name, nisn, histories, siswaId) {
            document.getElementById('modalStudentName').innerText = name;
            document.getElementById('modalStudentNisn').innerText = "NISN: " + nisn;
            
            currentRawHistories = histories;
            currentSiswaId = siswaId;
            
            const currentMonthIndex = new Date().getMonth();
            activeSemester = semesterMonths.ganjil.includes(currentMonthIndex) ? 'ganjil' : 'genap';
            activeMonthFilter = 'all';

            updateModalUI();

            document.getElementById('historyModal').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function changeSemester(sem) {
            activeSemester = sem;
            activeMonthFilter = 'all'; 
            updateModalUI();
        }

        function changeMonthFilter(monthIdx) {
            activeMonthFilter = monthIdx;
            updateModalUI();
        }

        function updateModalUI() {
            const tabGanjil = document.getElementById('semesterGanjilTab');
            const tabGenap = document.getElementById('semesterGenapTab');

            if(activeSemester === 'ganjil') {
                tabGanjil.className = "flex-1 py-2 px-3 rounded-xl text-xs font-extrabold transition-all bg-emerald-600 text-white shadow-sm";
                tabGenap.className = "flex-1 py-2 px-3 rounded-xl text-xs font-bold transition-all bg-white text-slate-500 border border-slate-200";
            } else {
                tabGanjil.className = "flex-1 py-2 px-3 rounded-xl text-xs font-bold transition-all bg-white text-slate-500 border border-slate-200";
                tabGenap.className = "flex-1 py-2 px-3 rounded-xl text-xs font-extrabold transition-all bg-emerald-600 text-white shadow-sm";
            }

            const monthsNavbar = document.getElementById('monthsNavbar');
            monthsNavbar.innerHTML = '';

            const btnAll = document.createElement('button');
            btnAll.type = 'button';
            btnAll.innerText = 'Semua';
            btnAll.onclick = () => changeMonthFilter('all');
            btnAll.className = activeMonthFilter === 'all' 
                ? "py-1 px-3 bg-emerald-50 text-emerald-700 font-extrabold text-[11px] rounded-lg border border-emerald-500/30 shrink-0 shadow-xs" 
                : "py-1 px-3 bg-white text-slate-600 font-semibold text-[11px] rounded-lg border border-slate-200 shrink-0 hover:bg-slate-100";
            monthsNavbar.appendChild(btnAll);

            semesterMonths[activeSemester].forEach(mIdx => {
                const btnMonth = document.createElement('button');
                btnMonth.type = 'button';
                btnMonth.innerText = namaBulan[mIdx];
                btnMonth.onclick = () => changeMonthFilter(mIdx);
                btnMonth.className = activeMonthFilter === mIdx 
                    ? "py-1 px-3 bg-emerald-50 text-emerald-700 font-extrabold text-[11px] rounded-lg border border-emerald-500/30 shrink-0 shadow-xs" 
                    : "py-1 px-3 bg-white text-slate-600 font-semibold text-[11px] rounded-lg border border-slate-200 shrink-0 hover:bg-slate-100";
                monthsNavbar.appendChild(btnMonth);
            });

            let filteredData = currentRawHistories.filter(item => {
                const itemDate = new Date(item.raw_date);
                const itemMonthIdx = itemDate.getMonth();
                const isInCorrectSemester = semesterMonths[activeSemester].includes(itemMonthIdx);
                if (!isInCorrectSemester) return false;
                if (activeMonthFilter !== 'all' && itemMonthIdx !== activeMonthFilter) return false;
                return true;
            });

            renderDataCards(filteredData);
        }

        function renderDataCards(dataList) {
            const container = document.getElementById('modalHistoryContainer');
            container.innerHTML = '';

            if (dataList.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-10 text-slate-400 text-xs">
                        <i class="fa-regular fa-folder-open text-xl block mb-2 text-slate-300"></i>
                        Tidak ada catatan kasus keterlambatan pada periode ini.
                    </div>`;
                return;
            }

            const finalGrouped = {};
            dataList.forEach(item => {
                const dateObj = new Date(item.raw_date);
                const keyName = namaBulan[dateObj.getMonth()] + " " + dateObj.getFullYear();
                if (!finalGrouped[keyName]) finalGrouped[keyName] = [];
                finalGrouped[keyName].push(item);
            });

            Object.keys(finalGrouped).forEach(monthTitle => {
                const blockSection = document.createElement('div');
                blockSection.className = 'space-y-2';
                blockSection.innerHTML = `
                    <div class="flex items-center gap-2 px-0.5">
                        <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider ${monthTitle}"></span>
                        <div class="h-[1px] bg-slate-200 flex-grow"></div>
                        <span class="text-[9px] bg-slate-100 text-slate-500 font-bold px-1.5 rounded">${finalGrouped[monthTitle].length}x</span>
                    </div>
                `;

                const itemsWrapper = document.createElement('div');
                itemsWrapper.className = 'space-y-2';

                finalGrouped[monthTitle].forEach(item => {
                    const row = document.createElement('div');
                    row.className = 'p-3 bg-slate-50 border border-slate-150 rounded-xl space-y-2.5 text-xs';
                    
                    let penangananHtml = '';
                    if (item.penanganan) {
                        penangananHtml = `
                            <div class="pt-2 border-t border-slate-200/60 bg-emerald-50/30 p-2 rounded-lg border border-dashed border-emerald-200">
                                <span class="text-[8px] font-bold text-emerald-600 uppercase block mb-0.5">Tindakan Anda (Wali Kelas):</span>
                                <p class="text-slate-700 font-semibold leading-relaxed">"${item.penanganan}"</p>
                            </div>`;
                    } else {
                        penangananHtml = `
                            <div class="pt-2 border-t border-slate-200/60 bg-white p-2 rounded-lg border border-dashed border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                                <div>
                                    <span class="text-[8px] font-bold text-slate-400 uppercase block mb-0.5">Tindakan Anda (Wali Kelas):</span>
                                    <p class="text-slate-400 italic font-medium">Belum ditindaklanjuti</p>
                                </div>
                                <button type="button" onclick="tindakTanggal('${item.id}')" 
                                        class="px-3 h-7 bg-emerald-600 hover:bg-emerald-700 text-white rounded-md text-[10px] font-bold shadow-xs transition-colors shrink-0 flex items-center justify-center gap-1">
                                    <i class="fa-solid fa-gavel text-[9px]"></i> Tindak Hari Ini
                                </button>
                            </div>`;
                    }

                    row.innerHTML = `
                        <div class="flex justify-between items-center font-bold">
                            <span class="text-slate-800 bg-white px-2 py-0.5 rounded-md border border-slate-200 shadow-xs">${item.tanggal}</span>
                            <span class="text-[9px] text-amber-600"><i class="fa-solid fa-bolt-lightning"></i> Data OSIS</span>
                        </div>
                        <div class="pt-1 px-1 grid grid-cols-1 gap-2">
                            <div>
                                <span class="text-[8px] font-bold text-slate-400 uppercase block mb-0.5">Alasan Utama (OSIS):</span>
                                <p class="text-slate-700 font-semibold">"${item.alasan}"</p>
                            </div>
                            <div class="bg-slate-100/60 p-1.5 rounded-md border border-slate-200/40">
                                <span class="text-[8px] font-bold text-slate-400 uppercase block mb-0.5">Keterangan Lain (OSIS):</span>
                                <p class="text-slate-600 font-medium italic">"${item.keterangan}"</p>
                            </div>
                        </div>
                        ${penangananHtml}
                    `;
                    itemsWrapper.appendChild(row);
                });

                blockSection.appendChild(itemsWrapper);
                container.appendChild(blockSection);
            });
        }

        function tindakTanggal(keterlambatanId) {
            window.location.href = `/walas/penanganan/${keterlambatanId}`;
        }

        function closeHistoryModal() {
            document.getElementById('historyModal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        window.onclick = function(event) {
            const modal = document.getElementById('historyModal');
            if (event.target === modal) closeHistoryModal();
        }
    </script>
</body>
</html>