@extends('layouts.admin')
@section('title', 'Laporan Pendapatan')
@section('page_title', 'Laporan Pendapatan')

@section('content')
<div class="card-premium overflow-hidden mb-6">
    <div class="p-7 border-b border-dark-100/80 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <form action="{{ route('admin.laporan.pendapatan') }}" method="GET" class="flex flex-wrap gap-4 w-full md:w-auto">
            <div class="flex items-center gap-2">
                <label class="text-sm font-bold text-dark-500">Tahun:</label>
                <select name="year" class="form-input py-2.5">
                    @for($i = date('Y'); $i >= date('Y') - 5; $i--)
                        <option value="{{ $i }}" {{ request('year', date('Y')) == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
            </div>
            <div class="flex items-center gap-2">
                <label class="text-sm font-bold text-dark-500">Bulan:</label>
                <select name="month" class="form-input py-2.5">
                    <option value="">Semua Bulan (Tahunan)</option>
                    @for($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ request('month', date('m')) == $i ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $i, 10)) }}</option>
                    @endfor
                </select>
            </div>
            <button type="submit" class="btn-primary py-2.5 shadow-lg shadow-primary-500/25"><i class="fas fa-filter mr-2"></i>Terapkan</button>
        </form>
        <div class="flex gap-3">
            <a href="{{ route('admin.laporan.pendapatan.excel', ['start_date' => $startDate, 'end_date' => $endDate]) }}" class="btn-secondary py-2.5 print:hidden shadow-sm">
                <i class="fas fa-file-excel mr-2 text-emerald-600"></i>Export Excel
            </a>
            <button onclick="window.print()" class="btn-secondary py-2.5 bg-dark-800 text-white hover:bg-dark-900 border-none print:hidden shadow-sm">
                <i class="fas fa-print mr-2"></i>Cetak PDF
            </button>
        </div>
    </div>

    <div class="p-7 bg-gradient-to-br from-emerald-50/80 to-teal-50/80 border-b border-primary-100/80 print:bg-white print:p-0 print:border-none">
        <div class="text-center mb-6 print:block hidden">
            <h2 class="text-2xl font-bold font-display uppercase tracking-widest text-dark-900">{{ config('app.name') }}</h2>
            <p class="text-dark-600">Laporan Pendapatan</p>
            <p class="text-sm text-dark-500 mt-1">Periode: {{ request('month') ? date('F', mktime(0, 0, 0, request('month'), 10)) : 'Keseluruhan' }} {{ request('year', date('Y')) }}</p>
            <hr class="my-4 border-dark-300">
        </div>

        <div class="flex flex-col items-center justify-center py-8">
            <p class="text-sm font-bold text-emerald-800 uppercase tracking-widest mb-3">Total Pendapatan Sukses</p>
            <p class="text-5xl lg:text-6xl font-display font-black text-emerald-600 drop-shadow-sm">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
            <p class="text-sm text-emerald-700 mt-4 bg-emerald-100/50 px-5 py-2 rounded-full border border-emerald-200/80">Dari <strong>{{ $bookings->count() }}</strong> transaksi terkonfirmasi/selesai</p>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left print:text-sm">
            <thead>
                <tr class="bg-dark-100/50 border-b border-dark-200/80">
                    <th class="py-3 px-4 text-xs font-bold text-dark-700 uppercase">Tgl Transaksi</th>
                    <th class="py-3 px-4 text-xs font-bold text-dark-700 uppercase">Kode Booking</th>
                    <th class="py-3 px-4 text-xs font-bold text-dark-700 uppercase">Metode</th>
                    <th class="py-3 px-4 text-xs font-bold text-dark-700 uppercase">Member</th>
                    <th class="py-3 px-4 text-xs font-bold text-dark-700 uppercase">Lapangan</th>
                    <th class="py-3 px-4 text-xs font-bold text-dark-700 uppercase text-right">Nominal (Rp)</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-dark-100/80 print:divide-dark-200">
                @forelse($bookings as $booking)
                <tr class="hover:bg-dark-50/50 transition-colors">
                    <td class="py-3 px-4 text-sm">{{ $booking->created_at->format('d/m/Y H:i') }}</td>
                    <td class="py-3 px-4 font-mono text-sm font-bold text-dark-900">{{ $booking->kode_booking }}</td>
                    <td class="py-3 px-4 uppercase text-xs font-bold">{{ $booking->metode_pembayaran }}</td>
                    <td class="py-3 px-4 text-sm">{{ $booking->user->name }}</td>
                    <td class="py-3 px-4 text-sm">{{ $booking->lapangan->nama }}</td>
                    <td class="py-3 px-4 text-right font-bold text-primary-700">{{ number_format($booking->total_harga, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-16 text-center text-dark-500">Tidak ada data pendapatan pada periode ini.</td>
                </tr>
                @endforelse
            </tbody>
            @if($bookings->count() > 0)
            <tfoot class="bg-dark-50/50 border-t-2 border-dark-300/80">
                <tr>
                    <td colspan="5" class="py-4 px-4 text-right font-bold text-dark-900 uppercase text-sm">Total:</td>
                    <td class="py-4 px-4 text-right font-black text-emerald-600 text-lg">{{ number_format($totalPendapatan, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
            @endif
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