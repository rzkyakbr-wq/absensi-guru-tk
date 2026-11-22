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

    // 7. Simpan Foto Profil Guru (Permanen ke DB & Storage)
    public function updateProfilePhoto(Request $request)
    {
        try {
            $request->validate([
                'photo' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            ]);

            /** @var User $user */
            $user = Auth::user();

            if ($request->hasFile('photo')) {
                // Hapus foto lama dari storage jika ada
                if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
                    Storage::disk('public')->delete($user->profile_photo);
                }

                // Simpan foto baru ke folder storage/app/public/profiles
                $path = $request->file('photo')->store('profiles', 'public');

                // Simpan ke database
                $user->profile_photo = $path;
                $user->save();

                return response()->json([
                    'status'    => 'success',
                    'message'   => 'Foto profil berhasil disimpan!',
                    'photo_url' => asset('storage/' . $path)
                ]);
            }

            return response()->json(['status' => 'error', 'message' => 'File foto tidak ditemukan.'], 400);

        } catch (\Exception $e) {
            Log::error("Error Upload Foto Profil: " . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Gagal mengunggah foto: ' . $e->getMessage()], 500);
        }
    }
}