@extends('layouts.admin')
@section('title', 'Daftar Booking')
@section('page_title', 'Daftar Booking')

@section('content')
<div class="bg-white rounded-2xl border border-dark-100 shadow-sm overflow-hidden">
    {{-- Header & Filter --}}
    <div class="p-6 border-b border-dark-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <form action="{{ route('admin.booking.index') }}" method="GET" class="flex flex-wrap gap-3 w-full">
            <div class="relative w-full md:w-64">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-dark-400"><i class="fas fa-search"></i></span>
                <input type="text" name="search" value="{{ request('search') }}" class="form-input py-2 pl-10" placeholder="Kode / Nama Member">
            </div>
            
            <div class="w-full md:w-48">
                <select name="status" class="form-input py-2" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="pending_payment" {{ request('status') == 'pending_payment' ? 'selected' : '' }}>Pending Payment</option>
                    <option value="under_review" {{ request('status') == 'under_review' ? 'selected' : '' }}>Under Review</option>
                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            
            <div class="w-full md:w-40">
                <input type="date" name="tanggal" value="{{ request('tanggal') }}" class="form-input py-2" onchange="this.form.submit()">
            </div>

            @if(request()->anyFilled(['search', 'status', 'tanggal']))
            <a href="{{ route('admin.booking.index') }}" class="btn-secondary py-2 px-3"><i class="fas fa-times"></i></a>
            @endif
        </form>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-dark-50 border-b border-dark-100">
                    <th class="py-4 px-6 text-xs font-bold text-dark-500 uppercase tracking-wider">Booking ID</th>
                    <th class="py-4 px-6 text-xs font-bold text-dark-500 uppercase tracking-wider">Member</th>
                    <th class="py-4 px-6 text-xs font-bold text-dark-500 uppercase tracking-wider">Jadwal</th>
                    <th class="py-4 px-6 text-xs font-bold text-dark-500 uppercase tracking-wider">Total</th>
                    <th class="py-4 px-6 text-xs font-bold text-dark-500 uppercase tracking-wider">Status</th>
                    <th class="py-4 px-6 text-xs font-bold text-dark-500 uppercase tracking-wider text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-dark-100">
                @forelse($bookings as $booking)
                <tr class="hover:bg-dark-50/50 transition-colors">
                    <td class="py-4 px-6">
                        <span class="font-mono font-bold text-dark-900">{{ $booking->kode_booking }}</span>
                        <div class="text-xs text-dark-400 mt-1">{{ $booking->created_at->format('d/m/Y H:i') }}</div>
                    </td>
                    <td class="py-4 px-6">
                        <div class="font-bold text-dark-900 mb-1">{{ $booking->user->name }}</div>
                        <div class="text-xs text-dark-500">{{ $booking->user->phone ?? '-' }}</div>
                    </td>
                    <td class="py-4 px-6">
                        <div class="text-sm font-medium text-primary-700 mb-1">{{ $booking->lapangan->nama }}</div>
                        <div class="text-xs text-dark-500">{{ $booking->tanggal->format('d M Y') }} • {{ substr($booking->jam_mulai, 0, 5) }}-{{ substr($booking->jam_selesai, 0, 5) }}</div>
                    </td>
                    <td class="py-4 px-6">
                        <span class="font-bold text-dark-900">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</span>
                    </td>
                    <td class="py-4 px-6">
                        <span class="badge {{ $booking->status_color }} whitespace-nowrap">
                            {{ $booking->status_label }}
                        </span>
                    </td>
                    <td class="py-4 px-6 text-right">
                        <a href="{{ route('admin.booking.show', $booking->id) }}" class="btn-secondary btn-sm whitespace-nowrap">
                            Detail <i class="fas fa-chevron-right ml-1"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-12 text-center text-dark-500">
                        Belum ada data booking.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="p-6 border-t border-dark-100">
        {{ $bookings->links() }}
    </div>
</div>
@endsection
