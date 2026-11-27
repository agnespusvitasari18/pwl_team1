@extends('layouts.app')

@section('title', 'Verifikasi Perusahaan')
@section('page_title', 'Verifikasi Perusahaan Mitra')
@section('page_description', 'Tinjau pendaftaran perusahaan baru dan kelola status kemitraan')

@section('content')
<div class="space-y-6">

    <!-- Filter Tabs -->
    <div class="bg-white p-2 rounded-2xl border border-slate-200/80 shadow-xs flex flex-wrap gap-1.5">
        <a href="{{ route('admin.companies.index', ['status' => 'pending']) }}"
           class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $status === 'pending' ? 'bg-[#0F4C3A] text-white shadow-xs' : 'text-slate-600 hover:bg-teal-50 hover:text-[#0F4C3A]' }}">
            Menunggu Persetujuan ({{ $statusCounts['pending'] ?? 0 }})
        </a>
        <a href="{{ route('admin.companies.index', ['status' => 'approved']) }}"
           class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $status === 'approved' ? 'bg-[#0F4C3A] text-white shadow-xs' : 'text-slate-600 hover:bg-teal-50 hover:text-[#0F4C3A]' }}">
            Disetujui ({{ $statusCounts['approved'] ?? 0 }})
        </a>
        <a href="{{ route('admin.companies.index', ['status' => 'rejected']) }}"
           class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $status === 'rejected' ? 'bg-[#0F4C3A] text-white shadow-xs' : 'text-slate-600 hover:bg-teal-50 hover:text-[#0F4C3A]' }}">
            Ditolak ({{ $statusCounts['rejected'] ?? 0 }})
        </a>
        <a href="{{ route('admin.companies.index', ['status' => 'all']) }}"
           class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $status === 'all' ? 'bg-[#0F4C3A] text-white shadow-xs' : 'text-slate-600 hover:bg-teal-50 hover:text-[#0F4C3A]' }}">
            Semua Perusahaan ({{ $statusCounts['all'] ?? 0 }})
        </a>
    </div>

    <!-- Company Cards List -->
    <div class="space-y-4">
        @forelse ($companies as $companyUser)
            @php
                $profile = $companyUser->companyProfile;
            @endphp
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5 sm:p-6 hover:border-teal-300 transition-all">
                <div class="flex flex-col md:flex-row md:items-start justify-between gap-5">

                    <!-- Detail Perusahaan -->
                    <div class="space-y-3 flex-1 min-w-0">
                        <div class="flex items-center gap-3 flex-wrap">
                            <div class="w-11 h-11 rounded-xl bg-teal-50 text-[#0F4C3A] font-black flex items-center justify-center text-sm border border-teal-200/80 overflow-hidden shrink-0">
                                @if ($profile?->logo_path)
                                    <img src="{{ asset('storage/'.$profile->logo_path) }}" alt="Logo {{ $profile->company_name }}" class="w-full h-full object-contain p-1">
                                @else
                                    {{ strtoupper(substr($profile?->company_name ?? $companyUser->name, 0, 1)) }}
                                @endif
                            </div>
                            <div class="min-w-0">
                                <h3 class="text-base font-bold text-slate-900 leading-tight">
                                    {{ $profile?->company_name ?? $companyUser->name }}
                                </h3>
                                <p class="text-xs text-slate-500 break-all">PIC: {{ $companyUser->name }} &bull; {{ $companyUser->email }}</p>
                            </div>

                            @if ($companyUser->approval_status === 'approved')
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    Disetujui
                                </span>
                            @elseif ($companyUser->approval_status === 'rejected')
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                    Ditolak
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                    Menunggu Peninjauan
                                </span>
                            @endif
                        </div>

                        <p class="text-xs text-slate-600 leading-relaxed pt-1">
                            {{ $profile?->description ?? 'Belum ada deskripsi profil yang ditambahkan.' }}
                        </p>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 pt-2 text-xs text-slate-500 bg-slate-50/70 p-3.5 rounded-xl border border-slate-100">
                            <div>
                                <span class="font-bold text-slate-700">Alamat:</span> {{ $profile?->address ?? '-' }}
                            </div>
                            <div>
                                <span class="font-bold text-slate-700">Kontak PIC:</span> {{ $profile?->contact ?? '-' }}
                            </div>
                            <div>
                                <span class="font-bold text-slate-700">Terdaftar Pada:</span> {{ $companyUser->created_at->format('d M Y, H:i') }}
                            </div>
                            <div>
                                <span class="font-bold text-slate-700">Peran:</span> Perusahaan Mitra
                            </div>
                        </div>

                        @if ($companyUser->approval_status === 'rejected' && $profile?->rejection_reason)
                            <div class="p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-xs text-rose-800">
                                <span class="font-bold block mb-0.5">Alasan Penolakan (terlihat oleh perusahaan saat masuk):</span>
                                {{ $profile->rejection_reason }}
                            </div>
                        @endif
                    </div>

                    <!-- Action Buttons -->
                    <div class="shrink-0 flex flex-col sm:flex-row md:flex-col gap-2 pt-3 md:pt-0 border-t md:border-t-0 border-slate-100 md:min-w-[160px]">
                        @if ($companyUser->approval_status !== 'approved')
                            <form action="{{ route('admin.companies.approve', $companyUser) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                        class="w-full px-4 py-2 bg-[#0F4C3A] hover:bg-[#0A3629] text-white text-xs font-bold rounded-xl shadow-xs transition text-center">
                                    Setujui Mitra
                                </button>
                            </form>
                        @endif

                        @if ($companyUser->approval_status !== 'rejected')
                            <button type="button" onclick="toggleRejectBox({{ $companyUser->id }})"
                                    class="w-full px-4 py-2 bg-white hover:bg-rose-50 text-rose-700 border border-rose-200 text-xs font-bold rounded-xl transition text-center">
                                Tolak Mitra
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Hidden Rejection Form Box -->
                @if ($companyUser->approval_status !== 'rejected')
                    <div id="reject-box-{{ $companyUser->id }}" class="hidden mt-4 pt-4 border-t border-slate-100">
                        <form action="{{ route('admin.companies.reject', $companyUser) }}" method="POST" class="space-y-3">
                            @csrf
                            @method('PATCH')
                            <div>
                                <label for="rejection_reason_{{ $companyUser->id }}" class="block text-xs font-bold text-slate-700 mb-1">
                                    Alasan Penolakan <span class="text-rose-500">*</span>
                                    <span class="font-normal text-slate-500">(akan ditampilkan kepada perusahaan saat mencoba masuk)</span>
                                </label>
                                <textarea id="rejection_reason_{{ $companyUser->id }}" name="rejection_reason" rows="2" required minlength="5"
                                          placeholder="Tuliskan alasan penolakan secara jelas (minimal 5 karakter)..."
                                          class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:outline-none focus:border-rose-500"></textarea>
                            </div>
                            <div class="flex items-center gap-2">
                                <button type="submit" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-xl shadow-xs transition">
                                    Konfirmasi Tolak
                                </button>
                                <button type="button" onclick="toggleRejectBox({{ $companyUser->id }})" class="px-3 py-2 text-xs font-semibold text-slate-500 hover:text-slate-800">
                                    Batal
                                </button>
                            </div>
                        </form>
                    </div>
                @endif
            </div>
        @empty
            <div class="p-12 text-center bg-white rounded-2xl border border-dashed border-slate-200 text-slate-400 text-xs">
                Tidak ada data perusahaan mitra pada kategori ini.
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if (method_exists($companies, 'links'))
        <div class="pt-2">
            {{ $companies->links() }}
        </div>
    @endif

</div>

<script>
    function toggleRejectBox(id) {
        const box = document.getElementById('reject-box-' + id);
        if (box) {
            box.classList.toggle('hidden');
        }
    }
</script>
@endsection
