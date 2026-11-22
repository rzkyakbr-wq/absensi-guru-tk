<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Absensi Guru TK</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 flex items-center justify-center min-h-screen p-4">
    <div class="bg-white p-8 rounded-3xl shadow-lg w-full max-w-sm">
        <h2 class="text-2xl font-bold text-center text-slate-800 mb-2">Selamat Datang</h2>
        <p class="text-xs text-center text-gray-500 mb-6">Aplikasi Absensi Harian Guru TK</p>

        @if(session('success'))
            <div class="bg-emerald-100 text-emerald-700 p-3 rounded-xl text-xs mb-4 text-center">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 text-red-600 p-3 rounded-xl text-xs mb-4 text-center">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="text-xs font-semibold text-gray-600">Email</label>
                <input type="email" name="email" required placeholder="Masukkan email" class="w-full mt-1 p-3 border rounded-xl text-sm focus:outline-teal-500">
            </div>
            <div>
                <label class="text-xs font-semibold text-gray-600">Kata Sandi</label>
                <input type="password" name="password" required placeholder="Masukkan kata sandi" class="w-full mt-1 p-3 border rounded-xl text-sm focus:outline-teal-500">
            </div>
            <button type="submit" class="w-full py-3 bg-teal-600 text-white rounded-xl font-bold shadow-md hover:bg-teal-700 transition">
                Masuk
            </button>
        </form>

        <p class="text-xs text-center text-gray-500 mt-6">
            Belum punya akun? <a href="{{ route('register') }}" class="text-teal-600 font-bold hover:underline">Daftar Baru</a>
        </p>
    </div>
</body>
</html>