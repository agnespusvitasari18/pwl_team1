<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Major;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

/** Siswa melengkapi dan memperbarui profilnya. Akun dan profil awal dibuat oleh admin. */
class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        $student = $request->user()->studentProfile;
        abort_unless($student, 403, 'Profil siswa belum tersedia. Hubungi admin sekolah.');

        return view('student.profile.edit', [
            'student' => $student,
            'majors' => Major::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();
        $student = $user->studentProfile;
        abort_unless($student, 403, 'Profil siswa belum tersedia. Hubungi admin sekolah.');

        $passwordRules = ['nullable', Password::min(8)];
        if ($request->filled('password_confirmation')) {
            $passwordRules[] = 'confirmed';
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nisn' => ['required', 'string', 'max:20', Rule::unique('student_profiles', 'nisn')->ignore($student->id)],
            'school_name' => ['nullable', 'string', 'max:255'],
            'major_id' => ['required', 'exists:majors,id'],
            'address' => ['nullable', 'string', 'max:1000'],
            'phone' => ['nullable', 'string', 'max:30'],
            'password' => $passwordRules,
        ]);

        if ($student->isPlaced() && (int) $data['major_id'] !== (int) $student->major_id) {
            return back()
                ->withInput()
                ->withErrors(['major_id' => 'Jurusan tidak dapat diubah setelah Anda resmi diterima di tempat PKL.']);
        }

        $user->name = $data['name'];
        if (! empty($data['password'])) {
            $user->password = $data['password'];
        }
        $user->save();

        $student->update([
            'nisn' => $data['nisn'],
            'school_name' => ($data['school_name'] ?? null) ?: config('pkloka.school_name'),
            'major_id' => $data['major_id'],
            'address' => $data['address'] ?? null,
            'phone' => $data['phone'] ?? null,
        ]);

        return back()->with('success', 'Profil siswa berhasil diperbarui.');
    }
}
