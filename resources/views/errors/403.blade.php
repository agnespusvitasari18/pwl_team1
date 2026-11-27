@extends('errors.layout')

@section('code', '403')
@section('title', 'Akses Ditolak')
@section('message')
    {{ $exception->getMessage() ?: 'Maaf, Anda tidak memiliki izin untuk membuka halaman atau sumber daya ini.' }}
@endsection

@section('actions')
    @auth
        <a href="{{ route('dashboard') }}" class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-[#0F4C3A] hover:bg-[#0A3629] text-white text-xs font-bold transition shadow-xs">
            Kembali ke Dashboard
        </a>
    @else
        <a href="{{ route('login') }}" class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-[#0F4C3A] hover:bg-[#0A3629] text-white text-xs font-bold transition shadow-xs">
            Masuk ke Akun
        </a>
    @endauth
    <a href="{{ url('/') }}" class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 text-xs font-bold transition">
        Ke Halaman Beranda
    </a>
@endsection
