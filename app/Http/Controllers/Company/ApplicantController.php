<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\InternshipPost;
use App\Models\StudentProfile;
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

        $status = $request->input('status', 'all');
        if (! in_array($status, ['all', 'pending', 'accepted', 'rejected'], true)) {
            $status = 'all';
        }

        $post->load('major');

        $allCounts = $post->applications()
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $statusCounts = [
            'all' => (int) $allCounts->sum(),
            'pending' => (int) ($allCounts[Application::STATUS_PENDING] ?? 0),
            'accepted' => (int) ($allCounts[Application::STATUS_ACCEPTED] ?? 0),
            'rejected' => (int) ($allCounts[Application::STATUS_REJECTED] ?? 0),
        ];

        $applications = $post->applications()
            ->with(['student.user', 'student.major'])
            ->when($status !== 'all', fn ($q) => $q->where('status', $status))
            ->latest()
            ->get();

        return view('company.applicants.index', compact('post', 'applications', 'status', 'statusCounts'));
    }

    public function accept(Request $request, Application $application): RedirectResponse
    {
        $this->authorizeApplication($request, $application);

        [$type, $message] = DB::transaction(function () use ($application) {
            // Kunci baris secara deterministik (Student -> Post -> Application)
            // agar dua transaksi bersamaan tidak bisa melewati kuota atau menerima siswa yang sama di dua perusahaan.
            StudentProfile::whereKey($application->student_id)->lockForUpdate()->firstOrFail();
            $post = InternshipPost::whereKey($application->post_id)->lockForUpdate()->firstOrFail();
            $locked = Application::whereKey($application->id)->lockForUpdate()->firstOrFail();

            if ($locked->status !== Application::STATUS_PENDING) {
                return ['error', 'Pendaftaran ini sudah diproses dan tidak dapat diubah.'];
            }

            if (! $post->hasQuota()) {
                return ['error', 'Kuota lowongan sudah penuh.'];
            }

            if (Application::accepted()->where('student_id', $locked->student_id)->exists()) {
                return ['error', 'Siswa ini sudah diterima di tempat PKL lain.'];
            }

            $locked->update([
                'status' => Application::STATUS_ACCEPTED,
                'rejection_reason' => null,
            ]);

            // Lamaran pending milik siswa yang sama di lowongan lain otomatis ditolak.
            Application::pending()
                ->where('student_id', $locked->student_id)
                ->where('id', '!=', $locked->id)
                ->update([
                    'status' => Application::STATUS_REJECTED,
                    'rejection_reason' => 'Otomatis dibatalkan karena siswa telah diterima di tempat PKL lain.',
                ]);

            return ['success', 'Pendaftaran diterima. Lamaran pending siswa di tempat lain otomatis dibatalkan.'];
        });

        return back()->with($type, $message);
    }

    public function reject(Request $request, Application $application): RedirectResponse
    {
        $this->authorizeApplication($request, $application);

        $validated = $request->validate([
            'rejection_reason' => ['nullable', 'string', 'max:1000'],
        ]);

        [$type, $message] = DB::transaction(function () use ($application, $validated) {
            $locked = Application::whereKey($application->id)->lockForUpdate()->firstOrFail();

            if ($locked->status !== Application::STATUS_PENDING) {
                return ['error', 'Pendaftaran ini sudah diproses dan tidak dapat diubah.'];
            }

            $reason = isset($validated['rejection_reason']) ? trim($validated['rejection_reason']) : null;

            $locked->update([
                'status' => Application::STATUS_REJECTED,
                'rejection_reason' => $reason !== '' ? $reason : null,
            ]);

            return ['success', 'Pendaftaran ditolak.'];
        });

        return back()->with($type, $message);
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
