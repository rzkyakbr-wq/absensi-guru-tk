<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    // 1. Tampilkan Halaman Login
    public function showLogin()
    {
        if (Auth::check()) {
            return Auth::user()->role === 'admin' 
                ? redirect()->route('admin.dashboard') 
                : redirect()->route('guru.dashboard');
        }

        return view('auth.login');
    }

    // 2. Tampilkan Halaman Register
    public function showRegister()
    {
        return view('auth.register');
    }

    // 3. Proses Register Guru Baru
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'nip_nik'  => 'nullable|string|max:50',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'guru',
            'nip_nik'  => $request->nip_nik,
        ]);

        Auth::login($user);

        return redirect()->route('guru.dashboard');
    }

    // 4. Proses Login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            if (Auth::user()->role === 'admin') {
                return redirect()->intended('/admin/dashboard');
            }

            return redirect()->intended('/guru/dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    // 5. Proses Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    // 6. Dashboard Guru
    public function guruDashboard()
    {
        /** @var User $user */
        $user = Auth::user();
        $today = Carbon::today()->toDateString();
        
        $attendance = Attendance::where('user_id', $user->id)
                                ->where('date', $today)
                                ->first();

        $currentMonth = Carbon::now()->month;
        $currentYear  = Carbon::now()->year;

        $totalHadir = Attendance::where('user_id', $user->id)
                        ->whereMonth('date', $currentMonth)
                        ->whereYear('date', $currentYear)
                        ->where('status', 'Hadir')
                        ->count();

        $totalTerlambat = Attendance::where('user_id', $user->id)
                            ->whereMonth('date', $currentMonth)
                            ->whereYear('date', $currentYear)
                            ->where('status', 'Terlambat')
                            ->count();

        $totalTidakHadir = Attendance::where('user_id', $user->id)
                            ->whereMonth('date', $currentMonth)
                            ->whereYear('date', $currentYear)
                            ->whereIn('status', ['Izin', 'Sakit', 'Tanpa Keterangan'])
                            ->count();

        $attendancesHistory = Attendance::where('user_id', $user->id)
                                ->orderBy('date', 'desc')
                                ->orderBy('id', 'desc')
                                ->get();

        return view('guru.dashboard', compact(
            'attendance', 
            'totalHadir', 
            'totalTerlambat', 
            'totalTidakHadir',
            'attendancesHistory'
        ));
    }

    // 7. Simpan Foto Profil Guru (Permanen di Supabase DB sebagai Base64)
    public function updateProfilePhoto(Request $request)
    {
        try {
            $request->validate([
                'photo' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            ], [
                'photo.required' => 'File foto wajib dipilih.',
                'photo.image'    => 'File harus berupa gambar.',
                'photo.max'      => 'Ukuran foto maksimal 5 MB.',
            ]);

            /** @var User $user */
            $user = Auth::user();

            if ($request->hasFile('photo')) {
                $file = $request->file('photo');

                // Resize & kompres menjadi avatar 200x200 agar tersimpan permanen di database Supabase (tidak terhapus saat redeploy)
                $imageData = file_get_contents($file->getRealPath());
                $srcImage = @imagecreatefromstring($imageData);

                if ($srcImage) {
                    $width = imagesx($srcImage);
                    $height = imagesy($srcImage);

                    // Crop bujur sangkar (square) dari tengah
                    $size = min($width, $height);
                    $x = (int) (($width - $size) / 2);
                    $y = (int) (($height - $size) / 2);

                    $targetSize = 200;
                    $destImage = imagecreatetruecolor($targetSize, $targetSize);

                    imagecopyresampled($destImage, $srcImage, 0, 0, $x, $y, $targetSize, $targetSize, $size, $size);

                    ob_start();
                    imagejpeg($destImage, null, 80);
                    $compressedData = ob_get_clean();

                    imagedestroy($srcImage);
                    imagedestroy($destImage);

                    $base64 = 'data:image/jpeg;base64,' . base64_encode($compressedData);
                } else {
                    $base64 = 'data:' . $file->getMimeType() . ';base64,' . base64_encode($imageData);
                }

                $user->profile_photo = $base64;
                $user->save();

                return response()->json([
                    'status'    => 'success',
                    'message'   => 'Foto profil berhasil disimpan permanen!',
                    'photo_url' => $base64
                ]);
            }

            return response()->json(['status' => 'error', 'message' => 'File foto tidak ditemukan.'], 400);

        } catch (\Exception $e) {
            Log::error("Error Upload Foto Profil: " . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Gagal mengunggah foto: ' . $e->getMessage()], 500);
        }
    }

    // 7. Tampilkan Halaman Lupa Password
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    // 8. Proses Reset Password Guru Mandiri
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email'                 => 'required|email|exists:users,email',
            'verification'          => 'required|string',
            'password'              => 'required|string|min:6|confirmed',
        ], [
            'email.required'        => 'Alamat email wajib diisi.',
            'email.email'           => 'Format email tidak valid.',
            'email.exists'          => 'Alamat email tidak ditemukan dalam sistem.',
            'verification.required' => 'NIP / NIK atau Nama Lengkap wajib diisi untuk verifikasi.',
            'password.required'     => 'Password baru wajib diisi.',
            'password.min'          => 'Password baru minimal harus 6 karakter.',
            'password.confirmed'    => 'Konfirmasi password baru tidak cocok.',
        ]);

        $user = User::where('email', $request->email)->first();

        // Verifikasi: cocokkan dengan NIP/NIK atau Nama Lengkap (case-insensitive)
        $inputVerif = trim(strtolower($request->verification));
        $dbNip = $user->nip_nik ? trim(strtolower($user->nip_nik)) : null;
        $dbName = trim(strtolower($user->name));

        $matched = false;
        if ($dbNip && $inputVerif === $dbNip) {
            $matched = true;
        } elseif ($inputVerif === $dbName || str_contains($dbName, $inputVerif) || str_contains($inputVerif, $dbName)) {
            $matched = true;
        }

        if (!$matched) {
            return back()->withErrors([
                'verification' => 'Verifikasi gagal: NIP/NIK atau Nama Lengkap tidak cocok dengan data akun email ini. Jika lupa, silakan hubungi Admin TK.',
            ])->withInput($request->except(['password', 'password_confirmation']));
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('login')->with('success', 'Password akun ' . $user->email . ' berhasil diperbarui! Silakan masuk dengan password baru.');
    }
}