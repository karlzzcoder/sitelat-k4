<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Keterlambatan - SiTelat K4</title>
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
            <a href="{{ route('admin.history') }}" class="w-8 h-8 rounded-lg border border-slate-200 flex items-center justify-center text-slate-400 hover:text-slate-600 transition-colors">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-lg font-bold tracking-tight">Edit Keterlambatan</h1>
                <p class="text-[11px] text-slate-400">Koreksi kesalahan input data</p>
            </div>
        </div>
        <div>
            <span class="text-xs text-indigo-600 font-bold uppercase bg-indigo-50 px-3 py-1 rounded-full">OSIS</span>
        </div>
    </header>

    <main class="flex-grow max-w-2xl mx-auto w-full px-6 py-8">
        <div class="bg-white border border-slate-200 rounded-3xl p-8 shadow-xl shadow-slate-100/50">
            
            <div class="mb-6">
                <h2 class="text-xl font-bold text-slate-800">Koreksi Data</h2>
                <p class="text-xs text-slate-400 mt-1">Anda mengedit catatan keterlambatan untuk siswa di bawah ini.</p>
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

            <form action="{{ route('keterlambatan.update', $riwayat->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Info Siswa -->
                <div class="bg-slate-50 border border-slate-200 rounded-2xl p-5 flex items-center gap-4">
                    <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-600 font-bold text-sm">
                        {{ substr($riwayat->siswa->nama, 0, 2) }}
                    </div>
                    <div>
                        <h4 class="font-bold text-sm text-slate-800">{{ $riwayat->siswa->nama }}</h4>
                        <p class="text-xs text-slate-400">Kelas {{ $riwayat->siswa->kelas }} &bull; NISN: {{ $riwayat->siswa->nisn }}</p>
                    </div>
                </div>

                <!-- Input Tanggal -->
                <div>
                    <label for="tanggal" class="block text-sm font-semibold text-slate-600 mb-1.5">Tanggal Keterlambatan <span class="text-rose-500">*</span></label>
                    <input type="date" id="tanggal" name="tanggal" required value="{{ old('tanggal', $riwayat->tanggal) }}"
                        class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-sm text-slate-800 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 focus:outline-none transition-all duration-200">
                </div>

                <!-- Input Alasan / Keterangan (Textarea) -->
                <div>
                    <label for="alasan" class="block text-sm font-semibold text-slate-600 mb-1.5">Alasan / Keterangan Keterlambatan <span class="text-rose-500">*</span></label>
                    <textarea id="alasan" name="alasan" required rows="4"
                        class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-sm text-slate-800 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 focus:outline-none transition-all duration-200 resize-none">{{ old('alasan', $riwayat->alasan) }}</textarea>
                </div>

                <!-- Submit Button -->
                <div class="flex gap-4 pt-2">
                    <a href="{{ route('admin.history') }}" 
                       class="flex-1 bg-slate-100 hover:bg-slate-200 active:scale-[0.99] text-slate-600 text-center font-semibold text-sm rounded-xl py-3 px-4 transition-all duration-200">
                        Batal
                    </a>
                    <button type="submit" 
                        class="flex-1 bg-indigo-600 hover:bg-indigo-700 active:scale-[0.99] text-white font-semibold text-sm rounded-xl py-3 px-4 shadow-lg shadow-indigo-600/10 hover:shadow-indigo-600/20 transition-all duration-200">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </main>

    <footer class="text-center text-xs text-slate-400 py-6 border-t border-slate-100 bg-white">
        &copy; 2026 Tim Pengembang SiTelat K4
    </footer>

</body>
</html>
