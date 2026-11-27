@if (session('success') || session('status'))
    <div class="mb-5 p-4 rounded-xl bg-teal-50 border border-teal-200 text-teal-900 flex items-start gap-3 shadow-xs" role="alert">
        <div class="w-6 h-6 rounded-full bg-[#0F4C3A] text-white flex items-center justify-center shrink-0 mt-0.5">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        <div class="flex-1 text-sm font-medium">
            {{ session('success') ?? session('status') }}
        </div>
        <button type="button" onclick="this.parentElement.remove()" class="text-teal-500 hover:text-teal-700 transition" aria-label="Tutup notifikasi">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
@endif

@if (session('reset_credentials'))
    @php($cred = session('reset_credentials'))
    <div class="mb-5 p-4 rounded-xl bg-emerald-50 border-2 border-emerald-300 text-emerald-950 shadow-xs space-y-2" role="alert">
        <div class="flex items-center justify-between gap-2">
            <div class="flex items-center gap-2 font-bold text-sm text-[#0F4C3A]">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                </svg>
                <span>Kredensial Baru Siswa (Segera Catat / Serahkan ke Siswa)</span>
            </div>
            <button type="button" onclick="this.closest('[role=alert]').remove()" class="text-emerald-600 hover:text-emerald-800 transition" aria-label="Tutup">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-2 text-xs bg-white p-3 rounded-lg border border-emerald-200">
            <div><span class="text-slate-500 block">Nama Siswa:</span> <strong class="text-slate-900">{{ $cred['name'] }}</strong></div>
            <div><span class="text-slate-500 block">Email:</span> <strong class="font-mono text-slate-900">{{ $cred['email'] }}</strong></div>
            <div><span class="text-slate-500 block">NISN:</span> <strong class="font-mono text-slate-900">{{ $cred['nisn'] }}</strong></div>
            <div><span class="text-slate-500 block">Kata Sandi Baru:</span> <strong class="font-mono text-[#0F4C3A] bg-teal-50 px-1.5 py-0.5 rounded">{{ $cred['password'] }}</strong></div>
        </div>
    </div>
@endif

@if (session('rejection_reason'))
    <div class="mb-5 p-4 rounded-xl bg-rose-50 border-2 border-rose-200 text-rose-900 shadow-xs space-y-1.5" role="alert">
        <div class="flex items-center gap-2 font-bold text-sm text-rose-800">
            <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>Pendaftaran Perusahaan Ditolak oleh Admin Sekolah</span>
        </div>
        <p class="text-xs leading-relaxed text-rose-800">
            <strong class="font-bold">Alasan Penolakan:</strong> {{ session('rejection_reason') }}
        </p>
        <p class="text-[11px] text-rose-700/90">
            Silakan hubungi pihak {{ config('pkloka.school_name') }} apabila Anda ingin mengajukan peninjauan ulang.
        </p>
    </div>
@endif

@if (session('error'))
    <div class="mb-5 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 flex items-start gap-3 shadow-xs" role="alert">
        <div class="w-6 h-6 rounded-full bg-rose-500 text-white flex items-center justify-center shrink-0 mt-0.5">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </div>
        <div class="flex-1 text-sm font-medium">
            {{ session('error') }}
        </div>
        <button type="button" onclick="this.parentElement.remove()" class="text-rose-400 hover:text-rose-600 transition" aria-label="Tutup notifikasi">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
@endif

@if (session('import_errors') && count(session('import_errors')) > 0)
    <div class="mb-5 p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 shadow-xs" role="alert">
        <div class="flex items-center justify-between gap-2 font-bold text-xs sm:text-sm mb-2 text-amber-800">
            <span>Catatan Baris CSV yang Dilewati ({{ count(session('import_errors')) }} baris):</span>
            <button type="button" onclick="this.closest('[role=alert]').remove()" class="text-amber-500 hover:text-amber-700" aria-label="Tutup">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <ul class="list-disc list-inside text-xs space-y-1 text-amber-800/90 max-h-40 overflow-y-auto pl-1">
            @foreach (session('import_errors') as $importErr)
                <li>{{ $importErr }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if ($errors->any())
    <div class="mb-5 p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 shadow-xs" role="alert">
        <div class="flex items-center gap-2 font-semibold text-sm mb-1 text-amber-800">
            <svg class="w-4 h-4 text-amber-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <span>Terdapat beberapa kesalahan:</span>
        </div>
        <ul class="list-disc list-inside text-xs space-y-1 text-amber-800/90 pl-1">
            @foreach ($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif
