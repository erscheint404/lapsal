@extends('layouts.admin')
@section('title', 'Tambah Lapangan')
@section('page_title', 'Tambah Lapangan Baru')

@section('content')
<div class="bg-white rounded-2xl border border-dark-100 shadow-sm overflow-hidden max-w-4xl">
    <div class="p-6 border-b border-dark-100 flex items-center gap-4">
        <a href="{{ route('admin.lapangan.index') }}" class="btn-icon text-dark-500 bg-dark-50 hover:bg-dark-100"><i class="fas fa-arrow-left"></i></a>
        <h2 class="font-bold text-lg text-dark-900">Form Lapangan</h2>
    </div>

    <div class="p-6" x-data="lapanganForm()">
        <form action="{{ route('admin.lapangan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Nama --}}
                <div class="md:col-span-2">
                    <label class="form-label">Nama Lapangan <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" value="{{ old('nama') }}" class="form-input" required placeholder="Contoh: Lapangan Vinyl A">
                    @error('nama') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                {{-- Tipe --}}
                <div>
                    <label class="form-label">Tipe Lapangan <span class="text-red-500">*</span></label>
                    <select name="tipe" class="form-input" required>
                        <option value="">Pilih Tipe</option>
                        <option value="vinyl" {{ old('tipe') == 'vinyl' ? 'selected' : '' }}>Vinyl</option>
                        <option value="rumput_sintetis" {{ old('tipe') == 'rumput_sintetis' ? 'selected' : '' }}>Rumput Sintetis</option>
                        <option value="semen" {{ old('tipe') == 'semen' ? 'selected' : '' }}>Semen / Plester</option>
                        <option value="parquette" {{ old('tipe') == 'parquette' ? 'selected' : '' }}>Parquette (Kayu)</option>
                    </select>
                    @error('tipe') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                {{-- Harga --}}
                <div>
                    <label class="form-label">Harga per Jam (Rp) <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-dark-500 font-bold">Rp</span>
                        <input type="number" name="harga_per_jam" value="{{ old('harga_per_jam') }}" class="form-input pl-12" required min="0" step="1000" placeholder="100000">
                    </div>
                    @error('harga_per_jam') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                {{-- Status --}}
                <div>
                    <label class="form-label">Status <span class="text-red-500">*</span></label>
                    <select name="status" class="form-input" required>
                        <option value="aktif" {{ old('status', 'aktif') == 'aktif' ? 'selected' : '' }}>Aktif (Bisa dibooking)</option>
                        <option value="nonaktif" {{ old('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif (Maintenance)</option>
                    </select>
                    @error('status') <p class="form-error">{{ $message }}</p> @enderror
                </div>
                
                {{-- Fasilitas (Tags Input) --}}
                <div class="md:col-span-2">
                    <label class="form-label">Fasilitas Termasuk</label>
                    <div class="p-4 border border-dark-200 rounded-xl bg-dark-50">
                        <div class="flex flex-wrap gap-2 mb-3">
                            <template x-for="(tag, index) in tags" :key="index">
                                <span class="inline-flex items-center gap-1 px-3 py-1 bg-primary-100 text-primary-800 rounded-lg text-sm font-medium">
                                    <span x-text="tag"></span>
                                    <button type="button" @click="removeTag(index)" class="text-primary-600 hover:text-red-600"><i class="fas fa-times"></i></button>
                                    <input type="hidden" name="fasilitas[]" :value="tag">
                                </span>
                            </template>
                        </div>
                        <input type="text" x-model="newTag" @keydown.enter.prevent="addTag" placeholder="Ketik fasilitas dan tekan Enter (ex: Bola, Rompi, Papan Skor)" class="w-full bg-transparent border-none focus:ring-0 text-sm p-0">
                    </div>
                    @error('fasilitas') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                {{-- Deskripsi --}}
                <div class="md:col-span-2">
                    <label class="form-label">Deskripsi Lapangan</label>
                    <textarea name="deskripsi" rows="4" class="form-input" placeholder="Jelaskan detail lapangan...">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                {{-- Foto Utama --}}
                <div class="md:col-span-2">
                    <label class="form-label">Foto Utama <span class="text-red-500">*</span></label>
                    <div class="border-2 border-dashed border-dark-300 rounded-2xl p-6 text-center hover:bg-dark-50 transition-colors cursor-pointer relative"
                         @dragover.prevent="$el.classList.add('border-primary-500', 'bg-primary-50')"
                         @dragleave.prevent="$el.classList.remove('border-primary-500', 'bg-primary-50')"
                         @drop.prevent="$el.classList.remove('border-primary-500', 'bg-primary-50'); handleMainPhoto($event.dataTransfer.files[0])">
                        
                        <input type="file" name="foto_utama" id="foto_utama" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" accept="image/*" @change="handleMainPhoto($event.target.files[0])" required>
                        
                        <div x-show="!mainPhotoPreview">
                            <div class="w-12 h-12 mx-auto bg-dark-100 text-dark-400 rounded-full flex items-center justify-center text-xl mb-2">
                                <i class="fas fa-cloud-upload-alt"></i>
                            </div>
                            <p class="font-bold text-dark-700">Klik atau drag gambar ke sini</p>
                            <p class="text-xs text-dark-400 mt-1">Format: JPG, PNG. Maksimal 2MB.</p>
                        </div>
                        
                        <div x-show="mainPhotoPreview" class="relative max-w-xs mx-auto" style="display: none;">
                            <img :src="mainPhotoPreview" class="w-full h-48 object-cover rounded-xl border-4 border-white shadow-lg">
                            <button type="button" @click.stop="mainPhotoPreview = null; document.getElementById('foto_utama').value = ''" class="absolute -top-3 -right-3 w-8 h-8 bg-red-500 text-white rounded-full flex items-center justify-center shadow-md z-20 hover:bg-red-600">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    @error('foto_utama') <p class="form-error">{{ $message }}</p> @enderror
                </div>
                
                {{-- Foto Tambahan / Galeri --}}
                <div class="md:col-span-2">
                    <label class="form-label">Foto Galeri Tambahan (Opsional)</label>
                    <input type="file" name="foto_tambahan[]" class="form-input p-2" accept="image/*" multiple>
                    <p class="form-help">Bisa pilih lebih dari satu foto sekaligus.</p>
                    @error('foto_tambahan.*') <p class="form-error">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="pt-6 border-t border-dark-100 flex justify-end gap-3">
                <a href="{{ route('admin.lapangan.index') }}" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary"><i class="fas fa-save mr-2"></i>Simpan Lapangan</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('lapanganForm', () => ({
        tags: {!! json_encode(old('fasilitas', [])) !!},
        newTag: '',
        mainPhotoPreview: null,
        
        addTag() {
            if (this.newTag.trim() !== '' && !this.tags.includes(this.newTag.trim())) {
                this.tags.push(this.newTag.trim());
            }
            this.newTag = '';
        },
        removeTag(index) {
            this.tags.splice(index, 1);
        },
        handleMainPhoto(file) {
            if (!file || !file.type.match('image.*')) return;
            const reader = new FileReader();
            reader.onload = (e) => {
                this.mainPhotoPreview = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    }));
});
</script>
@endpush
@endsection
