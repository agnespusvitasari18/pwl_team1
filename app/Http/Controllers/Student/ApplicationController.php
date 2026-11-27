<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\InternshipPost;
use App\Models\StudentProfile;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

/** Siswa mengajukan pendaftaran dan melihat statusnya. */
class ApplicationController extends Controller
{
    public function index(Request $request): View
    {
        $student = $this->student($request);

        $applications = $student->applications()
            ->with(['post.company', 'post.major'])
            ->latest('application_date')
            ->latest('id')
            ->get();

        $isAccepted = $student->applications()->accepted()->exists();

        return view('student.applications.index', compact('applications', 'isAccepted'));
    }

    public function store(Request $request, InternshipPost $post): RedirectResponse
    {
        $student = $this->student($request);

        $post->load('company.user');
        abort_unless($post->company->user->isApproved(), 404);

        // Validasi awal sebelum penyimpanan berkas agar tidak menyisakan file yatim.
        if ($post->major_id !== $student->major_id) {
            return back()->with('error', 'Lowongan ini tidak sesuai dengan jurusan Anda.');
        }

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
            return back()->with('error', 'Anda sudah diterima di salah satu tempat PKL, tidak dapat mendaftar lagi.');
        }

        $validated = $request->validate([
            'notes' => ['nullable', 'string', 'max:1000'],
            'portfolio_url' => ['nullable', 'url', 'max:255'],
            'resume_file' => ['nullable', 'file', 'mimes:pdf', 'max:2048'],
        ], [
            'portfolio_url.url' => 'Format URL portofolio tidak valid (harus diawali http:// atau https://).',
            'resume_file.mimes' => 'Berkas CV/Resume harus berupa file PDF.',
            'resume_file.max' => 'Ukuran berkas CV maksimal 2MB.',
        ]);

        // Simpan CV pelamar ke disk privat ('local' -> storage/app/private), BUKAN disk publik.
        $resumePath = null;
        if ($request->hasFile('resume_file')) {
            $resumePath = $request->file('resume_file')->store('resumes', 'local');
        }

        try {
            $error = DB::transaction(function () use ($student, $post, $validated, $resumePath): ?string {
                $lockedStudent = StudentProfile::whereKey($student->id)->lockForUpdate()->firstOrFail();
                $lockedPost = InternshipPost::whereKey($post->id)->lockForUpdate()->firstOrFail();

                if ($lockedPost->major_id !== $lockedStudent->major_id) {
                    return 'Lowongan ini tidak sesuai dengan jurusan Anda.';
                }

                if (! $lockedPost->isOpen()) {
                    return 'Batas pendaftaran lowongan ini sudah lewat.';
                }

                if (! $lockedPost->hasQuota()) {
                    return 'Kuota lowongan ini sudah penuh.';
                }

                if ($lockedStudent->applications()->accepted()->exists()) {
                    return 'Anda sudah diterima di salah satu tempat PKL, tidak dapat mendaftar lagi.';
                }

                if ($lockedStudent->hasApplied($lockedPost)) {
                    return 'Anda sudah mendaftar pada lowongan ini.';
                }

                Application::create([
                    'student_id' => $lockedStudent->id,
                    'post_id' => $lockedPost->id,
                    'application_date' => today(),
                    'status' => Application::STATUS_PENDING,
                    'resume_file' => $resumePath,
                    'portfolio_url' => $validated['portfolio_url'] ?? null,
                    'notes' => $validated['notes'] ?? null,
                ]);

                return null;
            });

            if ($error !== null) {
                if ($resumePath) {
                    Storage::disk('local')->delete($resumePath);
                }

                return back()->with('error', $error);
            }
        } catch (UniqueConstraintViolationException) {
            if ($resumePath) {
                Storage::disk('local')->delete($resumePath);
            }

            return back()->with('error', 'Anda sudah mendaftar pada lowongan ini.');
        } catch (\Throwable $e) {
            if ($resumePath) {
                Storage::disk('local')->delete($resumePath);
            }

            throw $e;
        }

        return redirect()->route('student.applications.index')
            ->with('success', 'Pendaftaran PKL berhasil diajukan. Silakan tunggu respon dari perusahaan.');
    }

    private function student(Request $request): StudentProfile
    {
        $student = $request->user()->studentProfile;
        abort_unless($student, 403, 'Profil siswa belum tersedia. Hubungi admin sekolah.');

        return $student;
    }
}
