@extends('layouts.public')

@section('title', 'PKLoka - Temukan & Daftar PKL Impianmu!')

@section('content')

    <!-- HERO SECTION -->
    <section id="beranda" class="relative overflow-hidden pt-8 pb-12 lg:pt-10 lg:pb-12 bg-gradient-to-b from-[#F4F9F6] via-white to-white">
        <div class="absolute top-0 right-0 -z-10 w-[500px] h-[500px] bg-gradient-to-br from-teal-200/35 to-emerald-100/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute top-20 left-10 -z-10 w-72 h-72 bg-teal-100/35 rounded-full blur-2xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">

                <!-- Left Column: Copy & Search -->
                <div class="lg:col-span-6 space-y-5 text-center lg:text-left relative z-10">
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-teal-100/90 border border-teal-200 text-[#0F4C3A] text-xs font-semibold shadow-xs">
                        <svg class="w-4 h-4 text-[#0F4C3A] shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>Platform Resmi PKL {{ config('pkloka.school_name') }}</span>
                    </div>

                    <h1 class="text-3xl sm:text-5xl lg:text-[3.35rem] font-extrabold text-slate-900 tracking-tight leading-[1.08]">
                        Temukan &amp; Daftar <br class="hidden sm:inline">
                        <span class="text-[#0F4C3A]">PKL Impianmu!</span>
                    </h1>

                    <p class="text-sm sm:text-lg text-slate-600 leading-relaxed max-w-xl mx-auto lg:mx-0">
                        PKLoka hadir sebagai platform terpusat yang menghubungkan siswa, mitra perusahaan, dan pihak {{ config('pkloka.school_name') }} dalam penyelenggaraan Praktik Kerja Lapangan.
                    </p>

                    <!-- Search Bar -->
                    <div class="pt-1 max-w-xl mx-auto lg:mx-0">
                        <form action="{{ route('home') }}#lowongan" method="GET" class="relative flex items-center bg-white rounded-full p-1.5 sm:p-2 pl-4 sm:pl-5 shadow-lg shadow-teal-900/5 border border-slate-200 hover:border-teal-300 focus-within:border-[#0F4C3A] focus-within:ring-4 focus-within:ring-teal-500/10 transition-all">
                            <svg class="w-5 h-5 text-slate-400 shrink-0 mr-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input type="text" name="q" value="{{ $search ?? '' }}" placeholder="Cari posisi, perusahaan, atau jurusan..."
                                   class="w-full min-w-0 bg-transparent text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none">
                            <button type="submit"
                                    class="px-4 sm:px-6 py-2 sm:py-2.5 rounded-full bg-[#0F4C3A] hover:bg-[#0A3629] text-white font-semibold text-xs sm:text-sm shadow-sm transition shrink-0">
                                Cari
                            </button>
                        </form>
                    </div>

                    <!-- 3 Value Props -->
                    <div class="pt-3 grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs font-semibold text-slate-700 max-w-xl mx-auto lg:mx-0">
                        <div class="flex items-center gap-2 justify-center lg:justify-start">
                            <div class="w-6 h-6 rounded-full bg-teal-100 text-[#0F4C3A] flex items-center justify-center shrink-0">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </div>
                            <span>Proses Mudah &amp; Cepat</span>
                        </div>

                        <div class="flex items-center gap-2 justify-center lg:justify-start">
                            <div class="w-6 h-6 rounded-full bg-teal-100 text-[#0F4C3A] flex items-center justify-center shrink-0">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                            </div>
                            <span>Terpercaya &amp; Terverifikasi</span>
                        </div>

                        <div class="flex items-center gap-2 justify-center lg:justify-start">
                            <div class="w-6 h-6 rounded-full bg-teal-100 text-[#0F4C3A] flex items-center justify-center shrink-0">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                            <span>Mitra {{ config('pkloka.school_city') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Illustration -->
                <div class="lg:col-span-6 relative flex justify-center lg:justify-end min-h-[280px] sm:min-h-[380px]">
                    <div class="absolute inset-0 rounded-[48%_0_42%_8%] bg-gradient-to-br from-emerald-100 via-teal-100 to-teal-200/70 opacity-90"></div>
                    <div class="absolute inset-0 rounded-[48%_0_42%_8%] bg-[url('/images/about-building.png')] bg-cover bg-center opacity-70 mix-blend-multiply"></div>
                    <div class="relative w-full max-w-[540px] self-end">
                        <div class="overflow-hidden">
                            <img src="{{ asset('images/hero-students.png') }}"
                                 alt="Siswa PKLoka"
                                 class="mx-auto w-[74%] max-h-[380px] object-contain object-bottom drop-shadow-xl transform hover:scale-[1.02] transition-transform duration-300">
                        </div>

                        <!-- Floating Micro Stats Badges (375px safe) -->
                        <div class="absolute bottom-2 left-2 sm:-bottom-3 sm:-left-3 bg-white rounded-2xl p-2.5 sm:p-3 shadow-lg border border-slate-100 flex items-center gap-2.5">
                            <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-[#0F4C3A] text-white flex items-center justify-center font-bold text-xs sm:text-sm shrink-0">
                                {{ $stats['posts_count'] ?? 0 }}
                            </div>
                            <div>
                                <p class="text-[11px] sm:text-xs font-bold text-slate-800">Lowongan Aktif</p>
                                <p class="text-[10px] sm:text-[11px] text-slate-500">Siap dilamar siswa</p>
                            </div>
                        </div>

                        <div class="absolute top-2 right-2 sm:-top-3 sm:-right-3 bg-white rounded-2xl p-2.5 sm:p-3 shadow-lg border border-slate-100 flex items-center gap-2">
                            <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-teal-100 text-[#0F4C3A] flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-[11px] sm:text-xs font-bold text-slate-800">{{ $stats['companies_count'] ?? 0 }} Mitra</p>
                                <p class="text-[10px] text-slate-400">Terverifikasi</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- SECTION FITUR UTAMA (5 Fitur Sesuai Infografik) -->
    <section id="fitur" class="py-10 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-6 space-y-1">
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Fitur Utama PKLoka</h2>
                <p class="text-sm text-slate-500">Semua kebutuhan pengelolaan PKL terintegrasi dalam satu platform</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                <div class="bg-white p-5 rounded-2xl border border-slate-100 hover:border-teal-200 shadow-sm hover:shadow-md transition-all group text-center space-y-2">
                    <div class="w-12 h-12 mx-auto rounded-2xl bg-teal-50 text-[#0F4C3A] flex items-center justify-center group-hover:bg-[#0F4C3A] group-hover:text-white transition-colors duration-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-sm text-slate-800">Pencarian Lowongan</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">Filter lowongan otomatis sesuai kompetensi keahlian siswa.</p>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-slate-100 hover:border-teal-200 shadow-sm hover:shadow-md transition-all group text-center space-y-2">
                    <div class="w-12 h-12 mx-auto rounded-2xl bg-teal-50 text-[#0F4C3A] flex items-center justify-center group-hover:bg-[#0F4C3A] group-hover:text-white transition-colors duration-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-sm text-slate-800">Pendaftaran &amp; Pelacakan</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">Kirim lamaran beserta CV dan pantau status seleksi langsung.</p>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-slate-100 hover:border-teal-200 shadow-sm hover:shadow-md transition-all group text-center space-y-2">
                    <div class="w-12 h-12 mx-auto rounded-2xl bg-teal-50 text-[#0F4C3A] flex items-center justify-center group-hover:bg-[#0F4C3A] group-hover:text-white transition-colors duration-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-sm text-slate-800">Verifikasi Perusahaan</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">Kurasi mitra industri resmi oleh admin sekolah sebelum membuka lowongan.</p>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-slate-100 hover:border-teal-200 shadow-sm hover:shadow-md transition-all group text-center space-y-2">
                    <div class="w-12 h-12 mx-auto rounded-2xl bg-teal-50 text-[#0F4C3A] flex items-center justify-center group-hover:bg-[#0F4C3A] group-hover:text-white transition-colors duration-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-sm text-slate-800">Dashboard &amp; Laporan</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">Statistik penempatan per bulan, rasio seleksi, dan ekspor rekap CSV.</p>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-slate-100 hover:border-teal-200 shadow-sm hover:shadow-md transition-all group text-center space-y-2 sm:col-span-2 lg:col-span-1">
                    <div class="w-12 h-12 mx-auto rounded-2xl bg-teal-50 text-[#0F4C3A] flex items-center justify-center group-hover:bg-[#0F4C3A] group-hover:text-white transition-colors duration-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-sm text-slate-800">Pengelolaan Profil</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">Pembaruan biodata siswa, data instansi, dan keamanan kata sandi.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION TENTANG PKLOKA -->
    <section id="tentang" class="py-10 bg-gradient-to-b from-white via-[#F4F9F6] to-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                <div class="lg:col-span-5 relative">
                    <div class="relative h-52 sm:h-60 rounded-[36%_16%_16%_16%] shadow-xl shadow-teal-900/5 overflow-hidden border border-teal-100">
                        <img src="{{ asset('images/about-building.png') }}"
                             alt="Gedung {{ config('pkloka.school_name') }}"
                             class="w-full h-full object-cover">
                    </div>
                </div>

                <div class="lg:col-span-5 space-y-3">
                    <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-teal-100 text-[#0F4C3A] text-xs font-bold">
                        Tentang PKLoka
                    </div>

                    <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight leading-tight">
                        Solusi Digital Pengelolaan PKL untuk {{ config('pkloka.school_name') }}
                    </h2>

                    <p class="text-sm text-slate-600 leading-relaxed">
                        PKLoka dirancang untuk memudahkan proses pencarian, pendaftaran, seleksi, dan pelaporan Praktik Kerja Lapangan secara terstruktur, transparan, dan bebas penumpukan pendaftaran.
                    </p>

                    <div class="lg:hidden space-y-2 pt-2">
                        <p class="flex items-start gap-2.5 text-xs font-semibold text-slate-700"><span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-[#0F4C3A] text-white text-[10px]">✓</span><span>Terintegrasi untuk Siswa, Perusahaan, dan Sekolah</span></p>
                        <p class="flex items-start gap-2.5 text-xs font-semibold text-slate-700"><span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-[#0F4C3A] text-white text-[10px]">✓</span><span>Penguncian otomatis saat siswa diterima PKL</span></p>
                        <p class="flex items-start gap-2.5 text-xs font-semibold text-slate-700"><span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-[#0F4C3A] text-white text-[10px]">✓</span><span>Akses aman dengan pembagian peran dan penyimpanan CV privat</span></p>
                    </div>

                    <div class="pt-3">
                        <a href="#lowongan" class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-[#0F4C3A] hover:bg-[#0A3629] text-white text-sm font-bold shadow-md shadow-teal-900/15 transition">
                            <span>Jelajahi Lowongan</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </a>
                    </div>
                </div>

                <div class="hidden lg:block lg:col-span-2 border-l border-teal-200 pl-6 space-y-4 text-xs text-slate-700">
                    <p class="flex items-start gap-2.5"><span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-[#0F4C3A] text-white text-[10px]">✓</span><span>Terintegrasi untuk Siswa, Perusahaan, dan Sekolah</span></p>
                    <p class="flex items-start gap-2.5"><span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-[#0F4C3A] text-white text-[10px]">✓</span><span>Penguncian otomatis saat siswa diterima PKL</span></p>
                    <p class="flex items-start gap-2.5"><span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-[#0F4C3A] text-white text-[10px]">✓</span><span>Keamanan berbasis peran &amp; penyimpanan berkas privat</span></p>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION KATALOG LOWONGAN TERBARU -->
    <section id="lowongan" class="py-14 bg-slate-50/70 border-t border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-6">
                <div class="space-y-1">
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                        {{ ($search ?? '') !== '' ? 'Hasil Pencarian Lowongan' : 'Daftar Lowongan PKL Terbaru' }}
                    </h2>
                    <p class="text-sm text-slate-500">
                        {{ ($search ?? '') !== '' ? 'Kata kunci: "'.$search.'"' : 'Temukan lowongan PKL dari mitra terverifikasi sesuai jurusanmu' }}
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    @if (($search ?? '') !== '' || ($selectedMajor ?? null) || ($showAll ?? false))
                        <a href="{{ route('home') }}#lowongan" class="text-xs font-semibold text-slate-500 hover:text-[#0F4C3A] transition">
                            Reset Filter
                        </a>
                    @endif
                    <a href="{{ route('home', ['semua' => 1]) }}#lowongan" class="text-sm font-bold text-[#0F4C3A] hover:text-[#0A3629] inline-flex items-center gap-1 transition">
                        <span>Lihat Semua Lowongan</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Filter Jurusan Cepat -->
            <div class="flex items-center gap-2 overflow-x-auto pb-4 mb-4">
                <a href="{{ route('home', array_filter(['q' => $search ?: null])) }}#lowongan"
                   class="px-3.5 py-1.5 rounded-full text-xs font-semibold whitespace-nowrap border transition {{ ! ($selectedMajor ?? null) ? 'bg-[#0F4C3A] text-white border-[#0F4C3A]' : 'bg-white text-slate-600 border-slate-200 hover:border-teal-300' }}">
                    Semua Jurusan
                </a>
                @foreach ($majors as $major)
                    <a href="{{ route('home', array_filter(['major_id' => $major->id, 'q' => $search ?: null])) }}#lowongan"
                       class="px-3.5 py-1.5 rounded-full text-xs font-semibold whitespace-nowrap border transition {{ (string) ($selectedMajor ?? '') === (string) $major->id ? 'bg-[#0F4C3A] text-white border-[#0F4C3A]' : 'bg-white text-slate-600 border-slate-200 hover:border-teal-300' }}">
                        {{ $major->name }}
                    </a>
                @endforeach
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($latestPosts as $post)
                    @php
                        $remaining = $post->remainingQuota();
                        $ctaUrl = route('student.posts.show', $post);
                        $ctaLabel = 'Lamar Sekarang';
                        if (auth()->check()) {
                            if (auth()->user()->isAdmin()) {
                                $ctaUrl = route('admin.posts.index', ['q' => $post->title]);
                                $ctaLabel = 'Pantau di Admin';
                            } elseif (auth()->user()->isCompany()) {
                                $ctaUrl = route('company.posts.index');
                                $ctaLabel = 'Kelola Lowongan';
                            }
                        }
                    @endphp
                    <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-xs hover:shadow-md hover:border-teal-300 transition-all flex flex-col justify-between group">
                        <div class="space-y-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-xl bg-teal-50 text-[#0F4C3A] flex items-center justify-center font-bold text-lg border border-teal-200/70 shrink-0 overflow-hidden">
                                    @if ($post->company?->logo_path)
                                        <img src="{{ asset('storage/'.$post->company->logo_path) }}" alt="Logo {{ $post->company->company_name }}" class="w-full h-full object-contain p-1">
                                    @else
                                        {{ strtoupper(substr($post->company->company_name ?? 'P', 0, 1)) }}
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <h4 class="text-xs font-semibold text-slate-500 truncate">{{ $post->company->company_name ?? 'Perusahaan Mitra' }}</h4>
                                    <h3 class="text-base font-bold text-slate-900 truncate group-hover:text-[#0F4C3A] transition-colors">
                                        {{ $post->title }}
                                    </h3>
                                </div>
                            </div>

                            <div class="flex items-center gap-1.5 flex-wrap">
                                <span class="px-2.5 py-1 rounded-md text-xs font-semibold bg-teal-50 text-[#0F4C3A] border border-teal-200/60">
                                    {{ $post->major->name ?? 'Semua Jurusan' }}
                                </span>
                                <span class="px-2.5 py-1 rounded-md text-xs font-medium {{ $remaining > 0 ? 'bg-slate-100 text-slate-600' : 'bg-rose-50 text-rose-700' }}">
                                    Sisa Kuota: {{ $remaining }} / {{ $post->quota }}
                                </span>
                            </div>

                            <p class="text-xs text-slate-600 line-clamp-2 leading-relaxed">
                                {{ Str::limit($post->description, 110) }}
                            </p>
                        </div>

                        <div class="pt-5 mt-4 border-t border-slate-100 flex items-center justify-between gap-2">
                            <div class="text-[11px] text-slate-500 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>Batas: {{ $post->deadline ? $post->deadline->format('d M Y') : 'Terbuka' }}</span>
                            </div>

                            <a href="{{ $ctaUrl }}"
                               class="px-4 py-2 rounded-full bg-[#0F4C3A] hover:bg-[#0A3629] text-white text-xs font-bold transition shadow-xs shrink-0">
                                {{ $ctaLabel }}
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-12 text-center bg-white rounded-2xl border border-dashed border-slate-200">
                        <svg class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <p class="text-sm text-slate-500 font-medium">Tidak ada lowongan yang sesuai pencarian saat ini.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- MITRA PKLOKA -->
    <section id="mitra" class="py-12 bg-white border-t border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-7 text-center space-y-1">
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Mitra Industri PKLoka</h2>
                <p class="text-sm text-slate-500">Perusahaan di {{ config('pkloka.school_city') }} yang telah diverifikasi oleh {{ config('pkloka.school_name') }}</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @forelse ($partners as $partner)
                    <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-xs transition hover:border-teal-300 hover:shadow-md">
                        <div class="flex items-start gap-3">
                            <div class="flex h-11 w-11 shrink-0 items-center justify-center overflow-hidden rounded-xl bg-teal-50 text-lg font-extrabold text-[#0F4C3A] border border-teal-100">
                                @if ($partner->logo_path)
                                    <img src="{{ asset('storage/'.$partner->logo_path) }}" alt="Logo {{ $partner->company_name }}" class="h-full w-full object-contain p-1">
                                @else
                                    {{ strtoupper(substr($partner->company_name, 0, 1)) }}
                                @endif
                            </div>
                            <div class="min-w-0">
                                <h3 class="font-bold text-sm text-slate-900">{{ $partner->company_name }}</h3>
                                <p class="mt-1 text-xs text-slate-500 leading-relaxed">{{ $partner->address ?: 'Mitra terverifikasi PKLoka' }}</p>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="col-span-full rounded-2xl border border-dashed border-slate-200 bg-slate-50 py-8 text-center text-sm text-slate-500">
                        Daftar mitra terverifikasi akan ditampilkan di sini.
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- SECTION FAQ -->
    <section id="faq" class="py-14 bg-white border-t border-slate-100">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <div class="text-center space-y-2">
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Pertanyaan Umum (FAQ)</h2>
                <p class="text-sm text-slate-500">Informasi seputar cara kerja platform PKLoka</p>
            </div>

            <div class="space-y-4">
                <details class="group bg-slate-50 p-5 rounded-2xl border border-slate-200/70 transition">
                    <summary class="flex justify-between items-center font-bold text-slate-800 cursor-pointer list-none text-sm gap-3">
                        <span>Bagaimana cara siswa mendaftar ke tempat PKL?</span>
                        <span class="transition group-open:rotate-180 shrink-0">
                            <svg class="w-4 h-4 text-[#0F4C3A]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </span>
                    </summary>
                    <p class="text-xs sm:text-sm text-slate-600 mt-3 leading-relaxed">
                        Akun siswa dibuat dan dikelola oleh Admin Sekolah {{ config('pkloka.school_name') }}. Siswa dapat masuk menggunakan email atau NISN, melengkapi profil, lalu memilih lowongan yang sesuai dengan jurusannya untuk mengajukan lamaran secara daring.
                    </p>
                </details>

                <details class="group bg-slate-50 p-5 rounded-2xl border border-slate-200/70 transition">
                    <summary class="flex justify-between items-center font-bold text-slate-800 cursor-pointer list-none text-sm gap-3">
                        <span>Apakah perusahaan harus diverifikasi terlebih dahulu?</span>
                        <span class="transition group-open:rotate-180 shrink-0">
                            <svg class="w-4 h-4 text-[#0F4C3A]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </span>
                    </summary>
                    <p class="text-xs sm:text-sm text-slate-600 mt-3 leading-relaxed">
                        Ya. Setiap mitra industri yang mendaftar akan ditinjau oleh Admin Sekolah. Setelah disetujui, perusahaan baru dapat masuk, menerbitkan lowongan PKL, dan memproses seleksi pelamar.
                    </p>
                </details>

                <details class="group bg-slate-50 p-5 rounded-2xl border border-slate-200/70 transition">
                    <summary class="flex justify-between items-center font-bold text-slate-800 cursor-pointer list-none text-sm gap-3">
                        <span>Apa yang terjadi jika siswa sudah diterima di salah satu lowongan?</span>
                        <span class="transition group-open:rotate-180 shrink-0">
                            <svg class="w-4 h-4 text-[#0F4C3A]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </span>
                    </summary>
                    <p class="text-xs sm:text-sm text-slate-600 mt-3 leading-relaxed">
                        PKLoka menerapkan aturan penguncian penempatan. Ketika satu perusahaan menerima lamaran seorang siswa, seluruh lamaran lain milik siswa tersebut yang masih berstatus menunggu otomatis dibatalkan agar kuota dapat dimanfaatkan siswa lain.
                    </p>
                </details>
            </div>
        </div>
    </section>

@endsection
