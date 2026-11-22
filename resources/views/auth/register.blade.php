<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun Guru - TK Aisyiyah 06</title>

    <!-- FAVICON LOGO AISYIYAH -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md bg-white rounded-3xl shadow-xl p-8 border border-slate-100">
        
        <!-- Header -->
        <div class="text-center mb-6">
            <div class="w-16 h-16 bg-teal-50 border border-teal-100 rounded-2xl flex items-center justify-center text-teal-600 text-2xl mx-auto mb-3 shadow-sm">
                <i class="fa-solid fa-user-plus"></i>
            </div>
            <h2 class="text-xl font-black text-slate-800">Daftar Akun Guru</h2>
            <p class="text-xs text-slate-400 mt-1">TK Aisyiyah Bustanul Athfal 06</p>
        </div>

        <!-- Pesan Error Validasi -->
        @if ($errors->any())
            <div class="bg-rose-50 border border-rose-100 text-rose-600 text-xs rounded-xl p-3 mb-4">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form Registrasi -->
        <form action="{{ route('register.post') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Input Nama Lengkap -->
            <div>
                <label for="name" class="block text-xs font-bold text-slate-700 mb-1">Nama Lengkap</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required placeholder="Masukkan nama lengkap" 
                       class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-xs focus:outline-none focus:border-teal-600 text-slate-800">
            </div>

            <!-- Input NIP / NIK -->
            <div>
                <label for="nip_nik" class="block text-xs font-bold text-slate-700 mb-1">NIP / NIK</label>
                <input type="text" id="nip_nik" name="nip_nik" value="{{ old('nip_nik') }}" placeholder="Masukkan NIP atau NIK (opsional)" 
                       class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-xs focus:outline-none focus:border-teal-600 text-slate-800">
            </div>

            <!-- Input Email -->
            <div>
                <label for="email" class="block text-xs font-bold text-slate-700 mb-1">Alamat Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="contoh@email.com" 
                       class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-xs focus:outline-none focus:border-teal-600 text-slate-800">
            </div>

            <!-- Input Password -->
            <div>
                <label for="password" class="block text-xs font-bold text-slate-700 mb-1">Password</label>
                <input type="password" id="password" name="password" required placeholder="Minimal 6 karakter" 
                       class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-xs focus:outline-none focus:border-teal-600 text-slate-800">
            </div>

            <!-- Tombol Submit -->
            <button type="submit" class="w-full py-3 bg-teal-600 hover:bg-teal-700 text-white font-bold rounded-xl text-xs transition shadow-sm mt-2">
                Daftar Akun Guru
            </button>
        </form>

        <!-- Footer Link ke Login -->
        <p class="text-xs text-center text-slate-400 mt-6">
            Sudah punya akun? <a href="{{ route('login') }}" class="text-teal-600 font-bold hover:underline">Masuk Sekarang</a>
        </p>

    </div>

</body>
</html>