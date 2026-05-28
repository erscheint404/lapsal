@extends('layouts.admin')
@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@section('content')
<div class="space-y-6">
    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-2xl p-6 border border-dark-100 shadow-sm flex items-center gap-4">
            <div class="w-14 h-14 rounded-xl bg-primary-50 text-primary-600 flex items-center justify-center text-2xl">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div>
                <p class="text-sm font-bold text-dark-500 uppercase tracking-wider mb-1">Booking Hari Ini</p>
                <p class="text-3xl font-display font-black text-dark-900">{{ $totalBookingHariIni }}</p>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl p-6 border border-dark-100 shadow-sm flex items-center gap-4">
            <div class="w-14 h-14 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-2xl">
                <i class="fas fa-wallet"></i>
            </div>
            <div>
                <p class="text-sm font-bold text-dark-500 uppercase tracking-wider mb-1">Pendapatan Bulan Ini</p>
                <p class="text-xl font-display font-black text-dark-900">Rp {{ number_format($pendapatanBulanIni, 0, ',', '.') }}</p>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl p-6 border border-dark-100 shadow-sm flex items-center gap-4">
            <div class="w-14 h-14 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-2xl">
                <i class="fas fa-users"></i>
            </div>
            <div>
                <p class="text-sm font-bold text-dark-500 uppercase tracking-wider mb-1">Total Member</p>
                <p class="text-3xl font-display font-black text-dark-900">{{ $totalMember }}</p>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl p-6 border border-dark-100 shadow-sm flex items-center gap-4">
            <div class="w-14 h-14 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-2xl">
                <i class="fas fa-futbol"></i>
            </div>
            <div>
                <p class="text-sm font-bold text-dark-500 uppercase tracking-wider mb-1">Total Lapangan</p>
                <p class="text-3xl font-display font-black text-dark-900">{{ $totalLapangan }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Charts --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl p-6 border border-dark-100 shadow-sm">
                <h3 class="font-bold text-lg text-dark-900 mb-6">Statistik Booking (7 Hari Terakhir)</h3>
                <div class="h-72">
                    <canvas id="bookingChart"></canvas>
                </div>
            </div>
            
            <div class="bg-white rounded-2xl p-6 border border-dark-100 shadow-sm">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="font-bold text-lg text-dark-900">Booking Perlu Aksi</h3>
                    <span class="badge badge-error">{{ $bookingPerluAksi->count() }} Pending</span>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-dark-50 border-b border-dark-100">
                                <th class="py-3 px-4 text-xs font-bold text-dark-500 uppercase">Kode</th>
                                <th class="py-3 px-4 text-xs font-bold text-dark-500 uppercase">Member</th>
                                <th class="py-3 px-4 text-xs font-bold text-dark-500 uppercase">Status</th>
                                <th class="py-3 px-4 text-xs font-bold text-dark-500 uppercase text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-dark-100">
                            @forelse($bookingPerluAksi as $booking)
                            <tr class="hover:bg-dark-50 transition-colors">
                                <td class="py-3 px-4 font-mono text-sm font-bold">{{ $booking->kode_booking }}</td>
                                <td class="py-3 px-4">
                                    <div class="font-medium text-dark-900 text-sm">{{ $booking->user->name }}</div>
                                    <div class="text-xs text-dark-500">{{ $booking->lapangan->nama }}</div>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="badge {{ $booking->status_color }} text-xs">{{ $booking->status_label }}</span>
                                </td>
                                <td class="py-3 px-4 text-right">
                                    @if($booking->status === 'under_review')
                                    <a href="{{ route('admin.pembayaran.index') }}?status=under_review" class="btn-primary py-1 px-3 text-xs">Cek Bukti</a>
                                    @else
                                    <a href="{{ route('admin.booking.show', $booking->id) }}" class="btn-secondary py-1 px-3 text-xs">Detail</a>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="py-8 text-center text-dark-500 text-sm">Tidak ada booking yang perlu aksi.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            <div class="bg-white rounded-2xl p-6 border border-dark-100 shadow-sm">
                <h3 class="font-bold text-lg text-dark-900 mb-6">Booking Terbaru</h3>
                
                <div class="space-y-4">
                    @forelse($bookingTerbaru as $booking)
                    <div class="flex gap-3">
                        <div class="w-10 h-10 rounded-full flex-none flex items-center justify-center text-white
                            {{ $booking->status === 'confirmed' ? 'bg-emerald-500' : 
                               ($booking->status === 'completed' ? 'bg-blue-500' : 
                               (in_array($booking->status, ['pending_payment', 'under_review', 'waiting_confirmation']) ? 'bg-amber-500' : 'bg-red-500')) }}">
                            <i class="fas {{ $booking->status === 'confirmed' ? 'fa-check' : 
                                            ($booking->status === 'completed' ? 'fa-flag-checkered' : 
                                            (in_array($booking->status, ['pending_payment', 'under_review', 'waiting_confirmation']) ? 'fa-clock' : 'fa-times')) }}"></i>
                        </div>
                        <div class="flex-1 overflow-hidden">
                            <p class="font-bold text-sm text-dark-900 truncate">{{ $booking->user->name }}</p>
                            <p class="text-xs text-dark-500 truncate">{{ $booking->lapangan->nama }} - {{ $booking->tanggal->format('d M') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs font-bold text-dark-900">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</p>
                            <p class="text-[10px] text-dark-400">{{ $booking->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @empty
                    <p class="text-center text-dark-500 text-sm py-4">Belum ada data booking.</p>
                    @endforelse
                </div>
                
                <a href="{{ route('admin.booking.index') }}" class="btn-secondary w-full mt-6 text-sm py-2">Lihat Semua Booking</a>
            </div>
            
            <div class="bg-gradient-to-br from-dark-900 to-dark-800 rounded-2xl p-6 text-white shadow-lg">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-qrcode text-xl"></i>
                    </div>
                    <h3 class="font-bold text-lg">Scan Tiket Masuk</h3>
                </div>
                <p class="text-sm text-dark-300 mb-6">Buka scanner untuk memverifikasi QR Code pemain yang datang ke lapangan.</p>
                <a href="{{ route('admin.qrscan.index') }}" class="btn-primary w-full py-3 bg-emerald-500 hover:bg-emerald-600 border-none">
                    <i class="fas fa-camera mr-2"></i> Buka Scanner
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('bookingChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($chartLabels) !!},
            datasets: [{
                label: 'Jumlah Booking',
                data: {!! json_encode($chartData) !!},
                backgroundColor: '#10b981',
                borderRadius: 6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            },
            plugins: {
                legend: { display: false }
            }
        }
    });
</script>
@endpush
@endsection
