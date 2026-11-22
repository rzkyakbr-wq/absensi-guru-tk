<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Guru Baru - TK</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 flex items-center justify-center min-h-screen p-4">
    <div class="bg-white p-8 rounded-3xl shadow-lg w-full max-w-sm">
        <h2 class="text-2xl font-bold text-center text-slate-800 mb-2">Pendaftaran Guru</h2>
        <p class="text-xs text-center text-gray-500 mb-6">Buat akun untuk melakukan absensi</p>

        <form action="{{ route('register') }}" method="POST" class="space-y-3">
            @csrf
            <div>
                <label class="text-xs font-semibold text-gray-600">Nama Lengkap</label>
                <input type="text" name="name" required placeholder="Siti Rahma, S.Pd." class="w-full mt-1 p-2.5 border rounded-xl text-sm focus:outline-teal-500">
            </div>
            <div>
                <label class="text-xs font-semibold text-gray-600">NIP / NIK</label>
                <input type="text" name="nip_nik" required placeholder="199203xxxx" class="w-full mt-1 p-2.5 border rounded-xl text-sm focus:outline-teal-500">
            </div>
            <div>
                <label class="text-xs font-semibold text-gray-600">Email</label>
                <input type="email" name="email" required placeholder="guru@sekolah.sch.id" class="w-full mt-1 p-2.5 border rounded-xl text-sm focus:outline-teal-500">
            </div>
            <div>
                <label class="text-xs font-semibold text-gray-600">Kata Sandi</label>
                <input type="password" name="password" required placeholder="Minimal 6 karakter" class="w-full mt-1 p-2.5 border rounded-xl text-sm focus:outline-teal-500">
            </div>
            <button type="submit" class="w-full py-3 bg-teal-600 text-white rounded-xl font-bold shadow-md hover:bg-teal-700 transition mt-2">
                Daftar & Lanjut Login
            </button>
        </form>

        <p class="text-xs text-center text-gray-500 mt-4">
            Sudah punya akun? <a href="{{ route('login') }}" class="text-teal-600 font-bold hover:underline">Kembali ke Login</a>
        </p>
    </div>
</body>
</html>