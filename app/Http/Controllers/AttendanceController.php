<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AttendanceController extends Controller
{
    // Key QR Code Resmi
    private $qrKeyMasuk  = "https://tk-aisyiyah06.sch.id/masuk";
    private $qrKeyPulang = "https://tk-aisyiyah06.sch.id/pulang";

    public function processScan(Request $request)
    {
        try {
            $request->validate(['qr_code' => 'required|string']);

            $user = Auth::user();
            if (!$user) {
                return response()->json(['status' => 'error', 'message' => 'Sesi login Anda telah habis. Silakan login ulang!'], 401);
            }

            $today = Carbon::today()->toDateString();
            $currentTime = Carbon::now('Asia/Jakarta')->format('H:i:s');
            $scannedCode = trim($request->qr_code);

            // A. ABSEN MASUK
            if ($scannedCode === $this->qrKeyMasuk) {
                $existing = Attendance::where('user_id', $user->id)->where('date', $today)->first();

                if ($existing && $existing->time_in) {
                    return response()->json(['status' => 'error', 'message' => 'Anda sudah melakukan absen masuk hari ini!']);
                }

                // Cek Keterlambatan (Batas jam 07:00 WIB)
                $currentHourMinute = Carbon::now('Asia/Jakarta')->format('H:i');
                $attendanceStatus = ($currentHourMinute <= '07:00') ? 'Hadir' : 'Terlambat';

                Attendance::updateOrCreate(
                    ['user_id' => $user->id, 'date' => $today],
                    [
                        'time_in' => $currentTime,
                        'status'  => $attendanceStatus
                    ]
                );

                return response()->json([
                    'status'  => 'success',
                    'message' => "Absen Masuk Berhasil! Status: {$attendanceStatus}",
                    'time'    => $currentTime
                ]);
            }

            // B. ABSEN PULANG
            if ($scannedCode === $this->qrKeyPulang) {
                $attendance = Attendance::where('user_id', $user->id)->where('date', $today)->first();

                if (!$attendance || !$attendance->time_in) {
                    return response()->json(['status' => 'error', 'message' => 'Anda belum melakukan absen masuk hari ini!']);
                }

                if ($attendance->time_out) {
                    return response()->json(['status' => 'error', 'message' => 'Anda sudah melakukan absen pulang hari ini!']);
                }

                $attendance->update(['time_out' => $currentTime]);

                return response()->json([
                    'status'  => 'success',
                    'message' => 'Absen Pulang Berhasil!',
                    'time'    => $currentTime
                ]);
            }

            return response()->json(['status' => 'error', 'message' => 'QR Code tidak dikenali oleh sistem sekolah!']);

        } catch (\Exception $e) {
            Log::error("Error Absensi: " . $e->getMessage());
            return response()->json([
                'status'  => 'error',
                'message' => 'Terjadi kesalahan server: ' . $e->getMessage()
            ], 500);
        }
    }
}