@extends('layouts.app')

@section('title', 'Profil Siswa')
@section('page_title', 'Profil Siswa')
@section('page_description', 'Kelola data pribadi, informasi kejuruan, dan kredensial akun siswa')

@section('content')
@php
    $isPlaced = $student->isPlaced();
@endphp
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl border border-[#D5E6DC] shadow-xs p-5 sm:p-8">
        <div class="border-b border-[#E0ECE6] pb-4 mb-6">
            <h2 class="text-base font-bold text-[#0D4A38]">Perbarui Profil Siswa</h2>
            <p class="text-xs text-slate-500">Pastikan data NISN, kontak, dan kompetensi keahlian Anda akurat sesuai buku induk sekolah.</p>
        </div>

        <form action="{{ route('student.profile.update') }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="name" class="block text-xs font-bold text-slate-700 mb-1.5">Nama Lengkap Siswa <span class="text-rose-500">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name', auth()->user()->name) }}" required
                           class="w-full px-3.5 py-2 text-sm bg-white border border-[#D5E6DC] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0F4C3A] focus:border-[#0F4C3A]">
                </div>

                <div>
                    <label for="nisn" class="block text-xs font-bold text-slate-700 mb-1.5">NISN <span class="text-rose-500">*</span></label>
                    <input type="text" id="nisn" name="nisn" value="{{ old('nisn', $student->nisn) }}" required maxlength="20"
                           class="w-full px-3.5 py-2 text-sm bg-white border border-[#D5E6DC] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0F4C3A] focus:border-[#0F4C3A] font-mono">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Email Akun (Terkunci)</label>
                    <input type="email" value="{{ auth()->user()->email }}" disabled
                           class="w-full px-3.5 py-2 text-sm bg-[#F4F8F6] border border-[#D5E6DC] rounded-lg text-slate-400 cursor-not-allowed">
                </div>

                <div>
                    <label for="major_id" class="block text-xs font-bold text-slate-700 mb-1.5">
                        Kompetensi Keahlian / Jurusan <span class="text-rose-500">*</span>
                    </label>
                    @if ($isPlaced)
                        <input type="hidden" name="major_id" value="{{ $student->major_id }}">
                        <input type="text" value="{{ $student->major->name }}" disabled
                               class="w-full px-3.5 py-2 text-sm bg-[#F4F8F6] border border-[#D5E6DC] rounded-lg text-slate-500 cursor-not-allowed">
                        <p class="text-[11px] text-slate-400 mt-1">Jurusan terkunci karena Anda sudah diterima PKL.</p>
                    @else
                        <select id="major_id" name="major_id" required
                                class="w-full px-3.5 py-2 text-sm bg-white border border-[#D5E6DC] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0F4C3A] focus:border-[#0F4C3A]">
                            @foreach ($majors as $major)
                                <option value="{{ $major->id }}" {{ old('major_id', $student->major_id) == $major->id ? 'selected' : '' }}>
                                    {{ $major->name }}
                                </option>
                            @endforeach
                        </select>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="school_name" class="block text-xs font-bold text-slate-700 mb-1.5">Asal Sekolah</label>
                    <input type="text" id="school_name" name="school_name" value="{{ old('school_name', $student->school_name ?: config('pkloka.school_name')) }}"
                           class="w-full px-3.5 py-2 text-sm bg-white border border-[#D5E6DC] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0F4C3A] focus:border-[#0F4C3A]">
                </div>

                <div>
                    <label for="phone" class="block text-xs font-bold text-slate-700 mb-1.5">Nomor Kontak / WhatsApp</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone', $student->phone) }}" maxlength="30"
                           placeholder="Contoh: 0812-7100-xxxx"
                           class="w-full px-3.5 py-2 text-sm bg-white border border-[#D5E6DC] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0F4C3A] focus:border-[#0F4C3A]">
                </div>
            </div>

            <div>
                <label for="address" class="block text-xs font-bold text-slate-700 mb-1.5">Alamat Tempat Tinggal</label>
                <textarea id="address" name="address" rows="3"
                          class="w-full px-3.5 py-2 text-sm bg-white border border-[#D5E6DC] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0F4C3A] focus:border-[#0F4C3A]">{{ old('address', $student->address) }}</textarea>
            </div>

            <div class="pt-3 border-t border-[#E0ECE6] grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="password" class="block text-xs font-bold text-slate-700 mb-1.5">Ganti Kata Sandi (Opsional)</label>
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
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
