<footer class="relative overflow-hidden border-t border-teal-100 bg-gradient-to-r from-[#F4F9F6] via-white to-[#F4F9F6]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 min-h-[66px] py-4 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-700 text-center sm:text-left">
        <a href="{{ route('home') }}" class="flex items-center gap-2 text-lg font-extrabold tracking-tight text-[#0F4C3A]">
            <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3L1 9L12 15L21 10.09V17H23V9M5 13.18V17.18L12 21L19 17.18V13.18L12 17L5 13.18Z"/></svg>
            PKLoka
        </a>
        <span class="hidden sm:block h-7 border-l border-teal-200"></span>
        <p>&copy; {{ date('Y') }} PKLoka. Seluruh hak cipta dilindungi.</p>
        <div class="flex items-center gap-2 font-semibold text-[#0F4C3A]">
            <span class="inline-flex w-7 h-7 items-center justify-center rounded-full bg-[#0F4C3A] text-white font-bold text-[11px]">S</span>
            <span>{{ config('pkloka.school_name') }}</span>
        </div>
    </div>
</footer>
