@extends('layouts.auth')

@section('title', 'Lupa Kata Sandi')

@section('auth_content')
<div class="space-y-6">
    <div class="space-y-1.5">
        <span class="inline-flex items-center px-3 py-1 rounded-full bg-teal-50 border border-teal-200 text-[#0F4C3A] text-[11px] font-bold">
            Pemulihan Akun
        </span>
        <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Lupa Kata Sandi?</h2>
        <p class="text-sm text-slate-500 leading-relaxed">
            Masukkan alamat email atau NISN akun PKLoka Anda. Kami akan mengirimkan tautan untuk mengatur ulang kata sandi Anda.
        </p>
    </div>

    @include('partials.flash')

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf

        <div class="space-y-1.5">
            <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Email atau NISN</label>
            <div class="relative flex items-center">
                <span class="absolute left-3.5 text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </span>
                <input type="text" id="email" name="email" value="{{ old('email') }}" required autofocus
                       placeholder="nama@email.com atau NISN siswa"
                       class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-[#0F4C3A] focus:ring-4 focus:ring-teal-500/10 transition">
            </div>
        </div>

        <button type="submit"
                class="w-full py-3 px-4 bg-[#0F4C3A] hover:bg-[#0A3629] text-white text-sm font-bold rounded-xl shadow-md shadow-teal-900/20 hover:shadow-lg transition-all">
            Kirim Tautan Atur Ulang Kata Sandi
        </button>

        <div class="p-3.5 rounded-xl bg-teal-50/70 border border-teal-200/80 text-xs text-slate-600 leading-relaxed">
            <strong class="text-[#0F4C3A]">Catatan untuk Siswa:</strong> Jika Anda tidak memiliki akses ke alamat email yang terdaftar, hubungi Admin Sekolah {{ config('pkloka.school_name') }} untuk membantu mengatur ulang kata sandi akun Anda.
        </div>

        <div class="text-center pt-1">
            <a href="{{ route('login') }}" class="text-xs font-bold text-[#0F4C3A] hover:text-[#0A3629] hover:underline transition">
                &larr; Kembali ke halaman Masuk
            </a>
        </div>
    </form>
</div>
@endsection
