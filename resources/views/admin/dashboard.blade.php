@extends('layouts.app')

@section('title', 'Dashboard Admin')
@section('page_title', 'Dashboard Administrator')
@section('page_description', 'Rekap penempatan PKL, verifikasi mitra, dan analitik penyerapan jurusan')

@section('content')
@php
    $maxMonthly = max(1, collect($monthlyPlacements)->max(fn ($m) => max($m['accepted'], $m['total'])));
    $chartWidth = 540;
    $chartHeight = 165;
    $padLeft = 28;
    $padRight = 24;
    $padTop = 22;
    $padBottom = 20;
    $plotW = $chartWidth - $padLeft - $padRight;
    $plotH = $chartHeight - $padTop - $padBottom;
    $countPoints = count($monthlyPlacements);
    $stepX = $countPoints > 1 ? $plotW / ($countPoints - 1) : 0;

    $acceptedCoords = [];
    foreach ($monthlyPlacements as $idx => $item) {
        $x = round($padLeft + ($idx * $stepX), 1);
        $yAcc = round($padTop + $plotH - (($item['accepted'] / $maxMonthly) * $plotH), 1);
        $yTot = round($padTop + $plotH - (($item['total'] / $maxMonthly) * $plotH), 1);
        $acceptedCoords[] = [
            'x' => $x,
            'y_acc' => $yAcc,
            'y_tot' => $yTot,
            'bar_h' => max(2, round(($item['total'] / $maxMonthly) * $plotH, 1)),
            'accepted' => $item['accepted'],
            'total' => $item['total'],
            'label' => $item['label'],
        ];
    }

    $linePath = collect($acceptedCoords)->map(fn ($p, $i) => ($i === 0 ? 'M ' : 'L ').$p['x'].','.$p['y_acc'])->implode(' ');
    $areaPath = $linePath.' L '.$acceptedCoords[$countPoints - 1]['x'].','.($padTop + $plotH).' L '.$acceptedCoords[0]['x'].','.($padTop + $plotH).' Z';

    // Segmen Donut Chart (3 status nyata: Diterima, Menunggu, Ditolak)
    $acceptedPct = (float) ($stats['accepted_pct'] ?? 0);
    $pendingPct = (float) ($stats['pending_pct'] ?? 0);
    $rejectedPct = (float) ($stats['rejected_pct'] ?? 0);
    $pendingOffset = -$acceptedPct;
    $rejectedOffset = -($acceptedPct + $pendingPct);
@endphp

<div class="space-y-6 sm:space-y-8">

    <!-- Admin Welcome Header & Quick Export -->
    <div class="bg-white p-5 sm:p-6 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <div class="space-y-1">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-teal-50 text-[#0F4C3A] border border-teal-200">
                {{ config('pkloka.school_name') }}
            </span>
            <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
                Selamat datang, {{ auth()->user()->name }}!
            </h2>
            <p class="text-xs sm:text-sm text-slate-500">
                Pantau rekap penempatan siswa, verifikasi perusahaan mitra, dan tren penyerapan per jurusan.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('admin.students.create') }}"
               class="px-4 py-2.5 bg-[#0F4C3A] hover:bg-[#0A3629] text-white text-xs font-bold rounded-xl shadow-xs transition inline-flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Tambah Siswa</span>
            </a>
            <a href="{{ route('admin.students.export') }}"
               class="px-4 py-2.5 bg-teal-50 hover:bg-teal-100 text-[#0F4C3A] border border-teal-200 text-xs font-bold rounded-xl transition inline-flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span>Ekspor Rekap CSV</span>
            </a>
            <a href="{{ route('admin.companies.index', ['status' => 'pending']) }}"
               class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition">
                Verifikasi Mitra ({{ $stats['companies_pending'] }})
            </a>
        </div>
    </div>

    <!-- 4 Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col justify-between hover:border-teal-300 transition">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wide">Total Siswa</span>
                <div class="w-10 h-10 rounded-xl bg-teal-50 text-[#0F4C3A] flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
            </div>
            <div>
                <p class="text-3xl font-black text-slate-900">{{ $stats['students_total'] }}</p>
                <p class="text-xs text-slate-500 mt-1">
                    <span class="text-[#0F4C3A] font-semibold">{{ $stats['students_placed'] }} Ditempatkan</span> &bull; {{ $stats['students_unplaced'] }} Belum
                </p>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col justify-between hover:border-teal-300 transition">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wide">Total Perusahaan</span>
                <div class="w-10 h-10 rounded-xl bg-teal-50 text-[#0F4C3A] flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
            </div>
            <div>
                <p class="text-3xl font-black text-slate-900">{{ $stats['companies_total'] }}</p>
                <p class="text-xs text-slate-500 mt-1">
                    <span class="text-emerald-700 font-semibold">{{ $stats['companies_approved'] }} Disetujui</span> &bull; {{ $stats['companies_rejected'] }} Ditolak
                </p>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col justify-between hover:border-teal-300 transition">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wide">Lowongan Aktif</span>
                <div class="w-10 h-10 rounded-xl bg-teal-50 text-[#0F4C3A] flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
            <div>
                <p class="text-3xl font-black text-slate-900">{{ $stats['posts_open'] }}</p>
                <p class="text-xs text-slate-500 mt-1">
                    Dari {{ $stats['posts_total'] }} total lowongan
                </p>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col justify-between hover:border-teal-300 transition">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wide">Rasio Penempatan</span>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
            </div>
            <div>
                <p class="text-3xl font-black text-slate-900">{{ $stats['placement_rate'] }}%</p>
                <p class="text-xs text-slate-500 mt-1">
                    {{ $stats['applications_total'] }} total lamaran masuk
                </p>
            </div>
        </div>
    </div>

    <!-- Charts Row (Data Nyata dari Database) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">

        <!-- Left: Penempatan per Bulan (6 Bulan Terakhir) -->
        <div class="lg:col-span-7 bg-white p-5 sm:p-6 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col justify-between space-y-4">
            <div class="flex flex-wrap items-center justify-between gap-2 pb-3 border-b border-slate-100">
                <div>
                    <h3 class="font-extrabold text-base text-slate-900">Penempatan PKL per Bulan</h3>
                    <p class="text-xs text-slate-500">Data aktual 6 bulan terakhir (total lamaran vs siswa diterima)</p>
                </div>
                <div class="flex items-center gap-3 text-[11px] font-semibold">
                    <span class="inline-flex items-center gap-1.5 text-slate-500">
                        <span class="w-2.5 h-2.5 rounded-xs bg-teal-100 border border-teal-300"></span> Total Lamaran
                    </span>
                    <span class="inline-flex items-center gap-1.5 text-[#0F4C3A]">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#0F4C3A]"></span> Diterima
                    </span>
                </div>
            </div>

            <div class="pt-2 overflow-x-auto">
                <svg class="w-full min-w-[300px] h-52 overflow-visible" viewBox="0 0 {{ $chartWidth }} {{ $chartHeight }}" role="img" aria-label="Grafik Penempatan PKL per Bulan">
                    <defs>
                        <linearGradient id="pklPlacementGrad" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0%" stop-color="#0F4C3A" stop-opacity="0.22"/>
                            <stop offset="100%" stop-color="#0F4C3A" stop-opacity="0.02"/>
                        </linearGradient>
                    </defs>

                    <!-- Grid lines -->
                    @for ($g = 0; $g <= 4; $g++)
                        @php
                            $gy = round($padTop + ($plotH / 4) * $g, 1);
                            $gVal = round($maxMonthly - ($maxMonthly / 4) * $g, 1);
                        @endphp
                        <line x1="{{ $padLeft }}" y1="{{ $gy }}" x2="{{ $chartWidth - $padRight }}" y2="{{ $gy }}" stroke="#F1F5F9" stroke-width="1"/>
                        <text x="{{ $padLeft - 6 }}" y="{{ $gy + 3 }}" text-anchor="end" class="fill-slate-400 text-[9px] font-semibold">{{ rtrim(rtrim((string)$gVal, '0'), '.') }}</text>
                    @endfor

                    <!-- Bars for Total Applications per Month -->
                    @foreach ($acceptedCoords as $pt)
                        <rect x="{{ $pt['x'] - 14 }}" y="{{ $padTop + $plotH - $pt['bar_h'] }}" width="28" height="{{ $pt['bar_h'] }}" rx="5" fill="#E8F4EE" stroke="#C6E2D2" stroke-width="1">
                            <title>{{ $pt['label'] }}: {{ $pt['total'] }} lamaran, {{ $pt['accepted'] }} diterima</title>
                        </rect>
                    @endforeach

                    <!-- Area & Line for Accepted Placements -->
                    <path d="{{ $areaPath }}" fill="url(#pklPlacementGrad)"/>
                    <path d="{{ $linePath }}" fill="none" stroke="#0F4C3A" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>

                    <!-- Data Points & Value Labels -->
                    @foreach ($acceptedCoords as $pt)
                        <circle cx="{{ $pt['x'] }}" cy="{{ $pt['y_acc'] }}" r="4.5" fill="#0F4C3A" stroke="#FFFFFF" stroke-width="2">
                            <title>{{ $pt['label'] }}: {{ $pt['accepted'] }} diterima ({{ $pt['total'] }} lamaran)</title>
                        </circle>
                        <text x="{{ $pt['x'] }}" y="{{ $pt['y_acc'] - 9 }}" text-anchor="middle" class="fill-[#0F4C3A] text-[10px] font-extrabold">{{ $pt['accepted'] }}</text>
                    @endforeach
                </svg>

                <!-- Month Labels -->
                <div class="grid grid-cols-6 text-center text-[11px] font-semibold text-slate-500 mt-1">
                    @foreach ($monthlyPlacements as $item)
                        <div>
                            <span class="block text-slate-700">{{ $item['label'] }}</span>
                            <span class="text-[10px] text-slate-400">{{ $item['accepted'] }}/{{ $item['total'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Right: Persentase Status Lamaran (Diterima / Menunggu / Ditolak) -->
        <div class="lg:col-span-5 bg-white p-5 sm:p-6 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col justify-between space-y-4">
            <div class="pb-3 border-b border-slate-100">
                <h3 class="font-extrabold text-base text-slate-900">Persentase Status Lamaran</h3>
                <p class="text-xs text-slate-500">Distribusi nyata lamaran diterima, menunggu, dan ditolak</p>
            </div>

            <div class="py-2 flex flex-col items-center justify-center flex-1">
                <div class="relative w-40 h-40 flex items-center justify-center">
                    <svg class="w-full h-full -rotate-90" viewBox="0 0 36 36" role="img" aria-label="Diagram Donat Status Lamaran">
                        <!-- Base ring -->
                        <circle cx="18" cy="18" r="15.9155" fill="none" stroke="#F1F5F9" stroke-width="3.6"/>

                        @if ($stats['applications_total'] > 0)
                            <!-- Diterima (Dark Green #0F4C3A) -->
                            @if ($acceptedPct > 0)
                                <circle cx="18" cy="18" r="15.9155" fill="none" stroke="#0F4C3A" stroke-width="3.6"
                                        stroke-dasharray="{{ $acceptedPct }} {{ 100 - $acceptedPct }}" stroke-dashoffset="0"/>
                            @endif

                            <!-- Menunggu (Amber #F59E0B) -->
                            @if ($pendingPct > 0)
                                <circle cx="18" cy="18" r="15.9155" fill="none" stroke="#F59E0B" stroke-width="3.6"
                                        stroke-dasharray="{{ $pendingPct }} {{ 100 - $pendingPct }}" stroke-dashoffset="{{ $pendingOffset }}"/>
                            @endif

                            <!-- Ditolak (Rose #E11D48) -->
                            @if ($rejectedPct > 0)
                                <circle cx="18" cy="18" r="15.9155" fill="none" stroke="#E11D48" stroke-width="3.6"
                                        stroke-dasharray="{{ $rejectedPct }} {{ 100 - $rejectedPct }}" stroke-dashoffset="{{ $rejectedOffset }}"/>
                            @endif
                        @endif
                    </svg>
                    <div class="absolute inset-0 flex flex-col items-center justify-center text-center">
                        <span class="text-2xl font-black text-slate-900">{{ $stats['accepted_pct'] }}%</span>
                        <span class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">Diterima</span>
                        <span class="text-[10px] text-slate-400">{{ $stats['applications_total'] }} lamaran</span>
                    </div>
                </div>

                <!-- 3-Status Legend -->
                <div class="w-full grid grid-cols-3 gap-2 mt-5 text-center">
                    <div class="p-2.5 rounded-xl bg-teal-50/70 border border-teal-200/70">
                        <div class="flex items-center justify-center gap-1.5 text-[11px] font-bold text-[#0F4C3A]">
                            <span class="w-2.5 h-2.5 rounded-full bg-[#0F4C3A] shrink-0"></span>
                            <span>Diterima</span>
                        </div>
                        <p class="text-base font-black text-slate-900 mt-0.5">{{ $stats['applications_accepted'] }}</p>
                        <p class="text-[10px] font-semibold text-slate-500">{{ $stats['accepted_pct'] }}%</p>
                    </div>

                    <div class="p-2.5 rounded-xl bg-amber-50/70 border border-amber-200/70">
                        <div class="flex items-center justify-center gap-1.5 text-[11px] font-bold text-amber-800">
                            <span class="w-2.5 h-2.5 rounded-full bg-amber-500 shrink-0"></span>
                            <span>Menunggu</span>
                        </div>
                        <p class="text-base font-black text-slate-900 mt-0.5">{{ $stats['applications_pending'] }}</p>
                        <p class="text-[10px] font-semibold text-slate-500">{{ $stats['pending_pct'] }}%</p>
                    </div>

                    <div class="p-2.5 rounded-xl bg-rose-50/70 border border-rose-200/70">
                        <div class="flex items-center justify-center gap-1.5 text-[11px] font-bold text-rose-800">
                            <span class="w-2.5 h-2.5 rounded-full bg-rose-600 shrink-0"></span>
                            <span>Ditolak</span>
                        </div>
                        <p class="text-base font-black text-slate-900 mt-0.5">{{ $stats['applications_rejected'] }}</p>
                        <p class="text-[10px] font-semibold text-slate-500">{{ $stats['rejected_pct'] }}%</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tren Penyerapan Siswa per Jurusan -->
    <div class="bg-white p-5 sm:p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-5">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-100">
            <div>
                <h3 class="font-extrabold text-base text-slate-900">Tren Penyerapan Siswa per Jurusan</h3>
                <p class="text-xs text-slate-500">Perbandingan jumlah siswa ditempatkan vs belum ditempatkan di setiap kompetensi keahlian</p>
            </div>
            <div class="flex flex-wrap items-center gap-2 text-xs">
                <a href="{{ route('admin.students.export', ['penempatan' => 'sudah']) }}" class="px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-800 border border-emerald-200 font-semibold hover:bg-emerald-100 transition">
                    Ekspor Sudah Ditempatkan
                </a>
                <a href="{{ route('admin.students.export', ['penempatan' => 'belum']) }}" class="px-3 py-1.5 rounded-lg bg-amber-50 text-amber-800 border border-amber-200 font-semibold hover:bg-amber-100 transition">
                    Ekspor Belum Ditempatkan
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @forelse ($majorsAbsorption as $major)
                <div class="p-4 rounded-xl border border-slate-200/80 bg-slate-50/40 space-y-2.5">
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0">
                            <h4 class="text-sm font-bold text-slate-900 truncate">{{ $major->name }}</h4>
                            <p class="text-[11px] text-slate-500">
                                {{ $major->placed_students }} ditempatkan &bull; {{ $major->unplaced_students }} belum &bull; {{ $major->total_posts }} lowongan
                            </p>
                        </div>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-extrabold shrink-0 {{ $major->absorption_rate >= 50 ? 'bg-teal-100 text-[#0F4C3A]' : 'bg-amber-100 text-amber-800' }}">
                            {{ $major->absorption_rate }}%
                        </span>
                    </div>

                    <div class="w-full h-2.5 bg-slate-200/80 rounded-full overflow-hidden">
                        <div class="h-full bg-[#0F4C3A] rounded-full transition-all duration-500"
                             style="width: {{ $major->absorption_rate }}%"></div>
                    </div>
                </div>
            @empty
                <p class="col-span-full text-center text-xs text-slate-400 py-6">Belum ada data jurusan.</p>
            @endforelse
        </div>
    </div>

    <!-- Bottom Row: Perusahaan Menunggu Verifikasi & Lamaran Terbaru -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

        <!-- Pending Companies -->
        <div class="lg:col-span-5 bg-white p-5 sm:p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div>
                    <h3 class="font-extrabold text-sm sm:text-base text-slate-900">Menunggu Verifikasi Mitra</h3>
                    <p class="text-xs text-slate-500">Pendaftaran perusahaan terbaru</p>
                </div>
                <a href="{{ route('admin.companies.index', ['status' => 'pending']) }}" class="text-xs font-bold text-[#0F4C3A] hover:underline">
                    Kelola &rarr;
                </a>
            </div>

            <div class="space-y-3">
                @forelse ($pendingCompanies as $companyUser)
                    <div class="p-3.5 rounded-xl border border-slate-100 bg-slate-50/50 flex items-center justify-between gap-3">
                        <div class="min-w-0">
                            <p class="text-sm font-bold text-slate-900 truncate">{{ $companyUser->companyProfile?->company_name ?? $companyUser->name }}</p>
                            <p class="text-[11px] text-slate-500 truncate">{{ $companyUser->email }} &bull; {{ $companyUser->created_at->format('d M Y') }}</p>
                        </div>
                        <a href="{{ route('admin.companies.index', ['status' => 'pending']) }}"
                           class="px-3 py-1.5 rounded-lg bg-[#0F4C3A] hover:bg-[#0A3629] text-white text-xs font-bold shrink-0 transition">
                            Tinjau
                        </a>
                    </div>
                @empty
                    <div class="py-8 text-center text-xs text-slate-400">
                        Semua pendaftaran perusahaan telah diverifikasi.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Recent Applications -->
        <div class="lg:col-span-7 bg-white p-5 sm:p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div>
                    <h3 class="font-extrabold text-sm sm:text-base text-slate-900">Pengajuan Lamaran Terbaru</h3>
                    <p class="text-xs text-slate-500">Aktivitas pendaftaran PKL siswa terkini</p>
                </div>
                <a href="{{ route('admin.applications.index') }}" class="text-xs font-bold text-[#0F4C3A] hover:underline">
                    Lihat Semua &rarr;
                </a>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse ($recentApplications as $app)
                    <div class="py-3 first:pt-0 last:pb-0 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <div class="min-w-0">
                            <p class="text-sm font-bold text-slate-900 truncate">{{ $app->student->user->name }}</p>
                            <p class="text-xs text-slate-500 truncate">
                                {{ $app->post->title }} &bull; <span class="text-slate-700 font-medium">{{ $app->post->company->company_name }}</span>
                            </p>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            <span class="text-[11px] text-slate-400">{{ $app->application_date->format('d M Y') }}</span>
                            @if ($app->status === 'accepted')
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">Diterima</span>
                            @elseif ($app->status === 'rejected')
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-rose-50 text-rose-800 border border-rose-200">Ditolak</span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-50 text-amber-800 border border-amber-200">Menunggu</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="py-8 text-center text-xs text-slate-400">
                        Belum ada pengajuan lamaran siswa.
                    </div>
                @endforelse
            </div>
        </div>

    </div>

</div>
@endsection
