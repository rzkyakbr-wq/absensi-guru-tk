<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - TK Aisyiyah Bustanul Athfal 06</title>

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
                <i class="fa-solid fa-key"></i>
            </div>
            <h2 class="text-xl font-black text-slate-800">Reset Password</h2>
            <p class="text-xs text-slate-400 mt-1">TK Aisyiyah Bustanul Athfal 06</p>
        </div>

        <!-- Info Card -->
        <div class="bg-amber-50 border border-amber-100 text-amber-800 text-[11px] rounded-xl p-3 mb-4 flex items-start gap-2">
            <i class="fa-solid fa-circle-info text-amber-500 mt-0.5"></i>
            <span>Masukkan Email dan verifikasi dengan NIP/NIK atau Nama Lengkap terdaftar untuk mengatur ulang password.</span>
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

        <!-- Form Reset Password -->
        <form action="{{ route('password.update') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Alamat Email -->
            <div>
                <label for="email" class="block text-xs font-bold text-slate-700 mb-1">Alamat Email Terdaftar</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="contoh@email.com" 
                       class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-xs focus:outline-none focus:border-teal-600 text-slate-800">
            </div>

            <!-- Verifikasi Identitas (NIP/NIK atau Nama) -->
            <div>
                <label for="verification" class="block text-xs font-bold text-slate-700 mb-1">Verifikasi: NIP / NIK atau Nama Lengkap</label>
                <input type="text" id="verification" name="verification" value="{{ old('verification') }}" required placeholder="Masukkan NIP, NIK, atau Nama Anda" 
                       class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-xs focus:outline-none focus:border-teal-600 text-slate-800">
                <p class="text-[10px] text-slate-400 mt-1">Sesuai data yang Anda gunakan saat mendaftar.</p>
            </div>

            <!-- Password Baru -->
            <div>
                <label for="password" class="block text-xs font-bold text-slate-700 mb-1">Password Baru</label>
                <input type="password" id="password" name="password" required placeholder="Minimal 6 karakter" 
                       class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-xs focus:outline-none focus:border-teal-600 text-slate-800">
            </div>

            <!-- Konfirmasi Password Baru -->
            <div>
                <label for="password_confirmation" class="block text-xs font-bold text-slate-700 mb-1">Konfirmasi Password Baru</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="Ulangi password baru" 
                       class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-xs focus:outline-none focus:border-teal-600 text-slate-800">
            </div>

            <!-- Tombol Submit -->
            <button type="submit" class="w-full py-3 bg-teal-600 hover:bg-teal-700 text-white font-bold rounded-xl text-xs transition shadow-sm mt-2">
                Simpan Password Baru
            </button>
        </form>

        <!-- Footer Link ke Login -->
        <p class="text-xs text-center text-slate-400 mt-6">
            Sudah ingat password Anda? <a href="{{ route('login') }}" class="text-teal-600 font-bold hover:underline">Kembali Masuk</a>
        </p>

        <p class="text-[11px] text-center text-slate-400 mt-3 pt-3 border-t border-slate-100">
            Masih terkendala? Anda juga bisa meminta <span class="font-semibold text-slate-600">Admin TK</span> untuk mereset password akun Anda langsung dari dashboard Admin.
        </p>

    </div>

</body>
</html>
