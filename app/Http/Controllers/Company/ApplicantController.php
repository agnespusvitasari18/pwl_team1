<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\InternshipPost;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

/** Perusahaan melihat pelamar dan memproses pendaftaran (terima atau tolak). */
class ApplicantController extends Controller
{
    public function index(Request $request, InternshipPost $post): View
    {
        abort_unless($post->company_id === $this->companyId($request), 403);

        $applications = $post->applications()
            ->with(['student.user', 'student.major'])
            ->latest()
            ->get();

        return view('company.applicants.index', compact('post', 'applications'));
    }

    public function accept(Request $request, Application $application): RedirectResponse
    {
        $this->authorizeApplication($request, $application);

        [$type, $message] = DB::transaction(function () use ($application) {
            // Kunci baris agar dua permintaan bersamaan tidak melewati kuota.
            $locked = Application::whereKey($application->id)->lockForUpdate()->firstOrFail();
            $post = InternshipPost::whereKey($locked->post_id)->lockForUpdate()->firstOrFail();

            if ($locked->status !== Application::STATUS_PENDING) {
                return ['error', 'Pendaftaran ini sudah diproses dan tidak dapat diubah.'];
            }

            if (! $post->hasQuota()) {
                return ['error', 'Kuota lowongan sudah penuh.'];
            }

            if (Application::accepted()->where('student_id', $locked->student_id)->exists()) {
                return ['error', 'Siswa ini sudah diterima di tempat PKL lain.'];
            }

            $locked->update(['status' => Application::STATUS_ACCEPTED]);

            // Lamaran siswa yang sama di tempat lain otomatis gugur.
            Application::pending()
                ->where('student_id', $locked->student_id)
                ->where('id', '!=', $locked->id)
                ->update(['status' => Application::STATUS_REJECTED]);

            return ['success', 'Pendaftaran diterima. Lamaran siswa di tempat lain otomatis dibatalkan.'];
        });

        return back()->with($type, $message);
    }

    public function reject(Request $request, Application $application): RedirectResponse
    {
        $this->authorizeApplication($request, $application);

        if ($application->status !== Application::STATUS_PENDING) {
            return back()->with('error', 'Pendaftaran ini sudah diproses dan tidak dapat diubah.');
        }

        $application->update(['status' => Application::STATUS_REJECTED]);

        return back()->with('success', 'Pendaftaran ditolak.');
    }

    private function companyId(Request $request): ?int
    {
        return $request->user()->companyProfile?->id;
    }

    private function authorizeApplication(Request $request, Application $application): void
    {
        $application->loadMissing('post');

        abort_unless(
            $application->post->company_id === $this->companyId($request),
            403
        );
    }
}
