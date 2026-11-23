<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Guru - TK Aisyiyah Bustanul Athfal 06</title>

    <!-- FAVICON LOGO AISYIYAH -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-100 min-h-screen flex justify-center">
    <div class="w-full max-w-md bg-white min-h-screen shadow-xl relative pb-24">
        
        <!-- ================= TAB 1: BERANDA ================= -->
        <div id="tab-beranda" class="tab-content">
            <!-- Header Profil Atas -->
            <div class="p-5 flex items-center justify-between border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="relative w-12 h-12 rounded-full overflow-hidden bg-teal-600 text-white font-bold flex items-center justify-center text-lg shadow-sm border border-teal-200">
                        <img id="avatar-preview-header" src="{{ Auth::user()->profile_photo ? asset('storage/' . Auth::user()->profile_photo) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=0D9488&color=fff' }}" alt="Foto Profil" class="w-full h-full object-cover">
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Selamat datang,</p>
                        <h3 class="font-bold text-base text-slate-800">{{ Auth::user()->name }}</h3>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" title="Keluar" class="w-10 h-10 rounded-full bg-red-50 text-red-500 hover:bg-red-100 flex items-center justify-center transition">
                        <i class="fa-solid fa-right-from-bracket"></i>
                    </button>
                </form>
            </div>

            <div class="p-5 space-y-4">
                <!-- Live Time Card -->
                <div class="bg-teal-50/60 border border-teal-100 rounded-3xl p-5 text-center">
                    <p class="text-[11px] font-bold text-teal-700 tracking-wider uppercase mb-1">TK AISYIYAH BUSTANUL ATHFAL 06</p>
                    <h1 id="live-clock" class="text-4xl font-black text-slate-800 tracking-tight my-1">--:--:--</h1>
                    <p class="text-xs text-slate-500 font-medium">{{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }}</p>
                </div>

                <!-- 2 Pilihan Absen (Masuk & Pulang) -->
                <div class="grid grid-cols-2 gap-3">
                    <!-- Card Absen Masuk -->
                    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between">
                        <div>
                            <div class="flex items-center gap-2 text-teal-700 text-xs font-bold mb-1">
                                <i class="fa-solid fa-right-to-bracket"></i>
                                <span>Absen Masuk</span>
                            </div>
                            <p class="text-xs font-bold {{ $attendance && $attendance->time_in ? 'text-emerald-600' : 'text-gray-400' }}">
                                {{ $attendance && $attendance->time_in ? $attendance->time_in . ' (' . $attendance->status . ')' : 'Belum absen' }}
                            </p>
                        </div>
                        <button onclick="openCameraScanner('masuk')" class="mt-3 w-full py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-xl text-xs font-bold shadow-sm transition flex items-center justify-center gap-1.5">
                            <i class="fa-solid fa-camera"></i> Scan Masuk
                        </button>
                    </div>

                    <!-- Card Absen Pulang -->
                    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between">
                        <div>
                            <div class="flex items-center gap-2 text-teal-700 text-xs font-bold mb-1">
                                <i class="fa-solid fa-right-from-bracket"></i>
                                <span>Absen Pulang</span>
                            </div>
                            <p class="text-xs font-bold {{ $attendance && $attendance->time_out ? 'text-emerald-600' : 'text-gray-400' }}">
                                {{ $attendance && $attendance->time_out ? $attendance->time_out : 'Belum absen' }}
                            </p>
                        </div>
                        <button onclick="openCameraScanner('pulang')" class="mt-3 w-full py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-xs font-bold shadow-sm transition flex items-center justify-center gap-1.5">
                            <i class="fa-solid fa-camera"></i> Scan Pulang
                        </button>
                    </div>
                </div>

                <!-- Layanan Guru -->
                <div class="pt-2">
                    <h4 class="font-bold text-slate-800 text-sm mb-3">Layanan Guru</h4>
                    <div class="grid grid-cols-3 gap-3 text-center">
                        <!-- Izin/Sakit via WA -->
                        <a href="https://wa.me/62?text=Halo%20Admin%20TK%20Aisyiyah%2006,%20saya%20{{ urlencode(Auth::user()->name) }}%20ingin%20mengajukan%20Izin/Sakit%20hari%20ini." target="_blank" class="flex flex-col items-center gap-2 group">
                            <div class="w-14 h-14 rounded-2xl bg-amber-50 group-hover:bg-amber-100 text-amber-600 flex items-center justify-center text-xl shadow-sm border border-amber-100 transition">
                                <i class="fa-solid fa-file-medical"></i>
                            </div>
                            <span class="text-xs text-slate-600 font-semibold">Izin/Sakit</span>
                        </a>

                        <!-- Ke Tab Riwayat -->
                        <div onclick="switchTab('riwayat')" class="flex flex-col items-center gap-2 cursor-pointer group">
                            <div class="w-14 h-14 rounded-2xl bg-teal-50 group-hover:bg-teal-100 text-teal-600 flex items-center justify-center text-xl shadow-sm border border-teal-100 transition">
                                <i class="fa-regular fa-clock"></i>
                            </div>
                            <span class="text-xs text-slate-600 font-semibold">Riwayat Absen</span>
                        </div>

                        <!-- Ke Tab Profil -->
                        <div onclick="switchTab('profil')" class="flex flex-col items-center gap-2 cursor-pointer group">
                            <div class="w-14 h-14 rounded-2xl bg-indigo-50 group-hover:bg-indigo-100 text-indigo-600 flex items-center justify-center text-xl shadow-sm border border-indigo-100 transition">
                                <i class="fa-regular fa-user"></i>
                            </div>
                            <span class="text-xs text-slate-600 font-semibold">Profil</span>
                        </div>
                    </div>
                </div>

                <!-- Ringkasan Perbulan -->
                <div class="bg-white p-5 rounded-3xl border border-slate-100 shadow-sm mt-4">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="font-bold text-slate-800 text-sm">Ringkasan {{ \Carbon\Carbon::now()->isoFormat('MMMM Y') }}</h4>
                        <span onclick="switchTab('riwayat')" class="text-xs text-teal-600 font-semibold cursor-pointer">Lihat semua</span>
                    </div>
                    <div class="grid grid-cols-3 gap-2 text-center">
                        <div class="bg-slate-50 p-3 rounded-2xl">
                            <h3 class="text-xl font-black text-emerald-600">{{ $totalHadir ?? 0 }}</h3>
                            <p class="text-[11px] text-gray-400 font-semibold mt-0.5">Hadir</p>
                        </div>
                        <div class="bg-slate-50 p-3 rounded-2xl">
                            <h3 class="text-xl font-black text-amber-500">{{ $totalTerlambat ?? 0 }}</h3>
                            <p class="text-[11px] text-gray-400 font-semibold mt-0.5">Terlambat</p>
                        </div>
                        <div class="bg-slate-50 p-3 rounded-2xl">
                            <h3 class="text-xl font-black text-rose-500">{{ $totalTidakHadir ?? 0 }}</h3>
                            <p class="text-[11px] text-gray-400 font-semibold mt-0.5">Izin / Sakit</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================= TAB 2: RIWAYAT ABSENSI ================= -->
        <div id="tab-riwayat" class="tab-content hidden p-5">
            <h2 class="text-lg font-bold text-slate-800 mb-4">Riwayat Absensi Saya</h2>
            
            @if(isset($attendancesHistory) && $attendancesHistory->count() > 0)
                <div class="space-y-3">
                    @foreach($attendancesHistory as $item)
                        <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
                            <div>
                                <p class="text-xs font-bold text-slate-800">
                                    {{ \Carbon\Carbon::parse($item->date)->isoFormat('D MMMM Y') }}
                                </p>
                                <div class="flex gap-3 text-[11px] text-gray-500 mt-1">
                                    <span><i class="fa-solid fa-right-to-bracket text-emerald-600"></i> {{ $item->time_in ?? '-' }}</span>
                                    <span><i class="fa-solid fa-right-from-bracket text-slate-600"></i> {{ $item->time_out ?? '-' }}</span>
                                </div>
                            </div>
                            <div>
                                @if($item->status == 'Hadir')
                                    <span class="bg-emerald-100 text-emerald-700 px-2.5 py-1 rounded-full text-[10px] font-bold">Hadir</span>
                                @elseif($item->status == 'Terlambat')
                                    <span class="bg-amber-100 text-amber-700 px-2.5 py-1 rounded-full text-[10px] font-bold">Terlambat</span>
                                @else
                                    <span class="bg-rose-100 text-rose-700 px-2.5 py-1 rounded-full text-[10px] font-bold">{{ $item->status }}</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 text-center text-xs text-gray-400 space-y-2">
                    <i class="fa-regular fa-calendar-xmark text-4xl text-slate-300 block mb-2"></i>
                    <p class="font-medium text-slate-600">Belum Ada Riwayat</p>
                    <p>Catatan kehadiran Anda akan otomatis tersusun di sini.</p>
                </div>
            @endif
        </div>

        <!-- ================= TAB 3: PROFIL ================= -->
        <div id="tab-profil" class="tab-content hidden p-5">
            <h2 class="text-lg font-bold text-slate-800 mb-4">Profil Saya</h2>
            
            <div class="bg-white rounded-3xl border border-slate-100 p-6 text-center shadow-sm mb-4">
                <div class="relative w-24 h-24 mx-auto mb-3">
                    <img id="avatar-preview-main" src="{{ Auth::user()->profile_photo ? asset('storage/' . Auth::user()->profile_photo) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=0D9488&color=fff' }}" alt="Foto Profil" class="w-full h-full rounded-full object-cover border-4 border-teal-50 shadow-md">
                    
                    <!-- Form Input Foto -->
                    <label for="profile-photo-input" title="Ubah Foto Profil" class="absolute bottom-0 right-0 bg-teal-600 hover:bg-teal-700 text-white w-8 h-8 rounded-full flex items-center justify-center cursor-pointer shadow-md transition">
                        <i class="fa-solid fa-camera text-xs"></i>
                        <input type="file" id="profile-photo-input" name="photo" accept="image/*" class="hidden" onchange="uploadProfilePhoto(event)">
                    </label>
                </div>

                <h3 class="text-base font-bold text-slate-800">{{ Auth::user()->name }}</h3>
                <p class="text-xs text-slate-400 mb-3">{{ Auth::user()->email }}</p>

                <div class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-700 border border-emerald-100 px-3 py-1 rounded-full text-xs font-semibold">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span>Masa Aktif: Aktif (TA 2026/2027)</span>
                </div>
            </div>

            <!-- Detail Informasi Pegawai -->
            <div class="bg-white rounded-3xl border border-slate-100 p-5 shadow-sm space-y-4 mb-5">
                <h4 class="font-bold text-slate-800 text-xs uppercase tracking-wider text-gray-400">Informasi Pegawai</h4>
                
                <div class="flex items-center justify-between border-b border-slate-50 pb-3">
                    <div class="flex items-center gap-3 text-slate-600 text-xs">
                        <i class="fa-solid fa-id-card text-teal-600"></i>
                        <span>NIP / NIK</span>
                    </div>
                    <span class="text-xs font-bold text-slate-800">{{ Auth::user()->nip_nik ?? '-' }}</span>
                </div>

                <div class="flex items-center justify-between border-b border-slate-50 pb-3">
                    <div class="flex items-center gap-3 text-slate-600 text-xs">
                        <i class="fa-solid fa-user-gear text-teal-600"></i>
                        <span>Peran (Role)</span>
                    </div>
                    <span class="text-xs font-bold text-teal-700 bg-teal-50 px-2.5 py-0.5 rounded-full capitalize">{{ Auth::user()->role ?? 'Guru' }}</span>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3 text-slate-600 text-xs">
                        <i class="fa-solid fa-school text-teal-600"></i>
                        <span>Unit Kerja</span>
                    </div>
                    <span class="text-xs font-bold text-slate-800">TK Aisyiyah 06</span>
                </div>
            </div>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full py-3.5 bg-red-50 hover:bg-red-100 text-red-600 font-bold rounded-2xl text-xs transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-right-from-bracket"></i> Keluar dari Akun
                </button>
            </form>
        </div>

        <!-- ================= POPUP MODAL KAMERA SCAN ================= -->
        <div id="camera-modal" class="fixed inset-0 bg-slate-900/70 backdrop-blur-sm hidden flex items-center justify-center p-4 z-50">
            <div class="bg-white rounded-3xl p-6 w-full max-w-sm text-center shadow-2xl relative">
                <h3 id="scan-title" class="text-base font-bold text-slate-800 mb-1">Scan QR Code</h3>
                <p class="text-[11px] text-slate-400 mb-3">Arahkan kamera tepat ke QR Code resmi sekolah</p>

                <!-- Container Video Scanner -->
                <div id="reader" class="rounded-2xl overflow-hidden border-2 border-teal-500 mb-3 bg-slate-900 min-h-[220px] flex items-center justify-center text-white text-xs">
                    <span class="text-slate-400">Menyiapkan kamera...</span>
                </div>

                <!-- Opsi Alternatif: Upload File Foto QR -->
                <div class="mb-3 p-2 bg-teal-50 border border-teal-100 rounded-xl">
                    <label for="qr-input-file" class="cursor-pointer text-xs font-bold text-teal-700 hover:text-teal-800 flex items-center justify-center gap-2">
                        <i class="fa-solid fa-image text-sm"></i> 
                        <span>Atau Pilih Foto / Gambar QR</span>
                        <input type="file" id="qr-input-file" accept="image/*" class="hidden" onchange="scanQrFromFile(this)">
                    </label>
                </div>

                <button onclick="closeCameraModal()" class="w-full py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold rounded-xl text-xs transition">
                    Tutup Scanner
                </button>
            </div>
        </div>

        <!-- ================= BOTTOM NAVIGATION BAR ================= -->
        <div class="fixed bottom-0 max-w-md w-full bg-white border-t border-slate-100 py-3 px-8 flex justify-between items-center z-40">
            <button onclick="switchTab('beranda')" id="nav-beranda" class="nav-btn flex flex-col items-center gap-1 text-teal-700 font-bold">
                <i class="fa-solid fa-house text-lg"></i>
                <span class="text-[10px]">Beranda</span>
            </button>
            <button onclick="switchTab('riwayat')" id="nav-riwayat" class="nav-btn flex flex-col items-center gap-1 text-gray-400 hover:text-teal-700">
                <i class="fa-regular fa-newspaper text-lg"></i>
                <span class="text-[10px] font-medium">Riwayat</span>
            </button>
            <button onclick="switchTab('profil')" id="nav-profil" class="nav-btn flex flex-col items-center gap-1 text-gray-400 hover:text-teal-700">
                <i class="fa-regular fa-user text-lg"></i>
                <span class="text-[10px] font-medium">Profil</span>
            </button>
        </div>

    </div>

    <script>
        function updateClock() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            document.getElementById('live-clock').innerText = `${hours}:${minutes}:${seconds}`;
        }
        setInterval(updateClock, 1000);
        updateClock();

        function switchTab(tabName) {
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('.nav-btn').forEach(el => {
                el.classList.remove('text-teal-700', 'font-bold');
                el.classList.add('text-gray-400');
            });

            document.getElementById(`tab-${tabName}`).classList.remove('hidden');
            const activeNav = document.getElementById(`nav-${tabName}`);
            activeNav.classList.remove('text-gray-400');
            activeNav.classList.add('text-teal-700', 'font-bold');
        }

        function uploadProfilePhoto(event) {
            const fileInput = event.target;
            const file = fileInput.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('avatar-preview-main').src = e.target.result;
                const headerImg = document.getElementById('avatar-preview-header');
                if (headerImg) headerImg.src = e.target.result;
            };
            reader.readAsDataURL(file);

            const formData = new FormData();
            formData.append('photo', file);
            formData.append('_token', '{{ csrf_token() }}');

            fetch("{{ route('guru.updatePhoto') }}", {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(async res => {
                const data = await res.json();
                if (!res.ok) throw new Error(data.message || `Upload Error (${res.status})`);
                return data;
            })
            .then(data => {
                if (data.status === 'success') {
                    const freshUrl = data.photo_url + '?t=' + new Date().getTime();
                    document.getElementById('avatar-preview-main').src = freshUrl;
                    const headerImg = document.getElementById('avatar-preview-header');
                    if (headerImg) headerImg.src = freshUrl;

                    alert("Foto profil berhasil diperbarui & disimpan permanen!");
                } else {
                    alert("Gagal: " + data.message);
                }
            })
            .catch(err => {
                alert("Error Upload Foto: " + err.message);
            });
        }

        let qrScanner = null;

        function openCameraScanner(type) {
            document.getElementById('scan-title').innerText = type === 'masuk' ? 'Scan QR Absen Masuk' : 'Scan QR Absen Pulang';
            document.getElementById('camera-modal').classList.remove('hidden');

            if (typeof Html5Qrcode === 'undefined') {
                alert("Library scanner belum selesai dimuat. Silakan refresh halaman!");
                closeCameraModal();
                return;
            }

            startScanner();
        }

        function startScanner() {
            try {
                if (!qrScanner) {
                    qrScanner = new Html5Qrcode("reader");
                }

                if (qrScanner.isScanning) {
                    return;
                }

                const config = {
                    fps: 15,
                    qrbox: function(viewfinderWidth, viewfinderHeight) {
                        const minEdge = Math.min(viewfinderWidth, viewfinderHeight);
                        const size = Math.max(160, Math.floor(minEdge * 0.75));
                        return { width: size, height: size };
                    }
                };

                // Coba kamera belakang (environment) terlebih dahulu
                qrScanner.start(
                    { facingMode: "environment" },
                    config,
                    onScanSuccess,
                    (err) => {}
                ).catch((errBack) => {
                    console.warn("Kamera belakang tidak tersedia, mencoba kamera depan/webcam:", errBack);
                    // Fallback ke kamera depan / webcam apa saja
                    return qrScanner.start(
                        { facingMode: "user" },
                        config,
                        onScanSuccess,
                        (err) => {}
                    );
                }).catch((finalErr) => {
                    console.error("Gagal membuka kamera:", finalErr);
                    const errMsg = (finalErr && (finalErr.message || finalErr.toString())) ? (finalErr.message || finalErr.toString()) : "Izin kamera ditolak atau tidak ditemukan.";
                    alert("Kamera tidak dapat diakses:\n" + errMsg + "\n\nTips: Berikan izin kamera di browser, atau gunakan tombol 'Pilih Foto / Gambar QR'.");
                });

            } catch (e) {
                console.error("Scanner Init Error:", e);
                const msg = (e && (e.message || e.toString())) ? (e.message || e.toString()) : "Terjadi kesalahan inisialisasi.";
                alert("Kendala scanner: " + msg);
            }
        }

        function closeCameraModal() {
            document.getElementById('camera-modal').classList.add('hidden');
            if (qrScanner && qrScanner.isScanning) {
                qrScanner.stop().catch(err => console.warn("Stop error:", err));
            }
        }

        function scanQrFromFile(input) {
            if (!input.files || input.files.length === 0) return;
            const file = input.files[0];

            if (typeof Html5Qrcode === 'undefined') {
                alert("Library scanner belum termuat.");
                return;
            }

            if (!qrScanner) {
                qrScanner = new Html5Qrcode("reader");
            }

            const stopPromise = (qrScanner.isScanning) ? qrScanner.stop() : Promise.resolve();

            stopPromise.then(() => {
                return qrScanner.scanFile(file, true);
            }).then(decodedText => {
                onScanSuccess(decodedText);
            }).catch(err => {
                const errMsg = (err && (err.message || err.toString())) ? (err.message || err.toString()) : "QR Code tidak terdeteksi pada gambar ini.";
                alert("Gagal membaca QR Code:\n" + errMsg + "\n\nPastikan foto QR code tidak buram atau terpotong!");
            }).finally(() => {
                input.value = '';
            });
        }

        function onScanSuccess(decodedText) {
            closeCameraModal();

            fetch("{{ route('guru.scan') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ qr_code: decodedText })
            })
            .then(async res => {
                const data = await res.json();
                if (!res.ok) throw new Error(data.message || `Server Error (${res.status})`);
                return data;
            })
            .then(data => {
                alert(data.message);
                if (data.status === 'success') {
                    window.location.reload();
                }
            })
            .catch(err => {
                alert("Detail Error: " + err.message);
            });
        }
    </script>
</body>
</html>