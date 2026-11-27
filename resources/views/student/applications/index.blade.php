@extends('layouts.app')

@section('title', 'Lamaran Saya')
@section('page_title', 'Status Pengajuan PKL Saya')
@section('page_description', 'Pantau status verifikasi, persetujuan, atau penolakan lamaran magang Anda')

@section('content')
<div class="space-y-6">

    @if ($isAccepted)
        <div class="p-4 sm:p-5 rounded-xl bg-gradient-to-r from-[#0F4C3A] to-[#07261D] text-white text-xs flex items-center gap-3 border border-emerald-800 shadow-xs">
            <svg class="w-6 h-6 text-amber-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <strong class="text-amber-300 font-bold">Selamat!</strong> Anda telah resmi diterima di salah satu tempat PKL. Seluruh pengajuan lamaran lainnya otomatis dinonaktifkan sesuai aturan mitigasi penumpukan pendaftaran.
            </div>
        </div>
    @endif

    <div class="space-y-4">
        @forelse ($applications as $app)
            <div class="bg-white rounded-xl border border-[#D5E6DC] shadow-xs p-5 sm:p-6 transition hover:border-[#0F4C3A]/50">
                <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
                    <div class="space-y-2 flex-1 min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <h3 class="text-base font-bold text-slate-900">
                                {{ $app->post->title }}
                            </h3>
                            <span class="text-slate-300">&bull;</span>
                            <span class="text-xs font-bold text-[#0D4A38]">
                                {{ $app->post->company->company_name }}
                            </span>

                            @if ($app->status === 'accepted')
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">
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

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs text-slate-600 pt-1">
                            <div>
                                <span class="text-slate-400">Jurusan:</span>
                                <span class="font-semibold text-slate-800">{{ $app->post->major->name }}</span>
                            </div>
                            <div>
                                <span class="text-slate-400">Tanggal Diajukan:</span>
                                <span class="text-slate-700">{{ $app->application_date->format('d M Y') }}</span>
                            </div>
                            <div>
                                <span class="text-slate-400">Alamat Perusahaan:</span>
                                <span class="text-slate-700">{{ $app->post->company->address }}</span>
                            </div>
                            <div>
                                <span class="text-slate-400">Kontak Perusahaan:</span>
                                <span class="text-slate-700">{{ $app->post->company->contact }}</span>
                            </div>
                        </div>

                        @if ($app->notes)
                            <div class="mt-3 p-3 bg-[#F4F8F6] rounded-lg border border-[#D5E6DC] text-xs text-slate-700">
                                <span class="font-bold text-[#0D4A38] block mb-0.5">Catatan Pengantar yang Anda Kirim:</span>
                                {{ $app->notes }}
                            </div>
                        @endif

                        <!-- Tautan berkas privat -->
                        <div class="flex items-center gap-2.5 pt-1 flex-wrap">
                            @if ($app->portfolio_url)
                                <a href="{{ $app->portfolio_url }}" target="_blank" rel="noopener noreferrer"
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-[#E8F4EE] text-[#0D4A38] hover:bg-[#D5E6DC] border border-[#C6E2D2] text-xs font-bold transition">
                                    <span>Tautan Portofolio Anda</span> &nearr;
                                </a>
                            @endif

                            @if ($app->resume_file)
                                <a href="{{ route('applications.resume', $app) }}" target="_blank"
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-slate-100 text-slate-700 hover:bg-slate-200 border border-slate-200 text-xs font-bold transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <span>Lihat Berkas CV/Resume</span>
                                </a>
                            @endif
                        </div>

                        @if ($app->status === 'rejected')
                            <div class="mt-3 p-3 rounded-lg bg-rose-50 border border-rose-200 text-xs text-rose-800 space-y-1">
                                <span class="font-bold block">Keterangan:</span>
                                <p>{{ $app->rejection_reason ?? 'Mohon maaf, profil Anda belum sesuai dengan kriteria yang dibutuhkan untuk periode saat ini.' }}</p>
                            </div>
                        @endif
                    </div>

                    <div class="shrink-0 flex items-center md:flex-col gap-2 pt-2 md:pt-0 border-t md:border-t-0 border-[#E0ECE6]">
                        <a href="{{ route('student.posts.show', $app->post) }}"
                           class="px-4 py-2 rounded-xl text-xs font-bold bg-[#E8F4EE] hover:bg-[#D5E6DC] text-[#0D4A38] border border-[#C6E2D2] transition text-center w-full">
                            Lihat Lowongan
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-xl border border-[#D5E6DC] shadow-xs p-12 text-center text-xs text-slate-500">
                Anda belum pernah mengajukan lamaran ke lowongan PKL mana pun.
                <div class="mt-3">
                    <a href="{{ route('student.posts.index') }}" class="px-4 py-2 bg-[#0F4C3A] hover:bg-[#0A3629] text-white rounded-xl font-bold text-xs inline-flex items-center gap-1.5 shadow-xs">
                        <svg class="w-3.5 h-3.5 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <span>Cari Lowongan PKL Sekarang</span>
                    </a>
                </div>
            </div>
        @endforelse
    </div>

</div>
@endsection
