@extends('layouts.app')

@section('title', $post->title)
@section('page_title', 'Detail Lowongan PKL')
@section('page_description', 'Informasi lengkap posisi, kualifikasi magang, dan formulir pengajuan lamaran')

@section('content')
<div class="space-y-6">

    <!-- Breadcrumb Nav -->
    <div class="flex items-center gap-2 text-xs text-slate-500">
        <a href="{{ route('student.posts.index') }}" class="text-[#0D4A38] font-semibold hover:underline flex items-center gap-1">
            <span>&larr;</span> Daftar Lowongan
        </a>
        <span>/</span>
        <span class="text-slate-800 font-bold truncate">{{ $post->title }}</span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Kolom Kiri: Rincian Lowongan & Profil Perusahaan -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Card Utama Lowongan -->
            <div class="bg-white rounded-xl border border-[#D5E6DC] shadow-xs p-6 sm:p-8">
                <div class="flex items-center gap-2 mb-3">
                    <span class="inline-block px-2.5 py-0.5 rounded text-xs font-bold bg-[#E8F4EE] text-[#0D4A38] border border-[#C6E2D2]">
                        {{ $post->major->name }}
                    </span>
                    @if ($hasApplied)
                        <span class="inline-block px-2.5 py-0.5 rounded text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200">
                            Sudah Anda Lamar
                        </span>
                    @endif
                </div>

                <h2 class="text-xl sm:text-2xl font-extrabold text-[#0D4A38]">{{ $post->title }}</h2>
                <p class="text-sm font-bold text-slate-700 mt-1">{{ $post->company->company_name }}</p>

                <div class="mt-6 pt-6 border-t border-[#E0ECE6]">
                    <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Deskripsi & Kualifikasi Peserta</h3>
                    <div class="text-sm text-slate-700 leading-relaxed whitespace-pre-line bg-[#F4F8F6] p-4 sm:p-5 rounded-xl border border-[#D5E6DC]">
{{ $post->description }}
                    </div>
                </div>
            </div>

            <!-- Card Profil Perusahaan -->
            <div class="bg-white rounded-xl border border-[#D5E6DC] shadow-xs p-6 sm:p-8">
                <h3 class="text-sm font-bold text-[#0D4A38] mb-3">Tentang Perusahaan Mitra</h3>

                <p class="text-xs text-slate-600 leading-relaxed mb-4">
                    {{ $post->company->description ?? 'Mitra industri resmi bekerja sama dengan sekolah untuk program Praktik Kerja Lapangan.' }}
                </p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs border-t border-[#E0ECE6] pt-4">
                    <div>
                        <span class="text-slate-400 block text-[11px]">Alamat Perusahaan:</span>
                        <span class="text-slate-800 font-semibold">{{ $post->company->address }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-[11px]">Kontak Resmi:</span>
                        <span class="text-slate-800 font-semibold">{{ $post->company->contact }}</span>
                    </div>
                </div>
            </div>

        </div>

        <!-- Kolom Kanan: Rangkuman & Formulir Pengajuan -->
        <div class="space-y-6">

            <!-- Card Rangkuman Lowongan -->
            <div class="bg-white rounded-xl border border-[#D5E6DC] shadow-xs p-5 sm:p-6 space-y-3">
                <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Ringkasan Lowongan</h3>

                <div class="space-y-2.5 text-xs">
                    <div class="flex justify-between py-1.5 border-b border-[#E0ECE6]">
                        <span class="text-slate-500">Total Kuota:</span>
                        <span class="font-bold text-slate-900">{{ $post->quota }} Siswa</span>
                    </div>
                    <div class="flex justify-between py-1.5 border-b border-[#E0ECE6]">
                        <span class="text-slate-500">Sisa Kuota:</span>
                        <span class="font-bold {{ $remainingQuota > 0 ? 'text-slate-900' : 'text-rose-600' }}">
                            {{ $remainingQuota }} Posisi
                        </span>
                    </div>
                    <div class="flex justify-between py-1.5 border-b border-[#E0ECE6]">
                        <span class="text-slate-500">Batas Waktu:</span>
                        <span class="font-bold text-slate-900">{{ $post->deadline->format('d M Y') }}</span>
                    </div>
                    <div class="flex justify-between py-1.5">
                        <span class="text-slate-500">Status Lowongan:</span>
                        <span class="font-bold {{ $post->isOpen() && $remainingQuota > 0 ? 'text-emerald-700' : 'text-rose-600' }}">
                            {{ $post->isOpen() && $remainingQuota > 0 ? 'Buka' : 'Ditutup / Penuh' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Card Form Lamar / Status Lamar -->
            <div class="bg-white rounded-xl border border-[#D5E6DC] shadow-xs p-5 sm:p-6">
                <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Status Pengajuan Anda</h3>

                @if ($isAccepted)
                    <div class="p-4 rounded-xl bg-gradient-to-r from-emerald-800 to-[#0D4A38] text-white text-xs space-y-2 border border-emerald-700">
                        <div class="font-bold flex items-center gap-1.5 text-amber-300">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Sudah Ditempatkan PKL</span>
                        </div>
                        <p class="text-[11px] text-emerald-100 leading-relaxed">
                            Anda telah diterima pada tempat PKL resmi. Anda tidak diizinkan melamar ke lowongan lain agar kuota dapat digunakan oleh siswa lain.
                        </p>
                    </div>

                @elseif ($hasApplied)
                    <div class="p-4 rounded-xl bg-amber-50 border border-amber-200 text-xs text-amber-900 space-y-2">
                        <div class="font-bold flex items-center gap-1.5 text-amber-800">
                            <svg class="w-4 h-4 shrink-0 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Lamaran Telah Terkirim</span>
                        </div>
                        <p class="text-[11px] text-amber-800 leading-relaxed">
                            Anda sudah mengajukan permohonan magang untuk posisi ini. Pantau status penerimaan pada halaman Lamaran Saya.
                        </p>
                        <a href="{{ route('student.applications.index') }}"
                           class="inline-block mt-2 px-3 py-1.5 bg-[#0F4C3A] hover:bg-[#0A3629] text-white rounded-lg text-xs font-bold shadow-xs">
                            Buka Lamaran Saya &rarr;
                        </a>
                    </div>

                @elseif ($student->major_id !== $post->major_id)
                    <div class="p-4 rounded-xl bg-amber-50 border border-amber-200 text-xs text-amber-900 space-y-1">
                        <div class="font-bold">Jurusan Tidak Sesuai</div>
                        <p class="text-[11px] text-amber-800 leading-relaxed">
                            Lowongan ini khusus untuk jurusan <strong>{{ $post->major->name }}</strong>. Jurusan Anda adalah <strong>{{ $student->major->name }}</strong>.
                        </p>
                    </div>

                @elseif (! $post->isOpen())
                    <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-xs text-rose-900 space-y-1">
                        <div class="font-bold">Batas Waktu Berakhir</div>
                        <p class="text-[11px] text-rose-800 leading-relaxed">
                            Pendaftaran untuk posisi ini telah ditutup karena melewati batas tanggal {{ $post->deadline->format('d M Y') }}.
                        </p>
                    </div>

                @elseif ($remainingQuota <= 0)
                    <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-xs text-rose-900 space-y-1">
                        <div class="font-bold">Kuota Sudah Penuh</div>
                        <p class="text-[11px] text-rose-800 leading-relaxed">
                            Kuota penerimaan siswa untuk posisi ini telah terpenuhi seluruhnya.
                        </p>
                    </div>

                @else
                    <!-- Form Pendaftaran PKL -->
                    <form action="{{ route('student.posts.apply', $post) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf

                        <div>
                            <label for="notes" class="block text-xs font-bold text-slate-700 mb-1">
                                Surat Pengantar / Catatan Motivasi
                            </label>
                            <textarea id="notes" name="notes" rows="3"
                                      class="w-full px-3 py-2 text-xs bg-white border border-[#D5E6DC] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0F4C3A] focus:border-[#0F4C3A]"
                                      placeholder="Perkenalkan diri Anda dan alasan tertarik magang di posisi ini...">{{ old('notes') }}</textarea>
                        </div>

                        <div>
                            <label for="portfolio_url" class="block text-xs font-bold text-slate-700 mb-1">
                                Tautan Portofolio / GitHub / LinkedIn (Opsional)
                            </label>
                            <input type="url" id="portfolio_url" name="portfolio_url" value="{{ old('portfolio_url') }}"
                                   class="w-full px-3 py-2 text-xs bg-white border border-[#D5E6DC] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0F4C3A] focus:border-[#0F4C3A]"
                                   placeholder="https://github.com/username">
                        </div>

                        <div>
                            <label for="resume_file" class="block text-xs font-bold text-slate-700 mb-1">
                                Berkas CV / Resume (PDF, Maks. 2MB)
                            </label>
                            <input type="file" id="resume_file" name="resume_file" accept=".pdf"
                                   class="w-full text-xs text-slate-600 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-[#E8F4EE] file:text-[#0D4A38] hover:file:bg-[#D5E6DC] cursor-pointer">
                        </div>

                        <div class="pt-2">
                            <button type="submit"
                                    class="w-full py-2.5 px-4 bg-[#0F4C3A] hover:bg-[#0A3629] text-white text-xs font-bold rounded-xl transition shadow-xs text-center flex items-center justify-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                </svg>
                                <span>Kirim Pengajuan Lamaran PKL</span>
                            </button>
                        </div>
                    </form>
                @endif
            </div>

        </div>

    </div>

</div>
@endsection
