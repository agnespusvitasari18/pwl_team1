@extends('layouts.app')

@section('title', 'Lowongan PKL Saya')
@section('page_title', 'Kelola Lowongan PKL')
@section('page_description', 'Daftar lowongan magang yang Anda buka untuk siswa SMK')

@section('content')
<div class="space-y-6">

    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <p class="text-xs text-slate-500">Maksimal pendaftaran otomatis disesuaikan dengan batas tanggal dan sisa kuota.</p>
        <a href="{{ route('company.posts.create') }}"
           class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5 bg-[#0F4C3A] hover:bg-[#0A3629] text-white text-xs font-bold rounded-xl transition shadow-xs shrink-0">
            <svg class="w-4 h-4 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <span>Tambah Lowongan Baru</span>
        </a>
    </div>

    <!-- Tabel Lowongan -->
    <div class="bg-white rounded-xl border border-[#D5E6DC] shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-[#F4F8F6] border-b border-[#E0ECE6] text-xs font-bold text-[#0D4A38] uppercase tracking-wider">
                    <tr>
                        <th class="px-5 py-3.5">Posisi Lowongan</th>
                        <th class="px-5 py-3.5">Jurusan</th>
                        <th class="px-5 py-3.5 text-center">Kuota & Sisa</th>
                        <th class="px-5 py-3.5">Batas Akhir</th>
                        <th class="px-5 py-3.5 text-center">Pelamar Masuk</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($posts as $post)
                        @php
                            $accepted = $post->acceptedCount();
                            $remaining = $post->remainingQuota();
                        @endphp
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="px-5 py-4">
                                <div class="font-bold text-slate-900">{{ $post->title }}</div>
                                <div class="text-[11px] text-slate-400">Dibuat {{ $post->created_at->format('d M Y') }}</div>
                            </td>
                            <td class="px-5 py-4">
                                <span class="inline-block px-2.5 py-0.5 rounded text-xs font-semibold bg-[#E8F4EE] text-[#0D4A38] border border-[#C6E2D2]">
                                    {{ $post->major->name }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-center">
                                <span class="font-bold text-slate-800">{{ $post->quota }} Siswa</span>
                                <div class="text-[11px] text-slate-500">
                                    Diterima: <span class="text-emerald-700 font-bold">{{ $accepted }}</span> &bull; Sisa: {{ $remaining }}
                                </div>
                            </td>
                            <td class="px-5 py-4 text-xs text-slate-700">
                                {{ $post->deadline->format('d M Y') }}
                                @if (! $post->isOpen())
                                    <span class="block text-[10px] text-rose-600 font-bold">(Lewat Batas)</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-center">
                                <a href="{{ route('company.posts.applicants.index', $post) }}"
                                   class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-bold bg-[#E8F4EE] text-[#0D4A38] border border-[#C6E2D2] hover:bg-[#D5E6DC] transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    <span>{{ $post->applications_count }} Pelamar</span>
                                </a>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('company.posts.edit', $post) }}"
                                       class="px-2.5 py-1 text-xs font-bold rounded-md text-[#0D4A38] bg-[#E8F4EE] border border-[#C6E2D2] hover:bg-[#D5E6DC] transition">
                                        Edit
                                    </a>
                                    @if ($accepted === 0)
                                        <form action="{{ route('company.posts.destroy', $post) }}" method="POST"
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus lowongan ini?')"
                                              class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="px-2.5 py-1 text-xs font-bold rounded-md text-rose-700 bg-rose-50 border border-rose-200 hover:bg-rose-100 transition">
                                                Hapus
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-[11px] text-slate-400 italic" title="Lowongan dengan siswa yang diterima tidak dapat dihapus">
                                            Terkunci
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-slate-500 text-xs">
                                Perusahaan Anda belum memiliki lowongan PKL. Klik tombol di atas untuk membuat lowongan pertama.
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
