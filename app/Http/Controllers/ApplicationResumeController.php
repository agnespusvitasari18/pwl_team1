<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Menyajikan berkas CV pelamar dari disk privat.
 * Hanya dapat diakses oleh siswa pemilik, perusahaan pemilik lowongan, dan admin sekolah.
 */
class ApplicationResumeController extends Controller
{
    public function __invoke(Request $request, Application $application): StreamedResponse
    {
        $user = $request->user();

        abort_unless($user && $user->isApproved(), 403, 'Anda tidak memiliki akses ke berkas ini.');

        $application->loadMissing(['student.user', 'post']);

        $isAuthorized = $user->isAdmin()
            || ($user->isStudent() && $user->studentProfile?->id === $application->student_id)
            || ($user->isCompany() && $user->companyProfile?->id === $application->post?->company_id);

        abort_unless($isAuthorized, 403, 'Anda tidak memiliki izin untuk mengakses berkas CV pelamar ini.');

        $path = $application->resume_file;

        if (! $path || str_contains($path, '..') || ! str_starts_with($path, 'resumes/')) {
            abort(404, 'Berkas CV tidak ditemukan.');
        }

        $disk = Storage::disk('local');

        if (! $disk->exists($path)) {
            abort(404, 'Berkas CV tidak tersedia di penyimpanan.');
        }

        $studentSlug = Str::slug($application->student?->user?->name ?? 'pelamar');
        $filename = "CV-{$studentSlug}-{$application->id}.pdf";

        if ($request->boolean('download')) {
            return $disk->download($path, $filename, [
                'Content-Type' => 'application/pdf',
            ]);
        }

        return $disk->response($path, $filename, [
            'Content-Type' => 'application/pdf',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }
}
