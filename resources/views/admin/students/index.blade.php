@extends('layouts.app')

@section('title', 'Data Siswa')
@section('page_title', 'Kelola Akun & Data Siswa')
@section('page_description', 'Manajemen akun siswa, impor CSV, reset kata sandi, dan ekspor rekap penempatan PKL')

@section('content')
<div class="space-y-6">

    <!-- Action Bar: Impor CSV, Ekspor Rekap, Tambah Siswa -->
    <div class="bg-white p-4 sm:p-5 rounded-xl border border-[#D5E6DC] shadow-xs space-y-4">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3">
            <!-- Search and Filter Form -->
            <form method="GET" action="{{ route('admin.students.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:flex lg:flex-wrap items-stretch sm:items-center gap-2 flex-1">
                <div class="relative flex-1 min-w-[180px]">
                    <input type="text" name="q" value="{{ $search }}"
                           placeholder="Cari nama, NISN, atau email..."
                           class="w-full pl-9 pr-3 py-2 text-xs bg-white border border-[#C6E2D2] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0F4C3A] focus:border-[#0F4C3A] placeholder-slate-400">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>

                <select name="major_id" class="py-2 px-3 text-xs bg-white border border-[#C6E2D2] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0F4C3A] text-slate-700">
                    <option value="">Semua Jurusan</option>
                    @foreach ($majors as $m)
                        <option value="{{ $m->id }}" {{ (string) $majorId === (string) $m->id ? 'selected' : '' }}>{{ $m->name }}</option>
                    @endforeach
                </select>

                <select name="penempatan" class="py-2 px-3 text-xs bg-white border border-[#C6E2D2] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0F4C3A] text-slate-700">
                    <option value="">Semua Penempatan</option>
                    <option value="sudah" {{ $placement === 'sudah' ? 'selected' : '' }}>Sudah Ditempatkan</option>
                    <option value="belum" {{ $placement === 'belum' ? 'selected' : '' }}>Belum Ditempatkan</option>
                </select>

                <div class="flex items-center gap-2">
                    <button type="submit" class="flex-1 sm:flex-none px-4 py-2 bg-[#E8F4EE] hover:bg-[#D5E6DC] border border-[#C6E2D2] text-[#0D4A38] text-xs font-bold rounded-lg transition">
                        Filter
                    </button>

                    @if ($search || $placement || $majorId)
                        <a href="{{ route('admin.students.index') }}" class="px-3 py-2 text-slate-500 hover:text-slate-800 text-xs font-semibold">
                            Reset
                        </a>
                    @endif
                </div>
            </form>

            <!-- Action Buttons -->
            <div class="flex flex-wrap items-center gap-2 shrink-0">
                <button type="button" onclick="document.getElementById('csv-import-panel').classList.toggle('hidden')"
                        class="inline-flex items-center justify-center gap-1.5 px-3.5 py-2 bg-white hover:bg-teal-50 text-[#0F4C3A] border border-[#C6E2D2] text-xs font-bold rounded-lg transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                    <span>Impor CSV</span>
                </button>

                <a href="{{ route('admin.students.export', array_filter(['q' => $search, 'penempatan' => $placement, 'major_id' => $majorId])) }}"
                   class="inline-flex items-center justify-center gap-1.5 px-3.5 py-2 bg-[#E8F4EE] hover:bg-[#D5E6DC] text-[#0D4A38] border border-[#C6E2D2] text-xs font-bold rounded-lg transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span>Ekspor Rekap</span>
                </a>

                <a href="{{ route('admin.students.create') }}"
                   class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-[#0F4C3A] hover:bg-[#0A3629] text-white text-xs font-bold rounded-lg transition shadow-xs">
                    <svg class="w-4 h-4 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Tambah Siswa</span>
                </a>
            </div>
        </div>

        <!-- Expandable CSV Import Panel -->
        <div id="csv-import-panel" class="{{ $errors->has('csv_file') ? '' : 'hidden' }} pt-4 border-t border-[#E0ECE6]">
            <div class="bg-[#F4F9F6] border border-[#C6E2D2] rounded-xl p-4 sm:p-5 space-y-3">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <div>
                        <h3 class="text-sm font-bold text-[#0F4C3A]">Impor Akun Siswa Massal (CSV)</h3>
                        <p class="text-xs text-slate-600">
                            Kolom wajib: <code class="bg-white px-1.5 py-0.5 rounded border border-teal-200 text-[11px]">name, email, nisn, major</code>.
                            Opsional: <code class="bg-white px-1.5 py-0.5 rounded border border-teal-200 text-[11px]">school_name, phone, address, password</code>.
                            Jika sandi kosong, otomatis menggunakan NISN (minimal 8 karakter).
                        </p>
                    </div>
                    <a href="{{ route('admin.students.import.template') }}"
                       class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white hover:bg-teal-50 text-[#0F4C3A] border border-[#C6E2D2] text-xs font-bold rounded-lg shrink-0 transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        <span>Unduh Template CSV</span>
                    </a>
                </div>

                <form action="{{ route('admin.students.import') }}" method="POST" enctype="multipart/form-data" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                    @csrf
                    <input type="file" name="csv_file" accept=".csv,text/csv" required
                           class="flex-1 text-xs text-slate-600 bg-white border border-[#C6E2D2] rounded-lg p-1.5 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-bold file:bg-[#E8F4EE] file:text-[#0F4C3A] hover:file:bg-[#D5E6DC]">
                    <button type="submit"
                            class="px-5 py-2.5 bg-[#0F4C3A] hover:bg-[#0A3629] text-white text-xs font-bold rounded-lg shadow-xs transition shrink-0">
                        Unggah &amp; Impor Siswa
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Tabel Data Siswa -->
    <div class="bg-white rounded-xl border border-[#D5E6DC] shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-[#F4F8F6] border-b border-[#E0ECE6] text-xs font-bold text-[#0D4A38] uppercase tracking-wider">
                    <tr>
                        <th class="px-5 py-3.5">NISN / Nama Siswa</th>
                        <th class="px-5 py-3.5">Jurusan</th>
                        <th class="px-5 py-3.5">Sekolah &amp; Kontak</th>
                        <th class="px-5 py-3.5 text-center">Status PKL</th>
                        <th class="px-5 py-3.5 text-center">Lamaran</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($students as $student)
                        @php
                            $acceptedApp = $student->applications->firstWhere('status', \App\Models\Application::STATUS_ACCEPTED);
                        @endphp
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="px-5 py-4">
                                <div class="font-bold text-slate-900">{{ $student->user->name }}</div>
                                <div class="text-xs text-slate-500 font-mono">NISN: {{ $student->nisn }}</div>
                                <div class="text-[11px] text-slate-400">{{ $student->user->email }}</div>
                            </td>
                            <td class="px-5 py-4">
                                <span class="inline-block px-2.5 py-0.5 rounded text-xs font-semibold bg-[#E8F4EE] text-[#0D4A38] border border-[#C6E2D2]">
                                    {{ $student->major->name }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-xs text-slate-700">
                                <div>{{ $student->school_name ?? config('pkloka.school_name') }}</div>
                                @if ($student->phone)
                                    <div class="text-[11px] text-slate-500">{{ $student->phone }}</div>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-center">
                                @if ($student->accepted_count > 0)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                        Sudah Ditempatkan
                                    </span>
                                    @if ($acceptedApp?->post?->company)
                                        <div class="text-[11px] text-slate-500 mt-1 max-w-[180px] mx-auto truncate" title="{{ $acceptedApp->post->company->company_name }}">
                                            {{ $acceptedApp->post->company->company_name }}
                                        </div>
                                    @endif
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200">
                                        Belum Ditempatkan
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-center text-xs font-bold text-slate-800">
                                {{ $student->applications_count }}
                            </td>
                            <td class="px-5 py-4 text-right whitespace-nowrap">
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="{{ route('admin.students.edit', $student) }}"
                                       class="px-2.5 py-1 text-xs font-semibold rounded-md text-[#0D4A38] bg-[#E8F4EE] border border-[#C6E2D2] hover:bg-[#D5E6DC] transition">
                                        Edit
                                    </a>
                                    <button type="button"
                                            onclick="openResetPasswordModal('{{ route('admin.students.reset-password', $student) }}', '{{ addslashes($student->user->name) }}', '{{ $student->nisn }}')"
                                            class="px-2.5 py-1 text-xs font-semibold rounded-md text-amber-800 bg-amber-50 border border-amber-200 hover:bg-amber-100 transition">
                                        Reset Sandi
                                    </button>
                                    <form action="{{ route('admin.students.destroy', $student) }}" method="POST"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun siswa ini? Semua data pendaftaran terkait juga akan ikut terhapus.')"
                                          class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="px-2.5 py-1 text-xs font-semibold rounded-md text-rose-700 bg-rose-50 border border-rose-200 hover:bg-rose-100 transition">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-slate-500 text-xs">
                                Tidak ada data siswa yang sesuai kriteria pencarian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($students->hasPages())
            <div class="px-5 py-3 border-t border-[#E0ECE6] bg-[#F4F8F6]">
                {{ $students->links() }}
            </div>
        @endif
    </div>

</div>

<!-- Modal Reset Kata Sandi Siswa -->
<div id="reset-password-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-xs">
    <div class="w-full max-w-md bg-white rounded-2xl border border-slate-200 shadow-xl p-6 space-y-4">
        <div class="flex items-start justify-between gap-3">
            <div>
                <h3 class="text-base font-extrabold text-slate-900">Atur Ulang Kata Sandi Siswa</h3>
                <p id="reset-modal-subtitle" class="text-xs text-slate-500 mt-0.5"></p>
            </div>
            <button type="button" onclick="closeResetPasswordModal()" class="text-slate-400 hover:text-slate-600" aria-label="Tutup modal">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form id="reset-password-form" method="POST" action="" class="space-y-4">
            @csrf
            @method('PATCH')
            <div>
                <label for="modal_new_password" class="block text-xs font-bold text-slate-700 mb-1.5">
                    Kata Sandi Baru <span class="font-normal text-slate-400">(Opsional — kosongkan untuk buat otomatis)</span>
                </label>
                <input type="text" id="modal_new_password" name="new_password" minlength="8"
                       placeholder="Minimal 8 karakter atau kosongkan"
                       class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0F4C3A]">
                <p class="text-[11px] text-slate-500 mt-1.5">
                    Setelah disimpan, kata sandi baru akan ditampilkan sekali agar dapat segera diserahkan ke siswa.
                </p>
            </div>

            <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
                <button type="button" onclick="closeResetPasswordModal()" class="px-4 py-2 text-xs font-semibold text-slate-600 hover:text-slate-800 rounded-lg border border-slate-200">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-[#0F4C3A] hover:bg-[#0A3629] text-white text-xs font-bold rounded-lg shadow-xs transition">
                    Simpan Kata Sandi Baru
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openResetPasswordModal(actionUrl, studentName, nisn) {
        const modal = document.getElementById('reset-password-modal');
        const form = document.getElementById('reset-password-form');
        const subtitle = document.getElementById('reset-modal-subtitle');
        const input = document.getElementById('modal_new_password');
        if (!modal || !form) return;

        form.action = actionUrl;
        subtitle.textContent = studentName + ' (NISN: ' + nisn + ')';
        if (input) input.value = '';
        modal.classList.remove('hidden');
    }

    function closeResetPasswordModal() {
        const modal = document.getElementById('reset-password-modal');
        if (modal) modal.classList.add('hidden');
    }
</script>
@endsection
