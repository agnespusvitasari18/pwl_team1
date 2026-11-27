@extends('layouts.app')

@section('title', 'Data Lowongan PKL')
@section('page_title', 'Data Seluruh Lowongan PKL')
@section('page_description', 'Monitoring lowongan PKL yang dibuka oleh mitra perusahaan industri')

@section('content')
<div class="space-y-6">

    <!-- Filter Bar -->
    <div class="bg-white p-4 rounded-xl border border-[#D5E6DC] shadow-xs">
        <form method="GET" action="{{ route('admin.posts.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <div>
                <input type="text" name="q" value="{{ $search }}"
                       placeholder="Cari posisi atau nama mitra..."
                       class="w-full px-3 py-2 text-xs bg-white border border-[#C6E2D2] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0F4C3A] focus:border-[#0F4C3A] placeholder-slate-400">
            </div>

            <div>
                <select name="major_id" class="w-full px-3 py-2 text-xs bg-white border border-[#C6E2D2] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0F4C3A] focus:border-[#0F4C3A] text-slate-700">
                    <option value="">Semua Jurusan</option>
                    @foreach ($majors as $m)
                        <option value="{{ $m->id }}" {{ $majorId == $m->id ? 'selected' : '' }}>{{ $m->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <select name="status" class="w-full px-3 py-2 text-xs bg-white border border-[#C6E2D2] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0F4C3A] focus:border-[#0F4C3A] text-slate-700">
                    <option value="all" {{ $status === 'all' ? 'selected' : '' }}>Semua Status</option>
                    <option value="open" {{ $status === 'open' ? 'selected' : '' }}>Masih Dibuka</option>
                    <option value="closed" {{ $status === 'closed' ? 'selected' : '' }}>Sudah Ditutup / Berakhir</option>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="flex-1 py-2 px-4 bg-[#0F4C3A] hover:bg-[#0A3629] text-white text-xs font-bold rounded-lg transition shadow-xs">
                    Terapkan Filter
                </button>
                @if ($search || $majorId || $status !== 'all')
                    <a href="{{ route('admin.posts.index') }}" class="px-3 py-2 text-xs text-slate-500 hover:text-slate-800 font-semibold">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Tabel Lowongan -->
    <div class="bg-white rounded-xl border border-[#D5E6DC] shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-[#F4F8F6] border-b border-[#E0ECE6] text-xs font-bold text-[#0D4A38] uppercase tracking-wider">
                    <tr>
                        <th class="px-5 py-3.5">Posisi Lowongan</th>
                        <th class="px-5 py-3.5">Perusahaan</th>
                        <th class="px-5 py-3.5">Jurusan</th>
                        <th class="px-5 py-3.5 text-center">Kuota Terisi</th>
                        <th class="px-5 py-3.5">Batas Akhir</th>
                        <th class="px-5 py-3.5 text-center">Pelamar</th>
                        <th class="px-5 py-3.5 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($posts as $post)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="px-5 py-4">
                                <div class="font-bold text-slate-900">{{ $post->title }}</div>
                                <div class="text-[11px] text-slate-400">Dibuat {{ $post->created_at->format('d M Y') }}</div>
                            </td>
                            <td class="px-5 py-4">
                                <div class="font-semibold text-slate-800">{{ $post->company->company_name }}</div>
                                <div class="text-[11px] text-slate-500">{{ $post->company->address }}</div>
                            </td>
                            <td class="px-5 py-4">
                                <span class="inline-block px-2.5 py-0.5 rounded text-xs font-semibold bg-[#E8F4EE] text-[#0D4A38] border border-[#C6E2D2]">
                                    {{ $post->major->name }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-center">
                                <span class="font-bold text-slate-800">{{ $post->accepted_count }}</span>
                                <span class="text-slate-400">/</span>
                                <span class="text-slate-600">{{ $post->quota }}</span>
                            </td>
                            <td class="px-5 py-4 text-xs text-slate-700">
                                {{ $post->deadline->format('d M Y') }}
                            </td>
                            <td class="px-5 py-4 text-center">
                                <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-bold bg-[#E8F4EE] text-[#0D4A38] border border-[#C6E2D2]">
                                    {{ $post->applications_count }} Pelamar
                                </span>
                            </td>
                            <td class="px-5 py-4 text-center">
                                @if (! $post->isOpen())
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-bold bg-rose-50 text-rose-800 border border-rose-200">
                                        Ditutup
                                    </span>
                                @elseif (! $post->hasQuota())
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200">
                                        Kuota Penuh
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                        Aktif
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center text-slate-500 text-xs">
                                Tidak ada data lowongan yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($posts->hasPages())
            <div class="px-5 py-3 border-t border-[#E0ECE6] bg-[#F4F8F6]">
                {{ $posts->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
