@extends('layouts.app')

@section('title', 'Edit Data Siswa')
@section('page_title', 'Perbarui Data Siswa')
@section('page_description', 'Mengubah informasi profil, jurusan, dan kredensial akun siswa')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="bg-white rounded-2xl border-2 border-[#D5E6DC] shadow-xs p-5 sm:p-8">
        <div class="flex items-center justify-between border-b border-[#E0ECE6] pb-4 mb-6 gap-2">
            <div>
                <h2 class="text-base font-bold text-[#0D4A38]">Perbarui Data Siswa: {{ $student->user->name }}</h2>
                <p class="text-xs text-slate-500">Ubah data di bawah ini sesuai dokumen administrasi sekolah.</p>
            </div>
            <a href="{{ route('admin.students.index') }}" class="text-xs font-semibold text-[#0D4A38] hover:underline shrink-0">
                &larr; Kembali
            </a>
        </div>

        <form action="{{ route('admin.students.update', $student) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="name" class="block text-xs font-bold text-slate-700 mb-1">Nama Lengkap Siswa <span class="text-rose-500">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name', $student->user->name) }}" required
                           class="w-full px-3 py-2 text-sm bg-white border border-[#C6E2D2] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0F4C3A] focus:border-[#0F4C3A]">
                </div>

                <div>
                    <label for="nisn" class="block text-xs font-bold text-slate-700 mb-1">NISN <span class="text-rose-500">*</span></label>
                    <input type="text" id="nisn" name="nisn" value="{{ old('nisn', $student->nisn) }}" required maxlength="20"
                           class="w-full px-3 py-2 text-sm bg-white border border-[#C6E2D2] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0F4C3A] focus:border-[#0F4C3A] font-mono">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="email" class="block text-xs font-bold text-slate-700 mb-1">Alamat Email <span class="text-rose-500">*</span></label>
                    <input type="email" id="email" name="email" value="{{ old('email', $student->user->email) }}" required
                           class="w-full px-3 py-2 text-sm bg-white border border-[#C6E2D2] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0F4C3A] focus:border-[#0F4C3A]">
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold text-slate-700 mb-1">Kata Sandi Baru (Opsional)</label>
                    <input type="password" id="password" name="password" minlength="8"
                           class="w-full px-3 py-2 text-sm bg-white border border-[#C6E2D2] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0F4C3A] focus:border-[#0F4C3A] placeholder-slate-400"
                           placeholder="Kosongkan jika tidak ingin diubah">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="major_id" class="block text-xs font-bold text-slate-700 mb-1">Jurusan <span class="text-rose-500">*</span></label>
                    <select id="major_id" name="major_id" required
                            class="w-full px-3 py-2 text-sm bg-white border border-[#C6E2D2] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0F4C3A] focus:border-[#0F4C3A]">
                        @foreach ($majors as $major)
                            <option value="{{ $major->id }}" {{ old('major_id', $student->major_id) == $major->id ? 'selected' : '' }}>
                                {{ $major->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="school_name" class="block text-xs font-bold text-slate-700 mb-1">Asal Sekolah</label>
                    <input type="text" id="school_name" name="school_name" value="{{ old('school_name', $student->school_name ?: config('pkloka.school_name')) }}"
                           class="w-full px-3 py-2 text-sm bg-white border border-[#C6E2D2] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0F4C3A] focus:border-[#0F4C3A]">
                </div>
            </div>

            <div>
                <label for="phone" class="block text-xs font-bold text-slate-700 mb-1">Nomor Kontak / WhatsApp</label>
                <input type="text" id="phone" name="phone" value="{{ old('phone', $student->phone) }}" maxlength="30"
                       class="w-full sm:w-1/2 px-3 py-2 text-sm bg-white border border-[#C6E2D2] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0F4C3A] focus:border-[#0F4C3A]"
                       placeholder="Contoh: 0812-7100-xxxx">
            </div>

            <div>
                <label for="address" class="block text-xs font-bold text-slate-700 mb-1">Alamat Tempat Tinggal</label>
                <textarea id="address" name="address" rows="3"
                          class="w-full px-3 py-2 text-sm bg-white border border-[#C6E2D2] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0F4C3A] focus:border-[#0F4C3A]">{{ old('address', $student->address) }}</textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('admin.students.index') }}"
                   class="px-4 py-2 bg-white hover:bg-slate-50 border border-slate-300 text-slate-700 text-xs font-semibold rounded-lg transition">
                    Batal
                </a>
                <button type="submit"
                        class="px-5 py-2.5 bg-[#0F4C3A] hover:bg-[#0A3629] text-white text-xs font-bold rounded-lg transition shadow-xs">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    <!-- Reset Kata Sandi Cepat -->
    <div class="bg-white rounded-2xl border border-amber-200 shadow-xs p-5 sm:p-6 space-y-3">
        <div>
            <h3 class="text-sm font-bold text-slate-900">Atur Ulang Kata Sandi Siswa</h3>
            <p class="text-xs text-slate-500">Gunakan fitur ini jika siswa lupa kata sandi. Biarkan kosong untuk membuat kata sandi sementara secara otomatis.</p>
        </div>

        <form action="{{ route('admin.students.reset-password', $student) }}" method="POST" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
            @csrf
            @method('PATCH')
            <input type="text" name="new_password" minlength="8"
                   placeholder="Kata sandi baru (opsional, min. 8 karakter)"
                   class="flex-1 px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0F4C3A]">
            <button type="submit"
                    class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold rounded-lg transition shrink-0">
                Reset Kata Sandi
            </button>
        </form>
    </div>
</div>
@endsection
