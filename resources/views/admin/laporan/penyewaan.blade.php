@extends('layouts.admin')
@section('title', 'Laporan Penyewaan')
@section('page_title', 'Laporan Penyewaan')

@section('content')
<div class="bg-white rounded-2xl border border-dark-100 shadow-sm overflow-hidden mb-6">
    <div class="p-6 border-b border-dark-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <form action="{{ route('admin.laporan.penyewaan') }}" method="GET" class="flex flex-wrap gap-4 w-full md:w-auto">
            <div class="flex items-center gap-2">
                <label class="text-sm font-bold text-dark-500">Mulai:</label>
                <input type="date" name="start_date" value="{{ $startDate }}" class="form-input py-2">
            </div>
            <div class="flex items-center gap-2">
                <label class="text-sm font-bold text-dark-500">Sampai:</label>
                <input type="date" name="end_date" value="{{ $endDate }}" class="form-input py-2">
            </div>
            <button type="submit" class="btn-primary py-2"><i class="fas fa-filter mr-2"></i>Filter Laporan</button>
        </form>
        <button onclick="window.print()" class="btn-secondary py-2 bg-dark-800 text-white hover:bg-dark-900 border-none print:hidden">
            <i class="fas fa-print mr-2"></i>Cetak Laporan
        </button>
    </div>
    
    <div class="p-6 bg-dark-50 border-b border-dark-100 print:bg-white print:p-0 print:border-none">
        <div class="text-center mb-6 print:block hidden">
            <h2 class="text-2xl font-bold font-display uppercase tracking-widest text-dark-900">{{ config('app.name') }}</h2>
            <p class="text-dark-600">Laporan Penyewaan Lapangan</p>
            <p class="text-sm text-dark-500 mt-1">Periode: {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</p>
            <hr class="my-4 border-dark-300">
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 print:grid-cols-4 print:gap-2">
            <div class="bg-white p-4 rounded-xl border border-dark-100 print:border-dark-300">
                <p class="text-xs font-bold text-dark-500 uppercase tracking-wider mb-1">Total Penyewaan</p>
                <p class="text-2xl font-black text-dark-900">{{ $bookings->count() }} <span class="text-sm font-normal text-dark-500">Sesi</span></p>
            </div>
            <div class="bg-white p-4 rounded-xl border border-dark-100 print:border-dark-300">
                <p class="text-xs font-bold text-dark-500 uppercase tracking-wider mb-1">Total Jam Bermain</p>
                <p class="text-2xl font-black text-primary-600">{{ $bookings->sum('durasi_jam') }} <span class="text-sm font-normal text-dark-500">Jam</span></p>
            </div>
            <div class="bg-white p-4 rounded-xl border border-dark-100 print:border-dark-300">
                <p class="text-xs font-bold text-dark-500 uppercase tracking-wider mb-1">Booking Selesai</p>
                <p class="text-2xl font-black text-emerald-600">{{ $bookings->where('status', 'completed')->count() }}</p>
            </div>
            <div class="bg-white p-4 rounded-xl border border-dark-100 print:border-dark-300">
                <p class="text-xs font-bold text-dark-500 uppercase tracking-wider mb-1">Dibatalkan</p>
                <p class="text-2xl font-black text-red-600">{{ $bookings->whereIn('status', ['cancelled', 'rejected'])->count() }}</p>
            </div>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left print:text-sm">
            <thead>
                <tr class="bg-dark-100 border-b border-dark-200">
                    <th class="py-3 px-4 text-xs font-bold text-dark-700 uppercase">Tanggal</th>
                    <th class="py-3 px-4 text-xs font-bold text-dark-700 uppercase">Kode</th>
                    <th class="py-3 px-4 text-xs font-bold text-dark-700 uppercase">Member</th>
                    <th class="py-3 px-4 text-xs font-bold text-dark-700 uppercase">Lapangan</th>
                    <th class="py-3 px-4 text-xs font-bold text-dark-700 uppercase">Waktu</th>
                    <th class="py-3 px-4 text-xs font-bold text-dark-700 uppercase">Durasi</th>
                    <th class="py-3 px-4 text-xs font-bold text-dark-700 uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-dark-100 print:divide-dark-200">
                @forelse($bookings as $booking)
                <tr>
                    <td class="py-3 px-4">{{ $booking->tanggal->format('d/m/Y') }}</td>
                    <td class="py-3 px-4 font-mono text-sm">{{ $booking->kode_booking }}</td>
                    <td class="py-3 px-4">{{ $booking->user->name }}</td>
                    <td class="py-3 px-4">{{ $booking->lapangan->nama }}</td>
                    <td class="py-3 px-4">{{ substr($booking->jam_mulai, 0, 5) }} - {{ substr($booking->jam_selesai, 0, 5) }}</td>
                    <td class="py-3 px-4">{{ $booking->durasi_jam }} Jam</td>
                    <td class="py-3 px-4">{{ $booking->status_label }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-8 text-center text-dark-500">Tidak ada data penyewaan pada periode ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<style>
    @media print {
        body * { visibility: hidden; }
        .bg-white, .bg-white * { visibility: visible; }
        .bg-white { position: absolute; left: 0; top: 0; width: 100%; border: none !important; box-shadow: none !important; }
        .print\:hidden { display: none !important; }
    }
</style>
@endsection
