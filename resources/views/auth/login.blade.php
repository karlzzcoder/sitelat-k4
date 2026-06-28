<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - SiTelat K4</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-700 min-h-screen flex flex-col items-center justify-center p-4">

    <div class="w-full max-w-md">
        <div class="bg-white border border-slate-200 rounded-2xl shadow-xl shadow-slate-100/50 p-8 md:p-10">
            
            <div class="text-center mb-8">
                <h1 class="text-3xl font-extrabold text-indigo-600 tracking-tight mb-2">SiTelat K4</h1>
                <p class="text-sm text-slate-400 px-4">Sistem Pencatatan Keterlambatan Siswa SMKN 4 Bogor</p>
            </div>
            
            @if ($errors->any())
                <div class="mb-5 p-4 bg-rose-50 border border-rose-200 text-rose-600 rounded-xl text-sm font-medium">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ url('/login') }}" method="POST" class="space-y-5">
                @csrf
                
                <div>
                    <label for="name" class="block text-sm font-semibold text-slate-600 mb-1.5">Nama Pengguna</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Contoh: OSIS Admin" autocomplete="off" required
                        class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 focus:outline-none transition-all duration-200">
                </div>
                
                <div>
                    <label for="password" class="block text-sm font-semibold text-slate-600 mb-1.5">Kata Sandi</label>
                    <input type="password" id="password" name="password" placeholder="••••••••" required
                        class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 focus:outline-none transition-all duration-200">
                </div>
                
                <div class="pt-2">
                    <button type="submit" 
                        class="w-full bg-indigo-600 hover:bg-indigo-700 active:scale-[0.99] text-white font-semibold text-sm rounded-xl py-3 px-4 shadow-lg shadow-indigo-600/10 hover:shadow-indigo-600/20 transition-all duration-200">
                        Masuk Aplikasi
                    </button>
                </div>
            </form>
        </div>
        
        <div class="text-center text-xs text-slate-400 mt-6">
            &copy; 2026 Tim Pengembang SiTelat K4
        </div>
    </div>

</body>
</html>