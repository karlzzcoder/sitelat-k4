<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Keterlambatan - SiTelat K4</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- jQuery and Select2 -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        /* Style Select2 to match modern design */
        .select2-container .select2-selection--single {
            height: 46px !important;
            border: 1px solid #cbd5e1 !important;
            border-radius: 0.75rem !important;
            background-color: #f8fafc !important;
            display: flex;
            align-items: center;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #1e293b !important;
            font-size: 0.875rem !important;
            padding-left: 1rem !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 44px !important;
            right: 10px !important;
        }
        .select2-dropdown {
            border: 1px solid #e2e8f0 !important;
            border-radius: 0.75rem !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05) !important;
            overflow: hidden;
            font-size: 0.875rem !important;
        }
        .select2-results__option--highlighted[aria-selected] {
            background-color: #4f46e5 !important;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col">

    <!-- Header Navigation -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-30 px-6 py-4 flex justify-between items-center shadow-sm">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.dashboard') }}" class="w-8 h-8 rounded-lg border border-slate-200 flex items-center justify-center text-slate-400 hover:text-slate-600 transition-colors">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-lg font-bold tracking-tight">Input Keterlambatan</h1>
                <p class="text-[11px] text-slate-400">Catat keterlambatan murid baru</p>
            </div>
        </div>
        <div class="flex items-center gap-4">
            <span class="text-xs text-indigo-600 font-bold uppercase bg-indigo-50 px-3 py-1 rounded-full">OSIS</span>
        </div>
    </header>

    <main class="flex-grow max-w-2xl mx-auto w-full px-6 py-8">
        <div class="bg-white border border-slate-200 rounded-3xl p-8 shadow-xl shadow-slate-100/50">
            
            <div class="mb-6">
                <h2 class="text-xl font-bold text-slate-800">Form Keterlambatan</h2>
                <p class="text-xs text-slate-400 mt-1">Gunakan filter Kelas dan Jurusan untuk mempermudah pencarian nama siswa di database.</p>
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

            <form action="{{ route('keterlambatan.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Filters & Autocomplete -->
                <div class="bg-slate-50 border border-slate-200 rounded-2xl p-5 space-y-4">
                    <span class="text-[10px] font-bold text-indigo-600 uppercase tracking-wider block"><i class="fa-solid fa-filter mr-1"></i> Filter Pencarian Siswa</span>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="kelas_filter" class="block text-xs font-semibold text-slate-600 mb-1.5">Pilih Tingkat / Kelas</label>
                            <select id="kelas_filter" class="w-full bg-white border border-slate-300 rounded-xl px-3 py-2.5 text-xs font-medium text-slate-700 focus:ring-2 focus:ring-indigo-600 outline-none transition-all">
                                <option value="Semua">Semua Tingkat</option>
                                <option value="X">Kelas X</option>
                                <option value="XI">Kelas XI</option>
                                <option value="XII">Kelas XII</option>
                            </select>
                        </div>
                        <div>
                            <label for="jurusan_filter" class="block text-xs font-semibold text-slate-600 mb-1.5">Pilih Jurusan</label>
                            <select id="jurusan_filter" class="w-full bg-white border border-slate-300 rounded-xl px-3 py-2.5 text-xs font-medium text-slate-700 focus:ring-2 focus:ring-indigo-600 outline-none transition-all">
                                <option value="Semua">Semua Jurusan</option>
                                <option value="PPLG">PPLG (RPL)</option>
                                <option value="TJKT">TJKT</option>
                                <option value="TO">TO</option>
                                <option value="TPFL">TPFL</option>
                            </select>
                        </div>
                    </div>

                    <!-- Autocomplete Siswa Input -->
                    <div>
                        <label for="siswa_id" class="block text-xs font-semibold text-slate-600 mb-1.5">Nama Siswa <span class="text-rose-500">*</span></label>
                        <select id="siswa_id" name="siswa_id" required class="w-full"></select>
                        <p class="text-[10px] text-slate-400 mt-1">Ketik minimal 1 huruf nama siswa (misal "S") untuk memicu pencarian autocomplete.</p>
                    </div>
                </div>

                <!-- Input Tanggal -->
                <div>
                    <label for="tanggal" class="block text-sm font-semibold text-slate-600 mb-1.5">Tanggal Keterlambatan <span class="text-rose-500">*</span></label>
                    <input type="date" id="tanggal" name="tanggal" required value="{{ date('Y-m-d') }}"
                        class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-sm text-slate-800 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 focus:outline-none transition-all duration-200">
                </div>

                <!-- Input Alasan / Keterangan (Textarea) -->
                <div>
                    <label for="alasan" class="block text-sm font-semibold text-slate-600 mb-1.5">Alasan / Keterangan Keterlambatan <span class="text-rose-500">*</span></label>
                    <textarea id="alasan" name="alasan" placeholder="Tulis alasan keterlambatan secara detail di sini..." required rows="4"
                        class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 focus:outline-none transition-all duration-200 resize-none"></textarea>
                </div>

                <!-- Submit Button -->
                <div class="flex gap-4 pt-2">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex-1 bg-slate-100 hover:bg-slate-200 active:scale-[0.99] text-slate-600 text-center font-semibold text-sm rounded-xl py-3 px-4 transition-all duration-200">
                        Batal
                    </a>
                    <button type="submit" 
                        class="flex-1 bg-indigo-600 hover:bg-indigo-700 active:scale-[0.99] text-white font-semibold text-sm rounded-xl py-3 px-4 shadow-lg shadow-indigo-600/10 hover:shadow-indigo-600/20 transition-all duration-200">
                        Kirim Data
                    </button>
                </div>
            </form>
        </div>
    </main>

    <footer class="text-center text-xs text-slate-400 py-6 border-t border-slate-100 bg-white">
        &copy; 2026 Tim Pengembang SiTelat K4
    </footer>

    <!-- Select2 Initializer -->
    <script>
        $(document).ready(function() {
            // Inisialisasi Select2 AJAX
            $('#siswa_id').select2({
                ajax: {
                    url: '{{ route("siswa.search") }}',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            q: params.term, // keyword pencarian
                            kelas: $('#kelas_filter').val(),
                            jurusan: $('#jurusan_filter').val()
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data.results
                        };
                    },
                    cache: true
                },
                placeholder: 'Ketik nama siswa...',
                minimumInputLength: 1,
                language: {
                    inputTooShort: function () {
                        return "Ketik minimal 1 karakter...";
                    },
                    searching: function () {
                        return "Sedang mencari data...";
                    },
                    noResults: function () {
                        return "Siswa tidak ditemukan.";
                    }
                }
            });

            // Reset Select2 saat filter kelas/jurusan diubah
            $('#kelas_filter, #jurusan_filter').change(function() {
                $('#siswa_id').val(null).trigger('change');
            });
        });
    </script>
</body>
</html>