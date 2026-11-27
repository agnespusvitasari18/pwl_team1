@extends('layouts.app')

@section('title', 'Tambah Siswa')
@section('page_title', 'Tambah Akun Siswa Baru')
@section('page_description', 'Pendaftaran akun siswa dan data kejuruan oleh pihak sekolah')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl border-2 border-[#D5E6DC] shadow-xs p-5 sm:p-8">
        <div class="flex items-center justify-between border-b border-[#E0ECE6] pb-4 mb-6 gap-2">
            <div>
                <h2 class="text-base font-bold text-[#0D4A38]">Formulir Data Siswa</h2>
                <p class="text-xs text-slate-500">Akun ini langsung aktif dan siswa dapat masuk dengan email atau NISN.</p>
            </div>
            <a href="{{ route('admin.students.index') }}" class="text-xs font-semibold text-[#0D4A38] hover:underline shrink-0">
                &larr; Kembali
            </a>
        </div>

        <form action="{{ route('admin.students.store') }}" method="POST" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="name" class="block text-xs font-bold text-slate-700 mb-1">Nama Lengkap Siswa <span class="text-rose-500">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                           class="w-full px-3 py-2 text-sm bg-white border border-[#C6E2D2] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0F4C3A] focus:border-[#0F4C3A]"
                           placeholder="Nama siswa">
                </div>

                <div>
                    <label for="nisn" class="block text-xs font-bold text-slate-700 mb-1">NISN (Nomor Induk Siswa Nasional) <span class="text-rose-500">*</span></label>
                    <input type="text" id="nisn" name="nisn" value="{{ old('nisn') }}" required maxlength="20"
                           class="w-full px-3 py-2 text-sm bg-white border border-[#C6E2D2] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0F4C3A] focus:border-[#0F4C3A] font-mono"
                           placeholder="Contoh: 0061234509">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="email" class="block text-xs font-bold text-slate-700 mb-1">Alamat Email Siswa <span class="text-rose-500">*</span></label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required
                           class="w-full px-3 py-2 text-sm bg-white border border-[#C6E2D2] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0F4C3A] focus:border-[#0F4C3A]"
                           placeholder="siswa@smkn4bandarlampung.sch.id">
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold text-slate-700 mb-1">Kata Sandi Awal <span class="text-rose-500">*</span></label>
                    <input type="password" id="password" name="password" required minlength="8"
                           class="w-full px-3 py-2 text-sm bg-white border border-[#C6E2D2] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0F4C3A] focus:border-[#0F4C3A]"
                           placeholder="Minimal 8 karakter">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="major_id" class="block text-xs font-bold text-slate-700 mb-1">Kompetensi Keahlian / Jurusan <span class="text-rose-500">*</span></label>
                    <select id="major_id" name="major_id" required
                            class="w-full px-3 py-2 text-sm bg-white border border-[#C6E2D2] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0F4C3A] focus:border-[#0F4C3A]">
                        <option value="">-- Pilih Jurusan --</option>
                        @foreach ($majors as $major)
                            <option value="{{ $major->id }}" {{ old('major_id') == $major->id ? 'selected' : '' }}>
                                {{ $major->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="school_name" class="block text-xs font-bold text-slate-700 mb-1">Asal Sekolah</label>
                    <input type="text" id="school_name" name="school_name" value="{{ old('school_name', config('pkloka.school_name')) }}"
                           class="w-full px-3 py-2 text-sm bg-white border border-[#C6E2D2] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0F4C3A] focus:border-[#0F4C3A]"
                           placeholder="Nama sekolah">
                </div>
            </div>

            <div>
                <label for="phone" class="block text-xs font-bold text-slate-700 mb-1">Nomor Kontak / WhatsApp</label>
                <input type="text" id="phone" name="phone" value="{{ old('phone') }}" maxlength="30"
                       class="w-full sm:w-1/2 px-3 py-2 text-sm bg-white border border-[#C6E2D2] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0F4C3A] focus:border-[#0F4C3A]"
                       placeholder="Contoh: 0812-7100-xxxx">
            </div>

            <div>
                <label for="address" class="block text-xs font-bold text-slate-700 mb-1">Alamat Tempat Tinggal</label>
                <textarea id="address" name="address" rows="3"
                          class="w-full px-3 py-2 text-sm bg-white border border-[#C6E2D2] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0F4C3A] focus:border-[#0F4C3A]"
                          placeholder="Alamat lengkap siswa di {{ config('pkloka.school_city') }}...">{{ old('address') }}</textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('admin.students.index') }}"
                   class="px-4 py-2 bg-white hover:bg-slate-50 border border-slate-300 text-slate-700 text-xs font-semibold rounded-lg transition">
                    Batal
                </a>
                <button type="submit"
                        class="px-5 py-2.5 bg-[#0F4C3A] hover:bg-[#0A3629] text-white text-xs font-bold rounded-lg transition shadow-xs">
                    Simpan Akun Siswa
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
