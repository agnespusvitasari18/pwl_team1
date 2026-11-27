@extends('layouts.app')

@section('title', 'Daftar Lowongan PKL')
@section('page_title', 'Daftar Lowongan PKL')
@section('page_description', 'Temukan lowongan PKL yang sesuai dengan kompetensi keahlianmu')

@section('content')
<div class="space-y-6">

    @if ($isAccepted)
        <div class="p-4 rounded-2xl bg-teal-50 border border-teal-200 text-[#0F4C3A] text-xs flex items-center gap-3 shadow-xs">
            <div class="w-6 h-6 rounded-full bg-[#0F4C3A] text-white flex items-center justify-center shrink-0">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <div>
                <strong class="font-bold">Informasi:</strong> Anda telah resmi diterima di salah satu tempat PKL. Pendaftaran ke lowongan lain dinonaktifkan secara otomatis.
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-8 items-start">

        <!-- LEFT COLUMN: Filter Sidebar -->
        <div class="lg:col-span-4 bg-white p-5 sm:p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-5">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center gap-2 font-bold text-slate-800 text-sm">
                    <svg class="w-4 h-4 text-[#0F4C3A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    <span>Filter Lowongan</span>
                </div>
                @if ($search || request()->has('major_id'))
                    <a href="{{ route('student.posts.index') }}" class="text-xs text-slate-400 hover:text-[#0F4C3A] font-semibold transition">
                        Reset
                    </a>
                @endif
            </div>

            <form method="GET" action="{{ route('student.posts.index') }}" class="space-y-4">
                <div class="space-y-1.5">
                    <label for="q" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Cari Kata Kunci</label>
                    <div class="relative">
                        <svg class="w-4 h-4 text-slate-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" id="q" name="q" value="{{ $search }}"
                               placeholder="Nama posisi / perusahaan..."
                               class="w-full pl-9 pr-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-[#0F4C3A] focus:ring-4 focus:ring-teal-500/10 transition">
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label for="major_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Jurusan / Keahlian</label>
                    <select id="major_id" name="major_id" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:outline-none focus:border-[#0F4C3A] focus:ring-4 focus:ring-teal-500/10 transition">
                        <option value="all" {{ $selectedMajor === null ? 'selected' : '' }}>Semua Jurusan</option>
                        @foreach ($majors as $m)
                            <option value="{{ $m->id }}" {{ (int) $selectedMajor === (int) $m->id ? 'selected' : '' }}>
                                {{ $m->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit"
                        class="w-full py-2.5 px-4 bg-[#0F4C3A] hover:bg-[#0A3629] text-white text-xs font-bold rounded-xl shadow-xs transition">
                    Terapkan Filter
                </button>
            </form>
        </div>

        <!-- RIGHT COLUMN: Jobs Cards -->
        <div class="lg:col-span-8 space-y-4">
            <div class="flex items-center justify-between text-xs text-slate-500 px-1">
                <span>Menampilkan <strong>{{ $posts->total() ?? count($posts) }}</strong> lowongan PKL</span>
            </div>

            <div class="space-y-4">
                @forelse ($posts as $post)
                    @php
                        $hasApplied = in_array($post->id, $appliedPostIds, true);
                        $remaining = $post->remainingQuota();
                        $diffDays = $post->deadline ? (int) today()->diffInDays($post->deadline->startOfDay(), false) : null;
                        $matchesMajor = isset($student) ? ((int) $student->major_id === (int) $post->major_id) : true;
                    @endphp

                    <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-xs hover:border-teal-300 hover:shadow-md transition-all flex flex-col sm:flex-row sm:items-center justify-between gap-4 group">
                        <div class="flex items-start gap-3.5 min-w-0">
                            <div class="w-12 h-12 rounded-2xl bg-teal-50 text-[#0F4C3A] font-black text-lg border border-teal-200/70 flex items-center justify-center shrink-0 overflow-hidden">
                                @if ($post->company?->logo_path)
                                    <img src="{{ asset('storage/'.$post->company->logo_path) }}" alt="Logo {{ $post->company->company_name }}" class="w-full h-full object-contain p-1">
                                @else
                                    {{ strtoupper(substr($post->company->company_name ?? 'M', 0, 1)) }}
                                @endif
                            </div>

                            <div class="space-y-1.5 min-w-0">
                                <h4 class="text-xs font-semibold text-slate-400 uppercase tracking-wide truncate">
                                    {{ $post->company->company_name ?? 'Perusahaan Mitra' }}
                                </h4>
                                <h3 class="text-base font-bold text-slate-900 group-hover:text-[#0F4C3A] transition">
                                    <a href="{{ route('student.posts.show', $post) }}">{{ $post->title }}</a>
                                </h3>

                                <div class="flex items-center gap-2 flex-wrap pt-0.5">
                                    <span class="px-2.5 py-0.5 rounded-md text-[11px] font-semibold bg-teal-50 text-[#0F4C3A] border border-teal-200/60">
                                        {{ $post->major->name ?? 'Semua Jurusan' }}
                                    </span>
                                    <span class="px-2.5 py-0.5 rounded-md text-[11px] font-medium {{ $remaining > 0 ? 'bg-slate-100 text-slate-600' : 'bg-rose-50 text-rose-700' }}">
                                        Sisa Kuota: {{ $remaining }} / {{ $post->quota }}
                                    </span>
                                    <span class="text-[11px] text-slate-400 truncate max-w-[220px]">
                                        {{ $post->company->address ?? config('pkloka.school_city') }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="flex sm:flex-col items-center sm:items-end justify-between sm:justify-center gap-3 pt-3 sm:pt-0 border-t sm:border-t-0 border-slate-100 shrink-0">
                            <span class="text-xs font-medium text-slate-500 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                @if ($diffDays !== null)
                                    @if ($diffDays > 0)
                                        <span>Sisa {{ $diffDays }} hari</span>
                                    @elseif ($diffDays === 0)
                                        <span class="text-amber-600 font-bold">Berakhir hari ini</span>
                                    @else
                                        <span class="text-rose-500 font-bold">Ditutup</span>
                                    @endif
                                @else
                                    <span>Terbuka</span>
                                @endif
                            </span>

                            @if ($hasApplied)
                                <span class="px-4 py-2 rounded-full text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200">
                                    Sudah Dilamar
                                </span>
                            @elseif ($isAccepted)
                                <span class="px-4 py-2 rounded-full text-xs font-bold bg-slate-100 text-slate-400 cursor-not-allowed">
                                    Terkunci
                                </span>
                            @elseif ($remaining <= 0)
                                <a href="{{ route('student.posts.show', $post) }}"
                                   class="px-4 py-2 rounded-full bg-rose-50 text-rose-700 border border-rose-200 text-xs font-bold transition">
                                    Kuota Penuh
                                </a>
                            @elseif (! $matchesMajor)
                                <a href="{{ route('student.posts.show', $post) }}"
                                   class="px-4 py-2 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition">
                                    Lihat Detail
                                </a>
                            @else
                                <a href="{{ route('student.posts.show', $post) }}"
                                   class="px-5 py-2 rounded-full bg-[#0F4C3A] hover:bg-[#0A3629] text-white text-xs font-bold shadow-xs transition">
                                    Lamar Sekarang
                                </a>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="p-12 text-center bg-white rounded-2xl border border-dashed border-slate-200 space-y-3">
                        <svg class="w-12 h-12 mx-auto text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <p class="text-sm font-semibold text-slate-700">Tidak ada lowongan ditemukan</p>
                        <p class="text-xs text-slate-400">Coba ubah kata kunci pencarian atau pilih filter "Semua Jurusan".</p>
                    </div>
                @endforelse
            </div>

            @if (method_exists($posts, 'links'))
                <div class="pt-4">
                    {{ $posts->links() }}
                </div>
            @endif
        </div>

    </div>

</div>
@endsection
