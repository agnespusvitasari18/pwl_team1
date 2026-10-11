<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Major;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
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

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nisn' => ['required', 'string', 'max:20', Rule::unique('student_profiles', 'nisn')->ignore($student->id)],
            'school_name' => ['nullable', 'string', 'max:255'],
            'major_id' => ['required', 'exists:majors,id'],
            'address' => ['nullable', 'string'],
        ]);

        $user->name = $data['name'];
        $user->save();

        $student->update([
            'nisn' => $data['nisn'],
            'school_name' => $data['school_name'] ?? null,
            'major_id' => $data['major_id'],
            'address' => $data['address'] ?? null,
        ]);

        return back()->with('success', 'Profil diperbarui.');
    }
}
