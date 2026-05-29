@extends('layouts.admin')
@section('title', 'Edit Lapangan')
@section('page_title', 'Edit Lapangan')
@section('page_description', 'Perbarui informasi detail lapangan futsal.')

@section('page_actions')
<a href="{{ route('admin.lapangan.index') }}" class="btn-secondary">
    <i class="fas fa-arrow-left mr-2"></i> Kembali
</a>
@endsection

@section('content')
<form action="{{ route('admin.lapangan.update', $lapangan->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Form Fields --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="card-premium p-8">
                <h3 class="font-bold text-lg text-dark-900 mb-6 border-b border-dark-100/60 pb-3">Informasi Utama</h3>

                <div class="space-y-5">
                    <div>
                        <label class="form-label">Nama Lapangan <span class="text-red-500">*</span></label>
                        <input type="text" name="nama" class="form-input" value="{{ old('nama', $lapangan->nama) }}" required>
                        @error('nama')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="form-label">Tipe Lapangan <span class="text-red-500">*</span></label>
                            <select name="tipe" class="form-input" required>
                                <option value="vinyl" {{ old('tipe', $lapangan->tipe) == 'vinyl' ? 'selected' : '' }}>Vinyl</option>
                                <option value="rumput_sintetis" {{ old('tipe', $lapangan->tipe) == 'rumput_sintetis' ? 'selected' : '' }}>Rumput Sintetis</option>
                                <option value="semen" {{ old('tipe', $lapangan->tipe) == 'semen' ? 'selected' : '' }}>Semen / Beton</option>
                                <option value="parquette" {{ old('tipe', $lapangan->tipe) == 'parquette' ? 'selected' : '' }}>Parquette (Kayu)</option>
                            </select>
                            @error('tipe')<p class="form-error">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="form-label">Harga per Jam (Rp) <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 font-bold text-dark-400">Rp</span>
                                <input type="number" name="harga_per_jam" class="form-input pl-11" value="{{ old('harga_per_jam', $lapangan->harga_per_jam) }}" min="0" required>
                            </div>
                            @error('harga_per_jam')<p class="form-error">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div>
                        <label class="form-label">Deskripsi Lapangan</label>
                        <textarea name="deskripsi" class="form-textarea h-32">{{ old('deskripsi', $lapangan->deskripsi) }}</textarea>
                        @error('deskripsi')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <div class="card-premium p-8">
                <h3 class="font-bold text-lg text-dark-900 mb-6 border-b border-dark-100/60 pb-3">Fasilitas (Pisahkan dengan koma)</h3>
                <div>
                    @php $fasilitasStr = is_array($lapangan->fasilitas) ? implode(', ', $lapangan->fasilitas) : $lapangan->fasilitas; @endphp
                    <input type="text" name="fasilitas" class="form-input" value="{{ old('fasilitas', $fasilitasStr) }}">
                    <p class="text-xs text-dark-500 mt-2"><i class="fas fa-info-circle mr-1"></i> Pisahkan setiap fasilitas dengan tanda koma (,)</p>
                    @error('fasilitas')<p class="form-error">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        {{-- Sidebar Photo --}}
        <div class="lg:col-span-1">
            <div class="card-premium p-6 sticky top-28">
                <h3 class="font-bold text-lg text-dark-900 mb-5 border-b border-dark-100/60 pb-3">Foto Utama Lapangan</h3>

                <div class="mb-5">
                    <div class="relative w-full">
                        <input type="file" name="foto_utama" id="foto_utama" class="hidden" accept="image/*" onchange="previewMainImage(event)">
                        <label for="foto_utama" class="flex flex-col items-center justify-center w-full aspect-video border-2 border-dashed rounded-2xl cursor-pointer transition-all duration-300"
                               style="background: rgba(0,0,0,0.02); border-color: rgba(0,0,0,0.1);"
                               onmouseover="this.style.background='rgba(204,255,0,0.02)';this.style.borderColor='rgba(204,255,0,0.3)'"
                               onmouseout="this.style.background='rgba(0,0,0,0.02)';this.style.borderColor='rgba(0,0,0,0.1)'">
                            @if($lapangan->foto_utama)
                            <div id="main-image-preview-container" class="flex w-full h-full p-2 relative">
                                <img id="main-image-preview" src="{{ Storage::url($lapangan->foto_utama) }}" class="w-full h-full object-cover rounded-xl shadow-sm">
                                <div class="absolute top-4 right-4 bg-white/90 p-2 rounded-lg shadow-sm text-xs font-bold text-dark-900 backdrop-blur-sm cursor-pointer border border-dark-100">
                                    Ganti Foto
                                </div>
                            </div>
                            @else
                            <div class="flex flex-col items-center justify-center pt-5 pb-6" id="upload-main-placeholder">
                                <div class="w-12 h-12 rounded-xl mb-3 flex items-center justify-center" style="background: rgba(204,255,0,0.1); color: #6e8f00;">
                                    <i class="fas fa-image text-2xl"></i>
                                </div>
                                <p class="mb-1 text-sm font-bold text-dark-900">Upload Foto Utama</p>
                                <p class="text-[10px] text-dark-500">JPG, PNG atau JPEG</p>
                            </div>
                            <div id="main-image-preview-container" class="hidden w-full h-full p-2 relative">
                                <img id="main-image-preview" class="w-full h-full object-cover rounded-xl shadow-sm">
                                <div class="absolute top-4 right-4 bg-white/90 p-2 rounded-lg shadow-sm text-xs font-bold text-dark-900 backdrop-blur-sm cursor-pointer border border-dark-100">
                                    Ganti
                                </div>
                            </div>
                            @endif
                        </label>
                    </div>
                    @error('foto_utama')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="pt-4" style="border-top: 1px solid rgba(0,0,0,0.06);">
                    <button type="submit" class="btn-primary w-full text-sm py-3.5 shadow-lg shadow-primary-500/20">
                        <i class="fas fa-save mr-2"></i> Update Lapangan
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
    function previewMainImage(event) {
        const input = event.target;
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const placeholder = document.getElementById('upload-main-placeholder');
                if(placeholder) placeholder.classList.add('hidden');
                
                const container = document.getElementById('main-image-preview-container');
                container.classList.remove('hidden');
                container.classList.add('flex');
                document.getElementById('main-image-preview').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
@endsection