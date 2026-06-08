@extends('layouts.admin')
@section('title', 'Dashboard')
@section('page_title', 'Overview')
@section('page_description', 'Statistik dan performa penyewaan lapangan futsal.')

@section('page_actions')
<div class="flex gap-3">
    <a href="{{ route('admin.booking.index') }}" class="btn-secondary btn-sm shadow-sm">
        <i class="fas fa-list mr-2"></i>Semua Booking
    </a>
    <a href="{{ route('admin.laporan.penyewaan') }}" class="btn-primary btn-sm shadow-lg">
        <i class="fas fa-download mr-2"></i>Download Laporan
    </a>
</div>
@endsection

@section('content')
<div class="space-y-8">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-2xl border border-secondary-200/40 p-6 relative overflow-hidden group sport-corner">
            <div class="absolute -right-6 -top-6 w-24 h-24 rounded-full bg-secondary-50 opacity-50 group-hover:scale-150 transition-transform duration-700"></div>
            <div class="flex items-center gap-4 relative z-10">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-2xl shadow-sm transition-transform group-hover:scale-110"
                     style="background: rgba(16,185,129,0.15); color: #059669;">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div>
                    <p class="text-xs font-bold text-secondary-400 uppercase tracking-wider mb-1">Booking Hari Ini</p>
                    <p class="text-3xl font-display font-black text-secondary-900">{{ $totalBookingHariIni }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-secondary-200/40 p-6 relative overflow-hidden group sport-corner">
            <div class="absolute -right-6 -top-6 w-24 h-24 rounded-full bg-secondary-50 opacity-50 group-hover:scale-150 transition-transform duration-700"></div>
            <div class="flex items-center gap-4 relative z-10">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-2xl shadow-sm transition-transform group-hover:scale-110"
                     style="background: rgba(16,185,129,0.1); color: #059669;">
                    <i class="fas fa-wallet"></i>
                </div>
                <div>
                    <p class="text-xs font-bold text-secondary-400 uppercase tracking-wider mb-1">Pendapatan Bulan Ini</p>
                    <p class="text-xl lg:text-2xl font-display font-black text-secondary-900">Rp {{ number_format($pendapatanBulanIni, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-secondary-200/40 p-6 relative overflow-hidden group sport-corner">
            <div class="absolute -right-6 -top-6 w-24 h-24 rounded-full bg-secondary-50 opacity-50 group-hover:scale-150 transition-transform duration-700"></div>
            <div class="flex items-center gap-4 relative z-10">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-2xl shadow-sm transition-transform group-hover:scale-110"
                     style="background: rgba(16,185,129,0.1); color: #059669;">
                    <i class="fas fa-users"></i>
                </div>
                <div>
                    <p class="text-xs font-bold text-secondary-400 uppercase tracking-wider mb-1">Total Member</p>
                    <p class="text-3xl font-display font-black text-secondary-900">{{ $totalMember }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-secondary-200/40 p-6 relative overflow-hidden group sport-corner">
            <div class="absolute -right-6 -top-6 w-24 h-24 rounded-full bg-secondary-50 opacity-50 group-hover:scale-150 transition-transform duration-700"></div>
            <div class="flex items-center gap-4 relative z-10">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-2xl shadow-sm transition-transform group-hover:scale-110"
                     style="background: rgba(16,185,129,0.1); color: #059669;">
                    <i class="fas fa-futbol"></i>
                </div>
                <div>
                    <p class="text-xs font-bold text-secondary-400 uppercase tracking-wider mb-1">Total Lapangan</p>
                    <p class="text-3xl font-display font-black text-secondary-900">{{ $totalLapangan }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white rounded-2xl border border-secondary-200/40 p-7 sport-corner">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="font-bold text-lg text-secondary-900">Statistik Booking (7 Hari Terakhir)</h3>
                </div>
                <div class="h-72 w-full relative">
                    <canvas id="bookingChart"></canvas>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-secondary-200/40 overflow-hidden sport-corner">
                <div class="p-6 border-b border-secondary-200/60 flex items-center justify-between" style="background: rgba(0,0,0,0.01);">
                    <h3 class="font-bold text-lg text-secondary-900 flex items-center gap-2">
                        <i class="fas fa-exclamation-circle text-amber-500"></i> Perlu Tindakan
                    </h3>
                    @if($bookingPerluAksi->count() > 0)
                    <span class="badge" style="background: rgba(16,185,129,0.1); color: #059669; border: 1px solid rgba(16,185,129,0.2);">
                        {{ $bookingPerluAksi->count() }} Menunggu
                    </span>
                    @endif
                </div>
                <div class="overflow-x-auto">
                    <table class="table-modern w-full">
                        <thead>
                            <tr>
                                <th class="py-4 px-6 text-xs font-bold text-secondary-400 uppercase tracking-wider">Kode / Waktu</th>
                                <th class="py-4 px-6 text-xs font-bold text-secondary-400 uppercase tracking-wider">Member / Lapangan</th>
                                <th class="py-4 px-6 text-xs font-bold text-secondary-400 uppercase tracking-wider">Status</th>
                                <th class="py-4 px-6 text-xs font-bold text-secondary-400 uppercase tracking-wider text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-secondary-100/40">
                            @forelse($bookingPerluAksi as $booking)
                            <tr class="hover:bg-secondary-50/50 transition-colors group">
                                <td class="py-4 px-6">
                                    <div class="font-mono text-sm font-bold text-secondary-900 mb-1">{{ $booking->kode_booking }}</div>
                                    <div class="text-xs text-secondary-500"><i class="far fa-clock mr-1"></i>{{ $booking->created_at->diffForHumans() }}</div>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="font-bold text-secondary-900 text-sm mb-1">{{ $booking->user->name }}</div>
                                    <div class="text-xs text-secondary-500">{{ $booking->lapangan->nama }}</div>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="badge" style="
                                        {{ $booking->status === 'under_review' ? 'background: rgba(0,229,255,0.1); color: #007a8f; border: 1px solid rgba(0,229,255,0.2);' : 'background: rgba(251,191,36,0.1); color: #d97706; border: 1px solid rgba(251,191,36,0.2);' }}">
                                        {{ $booking->status_label }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-right">
                                    @if($booking->status === 'under_review')
                                    <a href="{{ route('admin.pembayaran.index') }}?status=under_review" class="btn-primary py-2 px-4 text-xs font-bold rounded-xl shadow-none group-hover:shadow-lg transition-shadow">Cek Bukti</a>
                                    @else
                                    <a href="{{ route('admin.booking.show', $booking->id) }}" class="btn-secondary py-2 px-4 text-xs font-bold rounded-xl shadow-none group-hover:shadow-sm transition-shadow">Detail</a>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="py-12 text-center">
                                    <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-3" style="background: rgba(16,185,129,0.1); color: #059669;">
                                        <i class="fas fa-check text-2xl"></i>
                                    </div>
                                    <p class="text-secondary-900 font-bold">Semua Aman</p>
                                    <p class="text-secondary-500 text-sm mt-1">Tidak ada booking yang memerlukan tindakan saat ini.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="space-y-8">
            <div class="rounded-2xl p-7 relative overflow-hidden text-white shadow-xl group"
                 style="background: linear-gradient(135deg, #0f172a, #1e293b); border: 1px solid rgba(16,185,129,0.2);">
                <div class="absolute inset-0 dot-pattern opacity-10"></div>
                <div class="absolute -top-10 -right-10 w-32 h-32 rounded-full blur-[40px] transition-transform duration-700 group-hover:scale-150" style="background: rgba(16,185,129,0.15);"></div>

                <div class="relative z-10">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background: rgba(16,185,129,0.1); border: 1px solid rgba(16,185,129,0.2);">
                            <i class="fas fa-qrcode text-2xl" style="color: #10b981;"></i>
                        </div>
                        <h3 class="font-bold text-xl tracking-tight">Scan Tiket</h3>
                    </div>
                    <p class="text-sm leading-relaxed mb-6" style="color: rgba(255,255,255,0.7);">Verifikasi tiket member yang datang dengan cepat menggunakan scanner QR Code.</p>
                    <a href="{{ route('admin.qrscan.index') }}" class="btn-primary w-full py-3.5 flex items-center justify-center">
                        <i class="fas fa-camera mr-2"></i> Buka Scanner
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-secondary-200/40 p-7 sport-corner">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="font-bold text-lg text-secondary-900">Booking Terbaru</h3>
                </div>
                <div class="space-y-4">
                    @forelse($bookingTerbaru as $booking)
                    <div class="flex gap-4 p-4 rounded-xl transition-all duration-200 border"
                         style="background: rgba(0,0,0,0.01); border-color: rgba(0,0,0,0.04);"
                         onmouseover="this.style.background='white';this.style.borderColor='rgba(16,185,129,0.2)';this.style.boxShadow='0 4px 15px rgba(0,0,0,0.03)'"
                         onmouseout="this.style.background='rgba(0,0,0,0.01)';this.style.borderColor='rgba(0,0,0,0.04)';this.style.boxShadow='none'">
                        <div class="w-12 h-12 rounded-xl flex-none flex items-center justify-center text-white shadow-sm"
                             style="background: 
                                {{ $booking->status === 'confirmed' ? '#059669' :
                                   ($booking->status === 'completed' ? '#2563eb' :
                                   (in_array($booking->status, ['pending_payment', 'under_review', 'waiting_confirmation']) ? '#d97706' : '#dc2626')) }}">
                            <i class="fas {{ $booking->status === 'confirmed' ? 'fa-check' :
                                            ($booking->status === 'completed' ? 'fa-flag-checkered' :
                                            (in_array($booking->status, ['pending_payment', 'under_review', 'waiting_confirmation']) ? 'fa-clock' : 'fa-times')) }}"></i>
                        </div>
                        <div class="flex-1 min-w-0 flex flex-col justify-center">
                            <p class="font-bold text-sm text-secondary-900 truncate mb-1">{{ $booking->user->name }}</p>
                            <p class="text-xs text-secondary-500 truncate">{{ $booking->lapangan->nama }} <span class="mx-1">•</span> {{ $booking->tanggal->format('d M') }}</p>
                        </div>
                        <div class="text-right flex-none flex flex-col justify-center">
                            <p class="text-sm font-bold text-secondary-900 mb-1">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</p>
                            <p class="text-[10px] text-secondary-400 font-medium">{{ $booking->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-6 text-secondary-400">
                        <p class="text-sm">Belum ada data booking.</p>
                    </div>
                    @endforelse
                </div>
                <a href="{{ route('admin.booking.index') }}" class="btn-secondary w-full mt-6 text-sm py-3">Lihat Semua Booking</a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('bookingChart');
        if(ctx) {
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($chartLabels) !!},
                    datasets: [{
                        label: 'Jumlah Booking',
                        data: {!! json_encode($chartData) !!},
                        backgroundColor: '#10b981',
                        borderRadius: 6,
                        borderWidth: 1,
                        borderColor: '#059669',
                        hoverBackgroundColor: '#059669'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1, color: '#94a3b8' },
                            grid: { color: 'rgba(0,0,0,0.04)', drawBorder: false }
                        },
                        x: {
                            ticks: { color: '#94a3b8' },
                            grid: { display: false }
                        }
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            titleColor: '#10b981',
                            bodyColor: '#ffffff',
                            padding: 12,
                            cornerRadius: 12,
                            displayColors: false
                        }
                    }
                }
            });
        }
    });
</script>
@endpush
@endsection