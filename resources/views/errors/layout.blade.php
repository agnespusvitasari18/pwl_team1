<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('code') - @yield('title') | PKLoka</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @if (file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css'])
    @endif
    <style>
        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            background-color: #F4F9F6;
            color: #1E293B;
            margin: 0;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 sm:p-6 bg-[#F4F9F6] text-slate-800 antialiased">
    <div class="w-full max-w-lg bg-white rounded-3xl border border-[#D5E6DC] shadow-xl shadow-teal-900/5 p-6 sm:p-10 text-center space-y-6">
        <div class="flex flex-col items-center space-y-3">
            <div class="w-16 h-16 rounded-2xl bg-[#E8F4EE] border border-[#C6E2D2] text-[#0F4C3A] flex items-center justify-center font-extrabold text-2xl">
                @yield('code')
            </div>
            <span class="inline-flex items-center px-3 py-1 rounded-full bg-teal-50 border border-teal-200 text-[#0F4C3A] text-xs font-bold tracking-wide uppercase">
                PKLoka &bull; {{ config('pkloka.school_name', 'SMKN 4 Bandar Lampung') }}
            </span>
        </div>

        <div class="space-y-2">
            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">
                @yield('title')
            </h1>
            <p class="text-sm text-slate-600 leading-relaxed">
                @yield('message')
            </p>
        </div>

        <div class="pt-2 flex flex-col sm:flex-row items-center justify-center gap-3">
            @yield('actions')
        </div>
    </div>
</body>
</html>
