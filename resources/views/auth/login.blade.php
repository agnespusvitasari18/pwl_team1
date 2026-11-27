@extends('layouts.auth')

@section('title', 'Masuk ke Akun Anda')

@section('auth_content')
<div class="space-y-6">
    <div class="space-y-1.5">
        <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Masuk ke PKLoka</h2>
        <p class="text-sm text-slate-500">Gunakan email akun atau NISN (khusus siswa) untuk melanjutkan ke dashboard</p>
    </div>

    @include('partials.flash')

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Email atau NISN Input -->
        <div class="space-y-1.5">
            <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Email atau NISN</label>
            <div class="relative flex items-center">
                <span class="absolute left-3.5 text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </span>
                <input type="text" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                       placeholder="nama@email.com atau NISN siswa"
                       class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-[#0F4C3A] focus:ring-4 focus:ring-teal-500/10 transition">
            </div>
        </div>

        <!-- Password Input -->
        <div class="space-y-1.5">
            <div class="flex items-center justify-between gap-2">
                <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Kata Sandi</label>
                <a href="{{ route('password.request') }}" class="text-xs font-semibold text-[#0F4C3A] hover:text-[#0A3629] hover:underline transition">Lupa kata sandi?</a>
            </div>
            <div class="relative flex items-center">
                <span class="absolute left-3.5 text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </span>
                <input type="password" id="password" name="password" required autocomplete="current-password"
                       placeholder="••••••••"
                       class="w-full pl-10 pr-10 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-[#0F4C3A] focus:ring-4 focus:ring-teal-500/10 transition">
                <button type="button" onclick="togglePasswordVisibility('password')" aria-label="Tampilkan kata sandi" class="absolute right-3.5 text-slate-400 hover:text-slate-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Remember Me Checkbox -->
        <div class="flex items-center">
            <input type="checkbox" id="remember" name="remember" class="w-4 h-4 text-[#0F4C3A] border-slate-300 rounded focus:ring-[#0F4C3A]">
            <label for="remember" class="ml-2 text-xs font-medium text-slate-600">Ingat sesi saya</label>
        </div>

        <!-- Submit Button -->
        <button type="submit"
                class="w-full py-3 px-4 bg-[#0F4C3A] hover:bg-[#0A3629] text-white text-sm font-bold rounded-xl shadow-md shadow-teal-900/20 hover:shadow-lg transition-all">
            Masuk ke Akun
        </button>

        <div class="text-center pt-2 space-y-1">
            <p class="text-xs text-slate-500">
                Belum memiliki akun mitra?
                <a href="{{ route('register.company') }}" class="font-bold text-[#0F4C3A] hover:text-[#0A3629] hover:underline transition">
                    Daftarkan Perusahaan di sini
                </a>
            </p>
            <p class="text-[11px] text-slate-400">
                *Akun siswa didaftarkan secara resmi oleh pihak {{ config('pkloka.school_name') }}.
            </p>
        </div>
    </form>
</div>

<script>
    function togglePasswordVisibility(id) {
        const input = document.getElementById(id);
        if (input) {
            input.type = input.type === 'password' ? 'text' : 'password';
        }
    }
</script>
@endsection
