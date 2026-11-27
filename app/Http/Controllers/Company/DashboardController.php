<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $company = $request->user()->companyProfile;
        abort_unless($company, 403, 'Profil perusahaan tidak ditemukan.');

        $postsCount = $company->internshipPosts()->count();
        $totalQuota = (int) $company->internshipPosts()->sum('quota');

        $applicationsQuery = $company->applications();
        $totalApplicants = (clone $applicationsQuery)->count();
        $acceptedCount = (clone $applicationsQuery)->where('applications.status', Application::STATUS_ACCEPTED)->count();
        $pendingCount = (clone $applicationsQuery)->where('applications.status', Application::STATUS_PENDING)->count();
        $rejectedCount = (clone $applicationsQuery)->where('applications.status', Application::STATUS_REJECTED)->count();

        $recentApplicants = $company->applications()
            ->with(['student.user', 'student.major', 'post'])
            ->latest('applications.created_at')
            ->take(5)
            ->get();

        $activePosts = $company->internshipPosts()
            ->with('major')
            ->withCount([
                'applications',
                'applications as accepted_count' => fn ($q) => $q->accepted(),
            ])
            ->latest()
            ->take(5)
            ->get();

        $stats = [
            'posts_count' => $postsCount,
            'total_quota' => $totalQuota,
            'total_applicants' => $totalApplicants,
            'accepted_count' => $acceptedCount,
            'pending_count' => $pendingCount,
            'rejected_count' => $rejectedCount,
            'remaining_quota' => max(0, $totalQuota - $acceptedCount),
        ];

        return view('company.dashboard', compact('company', 'stats', 'recentApplicants', 'activePosts'));
    }
}
