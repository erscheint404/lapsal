@extends('layouts.admin')
@section('title', 'Manajemen User')
@section('page_title', 'Manajemen User')

@section('content')
<div class="card-premium overflow-hidden">
    <div class="p-7 border-b border-dark-100/80 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <form action="{{ route('admin.user.index') }}" method="GET" class="flex flex-wrap gap-3 w-full md:w-auto">
            <div class="relative w-full md:w-64">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-dark-400"><i class="fas fa-search"></i></span>
                <input type="text" name="search" value="{{ request('search') }}" class="form-input py-2.5 pl-11" placeholder="Cari nama / email...">
            </div>
            <select name="role" class="form-input py-2.5" onchange="this.form.submit()">
                <option value="">Semua Role</option>
                <option value="member" {{ request('role') == 'member' ? 'selected' : '' }}>Member</option>
                <option value="petugas" {{ request('role') == 'petugas' ? 'selected' : '' }}>Petugas</option>
                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="table-modern">
            <thead>
                <tr>
                    <th class="py-4 px-6 text-xs font-bold text-dark-500 uppercase">User</th>
                    <th class="py-4 px-6 text-xs font-bold text-dark-500 uppercase">Kontak</th>
                    <th class="py-4 px-6 text-xs font-bold text-dark-500 uppercase">Role</th>
                    <th class="py-4 px-6 text-xs font-bold text-dark-500 uppercase">Bergabung</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-dark-100/80">
                @forelse($users as $user)
                <tr class="hover:bg-dark-50/50 transition-colors">
                    <td class="py-4 px-6">
                        <div class="flex items-center gap-3">
                            <img src="{{ $user->avatar_url }}" class="w-10 h-10 rounded-full object-cover bg-dark-100 ring-2 ring-dark-100">
                            <div>
                                <p class="font-bold text-dark-900">{{ $user->name }}</p>
                                @if($user->google_id)
                                <span class="text-[10px] bg-red-100/80 text-red-600 px-2 py-0.5 rounded-lg font-bold inline-flex items-center"><i class="fab fa-google mr-1"></i> Google Auth</span>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="py-4 px-6">
                        <p class="text-sm font-medium text-dark-900">{{ $user->email }}</p>
                        <p class="text-xs text-dark-500">{{ $user->phone ?? '-' }}</p>
                    </td>
                    <td class="py-4 px-6">
                        <span class="badge {{ $user->role === 'admin' ? 'badge-primary' : ($user->role === 'petugas' ? 'bg-blue-100 text-blue-800 border-blue-200/80' : 'badge-success') }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>
                    <td class="py-4 px-6 text-sm text-dark-600">
                        {{ $user->created_at->format('d M Y') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="py-12 text-center text-dark-500">Data user tidak ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-6 border-t border-dark-100/80">
        {{ $users->links() }}
    </div>
</div>
@endsection