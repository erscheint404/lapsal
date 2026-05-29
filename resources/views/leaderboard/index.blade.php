@extends('layouts.app')
@section('title', 'Leaderboard')
@section('content')
<div class="section-dark min-h-screen pt-32 pb-24 relative overflow-hidden">
    <div class="absolute inset-0 grid-pattern"></div>
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[400px] rounded-[100%] blur-[150px]" style="background: rgba(204,255,0,0.06);"></div>
    <div class="absolute -bottom-40 -left-40 w-96 h-96 rounded-full blur-[150px]" style="background: rgba(0,229,255,0.04);"></div>
    <div class="absolute -bottom-40 -right-40 w-96 h-96 rounded-full blur-[150px]" style="background: rgba(204,255,0,0.04);"></div>

    <div class="container-custom relative z-10">
        <div class="text-center max-w-2xl mx-auto mb-16 reveal">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl mb-6 animate-pulse-glow"
                 style="background: rgba(204,255,0,0.08); border: 1px solid rgba(204,255,0,0.2);">
                <i class="fas fa-trophy text-4xl" style="color: #ccff00;"></i>
            </div>
            <h1 class="text-4xl md:text-5xl font-display font-black text-white tracking-tight mb-4 text-glow">
                Top Scorer Leaderboard
            </h1>
            <p class="text-dark-300 text-lg leading-relaxed">Pemain dengan catatan gol terbanyak sepanjang masa di Lapsal Futsal.</p>
        </div>

        {{-- Top 3 Podium --}}
        @if($leaderboard->count() >= 3)
        <div class="flex flex-col md:flex-row items-end justify-center gap-4 md:gap-8 mb-20 px-4 mt-32 md:mt-12 h-64 md:h-auto reveal-scale">
            {{-- Rank 2 --}}
            @php $rank2 = $leaderboard->get(1); @endphp
            @if($rank2)
            <div class="w-full md:w-48 flex flex-col items-center order-2 md:order-1 transform md:-translate-y-12">
                <div class="relative w-20 h-20 rounded-full flex items-center justify-center mb-[-2rem] z-10"
                     style="background: #1a2740; border: 3px solid #b8c9dd; box-shadow: 0 0 30px rgba(184,201,221,0.2);">
                    <i class="fas fa-user text-3xl text-dark-400"></i>
                    <div class="absolute -bottom-3 w-8 h-8 rounded-full flex items-center justify-center font-black text-sm" style="background: #b8c9dd; color: #0a1221;">2</div>
                </div>
                <div class="w-full rounded-t-3xl pt-12 pb-6 px-5 text-center h-48 flex flex-col justify-between"
                     style="background: linear-gradient(180deg, rgba(184,201,221,0.08), rgba(26,39,64,0.5)); border: 1px solid rgba(184,201,221,0.15);">
                    <div>
                        <p class="font-bold text-lg text-white truncate w-full">{{ $rank2->nama_pemain }}</p>
                        <p class="text-xs text-dark-400">{{ $rank2->total_sesi }} Sesi</p>
                    </div>
                    <div class="mt-auto">
                        <p class="text-3xl font-display font-black text-dark-200">{{ $rank2->total_gol }}</p>
                        <p class="text-[10px] uppercase tracking-widest text-dark-500 font-bold">Gol</p>
                    </div>
                </div>
            </div>
            @endif

            {{-- Rank 1 --}}
            @php $rank1 = $leaderboard->get(0); @endphp
            @if($rank1)
            <div class="w-full md:w-56 flex flex-col items-center order-1 md:order-2 z-20 md:-mt-8">
                <div class="absolute -top-16" style="color: #ccff00;"><i class="fas fa-crown text-4xl" style="filter: drop-shadow(0 0 15px rgba(204,255,0,0.6));"></i></div>
                <div class="relative w-28 h-28 rounded-full flex items-center justify-center mb-[-2.5rem] z-10"
                     style="background: #1a2740; border: 4px solid #ccff00; box-shadow: 0 0 50px rgba(204,255,0,0.25);">
                    <i class="fas fa-user text-5xl text-dark-400"></i>
                    <div class="absolute -bottom-3 w-10 h-10 rounded-full flex items-center justify-center font-black text-lg" style="background: #ccff00; color: #0a1221; border: 3px solid #0f1b2e;">1</div>
                </div>
                <div class="w-full rounded-t-3xl pt-14 pb-8 px-5 text-center h-60 flex flex-col justify-between relative overflow-hidden"
                     style="background: linear-gradient(180deg, rgba(204,255,0,0.06), rgba(15,27,46,0.6)); border: 1px solid rgba(204,255,0,0.2); box-shadow: 0 -10px 40px rgba(204,255,0,0.08);">
                    <div class="absolute inset-0 dot-pattern opacity-10"></div>
                    <div class="relative z-10">
                        <p class="font-bold text-xl truncate w-full" style="color: #ccff00; text-shadow: 0 0 20px rgba(204,255,0,0.3);">{{ $rank1->nama_pemain }}</p>
                        <p class="text-xs" style="color: rgba(204,255,0,0.5);">{{ $rank1->total_sesi }} Sesi</p>
                    </div>
                    <div class="mt-auto relative z-10">
                        <p class="text-5xl font-display font-black" style="color: #ccff00; text-shadow: 0 0 30px rgba(204,255,0,0.4);">{{ $rank1->total_gol }}</p>
                        <p class="text-[12px] uppercase tracking-widest font-bold mt-1" style="color: rgba(204,255,0,0.6);">Gol</p>
                    </div>
                </div>
            </div>
            @endif

            {{-- Rank 3 --}}
            @php $rank3 = $leaderboard->get(2); @endphp
            @if($rank3)
            <div class="w-full md:w-48 flex flex-col items-center order-3 transform md:-translate-y-8">
                <div class="relative w-20 h-20 rounded-full flex items-center justify-center mb-[-2rem] z-10"
                     style="background: #1a2740; border: 3px solid #8da3bd; box-shadow: 0 0 25px rgba(141,163,189,0.15);">
                    <i class="fas fa-user text-3xl text-dark-400"></i>
                    <div class="absolute -bottom-3 w-8 h-8 rounded-full flex items-center justify-center font-black text-sm" style="background: #8da3bd; color: #0a1221;">3</div>
                </div>
                <div class="w-full rounded-t-3xl pt-12 pb-6 px-5 text-center h-40 flex flex-col justify-between"
                     style="background: linear-gradient(180deg, rgba(141,163,189,0.06), rgba(26,39,64,0.4)); border: 1px solid rgba(141,163,189,0.12);">
                    <div>
                        <p class="font-bold text-lg text-white truncate w-full">{{ $rank3->nama_pemain }}</p>
                        <p class="text-xs text-dark-500">{{ $rank3->total_sesi }} Sesi</p>
                    </div>
                    <div class="mt-auto">
                        <p class="text-3xl font-display font-black text-dark-300">{{ $rank3->total_gol }}</p>
                        <p class="text-[10px] uppercase tracking-widest text-dark-500 font-bold">Gol</p>
                    </div>
                </div>
            </div>
            @endif
        </div>
        @endif

        {{-- Rest of leaderboard --}}
        <div class="max-w-4xl mx-auto rounded-3xl overflow-hidden shadow-2xl reveal"
             style="background: rgba(26,39,64,0.5); border: 1px solid rgba(255,255,255,0.06); backdrop-filter: blur(10px);">
            <div class="p-6 flex items-center justify-between" style="border-bottom: 1px solid rgba(255,255,255,0.06); background: rgba(26,39,64,0.5);">
                <h3 class="font-bold text-lg text-white">Peringkat Selanjutnya</h3>
                <span class="text-sm text-dark-400">Total {{ $leaderboard->total() }} Pemain</span>
            </div>

            @php $skipCount = $leaderboard->count() >= 3 ? 3 : 0; @endphp
            <div class="divide-y" style="--tw-divide-opacity: 1; border-color: rgba(255,255,255,0.04);">
                @foreach($leaderboard->skip($skipCount) as $index => $l)
                <div class="flex items-center gap-4 p-5 hover:bg-white/[0.03] transition-colors group" style="border-color: rgba(255,255,255,0.04);">
                    <div class="w-12 h-12 flex-none flex items-center justify-center font-display font-bold text-xl text-dark-400 group-hover:text-white transition-colors">
                        {{ $leaderboard->firstItem() + $index + $skipCount }}
                    </div>
                    <div class="w-12 h-12 flex-none rounded-xl flex items-center justify-center text-dark-400 group-hover:bg-dark-600 transition-colors" style="background: rgba(255,255,255,0.04);">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-lg text-white truncate">{{ $l->nama_pemain }}</p>
                        <p class="text-xs text-dark-400">{{ $l->total_sesi }} Sesi Bermain</p>
                    </div>
                    <div class="text-right px-6 py-2.5 rounded-2xl flex items-baseline gap-2 flex-none" style="background: rgba(204,255,0,0.05); border: 1px solid rgba(204,255,0,0.1);">
                        <span class="font-display font-black text-2xl" style="color: #ccff00;">{{ $l->total_gol }}</span>
                        <span class="text-xs font-bold text-dark-500 uppercase">Gol</span>
                    </div>
                </div>
                @endforeach

                @if($leaderboard->count() == 0)
                <div class="p-12 text-center text-dark-400">
                    <i class="fas fa-ghost text-4xl mb-4 opacity-50"></i>
                    <p class="text-dark-500">Belum ada pemain lain dalam daftar.</p>
                </div>
                @endif
            </div>

            @if($leaderboard->hasPages())
            <div class="p-5 pagination-dark" style="border-top: 1px solid rgba(255,255,255,0.06); background: rgba(26,39,64,0.5);">
                {{ $leaderboard->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection