@extends('layouts.admin')
@section('title', 'Pengaturan Aplikasi')
@section('page_title', 'Pengaturan Web')

@section('content')
<div class="card-premium overflow-hidden max-w-3xl">
    <div class="p-7 border-b border-dark-100/80">
        <h2 class="font-bold text-lg text-dark-900">Konfigurasi Umum</h2>
        <p class="text-sm text-dark-500 mt-1 leading-relaxed">Ubah nama, kontak, dan jam operasional aplikasi.</p>
    </div>

    <form action="{{ route('admin.pengaturan.update') }}" method="POST" class="p-7 space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label class="form-label">Nama Aplikasi</label>
                <input type="text" name="pengaturan[nama_web]" value="{{ \App\Models\Pengaturan::getValue('nama_web', 'Lapsal Futsal') }}" class="form-input" required>
            </div>

            <div class="md:col-span-2">
                <label class="form-label">Alamat Lengkap</label>
                <textarea name="pengaturan[alamat]" rows="3" class="form-textarea" required>{{ \App\Models\Pengaturan::getValue('alamat', 'Jl. Futsal No. 1, Jakarta') }}</textarea>
            </div>

            <div>
                <label class="form-label">No. Telepon / WhatsApp</label>
                <input type="text" name="pengaturan[telepon]" value="{{ \App\Models\Pengaturan::getValue('telepon', '082215042019') }}" class="form-input" required>
            </div>

            <div>
                <label class="form-label">Email Kontak</label>
                <input type="email" name="pengaturan[email]" value="{{ \App\Models\Pengaturan::getValue('email', 'info@lapsal.com') }}" class="form-input" required>
            </div>

            <div class="md:col-span-2 border-t border-dark-100/80 pt-6 mt-2">
                <h3 class="font-bold text-dark-900 mb-4">Jam Operasional Lapangan</h3>
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="form-label">Jam Buka</label>
                        <div class="relative">
                            <input type="number" name="pengaturan[jam_buka]" value="{{ \App\Models\Pengaturan::getValue('jam_buka', '08') }}" class="form-input pl-12" min="0" max="23" required>
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 font-bold text-dark-400">Jam</span>
                        </div>
                    </div>
                    <div>
                        <label class="form-label">Jam Tutup</label>
                        <div class="relative">
                            <input type="number" name="pengaturan[jam_tutup]" value="{{ \App\Models\Pengaturan::getValue('jam_tutup', '23') }}" class="form-input pl-12" min="1" max="24" required>
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 font-bold text-dark-400">Jam</span>
                        </div>
                    </div>
                </div>
                <p class="form-help mt-2 text-amber-600"><i class="fas fa-info-circle mr-1"></i> Perubahan jam operasional tidak akan mempengaruhi slot waktu yang sudah digenerate sebelumnya.</p>
            </div>
        </div>

        <div class="pt-6 border-t border-dark-100/80 text-right">
            <button type="submit" class="btn-primary shadow-lg shadow-primary-500/25"><i class="fas fa-save mr-2"></i>Simpan Pengaturan</button>
        </div>
    </form>
</div>
@endsection