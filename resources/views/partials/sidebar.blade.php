@php
    $user = auth()->user();
    $roleLabel = $user->isAdmin() ? 'Admin Sekolah' : ($user->isCompany() ? 'Perusahaan' : 'Siswa');
@endphp

<!-- Mobile Sidebar Backdrop -->
<div id="sidebar-backdrop" onclick="toggleSidebar()" class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs z-40 hidden lg:hidden transition-opacity"></div>

<!-- Main Aside Sidebar -->
<aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-slate-200/80 flex flex-col transition-transform duration-200 -translate-x-full lg:translate-x-0 lg:static lg:inset-0 shadow-xs shrink-0">
    <!-- Brand Header -->
    <div class="h-20 flex items-center justify-between px-5 border-b border-slate-100 gap-3">
        <div class="flex items-center gap-3 min-w-0">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-[#0F4C3A] to-[#157358] text-white flex items-center justify-center shadow-md shadow-teal-900/15 shrink-0">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 3L1 9L12 15L21 10.09V17H23V9M5 13.18V17.18L12 21L19 17.18V13.18L12 17L5 13.18Z"/>
                </svg>
            </div>
            <div class="min-w-0">
                <a href="{{ route('dashboard') }}" class="font-extrabold text-xl tracking-tight text-slate-900 block leading-tight">
                    PK<span class="text-[#0F4C3A]">Loka</span>
                </a>
                <span class="text-[10px] text-[#0F4C3A] font-semibold tracking-wider uppercase block truncate">
                    {{ $user->isAdmin() ? 'Portal Sekolah' : ($user->isCompany() ? 'Portal Mitra' : 'Portal Siswa') }}
                </span>
            </div>
        </div>
        <button type="button" onclick="toggleSidebar()" class="lg:hidden p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100" aria-label="Tutup sidebar">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    <!-- Navigation List -->
    <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto text-sm font-medium">

        {{-- ================= MENU ADMIN ================= --}}
        @if ($user->isAdmin())
            <div class="px-3 pb-2 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Navigasi Utama</div>

            <!-- Dashboard -->
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-[#0F4C3A] text-white shadow-sm shadow-teal-900/25 font-semibold' : 'text-slate-600 hover:bg-teal-50 hover:text-[#0F4C3A]' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span>Dashboard</span>
            </a>

            <!-- Kelola Perusahaan (Approval) -->
            <a href="{{ route('admin.companies.index') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.companies.*') ? 'bg-[#0F4C3A] text-white shadow-sm shadow-teal-900/25 font-semibold' : 'text-slate-600 hover:bg-teal-50 hover:text-[#0F4C3A]' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <span>Verifikasi Perusahaan</span>
            </a>

            <!-- Data Siswa -->
            <a href="{{ route('admin.students.index') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.students.*') ? 'bg-[#0F4C3A] text-white shadow-sm shadow-teal-900/25 font-semibold' : 'text-slate-600 hover:bg-teal-50 hover:text-[#0F4C3A]' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <span>Data Siswa</span>
            </a>

            <!-- Lowongan PKL -->
            <a href="{{ route('admin.posts.index') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.posts.*') ? 'bg-[#0F4C3A] text-white shadow-sm shadow-teal-900/25 font-semibold' : 'text-slate-600 hover:bg-teal-50 hover:text-[#0F4C3A]' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <span>Lowongan PKL</span>
            </a>

            <!-- Pengajuan Siswa -->
            <a href="{{ route('admin.applications.index') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.applications.*') ? 'bg-[#0F4C3A] text-white shadow-sm shadow-teal-900/25 font-semibold' : 'text-slate-600 hover:bg-teal-50 hover:text-[#0F4C3A]' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span>Pengajuan Siswa</span>
            </a>

        {{-- ================= MENU PERUSAHAAN ================= --}}
        @elseif ($user->isCompany())
            <div class="px-3 pb-2 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Navigasi Mitra</div>

            <!-- Dashboard -->
            <a href="{{ route('company.dashboard') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('company.dashboard') ? 'bg-[#0F4C3A] text-white shadow-sm shadow-teal-900/25 font-semibold' : 'text-slate-600 hover:bg-teal-50 hover:text-[#0F4C3A]' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span>Dashboard</span>
            </a>

            <!-- Lowongan Saya -->
            <a href="{{ route('company.posts.index') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('company.posts.*') ? 'bg-[#0F4C3A] text-white shadow-sm shadow-teal-900/25 font-semibold' : 'text-slate-600 hover:bg-teal-50 hover:text-[#0F4C3A]' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <span>Lowongan PKL</span>
            </a>

            <!-- Profil Perusahaan -->
            <a href="{{ route('company.profile.edit') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('company.profile.*') ? 'bg-[#0F4C3A] text-white shadow-sm shadow-teal-900/25 font-semibold' : 'text-slate-600 hover:bg-teal-50 hover:text-[#0F4C3A]' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <span>Profil Perusahaan</span>
            </a>

        {{-- ================= MENU SISWA ================= --}}
        @elseif ($user->isStudent())
            <div class="px-3 pb-2 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Navigasi Siswa</div>

            <!-- Dashboard -->
            <a href="{{ route('student.dashboard') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('student.dashboard') ? 'bg-[#0F4C3A] text-white shadow-sm shadow-teal-900/25 font-semibold' : 'text-slate-600 hover:bg-teal-50 hover:text-[#0F4C3A]' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span>Dashboard</span>
            </a>

            <!-- Cari Lowongan -->
            <a href="{{ route('student.posts.index') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('student.posts.*') ? 'bg-[#0F4C3A] text-white shadow-sm shadow-teal-900/25 font-semibold' : 'text-slate-600 hover:bg-teal-50 hover:text-[#0F4C3A]' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <span>Lowongan PKL</span>
            </a>

            <!-- Lamaran Saya -->
            <a href="{{ route('student.applications.index') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('student.applications.*') ? 'bg-[#0F4C3A] text-white shadow-sm shadow-teal-900/25 font-semibold' : 'text-slate-600 hover:bg-teal-50 hover:text-[#0F4C3A]' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span>Lamaran Saya</span>
            </a>

            <!-- Profil Siswa -->
            <a href="{{ route('student.profile.edit') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('student.profile.*') ? 'bg-[#0F4C3A] text-white shadow-sm shadow-teal-900/25 font-semibold' : 'text-slate-600 hover:bg-teal-50 hover:text-[#0F4C3A]' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <span>Profil Siswa</span>
            </a>
        @endif

        <div class="pt-4 mt-4 border-t border-slate-100">
            <p class="px-3 text-[11px] font-semibold text-slate-400 truncate">{{ config('pkloka.school_name') }}</p>
        </div>
    </nav>

    <!-- Bottom User Info & Logout -->
    <div class="p-4 border-t border-slate-100 bg-slate-50/70">
        <div class="flex items-center justify-between gap-2">
            <div class="flex items-center gap-3 min-w-0">
                <div class="w-9 h-9 rounded-full bg-teal-100 text-[#0F4C3A] font-bold flex items-center justify-center text-xs shrink-0">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-bold text-slate-800 truncate">{{ $user->name }}</p>
                    <span class="text-[10px] text-slate-500 font-medium block truncate">{{ $roleLabel }}</span>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST" class="shrink-0">
                @csrf
                <button type="submit" title="Keluar" aria-label="Keluar dari akun"
                        class="p-2 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</aside>
