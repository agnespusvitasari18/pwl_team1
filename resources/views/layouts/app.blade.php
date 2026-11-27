<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') - PKLoka</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50/60 text-slate-800 antialiased min-h-screen flex overflow-x-hidden">

    <!-- Modular Sidebar Component -->
    @include('partials.sidebar')

    <!-- Main Container -->
    <div class="flex-1 flex flex-col min-w-0 min-h-screen">

        <!-- Modular Topbar Component -->
        @include('partials.topbar')

        <!-- Main Body Area -->
        <main class="flex-1 p-4 sm:p-6 lg:p-8 min-w-0">
            <div class="max-w-7xl mx-auto">
                <!-- Flash Notification Component -->
                @include('partials.flash')

                <!-- Page Injected Content -->
                @yield('content')
            </div>
        </main>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');
            if (!sidebar || !backdrop) return;
            const isHidden = sidebar.classList.contains('-translate-x-full');

            if (isHidden) {
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('hidden');
            }
        }
    </script>
    @stack('scripts')
</body>
</html>
