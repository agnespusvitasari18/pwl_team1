@extends('layouts.auth')

@section('title', 'Registrasi Mitra Industri')

@section('auth_banner')
    <div class="space-y-6 sm:space-y-8">
        <div class="space-y-3">
            <h1 class="text-2xl sm:text-3xl font-extrabold leading-tight tracking-tight text-white">
                Jadi mitra PKL untuk siswa {{ config('pkloka.school_name') }}.
            </h1>
            <p class="text-sm sm:text-base leading-relaxed text-emerald-100/90">
                Pendaftaran perusahaan diverifikasi pihak sekolah supaya tempat PKL siswa terjamin legalitas dan keselarasannya.
            </p>
        </div>

        <ol class="space-y-4">
            <li class="flex items-center gap-3.5">
                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-amber-300 text-sm font-extrabold text-[#07261D]">1</span>
                <span class="text-xs sm:text-sm leading-relaxed text-white">Isi formulir pendaftaran perusahaan</span>
            </li>
            <li class="flex items-center gap-3.5">
                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-amber-300 text-sm font-extrabold text-[#07261D]">2</span>
                <span class="text-xs sm:text-sm leading-relaxed text-white">Admin sekolah memeriksa dan menyetujui akun</span>
            </li>
            <li class="flex items-center gap-3.5">
                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-amber-300 text-sm font-extrabold text-[#07261D]">3</span>
                <span class="text-xs sm:text-sm leading-relaxed text-white">Masuk, lalu publikasikan lowongan PKL</span>
            </li>
        </ol>
    </div>
@endsection

@section('auth_content')
<div class="space-y-5">
    <div class="space-y-1.5">
        <span class="inline-flex items-center px-3 py-1 rounded-full bg-teal-50 border border-teal-200 text-[#0F4C3A] text-[11px] font-bold">
            Formulir Registrasi Perusahaan
        </span>
        <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Daftarkan Perusahaan</h2>
        <p class="text-sm text-slate-500">Bergabung sebagai mitra industri PKLoka untuk menerima siswa PKL</p>
    </div>

    @include('partials.flash')

    <form method="POST" action="{{ route('register.company') }}" enctype="multipart/form-data" class="space-y-4 md:max-h-[520px] md:overflow-y-auto md:pr-1">
        @csrf

        <!-- Nama PIC & Email -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="space-y-1">
                <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Nama PIC / HRD</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                       placeholder="Nama penanggung jawab"
                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-[#0F4C3A] focus:ring-4 focus:ring-teal-500/10 transition">
            </div>

            <div class="space-y-1">
                <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Email Akun</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                       placeholder="hrd@perusahaan.com"
                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-[#0F4C3A] focus:ring-4 focus:ring-teal-500/10 transition">
            </div>
        </div>

        <!-- Password & Confirm -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="space-y-1">
                <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Kata Sandi</label>
                <input type="password" id="password" name="password" required minlength="8"
                       placeholder="Min. 8 karakter"
                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-[#0F4C3A] focus:ring-4 focus:ring-teal-500/10 transition">
            </div>

            <div class="space-y-1">
                <label for="password_confirmation" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Ulangi Sandi</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required minlength="8"
                       placeholder="Ulangi kata sandi"
                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-[#0F4C3A] focus:ring-4 focus:ring-teal-500/10 transition">
            </div>
        </div>

        <!-- Data Perusahaan -->
        <div class="pt-2 border-t border-slate-100 space-y-3">
            <div class="space-y-1">
                <label for="company_name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Nama Perusahaan / Instansi</label>
                <input type="text" id="company_name" name="company_name" value="{{ old('company_name') }}" required
                       placeholder="PT Solusi Teknologi Lampung"
                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-[#0F4C3A] focus:ring-4 focus:ring-teal-500/10 transition">
            </div>

            <div class="space-y-1">
                <label for="contact" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Nomor Kontak / WhatsApp</label>
                <input type="text" id="contact" name="contact" value="{{ old('contact') }}" required
                       placeholder="0812-7234-5678"
                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-[#0F4C3A] focus:ring-4 focus:ring-teal-500/10 transition">
            </div>

            <div class="space-y-1">
                <label for="address" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Alamat Kantor</label>
                <textarea id="address" name="address" rows="2" required
                          placeholder="Alamat lengkap lokasi kantor perusahaan di {{ config('pkloka.school_city') }}"
                          class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-[#0F4C3A] focus:ring-4 focus:ring-teal-500/10 transition">{{ old('address') }}</textarea>
            </div>

            <div class="space-y-1">
                <label for="description" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Deskripsi Singkat Profil</label>
                <textarea id="description" name="description" rows="2"
                          placeholder="Bidang usaha dan profil singkat perusahaan"
                          class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-[#0F4C3A] focus:ring-4 focus:ring-teal-500/10 transition">{{ old('description') }}</textarea>
            </div>

            <div class="space-y-1">
                <label for="logo" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Foto / Logo Perusahaan <span class="font-medium normal-case text-slate-400">(Opsional)</span></label>
                <div id="logo-dropzone" class="relative flex min-h-24 cursor-pointer flex-col items-center justify-center rounded-xl border-2 border-dashed border-slate-300 bg-slate-50 px-4 py-4 text-center transition hover:border-[#0F4C3A] hover:bg-teal-50/50">
                    <input type="file" id="logo" name="logo" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" aria-label="Pilih atau seret logo perusahaan"
                           class="absolute inset-0 z-10 h-full w-full cursor-pointer opacity-0">
                    <svg class="mb-1.5 h-6 w-6 text-[#0F4C3A]" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 16V4m0 0L7 9m5-5l5 5M5 14v4a2 2 0 002 2h10a2 2 0 002-2v-4"/>
                    </svg>
                    <p class="text-xs font-semibold text-slate-700">Seret berkas ke sini atau klik untuk memilih</p>
                    <p id="logo-file-name" class="mt-1 text-[11px] text-slate-400">JPG, PNG, atau WebP · Maks. 2 MB</p>
                </div>
                @error('logo') <p class="text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>
        </div>

        <button type="submit"
                class="w-full py-3 px-4 bg-[#0F4C3A] hover:bg-[#0A3629] text-white text-sm font-bold rounded-xl shadow-md shadow-teal-900/20 hover:shadow-lg transition-all">
            Kirim Pendaftaran Perusahaan
        </button>

        <p class="text-center text-xs text-slate-500 pt-1">
            Sudah memiliki akun?
            <a href="{{ route('login') }}" class="font-bold text-[#0F4C3A] hover:text-[#0A3629] hover:underline transition">
                Masuk di sini
            </a>
        </p>
    </form>
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
