<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Penanganan Siswa - SiTelat K4</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; -webkit-tap-highlight-color: transparent; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col">

    <header class="bg-white border-b border-slate-100 sticky top-0 z-30 px-4 py-3.5 flex justify-between items-center shadow-sm">
        <div class="flex items-center gap-3">
            <a href="{{ route('walas.dashboard') }}" class="w-9 h-9 rounded-xl border border-slate-200 flex items-center justify-center text-slate-400 active:bg-slate-50 active:text-slate-700 transition-colors">
                <i class="fa-solid fa-arrow-left text-xs"></i>
            </a>
            <div>
                <h1 class="text-sm font-extrabold tracking-tight">Form Penanganan</h1>
                <p class="text-[10px] text-slate-400 font-medium">Tindak lanjut keterlambatan</p>
            </div>
        </div>
        <div>
            <span class="text-[9px] text-emerald-600 font-extrabold uppercase bg-emerald-50 px-2.5 py-1 rounded-md tracking-wider">Walas</span>
        </div>
    </header>

    <main class="flex-grow max-w-md mx-auto w-full px-4 py-5">
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm space-y-5">
            
            <div>
                <h2 class="text-base font-extrabold text-slate-900 tracking-tight">Input Tindakan</h2>
                <p class="text-[11px] text-slate-400 font-medium mt-0.5">Catat tindakan agar terekam dalam database sistem.</p>
            </div>

            @if ($errors->any())
                <div class="p-3 bg-rose-50 border border-rose-150 text-rose-700 rounded-xl text-xs font-semibold">
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('walas.penanganan.update', $keterlambatan->id) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                <div class="bg-slate-50 border border-slate-150 rounded-xl p-3.5 space-y-3">
                    <span class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider block">Identitas Murid & Kasus</span>
                    
                    <div class="grid grid-cols-2 gap-x-3 gap-y-2.5 text-xs">
                        <div class="col-span-2">
                            <span class="text-slate-400 text-[10px] block font-medium">Nama Lengkap</span>
                            <span class="font-bold text-slate-800 text-sm truncate block">{{ $keterlambatan->siswa->nama }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 text-[10px] block font-medium">NISN</span>
                            <span class="font-semibold text-slate-700 tracking-wide">{{ $keterlambatan->siswa->nisn }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 text-[10px] block font-medium">Kelas</span>
                            <span class="font-bold text-slate-700">{{ $keterlambatan->siswa->kelas }}</span>
                        </div>
                        <div class="col-span-2 pt-1 border-t border-slate-200/60">
                            <span class="text-slate-400 text-[10px] block font-medium">Tanggal Keterlambatan</span>
                            <span class="font-bold text-slate-800">{{ date('d-m-Y', strtotime($keterlambatan->tanggal)) }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 text-[10px] block font-medium">Alasan Utama (OSIS)</span>
                            <span class="font-semibold text-slate-700 italic">"{{ $keterlambatan->alasan }}"</span>
                        </div>
                        <div>
                            <span class="text-slate-400 text-[10px] block font-medium">Keterangan Lain (OSIS)</span>
                            <span class="font-semibold text-slate-600 italic">"{{ $keterlambatan->keterangan ?? '-' }}"</span>
                        </div>
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label for="penanganan" class="block text-xs font-bold text-slate-700">
                        Tindakan / Pembinaan <span class="text-rose-500">*</span>
                    </label>
                    <textarea id="penanganan" name="penanganan" required rows="4"
                        placeholder="Contoh: Pemanggilan orang tua ke sekolah atau pemberian surat peringatan pertama..."
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs text-slate-800 placeholder-slate-400 focus:bg-white focus:border-emerald-600 focus:ring-4 focus:ring-emerald-600/5 focus:outline-none transition-all resize-none leading-relaxed">{{ $keterlambatan->penanganan }}</textarea>
                </div>

                <div class="flex flex-col gap-2.5 pt-1">
                    <button type="submit" 
                        class="w-full h-10 bg-emerald-600 hover:bg-emerald-700 active:scale-[0.98] text-white font-extrabold text-xs rounded-xl shadow-md shadow-emerald-100 transition-all flex items-center justify-center gap-1.5">
                        <i class="fa-solid fa-floppy-disk text-sm"></i> Simpan Penanganan
                    </button>
                    
                    <a href="{{ route('walas.dashboard') }}" 
                       class="w-full h-9 bg-slate-100 active:bg-slate-200 active:scale-[0.98] text-slate-500 text-center font-bold text-xs rounded-xl flex items-center justify-center transition-all">
                        Batalkan
                    </a>
                </div>
            </form>
        </div>
    </main>

    <footer class="text-center text-[9px] text-slate-400 py-4 border-t border-slate-100 bg-white">
        &copy; 2026 Tim Pengembang SiTelat K4
    </footer>

</body>
</html>