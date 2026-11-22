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