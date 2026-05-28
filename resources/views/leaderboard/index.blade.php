@extends('layouts.app')
@section('title', 'Leaderboard')
@section('content')
<div class="bg-dark-900 min-h-screen pt-12 pb-24 relative overflow-hidden text-white">
    {{-- Background Effects --}}
    <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-20"></div>
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[400px] bg-gradient-to-b from-amber-500/20 to-transparent rounded-[100%] blur-[100px] -z-10"></div>
    <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-emerald-500 rounded-full mix-blend-multiply filter blur-[128px] opacity-20 -z-10"></div>
    <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-primary-500 rounded-full mix-blend-multiply filter blur-[128px] opacity-20 -z-10"></div>

    <div class="container-custom relative z-10">
        <div class="text-center max-w-2xl mx-auto mb-16">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-amber-500/10 border-2 border-amber-500/50 rounded-2xl mb-6 shadow-xl shadow-amber-500/20">
                <i class="fas fa-trophy text-4xl text-amber-400"></i>
            </div>
            <h1 class="text-4xl md:text-5xl font-display font-black text-transparent bg-clip-text bg-gradient-to-br from-amber-200 via-amber-400 to-amber-600 mb-4">
                Top Scorer Leaderboard
            </h1>
            <p class="text-dark-300 text-lg">Pemain dengan catatan gol terbanyak sepanjang masa di Lapsal Futsal. Bermainlah, cetak gol, dan catatkan namamu di sini!</p>
        </div>

        {{-- Top 3 Podium --}}
        @if($leaderboard->count() >= 3)
        <div class="flex flex-col md:flex-row items-end justify-center gap-4 md:gap-8 mb-20 px-4 mt-32 md:mt-12 h-64 md:h-auto">
            {{-- Rank 2 --}}
            @php $rank2 = $leaderboard->get(1); @endphp
            @if($rank2)
            <div class="w-full md:w-48 flex flex-col items-center order-2 md:order-1 transform md:-translate-y-12">
                <div class="relative w-20 h-20 rounded-full bg-dark-800 border-4 border-dark-300 flex items-center justify-center mb-[-2rem] z-10 shadow-[0_0_30px_rgba(156,163,175,0.3)]">
                    <i class="fas fa-user text-3xl text-dark-400"></i>
                    <div class="absolute -bottom-3 w-8 h-8 rounded-full bg-dark-300 border-2 border-dark-900 flex items-center justify-center font-black text-sm text-dark-800">2</div>
                </div>
                <div class="w-full bg-gradient-to-t from-dark-800 to-dark-700/50 rounded-t-3xl pt-12 pb-6 px-4 text-center border border-dark-600/50 backdrop-blur-sm h-48 flex flex-col justify-between">
                    <div>
                        <p class="font-bold text-lg text-white truncate w-full">{{ $rank2->nama_pemain }}</p>
                        <p class="text-xs text-dark-400">{{ $rank2->total_sesi }} Sesi</p>
                    </div>
                    <div class="mt-auto">
                        <p class="text-3xl font-display font-black text-dark-300">{{ $rank2->total_gol }}</p>
                        <p class="text-[10px] uppercase tracking-widest text-dark-500 font-bold">Gol</p>
                    </div>
                </div>
            </div>
            @endif

            {{-- Rank 1 --}}
            @php $rank1 = $leaderboard->get(0); @endphp
            @if($rank1)
            <div class="w-full md:w-56 flex flex-col items-center order-1 md:order-2 z-20 md:-mt-8">
                <div class="absolute -top-16 text-amber-400 animate-bounce"><i class="fas fa-crown text-4xl filter drop-shadow-[0_0_15px_rgba(251,191,36,0.8)]"></i></div>
                <div class="relative w-28 h-28 rounded-full bg-dark-800 border-4 border-amber-400 flex items-center justify-center mb-[-2.5rem] z-10 shadow-[0_0_40px_rgba(251,191,36,0.4)]">
                    <i class="fas fa-user text-5xl text-dark-400"></i>
                    <div class="absolute -bottom-3 w-10 h-10 rounded-full bg-amber-400 border-4 border-dark-900 flex items-center justify-center font-black text-lg text-dark-900">1</div>
                </div>
                <div class="w-full bg-gradient-to-t from-amber-900/40 to-amber-600/20 rounded-t-3xl pt-14 pb-8 px-4 text-center border border-amber-500/30 backdrop-blur-md h-60 flex flex-col justify-between shadow-[0_-10px_40px_rgba(251,191,36,0.15)] relative overflow-hidden">
                    <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/stardust.png')] opacity-30 mix-blend-overlay"></div>
                    <div class="relative z-10">
                        <p class="font-bold text-xl text-amber-400 truncate w-full drop-shadow-md">{{ $rank1->nama_pemain }}</p>
                        <p class="text-xs text-amber-200/60">{{ $rank1->total_sesi }} Sesi</p>
                    </div>
                    <div class="mt-auto relative z-10">
                        <p class="text-5xl font-display font-black text-amber-400 drop-shadow-[0_0_10px_rgba(251,191,36,0.5)]">{{ $rank1->total_gol }}</p>
                        <p class="text-[12px] uppercase tracking-widest text-amber-500/80 font-bold mt-1">Gol</p>
                    </div>
                </div>
            </div>
            @endif

            {{-- Rank 3 --}}
            @php $rank3 = $leaderboard->get(2); @endphp
            @if($rank3)
            <div class="w-full md:w-48 flex flex-col items-center order-3 transform md:-translate-y-8">
                <div class="relative w-20 h-20 rounded-full bg-dark-800 border-4 border-amber-700 flex items-center justify-center mb-[-2rem] z-10 shadow-[0_0_30px_rgba(180,83,9,0.3)]">
                    <i class="fas fa-user text-3xl text-dark-400"></i>
                    <div class="absolute -bottom-3 w-8 h-8 rounded-full bg-amber-700 border-2 border-dark-900 flex items-center justify-center font-black text-sm text-white">3</div>
                </div>
                <div class="w-full bg-gradient-to-t from-amber-900/30 to-amber-800/10 rounded-t-3xl pt-12 pb-6 px-4 text-center border border-amber-700/50 backdrop-blur-sm h-40 flex flex-col justify-between">
                    <div>
                        <p class="font-bold text-lg text-white truncate w-full">{{ $rank3->nama_pemain }}</p>
                        <p class="text-xs text-amber-700/80">{{ $rank3->total_sesi }} Sesi</p>
                    </div>
                    <div class="mt-auto">
                        <p class="text-3xl font-display font-black text-amber-600">{{ $rank3->total_gol }}</p>
                        <p class="text-[10px] uppercase tracking-widest text-amber-700 font-bold">Gol</p>
                    </div>
                </div>
            </div>
            @endif
        </div>
        @endif

        {{-- List --}}
        <div class="max-w-4xl mx-auto bg-dark-800/50 border border-dark-700 rounded-3xl backdrop-blur-xl overflow-hidden shadow-2xl">
            <div class="p-6 border-b border-dark-700/50 flex items-center justify-between bg-dark-800/80">
                <h3 class="font-bold text-lg">Peringkat 4 - {{ max(3, $leaderboard->lastItem() ?? 0) }}</h3>
                <span class="text-sm text-dark-400">Total {{ $leaderboard->total() }} Pemain</span>
            </div>
            
            <div class="divide-y divide-dark-700/50">
                @foreach($leaderboard->skip(3) as $index => $l)
                <div class="flex items-center gap-4 p-4 hover:bg-dark-700/30 transition-colors group">
                    <div class="w-12 h-12 flex-none flex items-center justify-center font-display font-bold text-xl text-dark-400 group-hover:text-white transition-colors">
                        {{ $leaderboard->firstItem() + $index + 3 }}
                    </div>
                    <div class="w-12 h-12 flex-none rounded-xl bg-dark-700 flex items-center justify-center text-dark-400">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="flex-1">
                        <p class="font-bold text-lg text-white">{{ $l->nama_pemain }}</p>
                        <p class="text-xs text-dark-400">{{ $l->total_sesi }} Sesi Bermain</p>
                    </div>
                    <div class="text-right px-6 py-2 rounded-2xl bg-dark-900/50 border border-dark-700 flex items-baseline gap-2">
                        <span class="font-display font-black text-2xl text-emerald-400">{{ $l->total_gol }}</span>
                        <span class="text-xs font-bold text-dark-500 uppercase">Gol</span>
                    </div>
                </div>
                @endforeach

                @if($leaderboard->count() <= 3)
                <div class="p-12 text-center text-dark-400">
                    <i class="fas fa-ghost text-4xl mb-4 opacity-50"></i>
                    <p>Belum ada pemain lain dalam daftar.</p>
                </div>
                @endif
            </div>
            
            @if($leaderboard->hasPages())
            <div class="p-4 border-t border-dark-700/50 bg-dark-800/80 pagination-dark">
                {{ $leaderboard->links() }}
            </div>
            @endif
        </div>

    </div>
</div>

<style>
    .pagination-dark nav > div > div > p { color: #9ca3af; }
    .pagination-dark span[aria-current="page"] > span { background-color: #10b981; border-color: #10b981; color: white; }
    .pagination-dark a { background-color: transparent; border-color: #374151; color: #d1d5db; }
    .pagination-dark a:hover { background-color: #374151; color: white; }
</style>
@endsection
