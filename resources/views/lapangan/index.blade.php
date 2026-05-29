@extends('layouts.app')
@section('title', 'Daftar Lapangan')
@section('content')
{{-- Header with dark background --}}
<section class="section-dark pt-32 pb-16 relative">
    <div class="absolute inset-0 dot-pattern opacity-20"></div>
    <div class="absolute -bottom-20 left-1/3 w-80 h-80 rounded-full blur-[150px]" style="background: rgba(204,255,0,0.05);"></div>
    <div class="container-custom relative z-10">
        <div class="max-w-2xl">
            <span class="text-sm font-bold uppercase tracking-widest mb-3 block" style="color: #ccff00;">Temukan Lapanganmu</span>
            <h1 class="text-4xl md:text-5xl font-display font-bold text-white tracking-tight mb-4">Pilih Lapangan Futsal</h1>
            <p class="text-dark-300 text-lg leading-relaxed">Pilih dari berbagai jenis lapangan dengan kualitas terbaik untuk timmu.</p>
        </div>
    </div>
</section>

<div class="bg-dark-50 py-12 -mt-1">
    <div class="container-custom">
        {{-- Search & Filter --}}
        <div class="card-premium p-5 flex flex-col md:flex-row gap-4 mb-10 -mt-8 relative z-20 shadow-xl">
            <form action="{{ route('lapangan.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 w-full">
                <div class="flex-1 relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-dark-400"><i class="fas fa-search"></i></span>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-input pl-11" placeholder="Cari nama lapangan...">
                </div>
                <div class="w-full md:w-48">
                    <select name="tipe" class="form-input">
                        <option value="">Semua Tipe</option>
                        <option value="vinyl" {{ request('tipe') == 'vinyl' ? 'selected' : '' }}>Vinyl</option>
                        <option value="rumput_sintetis" {{ request('tipe') == 'rumput_sintetis' ? 'selected' : '' }}>Rumput Sintetis</option>
                        <option value="semen" {{ request('tipe') == 'semen' ? 'selected' : '' }}>Semen</option>
                        <option value="parquette" {{ request('tipe') == 'parquette' ? 'selected' : '' }}>Parquette (Kayu)</option>
                    </select>
                </div>
                <div class="w-full md:w-48">
                    <select name="sort" class="form-input">
                        <option value="">Terbaru</option>
                        <option value="harga_asc" {{ request('sort') == 'harga_asc' ? 'selected' : '' }}>Harga Terendah</option>
                        <option value="harga_desc" {{ request('sort') == 'harga_desc' ? 'selected' : '' }}>Harga Tertinggi</option>
                        <option value="nama" {{ request('sort') == 'nama' ? 'selected' : '' }}>Nama A-Z</option>
                    </select>
                </div>
                <button type="submit" class="btn-primary w-full md:w-auto"><i class="fas fa-filter mr-2"></i>Filter</button>
                @if(request()->anyFilled(['search', 'tipe', 'sort']))
                <a href="{{ route('lapangan.index') }}" class="btn-secondary w-full md:w-auto px-4"><i class="fas fa-times"></i></a>
                @endif
            </form>
        </div>

        {{-- Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($lapangan as $l)
            <a href="{{ route('lapangan.show', $l->id) }}" class="group block h-full reveal" style="transition-delay: {{ $loop->index * 80 }}ms;">
                <div class="card-hover h-full overflow-hidden flex flex-col bg-white">
                    <div class="relative h-56 overflow-hidden">
                        @if($l->foto_utama)
                        <img src="{{ Storage::url($l->foto_utama) }}" alt="{{ $l->nama }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                        @else
                        <div class="w-full h-full flex items-center justify-center" style="background: linear-gradient(135deg, #1a2740, #0f1b2e);">
                            <i class="fas fa-futbol text-4xl text-dark-600"></i>
                        </div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-dark-950/30 to-transparent"></div>
                        <div class="absolute top-4 left-4">
                            <span class="badge badge-info shadow-lg backdrop-blur-md bg-white/90 text-dark-700">
                                {{ ucfirst(str_replace('_', ' ', $l->tipe)) }}
                            </span>
                        </div>
                    </div>
                    <div class="p-6 flex-1 flex flex-col">
                        <div class="flex justify-between items-start mb-3 gap-2">
                            <h3 class="font-display font-bold text-lg text-dark-900 line-clamp-2">{{ $l->nama }}</h3>
                            <div class="flex items-center gap-1 text-xs font-bold flex-none px-2.5 py-1 rounded-xl" style="background: rgba(204,255,0,0.1); color: #6e8f00;">
                                <i class="fas fa-star" style="color: #a3cc00;"></i> {{ $l->rata_rating }}
                            </div>
                        </div>
                        <div class="flex flex-wrap gap-2 mb-4">
                            @if(is_array($l->fasilitas))
                                @foreach(array_slice($l->fasilitas, 0, 3) as $fasilitas)
                                <span class="text-xs text-dark-500 bg-dark-50 px-2.5 py-1 rounded-lg border border-dark-100/60">{{ $fasilitas }}</span>
                                @endforeach
                                @if(count($l->fasilitas) > 3)
                                <span class="text-xs text-dark-500 bg-dark-50 px-2.5 py-1 rounded-lg border border-dark-100/60">+{{ count($l->fasilitas) - 3 }}</span>
                                @endif
                            @endif
                        </div>
                        <div class="mt-auto pt-4 flex items-center justify-between" style="border-top: 1px solid rgba(0,0,0,0.06);">
                            <div>
                                <p class="text-xs text-dark-400 font-medium uppercase tracking-wider">Harga / Jam</p>
                                <p class="font-bold text-lg" style="color: #6e8f00;">Rp {{ number_format($l->harga_per_jam, 0, ',', '.') }}</p>
                            </div>
                            <div class="btn-primary py-2.5 px-5 rounded-2xl shadow-none group-hover:shadow-lg transition-all text-sm">
                                Pesan <i class="fas fa-arrow-right ml-1.5 text-xs"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
            @empty
            <div class="col-span-full py-20 text-center">
                <div class="w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-4 text-4xl" style="background: rgba(204,255,0,0.08); color: #6e8f00;">
                    <i class="fas fa-search"></i>
                </div>
                <h3 class="text-xl font-bold text-dark-900 mb-2">Tidak ditemukan</h3>
                <p class="text-dark-500 mb-6">Coba ubah kata kunci atau filter pencarian.</p>
                <a href="{{ route('lapangan.index') }}" class="btn-primary">Reset Pencarian</a>
            </div>
            @endforelse
        </div>

        <div class="mt-10">
            {{ $lapangan->links() }}
        </div>
    </div>
</div>
@endsection