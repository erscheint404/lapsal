@extends('layouts.app')
@section('title', 'Notifikasi')
@section('content')
<div class="bg-dark-50 py-12 min-h-screen">
    <div class="container-custom max-w-3xl">
        <div class="mb-8 flex items-center justify-between">
            <h1 class="text-3xl font-display font-bold text-dark-900">Notifikasi</h1>
            
            @if($notifikasi->where('is_read', false)->count() > 0)
            <form action="{{ route('member.notifikasi.readAll') }}" method="POST">
                @csrf
                <button type="submit" class="btn-secondary btn-sm">Tandai Semua Dibaca</button>
            </form>
            @endif
        </div>

        <div class="card p-0 overflow-hidden">
            @if($notifikasi->count() > 0)
            <div class="divide-y divide-dark-100">
                @foreach($notifikasi as $notif)
                <div class="p-4 flex gap-4 transition-colors {{ $notif->is_read ? 'bg-white' : 'bg-primary-50 hover:bg-primary-100/50' }}">
                    <div class="w-10 h-10 rounded-full flex-none flex items-center justify-center text-white shadow-md
                        {{ $notif->tipe === 'booking' ? 'bg-blue-500' : 
                          ($notif->tipe === 'payment' ? 'bg-amber-500' : 
                          ($notif->tipe === 'system' ? 'bg-dark-800' : 'bg-primary-500')) }}">
                        <i class="fas {{ $notif->tipe === 'booking' ? 'fa-calendar-check' : 
                                       ($notif->tipe === 'payment' ? 'fa-wallet' : 
                                       ($notif->tipe === 'system' ? 'fa-cog' : 'fa-info')) }}"></i>
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between items-start mb-1">
                            <h4 class="font-bold text-dark-900 {{ !$notif->is_read ? 'text-primary-800' : '' }}">{{ $notif->judul }}</h4>
                            <span class="text-xs text-dark-400 whitespace-nowrap ml-2">{{ $notif->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-sm text-dark-600 mb-2">{{ $notif->pesan }}</p>
                        
                        <div class="flex items-center justify-between">
                            @if(isset($notif->data['booking_id']))
                            <a href="{{ route('member.booking.show', $notif->data['booking_id']) }}" class="text-xs font-bold text-primary-600 hover:underline">
                                Lihat Detail
                            </a>
                            @else
                            <div></div>
                            @endif
                            
                            @if(!$notif->is_read)
                            <form action="{{ route('member.notifikasi.read', $notif->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="text-xs text-dark-400 hover:text-dark-700 font-medium">Tandai Dibaca</button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <div class="p-4 border-t border-dark-100 bg-dark-50">
                {{ $notifikasi->links() }}
            </div>
            @else
            <div class="p-12 text-center text-dark-500">
                <div class="w-16 h-16 mx-auto bg-dark-50 rounded-full flex items-center justify-center text-2xl text-dark-300 mb-4">
                    <i class="far fa-bell-slash"></i>
                </div>
                <h3 class="font-bold text-lg text-dark-800 mb-1">Kosong</h3>
                <p class="text-sm">Anda tidak memiliki notifikasi saat ini.</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
