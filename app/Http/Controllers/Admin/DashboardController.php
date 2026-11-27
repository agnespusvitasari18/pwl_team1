<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\InternshipPost;
use App\Models\Major;
use App\Models\StudentProfile;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    private const MONTH_LABELS = [
        1 => 'Jan',
        2 => 'Feb',
        3 => 'Mar',
        4 => 'Apr',
        5 => 'Mei',
        6 => 'Jun',
        7 => 'Jul',
        8 => 'Agu',
        9 => 'Sep',
        10 => 'Okt',
        11 => 'Nov',
        12 => 'Des',
    ];

    /** Rekap data siswa, statistik pelamaran, penempatan bulanan, dan tren penyerapan per jurusan. */
    public function index(): View
    {
        $studentsTotal = StudentProfile::count();
        $studentsPlaced = StudentProfile::whereHas('applications', fn ($q) => $q->accepted())->count();

        $companiesByStatus = User::query()
            ->where('role', User::ROLE_COMPANY)
            ->selectRaw('approval_status, count(*) as total')
            ->groupBy('approval_status')
            ->pluck('total', 'approval_status');

        $applicationsByStatus = Application::query()
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $applicationsAccepted = (int) ($applicationsByStatus[Application::STATUS_ACCEPTED] ?? 0);
        $applicationsPending = (int) ($applicationsByStatus[Application::STATUS_PENDING] ?? 0);
        $applicationsRejected = (int) ($applicationsByStatus[Application::STATUS_REJECTED] ?? 0);
        $applicationsTotal = $applicationsAccepted + $applicationsPending + $applicationsRejected;

        $stats = [
            'students_total' => $studentsTotal,
            'students_placed' => $studentsPlaced,
            'students_unplaced' => max(0, $studentsTotal - $studentsPlaced),
            'placement_rate' => $studentsTotal > 0 ? round(($studentsPlaced / $studentsTotal) * 100) : 0,
            'companies_total' => (int) $companiesByStatus->sum(),
            'companies_pending' => (int) ($companiesByStatus[User::STATUS_PENDING] ?? 0),
            'companies_approved' => (int) ($companiesByStatus[User::STATUS_APPROVED] ?? 0),
            'companies_rejected' => (int) ($companiesByStatus[User::STATUS_REJECTED] ?? 0),
            'posts_total' => InternshipPost::count(),
            'posts_open' => InternshipPost::open()
                ->whereHas('company.user', fn ($q) => $q->where('approval_status', User::STATUS_APPROVED))
                ->count(),
            'applications_total' => $applicationsTotal,
            'applications_pending' => $applicationsPending,
            'applications_accepted' => $applicationsAccepted,
            'applications_rejected' => $applicationsRejected,
            'accepted_pct' => $applicationsTotal > 0 ? round(($applicationsAccepted / $applicationsTotal) * 100, 1) : 0,
            'pending_pct' => $applicationsTotal > 0 ? round(($applicationsPending / $applicationsTotal) * 100, 1) : 0,
            'rejected_pct' => $applicationsTotal > 0 ? round(($applicationsRejected / $applicationsTotal) * 100, 1) : 0,
        ];

        // Penempatan per bulan (6 bulan terakhir, kompatibel PostgreSQL & SQLite)
        $startMonth = now()->startOfMonth()->subMonths(5);
        $recentAppsInRange = Application::query()
            ->whereDate('application_date', '>=', $startMonth->toDateString())
            ->get(['application_date', 'status']);

        $monthlyPlacements = [];
        for ($i = 5; $i >= 0; $i--) {
            $monthDate = now()->startOfMonth()->subMonths($i);
            $key = $monthDate->format('Y-m');

            $monthItems = $recentAppsInRange->filter(
                fn ($app) => $app->application_date && $app->application_date->format('Y-m') === $key
            );

            $monthlyPlacements[] = [
                'key' => $key,
                'label' => self::MONTH_LABELS[(int) $monthDate->format('n')].' '.$monthDate->format('y'),
                'short_label' => self::MONTH_LABELS[(int) $monthDate->format('n')],
                'accepted' => $monthItems->where('status', Application::STATUS_ACCEPTED)->count(),
                'total' => $monthItems->count(),
            ];
        }

        // Tren Penyerapan Siswa Berdasarkan Jurusan (Bab II.2.2.3 poin e)
        $majorsAbsorption = Major::withCount([
            'studentProfiles as total_students',
            'studentProfiles as placed_students' => fn ($q) => $q->whereHas('applications', fn ($a) => $a->accepted()),
            'internshipPosts as total_posts',
        ])
            ->orderBy('name')
            ->get()
            ->map(function ($major) {
                $major->unplaced_students = max(0, $major->total_students - $major->placed_students);
                $major->absorption_rate = $major->total_students > 0
                    ? round(($major->placed_students / $major->total_students) * 100)
                    : 0;

                return $major;
            });

        // Perusahaan menunggu persetujuan
        $pendingCompanies = User::where('role', User::ROLE_COMPANY)
            ->where('approval_status', User::STATUS_PENDING)
            ->with('companyProfile')
            ->latest()
            ->take(5)
            ->get();

        // 5 Lamaran siswa terbaru
        $recentApplications = Application::with(['student.user', 'student.major', 'post.company'])
            ->latest('application_date')
            ->latest('id')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'monthlyPlacements',
            'majorsAbsorption',
            'pendingCompanies',
            'recentApplications'
        ));
    }
}
