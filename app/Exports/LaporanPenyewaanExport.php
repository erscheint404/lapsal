<?php

namespace App\Exports;

use App\Models\Booking;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class LaporanPenyewaanExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $tanggalMulai;
    protected $tanggalSelesai;

    public function __construct($tanggalMulai, $tanggalSelesai)
    {
        $this->tanggalMulai = $tanggalMulai;
        $this->tanggalSelesai = $tanggalSelesai;
    }

    public function collection()
    {
        return Booking::with(['lapangan', 'user'])
            ->whereBetween('created_at', [$this->tanggalMulai . ' 00:00:00', $this->tanggalSelesai . ' 23:59:59'])
            ->whereIn('status', ['confirmed', 'completed'])
            ->get();
    }

    public function headings(): array
    {
        return ['Kode Booking', 'Member', 'Lapangan', 'Tanggal', 'Jam', 'Status', 'Total Bayar', 'Tgl Booking'];
    }

    public function map($booking): array
    {
        return [
            $booking->kode_booking,
            $booking->user->name,
            $booking->lapangan->nama,
            $booking->tanggal->format('d/m/Y'),
            substr($booking->jam_mulai, 0, 5) . ' - ' . substr($booking->jam_selesai, 0, 5),
            $booking->status_label,
            'Rp ' . number_format($booking->total_harga, 0, ',', '.'),
            $booking->created_at->format('d/m/Y H:i'),
        ];
    }
}
