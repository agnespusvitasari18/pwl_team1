@extends('layouts.app')

@section('title', 'Edit Lowongan PKL')
@section('page_title', 'Perbarui Lowongan PKL')
@section('page_description', 'Mengubah rincian posisi, kuota, atau batas pendaftaran lowongan')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl border border-[#D5E6DC] shadow-xs p-6 sm:p-8">
        <div class="flex items-center justify-between border-b border-[#E0ECE6] pb-4 mb-6">
            <div>
                <h2 class="text-base font-bold text-[#0D4A38]">Perbarui Lowongan: {{ $post->title }}</h2>
                <p class="text-xs text-slate-500">Kuota tidak boleh lebih kecil dari jumlah siswa yang sudah diterima ({{ $post->acceptedCount() }} siswa).</p>
            </div>
            <a href="{{ route('company.posts.index') }}" class="text-xs font-semibold text-[#0D4A38] hover:underline flex items-center gap-1">
                <span>&larr;</span> Kembali
            </a>
        </div>

        <form action="{{ route('company.posts.update', $post) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="title" class="block text-xs font-bold text-slate-700 mb-1.5">Judul Posisi Lowongan <span class="text-rose-500">*</span></label>
                <input type="text" id="title" name="title" value="{{ old('title', $post->title) }}" required
                       class="w-full px-3.5 py-2 text-sm bg-white border border-[#D5E6DC] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0F4C3A] focus:border-[#0F4C3A]">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="major_id" class="block text-xs font-bold text-slate-700 mb-1.5">Target Jurusan SMK <span class="text-rose-500">*</span></label>
                    @if ($post->acceptedCount() > 0)
                        <input type="hidden" name="major_id" value="{{ $post->major_id }}">
                        <input type="text" value="{{ $post->major->name }}" disabled
                               class="w-full px-3.5 py-2 text-sm bg-[#F4F8F6] border border-[#D5E6DC] rounded-lg text-slate-500 cursor-not-allowed">
                        <p class="text-[11px] text-slate-400 mt-1">Jurusan terkunci karena sudah ada siswa yang diterima.</p>
                    @else
                        <select id="major_id" name="major_id" required
                                class="w-full px-3.5 py-2 text-sm bg-white border border-[#D5E6DC] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0F4C3A] focus:border-[#0F4C3A]">
                            @foreach ($majors as $major)
                                <option value="{{ $major->id }}" {{ old('major_id', $post->major_id) == $major->id ? 'selected' : '' }}>
                                    {{ $major->name }}
                                </option>
                            @endforeach
                        </select>
                    @endif
                </div>

                <div>
                    <label for="quota" class="block text-xs font-bold text-slate-700 mb-1.5">Kuota Penerimaan (Siswa) <span class="text-rose-500">*</span></label>
                    <input type="number" id="quota" name="quota" value="{{ old('quota', $post->quota) }}" min="{{ max(1, $post->acceptedCount()) }}" required
                           class="w-full px-3.5 py-2 text-sm bg-white border border-[#D5E6DC] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0F4C3A] focus:border-[#0F4C3A]">
                </div>
            </div>

            <div>
                <label for="deadline" class="block text-xs font-bold text-slate-700 mb-1.5">Batas Akhir Pendaftaran <span class="text-rose-500">*</span></label>
                <input type="date" id="deadline" name="deadline" value="{{ old('deadline', $post->deadline->format('Y-m-d')) }}" required
                       class="w-full sm:w-1/2 px-3.5 py-2 text-sm bg-white border border-[#D5E6DC] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0F4C3A] focus:border-[#0F4C3A]">
            </div>

            <div>
                <label for="description" class="block text-xs font-bold text-slate-700 mb-1.5">Deskripsi Tugas & Kualifikasi Lowongan <span class="text-rose-500">*</span></label>
                <textarea id="description" name="description" rows="5" required
                          class="w-full px-3.5 py-2 text-sm bg-white border border-[#D5E6DC] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0F4C3A] focus:border-[#0F4C3A] leading-relaxed">{{ old('description', $post->description) }}</textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-[#E0ECE6]">
                <a href="{{ route('company.posts.index') }}"
                   class="px-4 py-2 bg-white hover:bg-[#F4F8F6] border border-[#D5E6DC] text-slate-700 text-xs font-bold rounded-lg transition">
                    Batal
                </a>
                <button type="submit"
                        class="px-5 py-2 bg-[#0F4C3A] hover:bg-[#0A3629] text-white text-xs font-bold rounded-lg transition shadow-xs">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
