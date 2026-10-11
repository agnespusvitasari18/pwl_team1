<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Major;
use App\Models\StudentProfile;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

/** Admin mengelola data master siswa (akun users + student_profiles). */
class StudentController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->input('q', ''));
        $placement = $request->input('penempatan'); // 'sudah' | 'belum' | kosong = semua

        $students = StudentProfile::query()
            ->with(['user', 'major'])
            ->withCount([
                'applications',
                'applications as accepted_count' => fn ($q) => $q->accepted(),
            ])
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($q) use ($search) {
                    $q->where('nisn', 'like', "%{$search}%")
                        ->orWhereHas('user', fn ($u) => $u->whereRaw('LOWER(name) like ?', ['%'.mb_strtolower($search).'%']));
                });
            })
            ->when($placement === 'sudah', fn ($q) => $q->whereHas('applications', fn ($a) => $a->accepted()))
            ->when($placement === 'belum', fn ($q) => $q->whereDoesntHave('applications', fn ($a) => $a->accepted()))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.students.index', compact('students', 'search', 'placement'));
    }

    public function create(): View
    {
        return view('admin.students.create', ['majors' => Major::orderBy('name')->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', Password::min(8)],
            'nisn' => ['required', 'string', 'max:20', 'unique:student_profiles,nisn'],
            'school_name' => ['nullable', 'string', 'max:255'],
            'major_id' => ['required', 'exists:majors,id'],
            'address' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($data) {
            $user = new User([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'], // di-hash oleh cast 'hashed'
            ]);
            $user->role = User::ROLE_STUDENT;
            $user->approval_status = User::STATUS_APPROVED;
            $user->save();

            StudentProfile::create([
                'user_id' => $user->id,
                'nisn' => $data['nisn'],
                'school_name' => $data['school_name'] ?? null,
                'major_id' => $data['major_id'],
                'address' => $data['address'] ?? null,
            ]);
        });

        return redirect()->route('admin.students.index')->with('success', 'Akun siswa berhasil dibuat.');
    }

    public function edit(StudentProfile $student): View
    {
        $student->load('user');

        return view('admin.students.edit', [
            'student' => $student,
            'majors' => Major::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, StudentProfile $student): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($student->user_id)],
            'password' => ['nullable', Password::min(8)],
            'nisn' => ['required', 'string', 'max:20', Rule::unique('student_profiles', 'nisn')->ignore($student->id)],
            'school_name' => ['nullable', 'string', 'max:255'],
            'major_id' => ['required', 'exists:majors,id'],
            'address' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($data, $student) {
            $user = $student->user;
            $user->name = $data['name'];
            $user->email = $data['email'];
            if (! empty($data['password'])) {
                $user->password = $data['password'];
            }
            $user->save();

            $student->update([
                'nisn' => $data['nisn'],
                'school_name' => $data['school_name'] ?? null,
                'major_id' => $data['major_id'],
                'address' => $data['address'] ?? null,
            ]);
        });

        return redirect()->route('admin.students.index')->with('success', 'Data siswa diperbarui.');
    }

    public function destroy(StudentProfile $student): RedirectResponse
    {
        // Menghapus akun ikut menghapus profil dan pendaftaran siswa (cascade di migration).
        $student->user->delete();

        return redirect()->route('admin.students.index')->with('success', 'Akun siswa dihapus.');
    }
}
