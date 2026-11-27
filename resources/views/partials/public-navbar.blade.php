<header class="bg-white/95 backdrop-blur-md border-b border-slate-100 sticky top-0 z-40 transition-all">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between gap-2">
        <!-- Logo -->
        <a href="{{ route('home') }}" class="flex items-center gap-2.5 group shrink-0">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-[#0F4C3A] to-[#157358] text-white flex items-center justify-center shadow-md shadow-teal-900/15 group-hover:scale-105 transition-transform">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 3L1 9L12 15L21 10.09V17H23V9M5 13.18V17.18L12 21L19 17.18V13.18L12 17L5 13.18Z"/>
                </svg>
            </div>
            <div>
                <span class="font-extrabold text-xl sm:text-2xl tracking-tight text-slate-900 block leading-tight">
                    PK<span class="text-[#0F4C3A]">Loka</span>
                </span>
            </div>
        </a>

        <!-- Center Nav Links (Desktop) -->
        <nav class="hidden md:flex items-center gap-7 text-sm font-medium text-slate-600">
            <a href="{{ route('home') }}" class="text-[#0F4C3A] font-semibold hover:text-[#0A3629] transition">Beranda</a>
            <a href="#lowongan" class="hover:text-[#0F4C3A] transition">Lowongan</a>
            <a href="#mitra" class="hover:text-[#0F4C3A] transition">Mitra</a>
            <a href="#tentang" class="hover:text-[#0F4C3A] transition">Tentang Kami</a>
            <a href="#faq" class="hover:text-[#0F4C3A] transition">FAQ</a>
        </nav>

        <!-- Right Action Buttons -->
        <div class="flex items-center gap-2 sm:gap-3">
            @auth
                <a href="{{ route('dashboard') }}"
                   class="px-3.5 sm:px-5 py-2 sm:py-2.5 bg-[#0F4C3A] hover:bg-[#0A3629] text-white text-xs sm:text-sm font-semibold rounded-full transition shadow-sm shadow-teal-900/20 inline-flex items-center gap-1.5">
                    <span>Dashboard</span>
                    <svg class="w-4 h-4 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>
            @else
                <a href="{{ route('login') }}"
                   class="px-3 sm:px-4 py-1.5 sm:py-2 text-xs sm:text-sm font-semibold text-[#0F4C3A] border border-[#0F4C3A] rounded-full hover:bg-teal-50 transition">
                    Masuk
                </a>
                <a href="{{ route('register.company') }}"
                   class="px-3.5 sm:px-5 py-1.5 sm:py-2.5 bg-[#0F4C3A] hover:bg-[#0A3629] text-white text-xs sm:text-sm font-semibold rounded-full transition shadow-sm shadow-teal-900/20 inline-flex items-center">
                    Daftar
                </a>
            @endauth

            <!-- Mobile Menu Button -->
            <button type="button" onclick="document.getElementById('public-mobile-nav').classList.toggle('hidden')"
                    class="md:hidden p-2 rounded-xl text-slate-600 hover:bg-slate-100 transition" aria-label="Buka menu navigasi">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile Navigation Panel -->
    <div id="public-mobile-nav" class="hidden md:hidden border-t border-slate-100 bg-white px-4 py-3 space-y-1.5 text-sm font-medium text-slate-700 shadow-sm">
        <a href="{{ route('home') }}" onclick="document.getElementById('public-mobile-nav').classList.add('hidden')" class="block px-3 py-2 rounded-lg hover:bg-teal-50 text-[#0F4C3A] font-semibold">Beranda</a>
        <a href="#lowongan" onclick="document.getElementById('public-mobile-nav').classList.add('hidden')" class="block px-3 py-2 rounded-lg hover:bg-teal-50">Lowongan</a>
        <a href="#mitra" onclick="document.getElementById('public-mobile-nav').classList.add('hidden')" class="block px-3 py-2 rounded-lg hover:bg-teal-50">Mitra</a>
        <a href="#tentang" onclick="document.getElementById('public-mobile-nav').classList.add('hidden')" class="block px-3 py-2 rounded-lg hover:bg-teal-50">Tentang Kami</a>
        <a href="#faq" onclick="document.getElementById('public-mobile-nav').classList.add('hidden')" class="block px-3 py-2 rounded-lg hover:bg-teal-50">FAQ</a>
    </div>
</header>
