@extends('layouts.app')

@section('title', 'Dashboard Siswa')
@section('page_title', 'Dashboard Siswa')
@section('page_description', 'Portal informasi status pendaftaran dan penempatan PKL')

@section('content')
<div class="space-y-6 sm:space-y-8">

    <!-- Greeting Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-5 sm:p-6 rounded-2xl border border-slate-200/80 shadow-xs">
        <div class="space-y-1">
            <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
                Halo, {{ $student->user->name }}!
            </h2>
            <p class="text-xs sm:text-sm text-slate-500">
                NISN: <span class="font-mono font-semibold text-slate-700">{{ $student->nisn }}</span> &bull;
                Jurusan: <span class="font-semibold text-[#0F4C3A]">{{ $student->major->name ?? 'Siswa' }}</span>
            </p>
        </div>

        @if ($acceptedApplication)
            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse shrink-0"></span>
                <span>Diterima PKL di {{ $acceptedApplication->post->company->company_name ?? 'Perusahaan' }}</span>
            </span>
        @else
            <a href="{{ route('student.posts.index') }}"
               class="px-5 py-2.5 rounded-full bg-[#0F4C3A] hover:bg-[#0A3629] text-white text-xs font-bold transition shadow-xs self-start md:self-center inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <span>Cari Lowongan PKL</span>
            </a>
        @endif
    </div>

    <!-- 4 Stats Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col justify-between hover:border-teal-300 transition">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-teal-50 text-[#0F4C3A] flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
            <div>
                <p class="text-2xl sm:text-3xl font-black text-slate-900">{{ $stats['available_posts_count'] ?? 0 }}</p>
                <p class="text-xs font-semibold text-slate-500 mt-0.5">Lowongan Sesuai</p>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col justify-between hover:border-teal-300 transition">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                </div>
            </div>
            <div>
                <p class="text-2xl sm:text-3xl font-black text-slate-900">{{ $stats['pending_count'] ?? 0 }}</p>
                <p class="text-xs font-semibold text-slate-500 mt-0.5">Lamaran Menunggu</p>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col justify-between hover:border-teal-300 transition">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
            </div>
            <div>
                <p class="text-2xl sm:text-3xl font-black text-slate-900">{{ $stats['accepted_count'] ?? 0 }}</p>
                <p class="text-xs font-semibold text-slate-500 mt-0.5">Diterima</p>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col justify-between hover:border-teal-300 transition">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-teal-50 text-[#0F4C3A] flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
            <div>
                <p class="text-2xl sm:text-3xl font-black text-slate-900">{{ $stats['total_applications'] ?? 0 }}</p>
                <p class="text-xs font-semibold text-slate-500 mt-0.5">Total Diajukan</p>
            </div>
        </div>
    </div>

    <!-- Main Grid Section -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-8 items-start">

        <!-- Left: Lowongan Terbaru -->
        <div class="lg:col-span-7 bg-white p-5 sm:p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-5">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#0F4C3A]"></span>
                    <h3 class="font-extrabold text-base text-slate-900">Rekomendasi Lowongan Sesuai Jurusan</h3>
                </div>
                <a href="{{ route('student.posts.index') }}" class="text-xs font-bold text-[#0F4C3A] hover:underline shrink-0">
                    Lihat Semua &rarr;
                </a>
            </div>

            <div class="space-y-3">
                @forelse ($recommendedPosts as $post)
                    <div class="p-4 rounded-xl border border-slate-100 hover:border-teal-200 hover:bg-teal-50/30 transition flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-10 h-10 rounded-xl bg-teal-100 text-[#0F4C3A] font-bold flex items-center justify-center shrink-0 text-sm overflow-hidden">
                                @if ($post->company?->logo_path)
                                    <img src="{{ asset('storage/'.$post->company->logo_path) }}" alt="Logo {{ $post->company->company_name }}" class="w-full h-full object-contain p-1">
                                @else
                                    {{ strtoupper(substr($post->company->company_name ?? 'M', 0, 1)) }}
                                @endif
                            </div>
                            <div class="min-w-0">
                                <h4 class="text-sm font-bold text-slate-900 truncate hover:text-[#0F4C3A] transition">
                                    <a href="{{ route('student.posts.show', $post) }}">{{ $post->title }}</a>
                                </h4>
                                <p class="text-xs text-slate-500 truncate">{{ $post->company->company_name ?? 'Perusahaan Mitra' }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 shrink-0">
                            <span class="text-[11px] text-slate-500 hidden sm:inline">
                                Sisa: {{ $post->remainingQuota() }}/{{ $post->quota }}
                            </span>
                            <a href="{{ route('student.posts.show', $post) }}"
                               class="px-3.5 py-1.5 rounded-full bg-teal-50 hover:bg-[#0F4C3A] hover:text-white text-[#0F4C3A] text-xs font-bold transition">
                                Detail
                            </a>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 py-6 text-center">Belum ada rekomendasi lowongan baru untuk jurusan Anda saat ini.</p>
                @endforelse
            </div>
        </div>

        <!-- Right: Banner & Riwayat Lamaran -->
        <div class="lg:col-span-5 space-y-6">
            <div class="relative overflow-hidden p-6 rounded-2xl bg-gradient-to-br from-[#0F4C3A] to-[#07261D] text-white shadow-md">
                <div class="relative z-10 space-y-3">
                    <span class="inline-block px-2.5 py-1 rounded-md text-[10px] font-bold bg-white/15 text-amber-200 uppercase tracking-wider">
                        Eksplorasi Peluang
                    </span>
                    <h3 class="text-lg font-black tracking-tight leading-tight">
                        Wujudkan Pengalaman PKL Terbaikmu!
                    </h3>
                    <p class="text-xs text-emerald-100/90 leading-relaxed">
                        Temukan kesempatan praktik kerja lapangan dari mitra industri resmi {{ config('pkloka.school_name') }}.
                    </p>
                    <div class="pt-1">
                        <a href="{{ route('student.posts.index') }}"
                           class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white text-[#0F4C3A] text-xs font-bold hover:bg-teal-50 transition shadow-xs">
                            <span>Cari Lowongan Sekarang</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <div class="bg-white p-5 sm:p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="font-bold text-sm text-slate-800">Status Lamaran Terakhir</h3>
                    <a href="{{ route('student.applications.index') }}" class="text-xs font-semibold text-[#0F4C3A] hover:underline">Semua</a>
                </div>

                <div class="space-y-3">
                    @forelse ($recentApplications as $app)
                        <div class="flex items-center justify-between text-xs p-2.5 rounded-xl hover:bg-slate-50 transition gap-2">
                            <div class="min-w-0">
                                <p class="font-bold text-slate-800 truncate">{{ $app->post->title }}</p>
                                <p class="text-[11px] text-slate-400 truncate">{{ $app->post->company->company_name }}</p>
                            </div>
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold shrink-0
                                {{ $app->status === 'accepted' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($app->status === 'rejected' ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-amber-50 text-amber-700 border border-amber-200') }}">
                                {{ $app->status === 'accepted' ? 'Diterima' : ($app->status === 'rejected' ? 'Ditolak' : 'Menunggu') }}
                            </span>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 py-3 text-center">Belum ada riwayat lamaran yang diajukan.</p>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
