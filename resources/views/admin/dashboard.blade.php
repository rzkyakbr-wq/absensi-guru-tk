<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Kehadiran Guru - Admin TK Aisyiyah 06</title>
    
    <!-- FAVICON LOGO AISYIYAH -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-100 min-h-screen p-4 md:p-8">

    <div class="max-w-6xl mx-auto bg-white rounded-3xl shadow-xl p-6 md:p-8">
        
        <!-- Header Top Bar -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-100 pb-6">
            <div>
                <h1 class="text-2xl font-black text-slate-800">Rekap Kehadiran Guru TK</h1>
                <p class="text-xs font-semibold text-slate-400 mt-1">Periode: {{ \Carbon\Carbon::parse($startDate)->isoFormat('D MMMM Y') }} s/d {{ \Carbon\Carbon::parse($endDate)->isoFormat('D MMMM Y') }}</p>
            </div>

            <div class="flex items-center gap-3">
                <!-- Tombol Export Excel Berdasarkan Filter -->
                <a href="{{ route('admin.exportExcel', ['guru_id' => $selectedGuru, 'start_date' => $startDate, 'end_date' => $endDate]) }}" 
                   class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs transition flex items-center gap-2 shadow-sm">
                    <i class="fa-solid fa-file-excel"></i> Download Excel (.xlsx)
                </a>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-2.5 bg-red-50 hover:bg-red-100 text-red-600 font-bold rounded-xl text-xs transition flex items-center gap-2">
                        <i class="fa-solid fa-right-from-bracket"></i> Keluar
                    </button>
                </form>
            </div>
        </div>

        <!-- Alert Notifikasi -->
        @if(session('success'))
            <div class="my-4 bg-emerald-50 border border-emerald-100 text-emerald-800 text-xs rounded-2xl p-4 flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
                    <span class="font-bold">{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-slate-400 hover:text-slate-600">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="my-4 bg-rose-50 border border-rose-100 text-rose-800 text-xs rounded-2xl p-4 flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-triangle-exclamation text-rose-600 text-base"></i>
                    <span class="font-bold">{{ session('error') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-slate-400 hover:text-slate-600">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif

        <!-- Card Kelola Akun Guru & Reset Password -->
        <div class="my-6 bg-slate-50 p-5 rounded-2xl border border-slate-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-teal-100 text-teal-700 flex items-center justify-center text-base shadow-sm">
                        <i class="fa-solid fa-users-gear"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-slate-800">Kelola Akun Guru & Reset Password</h2>
                        <p class="text-[11px] text-slate-400">Admin dapat mengatur ulang password guru yang lupa akun.</p>
                    </div>
                </div>
                <button type="button" onclick="toggleGuruTable()" class="px-3 py-1.5 bg-white border border-slate-200 rounded-xl text-xs font-bold text-teal-600 hover:bg-teal-50 transition flex items-center gap-2 shadow-sm">
                    <span id="toggleText">Tampilkan Guru ({{ count($allGurus) }})</span>
                    <i id="toggleIcon" class="fa-solid fa-chevron-down text-[10px] transition-transform duration-200"></i>
                </button>
            </div>

            <!-- Tabel Daftar Guru (Toggleable) -->
            <div id="guruTableWrapper" class="hidden overflow-x-auto border border-slate-200 rounded-xl bg-white mt-4 shadow-sm">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-100 text-[11px] font-bold text-slate-500 uppercase border-b border-slate-200">
                            <th class="p-3">Nama Guru</th>
                            <th class="p-3">Email</th>
                            <th class="p-3">NIP / NIK</th>
                            <th class="p-3 text-center">Reset Password Baru</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($allGurus as $guru)
                            <tr class="hover:bg-slate-50/70 transition">
                                <td class="p-3 font-bold text-slate-800">{{ $guru->name }}</td>
                                <td class="p-3 text-slate-600">{{ $guru->email }}</td>
                                <td class="p-3 text-slate-500">{{ $guru->nip_nik ?? '-' }}</td>
                                <td class="p-3">
                                    <form action="{{ route('admin.resetGuruPassword', $guru->id) }}" method="POST" 
                                          onsubmit="return confirm('Yakin ingin mereset password akun guru {{ $guru->name }}?')"
                                          class="flex items-center justify-center gap-2">
                                        @csrf
                                        <input type="text" name="new_password" value="password123" required 
                                               placeholder="Password baru"
                                               class="px-3 py-1.5 border border-slate-200 rounded-lg text-xs font-medium text-slate-700 bg-white w-36 focus:outline-none focus:border-teal-600">
                                        <button type="submit" class="px-3 py-1.5 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-lg text-xs transition flex items-center gap-1.5 shadow-sm">
                                            <i class="fa-solid fa-key text-[10px]"></i> Reset
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-4 text-center text-slate-400">Belum ada akun guru terdaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <script>
            function toggleGuruTable() {
                const el = document.getElementById('guruTableWrapper');
                const icon = document.getElementById('toggleIcon');
                const text = document.getElementById('toggleText');
                if (el.classList.contains('hidden')) {
                    el.classList.remove('hidden');
                    icon.classList.add('rotate-180');
                    text.innerText = 'Sembunyikan Guru';
                } else {
                    el.classList.add('hidden');
                    icon.classList.remove('rotate-180');
                    text.innerText = 'Tampilkan Guru ({{ count($allGurus) }})';
                }
            }
        </script>

        <!-- Form Filter Guru & Tanggal -->
        <div class="my-6 bg-slate-50 p-4 rounded-2xl border border-slate-100">
            <form action="{{ route('admin.dashboard') }}" method="GET" class="flex flex-wrap items-end gap-3">
                
                <!-- Filter Guru -->
                <div class="flex flex-col gap-1">
                    <label class="text-[11px] font-bold text-slate-500 uppercase">Pilih Guru</label>
                    <select name="guru_id" class="px-3 py-2 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 bg-white focus:outline-none focus:border-teal-600">
                        <option value="">-- Semua Guru --</option>
                        @foreach($allGurus as $g)
                            <option value="{{ $g->id }}" {{ $selectedGuru == $g->id ? 'selected' : '' }}>
                                {{ $g->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Tanggal Dari -->
                <div class="flex flex-col gap-1">
                    <label class="text-[11px] font-bold text-slate-500 uppercase">Dari Tanggal</label>
                    <input type="date" name="start_date" value="{{ $startDate }}" class="px-3 py-2 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 bg-white focus:outline-none focus:border-teal-600">
                </div>

                <!-- Filter Tanggal Sampai -->
                <div class="flex flex-col gap-1">
                    <label class="text-[11px] font-bold text-slate-500 uppercase">Sampai Tanggal</label>
                    <input type="date" name="end_date" value="{{ $endDate }}" class="px-3 py-2 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 bg-white focus:outline-none focus:border-teal-600">
                </div>

                <!-- Tombol Submit Filter -->
                <button type="submit" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-xs font-bold transition flex items-center gap-2">
                    <i class="fa-solid fa-filter"></i> Tampilkan
                </button>
            </form>
        </div>

        <!-- Tabel Rekap Absensi -->
        <div class="overflow-x-auto rounded-2xl border border-slate-100">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-[11px] font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100">
                        <th class="p-4">Tanggal</th>
                        <th class="p-4">Nama Guru</th>
                        <th class="p-4">NIP / NIK</th>
                        <th class="p-4">Jam Masuk</th>
                        <th class="p-4">Jam Pulang</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-center">Ubah Status (Admin)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($attendances as $item)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="p-4 font-semibold text-slate-600">{{ \Carbon\Carbon::parse($item->date)->isoFormat('D MMM Y') }}</td>
                            <td class="p-4 font-bold text-slate-800">{{ $item->user->name ?? '-' }}</td>
                            <td class="p-4 text-slate-500 font-medium">{{ $item->user->nip_nik ?? '-' }}</td>
                            <td class="p-4 text-emerald-600 font-bold">{{ $item->time_in ?? '-' }}</td>
                            <td class="p-4 text-slate-600 font-bold">{{ $item->time_out ?? '-' }}</td>
                            <td class="p-4">
                                @if($item->status == 'Hadir')
                                    <span class="bg-emerald-100 text-emerald-700 px-2.5 py-1 rounded-full text-[10px] font-bold">Hadir</span>
                                @elseif($item->status == 'Terlambat')
                                    <span class="bg-amber-100 text-amber-700 px-2.5 py-1 rounded-full text-[10px] font-bold">Terlambat</span>
                                @else
                                    <span class="bg-rose-100 text-rose-700 px-2.5 py-1 rounded-full text-[10px] font-bold">{{ $item->status }}</span>
                                @endif
                            </td>
                            <td class="p-4">
                                <form action="{{ route('admin.updateStatus', $item->user_id) }}" method="POST" class="flex items-center justify-center gap-2">
                                    @csrf
                                    <input type="hidden" name="date" value="{{ $item->date }}">
                                    <select name="status" class="px-3 py-1.5 border border-slate-200 rounded-lg text-xs font-medium text-slate-700 bg-white">
                                        <option value="Hadir" {{ $item->status == 'Hadir' ? 'selected' : '' }}>Hadir</option>
                                        <option value="Terlambat" {{ $item->status == 'Terlambat' ? 'selected' : '' }}>Terlambat</option>
                                        <option value="Izin" {{ $item->status == 'Izin' ? 'selected' : '' }}>Izin</option>
                                        <option value="Sakit" {{ $item->status == 'Sakit' ? 'selected' : '' }}>Sakit</option>
                                        <option value="Tanpa Keterangan" {{ $item->status == 'Tanpa Keterangan' ? 'selected' : '' }}>Alpa</option>
                                    </select>
                                    <button type="submit" class="px-3 py-1.5 bg-teal-600 hover:bg-teal-700 text-white font-bold rounded-lg text-xs transition">
                                        Simpan
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-slate-400 font-medium">
                                Tidak ada data absensi untuk filter ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

</body>
</html>