@extends('errors.layout')

@section('code', '419')
@section('title', 'Sesi Halaman Kedaluwarsa')
@section('message')
    Sesi formulir Anda telah berakhir karena terlalu lama tidak aktif. Silakan muat ulang halaman dan coba kirim kembali.
@endsection

@section('actions')
    <button type="button" onclick="window.location.reload()" class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-[#0F4C3A] hover:bg-[#0A3629] text-white text-xs font-bold transition shadow-xs">
        Muat Ulang Halaman
    </button>
    <a href="{{ url('/') }}" class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 text-xs font-bold transition">
        Ke Halaman Beranda
    </a>
@endsection
