<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\InternshipPost;
use App\Models\StudentProfile;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /** Rekap data siswa dan statistik pelamaran. */
    public function index(): View
    {
        $studentsTotal = StudentProfile::count();
        $studentsPlaced = StudentProfile::whereHas('applications', fn ($q) => $q->accepted())->count();

        $applicationsByStatus = Application::query()
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $stats = [
            'students_total' => $studentsTotal,
            'students_placed' => $studentsPlaced,
            'students_unplaced' => $studentsTotal - $studentsPlaced,
            'companies_pending' => User::where('role', User::ROLE_COMPANY)
                ->where('approval_status', User::STATUS_PENDING)->count(),
            'companies_approved' => User::where('role', User::ROLE_COMPANY)
                ->where('approval_status', User::STATUS_APPROVED)->count(),
            'posts_total' => InternshipPost::count(),
            'applications_total' => $applicationsByStatus->sum(),
            'applications_pending' => $applicationsByStatus[Application::STATUS_PENDING] ?? 0,
            'applications_accepted' => $applicationsByStatus[Application::STATUS_ACCEPTED] ?? 0,
            'applications_rejected' => $applicationsByStatus[Application::STATUS_REJECTED] ?? 0,
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
