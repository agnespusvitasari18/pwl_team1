@php
    $user = auth()->user();
    $notifications = [];

    if ($user->isAdmin()) {
        $pendingCompaniesCount = \App\Models\User::where('role', \App\Models\User::ROLE_COMPANY)
            ->where('approval_status', \App\Models\User::STATUS_PENDING)
            ->count();
        $pendingAppsCount = \App\Models\Application::pending()->count();

        if ($pendingCompaniesCount > 0) {
            $notifications[] = [
                'text' => "{$pendingCompaniesCount} perusahaan menunggu verifikasi kemitraan.",
                'url' => route('admin.companies.index', ['status' => 'pending']),
            ];
        }
        if ($pendingAppsCount > 0) {
            $notifications[] = [
                'text' => "{$pendingAppsCount} pengajuan PKL siswa sedang menunggu proses seleksi.",
                'url' => route('admin.applications.index', ['status' => 'pending']),
            ];
        }
    } elseif ($user->isCompany() && $user->companyProfile) {
        $pendingApplicantsCount = $user->companyProfile->applications()
            ->where('applications.status', \App\Models\Application::STATUS_PENDING)
            ->count();

        if ($pendingApplicantsCount > 0) {
            $notifications[] = [
                'text' => "{$pendingApplicantsCount} pelamar siswa menunggu keputusan seleksi Anda.",
                'url' => route('company.posts.index'),
            ];
        }
    } elseif ($user->isStudent() && $user->studentProfile) {
        $acceptedApp = $user->studentProfile->applications()->accepted()->with('post.company')->first();
        $pendingCount = $user->studentProfile->applications()->pending()->count();

        if ($acceptedApp) {
            $notifications[] = [
                'text' => 'Selamat! Anda diterima PKL di '.($acceptedApp->post->company->company_name ?? 'perusahaan mitra').'.',
                'url' => route('student.applications.index'),
            ];
        } elseif ($pendingCount > 0) {
            $notifications[] = [
                'text' => "{$pendingCount} lamaran PKL Anda sedang menunggu respon perusahaan.",
                'url' => route('student.applications.index'),
            ];
        }
    }
@endphp

<header class="h-16 sm:h-20 bg-white border-b border-slate-200/80 flex items-center justify-between px-4 sm:px-6 lg:px-8 sticky top-0 z-30 gap-2">
    <div class="flex items-center gap-2.5 sm:gap-3 min-w-0">
        <!-- Mobile Sidebar Toggle Button -->
        <button type="button" onclick="toggleSidebar()" class="lg:hidden p-2 rounded-xl text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition shrink-0" aria-label="Buka menu">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>

        <div class="min-w-0">
            <h1 class="text-base sm:text-xl font-bold text-slate-900 tracking-tight truncate">@yield('page_title', 'Dashboard')</h1>
            <p class="text-xs text-slate-500 hidden sm:block truncate">@yield('page_description', 'Kelola informasi dan pantau aktivitas PKL')</p>
        </div>
    </div>

    <div class="flex items-center gap-2 sm:gap-4 shrink-0">
        <!-- View Public Site Button -->
        <a href="{{ route('home') }}" target="_blank"
           class="hidden md:inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 hover:text-[#0F4C3A] px-3.5 py-2 rounded-xl border border-slate-200 hover:bg-slate-50 transition">
            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
            </svg>
            <span>Lihat Website</span>
        </a>

        <!-- Notification Bell Dropdown -->
        <div class="relative">
            <button type="button" onclick="document.getElementById('notif-popover').classList.toggle('hidden')"
                    class="relative p-2 sm:p-2.5 rounded-xl text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition" aria-label="Notifikasi">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                @if (count($notifications) > 0)
                    <span class="absolute top-2 right-2 w-2 h-2 rounded-full bg-amber-500 ring-2 ring-white"></span>
                @endif
            </button>

            <div id="notif-popover" class="hidden absolute right-0 mt-2 w-72 sm:w-80 bg-white rounded-2xl border border-slate-200 shadow-lg p-3 z-50">
                <div class="flex items-center justify-between pb-2 mb-2 border-b border-slate-100">
                    <span class="text-xs font-bold text-slate-800">Ringkasan Aktivitas</span>
                    <button type="button" onclick="document.getElementById('notif-popover').classList.add('hidden')" class="text-[11px] text-slate-400 hover:text-slate-600">Tutup</button>
                </div>
                <div class="space-y-2">
                    @forelse ($notifications as $item)
                        <a href="{{ $item['url'] }}" class="block p-2.5 rounded-xl bg-teal-50/70 hover:bg-teal-100/70 text-xs text-slate-700 leading-relaxed transition">
                            {{ $item['text'] }}
                        </a>
                    @empty
                        <p class="py-4 text-center text-xs text-slate-400">Tidak ada pemberitahuan baru saat ini.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- User Profile Pill -->
        <div class="flex items-center gap-2.5 pl-2 border-l border-slate-200">
            <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-gradient-to-tr from-[#0F4C3A] to-[#157358] text-white font-bold text-xs flex items-center justify-center shadow-xs shrink-0">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div class="hidden sm:block text-left">
                <p class="text-xs font-bold text-slate-800 leading-tight max-w-[140px] truncate">{{ $user->name }}</p>
                <span class="text-[10px] text-[#0F4C3A] font-semibold tracking-wide uppercase">
                    {{ $user->isAdmin() ? 'Admin Sekolah' : ($user->isCompany() ? 'Mitra Industri' : 'Siswa') }}
                </span>
            </div>
        </div>
    </div>
</header>
