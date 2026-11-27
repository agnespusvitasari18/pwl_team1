<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Masuk') - PKLoka</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-3 sm:p-6 lg:p-8 overflow-x-hidden">

    <div class="w-full max-w-5xl bg-white rounded-2xl sm:rounded-3xl shadow-xl shadow-teal-900/5 border border-slate-200/80 overflow-hidden grid grid-cols-1 md:grid-cols-12 md:min-h-[620px]">
        <!-- Left Banner -->
        <div class="md:col-span-5 @hasSection('auth_banner') bg-gradient-to-br from-[#07261D] via-[#0F4C3A] to-[#157358] @else bg-[#0F4C3A] @endif p-6 sm:p-8 text-white flex flex-col justify-between relative overflow-hidden"
             @unless (request()->routeIs('register.company'))
                 style="background-image: linear-gradient(180deg, rgba(7, 38, 29, .82), rgba(15, 76, 58, .62) 48%, rgba(7, 38, 29, .88)), url('{{ asset('images/login-building.jpg') }}'); background-size: cover; background-position: center;"
             @endunless>
            <!-- Background glow -->
            <div class="absolute -top-16 -right-16 w-48 h-48 bg-emerald-300/15 rounded-full blur-2xl pointer-events-none"></div>
            <div class="absolute -bottom-16 -left-16 w-48 h-48 bg-teal-300/15 rounded-full blur-2xl pointer-events-none"></div>

            <div class="relative z-10 space-y-3">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2.5">
                    <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-xs text-white flex items-center justify-center font-bold text-lg border border-white/20">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 3L1 9L12 15L21 10.09V17H23V9M5 13.18V17.18L12 21L19 17.18V13.18L12 17L5 13.18Z"/>
                        </svg>
                    </div>
                    <span class="font-extrabold text-2xl tracking-tight text-white">PKLoka</span>
                </a>
                @unless (request()->routeIs('register.company'))
                    <div class="pt-4 sm:pt-5 space-y-2">
                        <h1 class="max-w-sm text-2xl sm:text-3xl font-extrabold leading-tight tracking-tight text-white">
                            Temukan tempat PKL yang sesuai jurusanmu.
                        </h1>
                        <p class="max-w-sm text-xs sm:text-sm leading-relaxed text-emerald-100/90">
                            Satu portal resmi {{ config('pkloka.school_name') }} untuk siswa, mitra industri, dan pihak sekolah dalam mengelola Praktik Kerja Lapangan.
                        </p>
                    </div>
                @endunless
            </div>

            @hasSection('auth_banner')
                <div class="relative z-10 my-6 sm:my-8">
                    @yield('auth_banner')
                </div>
            @else
                <div class="relative z-10 my-4 hidden md:block md:min-h-[160px] flex-1"></div>
            @endif

            <div class="relative z-10 pt-4 text-[11px] text-emerald-100/80 flex flex-wrap items-center justify-between gap-2">
                <span>&copy; {{ date('Y') }} PKLoka</span>
                <span>{{ config('pkloka.school_name') }}</span>
            </div>
        </div>

        <!-- Right Content Panel -->
        <div class="md:col-span-7 p-6 sm:p-10 lg:p-12 flex flex-col justify-center">
            @yield('auth_content')
        </div>
    </div>

</body>
</html>
