<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\InternshipPost;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $student = $request->user()->studentProfile;
        abort_unless($student, 403, 'Profil siswa belum tersedia. Hubungi admin sekolah.');

        $student->load(['user', 'major']);

        // Cek status penerimaan
        $acceptedApplication = $student->applications()
            ->where('status', Application::STATUS_ACCEPTED)
            ->with(['post.company', 'post.major'])
            ->first();

        $applicationsCount = $student->applications()->count();
        $pendingCount = $student->applications()->where('status', Application::STATUS_PENDING)->count();
        $rejectedCount = $student->applications()->where('status', Application::STATUS_REJECTED)->count();

        // 5 Lamaran terbaru siswa
        $recentApplications = $student->applications()
            ->with(['post.company', 'post.major'])
            ->latest()
            ->take(5)
            ->get();

        $appliedPostIds = $student->applications()->pluck('post_id')->all();

        $matchingQuery = InternshipPost::query()
            ->open()
            ->whereHas('company.user', fn ($q) => $q->where('approval_status', User::STATUS_APPROVED))
            ->where('major_id', $student->major_id)
            ->whereNotIn('id', $appliedPostIds)
            ->with('company')
            ->withCount(['applications as accepted_count' => fn ($q) => $q->accepted()]);

        $availablePostsCount = (clone $matchingQuery)->count();

        // Rekomendasi lowongan sesuai jurusan yang belum dilamar
        $recommendedPosts = $matchingQuery
            ->latest()
            ->take(4)
            ->get();

        $stats = [
            'available_posts_count' => $availablePostsCount,
            'total_applications' => $applicationsCount,
            'pending_count' => $pendingCount,
            'accepted_count' => $acceptedApplication ? 1 : 0,
            'rejected_count' => $rejectedCount,
            'is_placed' => $acceptedApplication !== null,
        ];

        return view('student.dashboard', compact('student', 'acceptedApplication', 'stats', 'recentApplications', 'recommendedPosts'));
    }
}
