@extends('layouts.app')

@section('title', 'Pelamar Lowongan')
@section('page_title', 'Daftar Pelamar PKL')
@section('page_description', 'Seleksi dan tanggapi permohonan magang dari siswa SMK')

@section('content')
<div class="space-y-6">

    <!-- Header Ringkasan Lowongan -->
    <div class="bg-white p-5 sm:p-6 rounded-xl border border-[#D5E6DC] shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1 flex-wrap">
                <a href="{{ route('company.posts.index') }}" class="text-xs font-semibold text-[#0D4A38] hover:underline flex items-center gap-1">
                    <span>&larr;</span> Lowongan Saya
                </a>
                <span class="text-slate-300">&bull;</span>
                <span class="text-xs font-bold text-amber-700 bg-amber-50 px-2 py-0.5 rounded border border-amber-200">
                    {{ $post->major->name }}
                </span>
            </div>
            <h2 class="text-lg font-extrabold text-[#0D4A38]">{{ $post->title }}</h2>
            <div class="mt-2 flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-slate-600">
                <span>Kuota: <strong class="text-slate-900">{{ $post->quota }}</strong> siswa</span>
                <span>&bull;</span>
                <span>Diterima: <strong class="text-emerald-700">{{ $post->acceptedCount() }}</strong></span>
                <span>&bull;</span>
                <span>Sisa Kuota: <strong class="{{ $post->remainingQuota() > 0 ? 'text-slate-900' : 'text-rose-600' }}">{{ $post->remainingQuota() }}</strong></span>
                <span>&bull;</span>
                <span>Batas: {{ $post->deadline->format('d M Y') }}</span>
            </div>
        </div>

        <div class="shrink-0 flex items-center gap-2">
            <span class="px-3.5 py-1.5 rounded-xl text-xs font-bold bg-[#E8F4EE] text-[#0D4A38] border border-[#C6E2D2]">
                Total {{ $statusCounts['all'] ?? $applications->count() }} Pendaftar
            </span>
        </div>
    </div>

    <!-- Filter Status Pelamar -->
    <div class="bg-white p-2 rounded-xl border border-[#D5E6DC] shadow-xs flex flex-wrap gap-1.5">
        <a href="{{ route('company.posts.applicants.index', ['post' => $post, 'status' => 'all']) }}"
           class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition {{ ($status ?? 'all') === 'all' ? 'bg-[#0F4C3A] text-white' : 'text-slate-600 hover:bg-teal-50' }}">
            Semua ({{ $statusCounts['all'] ?? 0 }})
        </a>
        <a href="{{ route('company.posts.applicants.index', ['post' => $post, 'status' => 'pending']) }}"
           class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition {{ ($status ?? 'all') === 'pending' ? 'bg-[#0F4C3A] text-white' : 'text-slate-600 hover:bg-teal-50' }}">
            Menunggu ({{ $statusCounts['pending'] ?? 0 }})
        </a>
        <a href="{{ route('company.posts.applicants.index', ['post' => $post, 'status' => 'accepted']) }}"
           class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition {{ ($status ?? 'all') === 'accepted' ? 'bg-[#0F4C3A] text-white' : 'text-slate-600 hover:bg-teal-50' }}">
            Diterima ({{ $statusCounts['accepted'] ?? 0 }})
        </a>
        <a href="{{ route('company.posts.applicants.index', ['post' => $post, 'status' => 'rejected']) }}"
           class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition {{ ($status ?? 'all') === 'rejected' ? 'bg-[#0F4C3A] text-white' : 'text-slate-600 hover:bg-teal-50' }}">
            Ditolak ({{ $statusCounts['rejected'] ?? 0 }})
        </a>
    </div>

    <!-- Daftar Pelamar Siswa -->
    <div class="space-y-4">
        @forelse ($applications as $app)
            @php
                $student = $app->student;
            @endphp
            <div class="bg-white rounded-xl border border-[#D5E6DC] shadow-xs p-5 sm:p-6 transition hover:border-[#0F4C3A]/40">
                <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
                    <!-- Info Siswa -->
                    <div class="space-y-2.5 flex-1 min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <h3 class="text-base font-bold text-slate-900">
                                {{ $student->user->name }}
                            </h3>
                            <span class="font-mono text-xs px-2 py-0.5 rounded bg-slate-100 text-slate-700 border border-slate-200 font-semibold">
                                NISN: {{ $student->nisn }}
                            </span>

                            @if ($app->status === 'accepted')
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-800 border border-emerald-200 flex items-center gap-1">
                                    <svg class="w-3 h-3 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    Diterima
                                </span>
                            @elseif ($app->status === 'rejected')
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-50 text-rose-800 border border-rose-200">
                                    Ditolak
                                </span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200">
                                    Menunggu Keputusan
                                </span>
                            @endif
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs text-slate-600">
                            <div>
                                <span class="text-slate-400">Asal Sekolah:</span>
                                <span class="font-semibold text-slate-800">{{ $student->school_name ?? config('pkloka.school_name') }}</span>
                            </div>
                            <div>
                                <span class="text-slate-400">Jurusan:</span>
                                <span class="font-semibold text-slate-800">{{ $student->major->name }}</span>
                            </div>
                            <div>
                                <span class="text-slate-400">Email:</span>
                                <span class="text-slate-700 break-all">{{ $student->user->email }}</span>
                            </div>
                            <div>
                                <span class="text-slate-400">No. Kontak:</span>
                                <span class="text-slate-700">{{ $student->phone ?? '-' }}</span>
                            </div>
                            <div>
                                <span class="text-slate-400">Alamat:</span>
                                <span class="text-slate-700">{{ $student->address ?? '-' }}</span>
                            </div>
                            <div>
                                <span class="text-slate-400">Tanggal Melamar:</span>
                                <span class="text-slate-700">{{ $app->application_date->format('d M Y') }}</span>
                            </div>
                        </div>

                        @if ($app->notes)
                            <div class="p-3 bg-[#F4F8F6] rounded-lg border border-[#D5E6DC] text-xs text-slate-700">
                                <span class="font-bold text-[#0D4A38] block mb-1">Surat Motivasi / Catatan Pelamar:</span>
                                {{ $app->notes }}
                            </div>
                        @endif

                        <!-- Lampiran Berkas / Portofolio -->
                        <div class="flex items-center gap-2.5 pt-1 flex-wrap">
                            @if ($app->portfolio_url)
                                <a href="{{ $app->portfolio_url }}" target="_blank" rel="noopener noreferrer"
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-[#E8F4EE] text-[#0D4A38] hover:bg-[#D5E6DC] border border-[#C6E2D2] text-xs font-bold transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                    </svg>
                                    <span>Tautan Portofolio Siswa</span>
                                </a>
                            @endif

                            @if ($app->resume_file)
                                <a href="{{ route('applications.resume', $app) }}" target="_blank"
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-slate-100 text-slate-700 hover:bg-slate-200 border border-slate-200 text-xs font-bold transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    <span>Lihat CV (PDF)</span>
                                </a>
                                <a href="{{ route('applications.resume', ['application' => $app, 'download' => 1]) }}"
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white text-slate-600 hover:bg-slate-50 border border-slate-200 text-xs font-semibold transition">
                                    <span>Unduh CV</span>
                                </a>
                            @endif
                        </div>

                        @if ($app->status === 'rejected' && $app->rejection_reason)
                            <div class="mt-2 p-3 rounded-lg bg-rose-50 border border-rose-200 text-xs text-rose-800">
                                <span class="font-bold">Keterangan Penolakan:</span> {{ $app->rejection_reason }}
                            </div>
                        @endif
                    </div>

                    <!-- Tombol Aksi (Terima / Tolak) -->
                    @if ($app->status === 'pending')
                        <div class="shrink-0 flex flex-col sm:flex-row md:flex-col gap-2 pt-2 md:pt-0 border-t md:border-t-0 border-[#E0ECE6] md:min-w-[155px]">
                            @if ($post->hasQuota())
                                <form action="{{ route('company.applications.accept', $app) }}" method="POST"
                                      onsubmit="return confirm('Apakah Anda yakin ingin MENERIMA siswa {{ addslashes($student->user->name) }}? Lamaran siswa ini di tempat lain akan otomatis dibatalkan.')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                            class="w-full px-4 py-2 bg-[#0F4C3A] hover:bg-[#0A3629] text-white text-xs font-bold rounded-xl transition shadow-xs text-center flex items-center justify-center gap-1.5">
                                        <svg class="w-3.5 h-3.5 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        <span>Terima Siswa</span>
                                    </button>
                                </form>
                            @else
                                <button type="button" disabled
                                        class="w-full px-4 py-2 bg-slate-100 text-slate-400 text-xs font-bold rounded-xl cursor-not-allowed text-center border border-slate-200">
                                    Kuota Penuh
                                </button>
                            @endif

                            <button type="button" onclick="toggleRejectBox({{ $app->id }})"
                                    class="w-full px-4 py-2 bg-white hover:bg-rose-50 text-rose-700 border border-rose-200 text-xs font-bold rounded-xl transition text-center">
                                Tolak Lamaran
                            </button>
                        </div>
                    @else
                        <div class="shrink-0 text-right">
                            <span class="text-xs text-slate-400 font-medium">Sudah Diproses</span>
                        </div>
                    @endif
                </div>

                <!-- Formulir Tolak Lamaran -->
                @if ($app->status === 'pending')
                    <div id="reject-box-{{ $app->id }}" class="hidden mt-4 pt-4 border-t border-[#E0ECE6]">
                        <form action="{{ route('company.applications.reject', $app) }}" method="POST" class="space-y-3">
                            @csrf
                            @method('PATCH')
                            <div>
                                <label for="rejection_reason-{{ $app->id }}" class="block text-xs font-bold text-slate-700 mb-1">
                                    Alasan Penolakan (akan dapat dibaca oleh siswa):
                                </label>
                                <textarea id="rejection_reason-{{ $app->id }}" name="rejection_reason" rows="2"
                                          class="w-full px-3 py-2 text-xs bg-white border border-[#D5E6DC] rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-rose-500"
                                          placeholder="Contoh: Kualifikasi portofolio belum sesuai dengan kebutuhan proyek saat ini..."></textarea>
                            </div>
                            <div class="flex items-center justify-end gap-2">
                                <button type="button" onclick="toggleRejectBox({{ $app->id }})"
                                        class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-lg transition">
                                    Batal
                                </button>
                                <button type="submit"
                                        class="px-3.5 py-1.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-lg transition shadow-xs">
                                    Konfirmasi Tolak
                                </button>
                            </div>
                        </form>
                    </div>
                @endif
            </div>
        @empty
            <div class="bg-white rounded-xl border border-[#D5E6DC] shadow-xs p-12 text-center text-xs text-slate-500">
                Belum ada siswa pelamar pada kategori ini.
            </div>
        @endforelse
    </div>

</div>

<script>
    function toggleRejectBox(id) {
        const el = document.getElementById('reject-box-' + id);
        if (el) {
            el.classList.toggle('hidden');
        }
    }
</script>
@endsection
