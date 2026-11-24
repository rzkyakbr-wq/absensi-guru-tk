<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Attendance;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AttendanceExport;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $selectedGuru = $request->input('guru_id');
        $startDate    = $request->input('start_date', Carbon::today()->startOfMonth()->toDateString());
        $endDate      = $request->input('end_date', Carbon::today()->toDateString());

        // Ambil daftar guru untuk dropdown filter
        $allGurus = User::where('role', 'guru')->get();

        // Query data absensi berdasarkan filter
        $query = Attendance::with('user')->whereBetween('date', [$startDate, $endDate]);

        if ($selectedGuru) {
            $query->where('user_id', $selectedGuru);
        }

        $attendances = $query->orderBy('date', 'desc')->get();

        return view('admin.dashboard', compact(
            'allGurus',
            'attendances',
            'selectedGuru',
            'startDate',
            'endDate'
        ));
    }

    // Update Status Absensi Manual oleh Admin
    public function updateStatus(Request $request, $userId)
    {
        $request->validate([
            'status' => 'required|in:Hadir,Terlambat,Izin,Sakit,Tanpa Keterangan',
            'date'   => 'required|date',
        ]);

        $attendance = Attendance::firstOrNew([
            'user_id' => $userId,
            'date'    => $request->date,
        ]);

        $attendance->status = $request->status;

        if (in_array($request->status, ['Hadir', 'Terlambat']) && !$attendance->time_in) {
            $attendance->time_in = Carbon::now()->toTimeString();
        }

        $attendance->save();

        return back()->with('success', 'Status absensi berhasil diperbarui!');
    }

    // Export Excel Sesuai Filter Nama & Tanggal
    public function exportExcel(Request $request)
    {
        $guruId    = $request->input('guru_id');
        $startDate = $request->input('start_date');
        $endDate   = $request->input('end_date');

        $fileName = 'rekap_absensi_' . date('Y-m-d_H-i-s') . '.xlsx';

        return Excel::download(new AttendanceExport($guruId, $startDate, $endDate), $fileName);
    }

    // Hapus Data Presensi oleh Admin
    public function deleteAttendance($attendanceId)
    {
        $attendance = Attendance::findOrFail($attendanceId);
        $gurNama = $attendance->user->name ?? 'Guru';
        $tanggal = $attendance->date;
        $attendance->delete();

        return back()->with('success', "Data presensi {$gurNama} tanggal {$tanggal} berhasil dihapus!");
    }

    // Reset Password Guru oleh Admin
    public function resetGuruPassword(Request $request, $userId)
    {
        $request->validate([
            'new_password' => 'required|string|min:6',
        ], [
            'new_password.required' => 'Password baru wajib diisi.',
            'new_password.min'      => 'Password baru minimal harus 6 karakter.',
        ]);

        $guru = User::where('id', $userId)->where('role', 'guru')->firstOrFail();
        $guru->password = Hash::make($request->new_password);
        $guru->save();

        return back()->with('success', 'Password akun guru ' . $guru->name . ' (' . $guru->email . ') berhasil direset menjadi: ' . $request->new_password);
    }
}