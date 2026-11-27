@extends('layouts.app')

@section('title', 'Dashboard Perusahaan')
@section('page_title', 'Dashboard Perusahaan')
@section('page_description', 'Kelola lowongan PKL dan pantau pelamar dari siswa')

@section('content')
<div class="space-y-6 sm:space-y-8">

    <!-- Company Header Bar -->
    <div class="bg-white p-5 sm:p-6 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-start gap-3.5 min-w-0">
            <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-teal-50 text-[#0F4C3A] font-black text-lg flex items-center justify-center border border-teal-200 shrink-0 overflow-hidden">
                @if ($company->logo_path)
                    <img src="{{ asset('storage/'.$company->logo_path) }}" alt="Logo {{ $company->company_name }}" class="w-full h-full object-contain p-1">
                @else
                    {{ strtoupper(substr($company->company_name, 0, 1)) }}
                @endif
            </div>
            <div class="space-y-1 min-w-0">
                <div class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full text-[11px] font-semibold bg-teal-50 text-[#0F4C3A] border border-teal-200/70">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span>Mitra Industri Terverifikasi</span>
                </div>
                <h2 class="text-lg sm:text-2xl font-black text-slate-900 tracking-tight truncate">{{ $company->company_name }}</h2>
                <p class="text-xs text-slate-500">{{ $company->address }} &bull; Kontak: {{ $company->contact }}</p>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-2.5 shrink-0">
            <a href="{{ route('company.posts.create') }}"
               class="px-4 sm:px-5 py-2.5 bg-[#0F4C3A] hover:bg-[#0A3629] text-white text-xs font-bold rounded-xl shadow-xs transition inline-flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Buka Lowongan Baru</span>
            </a>
            <a href="{{ route('company.profile.edit') }}"
               class="px-4 py-2.5 bg-slate-50 hover:bg-slate-100 text-slate-700 text-xs font-bold rounded-xl border border-slate-200 transition">
                Profil
            </a>
        </div>
    </div>

    <!-- 3 Key Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6">
        <div class="bg-white p-5 sm:p-6 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4 hover:border-teal-300 transition">
            <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-teal-50 text-[#0F4C3A] flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 sm:w-7 sm:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl sm:text-3xl font-black text-slate-900">{{ $stats['posts_count'] ?? 0 }}</p>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wide mt-0.5">Total Lowongan</p>
                <p class="text-[11px] text-[#0F4C3A] font-medium mt-1">Total kuota: {{ $stats['total_quota'] }} siswa</p>
            </div>
        </div>

        <div class="bg-white p-5 sm:p-6 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4 hover:border-teal-300 transition">
            <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-amber-50 text-amber-700 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 sm:w-7 sm:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl sm:text-3xl font-black text-slate-900">{{ $stats['total_applicants'] ?? 0 }}</p>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wide mt-0.5">Total Pelamar</p>
                <p class="text-[11px] text-amber-700 font-medium mt-1">{{ $stats['pending_count'] }} perlu ditinjau</p>
            </div>
        </div>

        <div class="bg-white p-5 sm:p-6 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4 hover:border-teal-300 transition">
            <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 sm:w-7 sm:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl sm:text-3xl font-black text-slate-900">{{ $stats['accepted_count'] ?? 0 }}</p>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wide mt-0.5">Siswa Diterima</p>
                <p class="text-[11px] text-emerald-700 font-medium mt-1">Sisa kuota: {{ $stats['remaining_quota'] }} &bull; Ditolak: {{ $stats['rejected_count'] }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        <!-- Daftar Lowongan -->
        <div class="lg:col-span-7 bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-xs space-y-5">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div>
                    <h3 class="font-extrabold text-base text-slate-900">Lowongan PKL Anda</h3>
                    <p class="text-xs text-slate-500">Status kuota dan pelamar tiap posisi</p>
                </div>
                <a href="{{ route('company.posts.index') }}" class="text-xs font-bold text-[#0F4C3A] hover:underline">
                    Lihat Semua &rarr;
                </a>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse ($activePosts as $post)
                    @php
                        $isOpen = $post->isOpen();
                        $hasQuota = $post->hasQuota();
                    @endphp
                    <div class="py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 first:pt-0 last:pb-0">
                        <div class="flex items-center gap-3.5 min-w-0">
                            <div class="w-11 h-11 rounded-xl bg-teal-50 text-[#0F4C3A] flex items-center justify-center font-bold text-base border border-teal-100 shrink-0">
                                {{ strtoupper(substr($post->title, 0, 1)) }}
                            </div>
                            <div class="space-y-1 min-w-0">
                                <h4 class="text-sm font-bold text-slate-900 hover:text-[#0F4C3A] transition truncate">
                                    <a href="{{ route('company.posts.applicants.index', $post) }}">{{ $post->title }}</a>
                                </h4>
                                <div class="flex flex-wrap items-center gap-x-2 gap-y-0.5 text-xs text-slate-500">
                                    <span>{{ $post->major->name }}</span>
                                    <span>&bull;</span>
                                    <span class="text-[#0F4C3A] font-semibold">{{ $post->applications_count }} Pelamar</span>
                                    <span>&bull;</span>
                                    <span>Sisa Kuota: {{ $post->remainingQuota() }}/{{ $post->quota }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 shrink-0 self-start sm:self-center">
                            @if (! $isOpen)
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                    Ditutup
                                </span>
                            @elseif (! $hasQuota)
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-50 text-amber-800 border border-amber-200">
                                    Kuota Penuh
                                </span>
                            @else
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-teal-50 text-[#0F4C3A] border border-teal-200">
                                    Aktif
                                </span>
                            @endif

                            <a href="{{ route('company.posts.applicants.index', $post) }}"
                               class="px-3.5 py-1.5 rounded-xl bg-slate-100 hover:bg-[#0F4C3A] hover:text-white text-slate-700 text-xs font-bold transition">
                                Pelamar
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="py-8 text-center text-slate-400 text-xs">
                        Belum ada lowongan PKL yang dibuka.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Pelamar Terbaru -->
        <div class="lg:col-span-5 bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-xs space-y-5">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div>
                    <h3 class="font-extrabold text-base text-slate-900">Pelamar Terbaru</h3>
                    <p class="text-xs text-slate-500">5 pengajuan masuk terakhir</p>
                </div>
            </div>

            <div class="space-y-3">
                @forelse ($recentApplicants as $app)
                    <a href="{{ route('company.posts.applicants.index', $app->post) }}"
                       class="block p-3.5 rounded-xl border border-slate-100 hover:border-teal-200 hover:bg-teal-50/30 transition">
                        <div class="flex items-start justify-between gap-2">
                            <div class="min-w-0">
                                <p class="text-sm font-bold text-slate-900 truncate">{{ $app->student->user->name }}</p>
                                <p class="text-xs text-slate-500 truncate">{{ $app->post->title }}</p>
                                <p class="text-[11px] text-slate-400 mt-0.5">{{ $app->student->major->name }} &bull; {{ $app->application_date->format('d M Y') }}</p>
                            </div>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold shrink-0
                                {{ $app->status === 'accepted' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($app->status === 'rejected' ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-amber-50 text-amber-800 border border-amber-200') }}">
                                {{ $app->status === 'accepted' ? 'Diterima' : ($app->status === 'rejected' ? 'Ditolak' : 'Menunggu') }}
                            </span>
                        </div>
                    </a>
                @empty
                    <div class="py-8 text-center text-xs text-slate-400">
                        Belum ada siswa yang melamar ke lowongan Anda.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

</div>
@endsection
