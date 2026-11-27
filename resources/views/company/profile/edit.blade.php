@extends('layouts.app')

@section('title', 'Profil Perusahaan')
@section('page_title', 'Profil Mitra Industri')
@section('page_description', 'Kelola informasi resmi perusahaan, kontak PIC, dan profil usaha')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl border border-[#D5E6DC] shadow-xs p-5 sm:p-8">
        <div class="border-b border-[#E0ECE6] pb-4 mb-6">
            <h2 class="text-base font-bold text-[#0D4A38]">Perbarui Profil Perusahaan</h2>
            <p class="text-xs text-slate-500">Informasi ini akan ditampilkan kepada siswa SMK yang mencari lowongan PKL.</p>
        </div>

        <form action="{{ route('company.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="company_name" class="block text-xs font-bold text-slate-700 mb-1.5">Nama Resmi Perusahaan <span class="text-rose-500">*</span></label>
                    <input type="text" id="company_name" name="company_name" value="{{ old('company_name', $company->company_name) }}" required
                           class="w-full px-3.5 py-2 text-sm bg-white border border-[#D5E6DC] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0F4C3A] focus:border-[#0F4C3A]">
                </div>

                <div>
                    <label for="name" class="block text-xs font-bold text-slate-700 mb-1.5">Nama PIC / Penanggung Jawab <span class="text-rose-500">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name', auth()->user()->name) }}" required
                           class="w-full px-3.5 py-2 text-sm bg-white border border-[#D5E6DC] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0F4C3A] focus:border-[#0F4C3A]">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Email Akun (Terkunci)</label>
                    <input type="email" value="{{ auth()->user()->email }}" disabled
                           class="w-full px-3.5 py-2 text-sm bg-[#F4F8F6] border border-[#D5E6DC] rounded-lg text-slate-400 cursor-not-allowed">
                </div>

                <div>
                    <label for="contact" class="block text-xs font-bold text-slate-700 mb-1.5">No. Kontak / WhatsApp <span class="text-rose-500">*</span></label>
                    <input type="text" id="contact" name="contact" value="{{ old('contact', $company->contact) }}" required
                           class="w-full px-3.5 py-2 text-sm bg-white border border-[#D5E6DC] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0F4C3A] focus:border-[#0F4C3A]">
                </div>
            </div>

            <div>
                <label for="address" class="block text-xs font-bold text-slate-700 mb-1.5">Alamat Lengkap Perusahaan <span class="text-rose-500">*</span></label>
                <input type="text" id="address" name="address" value="{{ old('address', $company->address) }}" required
                       class="w-full px-3.5 py-2 text-sm bg-white border border-[#D5E6DC] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0F4C3A] focus:border-[#0F4C3A]">
            </div>

            <div>
                <label for="description" class="block text-xs font-bold text-slate-700 mb-1.5">Deskripsi &amp; Bidang Usaha Perusahaan</label>
                <textarea id="description" name="description" rows="4"
                          class="w-full px-3.5 py-2 text-sm bg-white border border-[#D5E6DC] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0F4C3A] focus:border-[#0F4C3A] leading-relaxed">{{ old('description', $company->description) }}</textarea>
            </div>

            <div>
                <label for="logo" class="block text-xs font-bold text-slate-700 mb-1.5">Foto / Logo Perusahaan <span class="font-medium text-slate-400">(Opsional)</span></label>
                @if ($company->logo_path)
                    <img src="{{ asset('storage/'.$company->logo_path) }}" alt="Logo {{ $company->company_name }}" class="mb-3 h-16 w-16 rounded-xl border border-slate-200 bg-white object-contain p-1">
                @endif
                <div id="logo-dropzone" class="relative flex min-h-28 cursor-pointer flex-col items-center justify-center rounded-xl border-2 border-dashed border-[#D5E6DC] bg-[#F8FBF9] px-4 py-5 text-center transition hover:border-[#0F4C3A] hover:bg-teal-50/50">
                    <input type="file" id="logo" name="logo" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" aria-label="Pilih atau seret logo perusahaan"
                           class="absolute inset-0 z-10 h-full w-full cursor-pointer opacity-0">
                    <svg class="mb-2 h-6 w-6 text-[#0F4C3A]" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 16V4m0 0L7 9m5-5l5 5M5 14v4a2 2 0 002 2h10a2 2 0 002-2v-4"/>
                    </svg>
                    <p class="text-xs font-semibold text-slate-700">Seret berkas ke sini atau klik untuk memilih</p>
                    <p id="logo-file-name" class="mt-1 text-[11px] text-slate-400">JPG, PNG, atau WebP · Maks. 2 MB</p>
                </div>
                @error('logo') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>

            <div class="pt-3 border-t border-[#E0ECE6] grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="password" class="block text-xs font-bold text-slate-700 mb-1.5">Ubah Kata Sandi (Opsional)</label>
                    <input type="password" id="password" name="password" minlength="8"
                           class="w-full px-3.5 py-2 text-sm bg-white border border-[#D5E6DC] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0F4C3A] focus:border-[#0F4C3A] placeholder-slate-400"
                           placeholder="Kosongkan jika tidak diubah">
                </div>
                <div>
                    <label for="password_confirmation" class="block text-xs font-bold text-slate-700 mb-1.5">Konfirmasi Kata Sandi Baru</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" minlength="8"
                           class="w-full px-3.5 py-2 text-sm bg-white border border-[#D5E6DC] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0F4C3A] focus:border-[#0F4C3A] placeholder-slate-400"
                           placeholder="Ulangi kata sandi baru">
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-[#E0ECE6]">
                <button type="submit"
                        class="px-5 py-2.5 bg-[#0F4C3A] hover:bg-[#0A3629] text-white text-xs font-bold rounded-lg transition shadow-xs">
                    Simpan Perubahan Profil
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    (() => {
        const input = document.getElementById('logo');
        const dropzone = document.getElementById('logo-dropzone');
        const fileName = document.getElementById('logo-file-name');
        if (!input || !dropzone || !fileName) return;

        const showFileName = () => {
            fileName.textContent = input.files?.[0]?.name || 'JPG, PNG, atau WebP · Maks. 2 MB';
            fileName.classList.toggle('text-[#0F4C3A]', Boolean(input.files?.length));
        };

        input.addEventListener('change', showFileName);
        dropzone.addEventListener('dragover', (event) => {
            event.preventDefault();
            dropzone.classList.add('border-[#0F4C3A]', 'bg-teal-50');
        });
        dropzone.addEventListener('dragleave', () => dropzone.classList.remove('border-[#0F4C3A]', 'bg-teal-50'));
        dropzone.addEventListener('drop', (event) => {
            event.preventDefault();
            dropzone.classList.remove('border-[#0F4C3A]', 'bg-teal-50');
            if (event.dataTransfer.files.length) {
                input.files = event.dataTransfer.files;
                showFileName();
            }
        });
    })();
</script>
@endsection
