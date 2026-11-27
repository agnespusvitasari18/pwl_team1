@extends('layouts.auth')

@section('title', 'Atur Ulang Kata Sandi')

@section('auth_content')
<div class="space-y-6">
    <div class="space-y-1.5">
        <span class="inline-flex items-center px-3 py-1 rounded-full bg-teal-50 border border-teal-200 text-[#0F4C3A] text-[11px] font-bold">
            Kata Sandi Baru
        </span>
        <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Atur Ulang Kata Sandi</h2>
        <p class="text-sm text-slate-500">Buat kata sandi baru minimal 8 karakter untuk mengamankan akun PKLoka Anda.</p>
    </div>

    @include('partials.flash')

    <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div class="space-y-1.5">
            <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Alamat Email</label>
            <input type="email" id="email" name="email" value="{{ old('email', $email) }}" required autocomplete="email"
                   placeholder="nama@email.com"
                   class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-[#0F4C3A] focus:ring-4 focus:ring-teal-500/10 transition">
        </div>

        <div class="space-y-1.5">
            <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Kata Sandi Baru</label>
            <input type="password" id="password" name="password" required minlength="8" autocomplete="new-password"
                   placeholder="Minimal 8 karakter"
                   class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-[#0F4C3A] focus:ring-4 focus:ring-teal-500/10 transition">
        </div>

        <div class="space-y-1.5">
            <label for="password_confirmation" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Konfirmasi Kata Sandi Baru</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required minlength="8" autocomplete="new-password"
                   placeholder="Ulangi kata sandi baru"
                   class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-[#0F4C3A] focus:ring-4 focus:ring-teal-500/10 transition">
        </div>

        <button type="submit"
                class="w-full py-3 px-4 bg-[#0F4C3A] hover:bg-[#0A3629] text-white text-sm font-bold rounded-xl shadow-md shadow-teal-900/20 hover:shadow-lg transition-all">
            Simpan Kata Sandi Baru
        </button>

        <div class="text-center pt-1">
            <a href="{{ route('login') }}" class="text-xs font-bold text-[#0F4C3A] hover:text-[#0A3629] hover:underline transition">
                &larr; Kembali ke halaman Masuk
            </a>
        </div>
    </form>
</div>
@endsection
