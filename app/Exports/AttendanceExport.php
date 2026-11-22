<?php

namespace App\Exports;

use App\Models\Attendance;
use Illuminate\Support\Enumerable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AttendanceExport implements FromCollection, WithHeadings, WithMapping
{
    protected $guruId;
    protected $startDate;
    protected $endDate;

    public function __construct($guruId = null, $startDate = null, $endDate = null)
    {
        $this->guruId = $guruId;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
     * @return Enumerable
     */
    public function collection(): Enumerable
    {
        $query = Attendance::with('user');

        if ($this->guruId) {
            $query->where('user_id', $this->guruId);
        }

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('date', [$this->startDate, $this->endDate]);
        }

        return $query->orderBy('date', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Nama Guru',
            'NIP / NIK',
            'Jam Masuk',
            'Jam Pulang',
            'Status',
        ];
    }

    public function map($attendance): array
    {
        return [
            $attendance->date,
            $attendance->user->name ?? '-',
            $attendance->user->nip_nik ?? '-',
            $attendance->time_in ?? '-',
            $attendance->time_out ?? '-',
            $attendance->status,
        ];
    }
}