@extends('layouts.app')

@section('title', 'Data Pengajuan PKL')
@section('page_title', 'Monitoring Pengajuan Lamaran PKL')
@section('page_description', 'Pantau seluruh pendaftaran PKL yang diajukan oleh siswa ke perusahaan mitra')

@section('content')
<div class="space-y-6">

    <!-- Filter Bar -->
    <div class="bg-white p-4 rounded-xl border border-[#D5E6DC] shadow-xs">
        <form method="GET" action="{{ route('admin.applications.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <div>
                <input type="text" name="q" value="{{ $search }}"
                       placeholder="Cari siswa, NISN, atau mitra..."
                       class="w-full px-3 py-2 text-xs bg-white border border-[#C6E2D2] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0F4C3A] focus:border-[#0F4C3A] placeholder-slate-400">
            </div>

            <div>
                <select name="status" class="w-full px-3 py-2 text-xs bg-white border border-[#C6E2D2] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0F4C3A] focus:border-[#0F4C3A] text-slate-700">
                    <option value="all" {{ $status === 'all' ? 'selected' : '' }}>Semua Status</option>
                    <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Menunggu Keputusan</option>
                    <option value="accepted" {{ $status === 'accepted' ? 'selected' : '' }}>Diterima</option>
                    <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>

            <div>
                <select name="major_id" class="w-full px-3 py-2 text-xs bg-white border border-[#C6E2D2] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0F4C3A] focus:border-[#0F4C3A] text-slate-700">
                    <option value="">Semua Jurusan</option>
                    @foreach ($majors as $m)
                        <option value="{{ $m->id }}" {{ (string) $majorId === (string) $m->id ? 'selected' : '' }}>{{ $m->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="flex-1 py-2 px-4 bg-[#0F4C3A] hover:bg-[#0A3629] text-white text-xs font-bold rounded-lg transition shadow-xs">
                    Terapkan Filter
                </button>
                @if ($search || $status !== 'all' || $majorId)
                    <a href="{{ route('admin.applications.index') }}" class="px-3 py-2 text-xs text-slate-500 hover:text-slate-800 font-semibold">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Tabel Pengajuan -->
    <div class="bg-white rounded-xl border border-[#D5E6DC] shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-[#F4F8F6] border-b border-[#E0ECE6] text-xs font-bold text-[#0D4A38] uppercase tracking-wider">
                    <tr>
                        <th class="px-5 py-3.5">Siswa Pelamar</th>
                        <th class="px-5 py-3.5">Jurusan</th>
                        <th class="px-5 py-3.5">Perusahaan &amp; Lowongan</th>
                        <th class="px-5 py-3.5">Tanggal</th>
                        <th class="px-5 py-3.5 text-center">Berkas / Portofolio</th>
                        <th class="px-5 py-3.5 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($applications as $app)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="px-5 py-4">
                                <div class="font-bold text-slate-900">{{ $app->student->user->name }}</div>
                                <div class="text-xs text-slate-500 font-mono">NISN: {{ $app->student->nisn }}</div>
                            </td>
                            <td class="px-5 py-4">
                                <span class="inline-block px-2.5 py-0.5 rounded text-xs font-semibold bg-[#E8F4EE] text-[#0D4A38] border border-[#C6E2D2]">
                                    {{ $app->student->major->name }}
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                <div class="font-bold text-slate-800">{{ $app->post->title }}</div>
                                <div class="text-xs text-slate-500">{{ $app->post->company->company_name }}</div>
                            </td>
                            <td class="px-5 py-4 text-xs text-slate-600 whitespace-nowrap">
                                {{ $app->application_date->format('d M Y') }}
                            </td>
                            <td class="px-5 py-4 text-center">
                                <div class="inline-flex items-center gap-2 flex-wrap justify-center">
                                    @if ($app->portfolio_url)
                                        <a href="{{ $app->portfolio_url }}" target="_blank" rel="noopener noreferrer"
                                           class="px-2.5 py-1 text-[11px] rounded-md bg-[#E8F4EE] text-[#0D4A38] hover:bg-[#D5E6DC] font-bold border border-[#C6E2D2]">
                                            Portofolio &nearr;
                                        </a>
                                    @endif

                                    @if ($app->resume_file)
                                        <a href="{{ route('applications.resume', $app) }}" target="_blank"
                                           class="px-2.5 py-1 text-[11px] rounded-md bg-slate-100 text-slate-700 hover:bg-slate-200 font-bold border border-slate-300">
                                            CV (PDF)
                                        </a>
                                    @endif

                                    @if (! $app->portfolio_url && ! $app->resume_file)
                                        <span class="text-xs text-slate-400">-</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-5 py-4 text-center">
                                @if ($app->status === 'accepted')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                        Diterima
                                    </span>
                                @elseif ($app->status === 'rejected')
                                    <div class="inline-flex flex-col items-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-50 text-rose-800 border border-rose-200">
                                            Ditolak
                                        </span>
                                        @if ($app->rejection_reason)
                                            <span class="text-[10px] text-rose-600 mt-1 max-w-[160px] truncate" title="{{ $app->rejection_reason }}">
                                                "{{ $app->rejection_reason }}"
                                            </span>
                                        @endif
                                    </div>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200">
                                        Menunggu
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-slate-500 text-xs">
                                Tidak ada data pengajuan lamaran yang sesuai kriteria filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($applications->hasPages())
            <div class="px-5 py-3 border-t border-[#E0ECE6] bg-[#F4F8F6]">
                {{ $applications->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
