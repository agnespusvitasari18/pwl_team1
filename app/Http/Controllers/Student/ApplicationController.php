<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\InternshipPost;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/** Siswa mengajukan pendaftaran dan melihat statusnya. */
class ApplicationController extends Controller
{
    public function index(Request $request): View
    {
        $student = $this->student($request);

        $applications = $student->applications()
            ->with(['post.company', 'post.major'])
            ->latest()
            ->get();

        return view('student.applications.index', compact('applications'));
    }

    public function store(Request $request, InternshipPost $post): RedirectResponse
    {
        $student = $this->student($request);

        $post->load('company.user');
        abort_unless($post->company->user->isApproved(), 404);

        if (! $post->isOpen()) {
            return back()->with('error', 'Batas pendaftaran lowongan ini sudah lewat.');
        }

        if (! $post->hasQuota()) {
            return back()->with('error', 'Kuota lowongan ini sudah penuh.');
        }

        if ($student->hasApplied($post)) {
            return back()->with('error', 'Anda sudah mendaftar pada lowongan ini.');
        }

        // Setelah diterima di satu tempat, status siswa terkunci dan tidak bisa mendaftar lagi.
        if ($student->applications()->accepted()->exists()) {
            return back()->with('error', 'Anda sudah diterima di tempat PKL, tidak dapat mendaftar lagi.');
        }

        try {
            Application::create([
                'student_id' => $student->id,
                'post_id' => $post->id,
                'application_date' => today(),
                'status' => Application::STATUS_PENDING,
            ]);
        } catch (UniqueConstraintViolationException) {
            return back()->with('error', 'Anda sudah mendaftar pada lowongan ini.');
        }

        return redirect()->route('student.applications.index')
            ->with('success', 'Pendaftaran berhasil diajukan.');
    }

    private function student(Request $request)
    {
        $student = $request->user()->studentProfile;
        abort_unless($student, 403, 'Profil siswa belum tersedia. Hubungi admin sekolah.');

        return $student;
    }
}
