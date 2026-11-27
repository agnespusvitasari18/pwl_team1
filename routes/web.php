<?php

use App\Http\Controllers\Admin\ApplicationController as AdminApplicationController;
use App\Http\Controllers\Admin\CompanyApprovalController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\InternshipPostController as AdminPostController;
use App\Http\Controllers\Admin\StudentController as AdminStudentController;
use App\Http\Controllers\ApplicationResumeController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterCompanyController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Company\ApplicantController;
use App\Http\Controllers\Company\DashboardController as CompanyDashboardController;
use App\Http\Controllers\Company\InternshipPostController as CompanyPostController;
use App\Http\Controllers\Company\ProfileController as CompanyProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Student\ApplicationController as StudentApplicationController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Student\InternshipPostController as StudentPostController;
use App\Http\Controllers\Student\ProfileController as StudentProfileController;
use App\Models\CompanyProfile;
use App\Models\InternshipPost;
use App\Models\Major;
use App\Models\StudentProfile;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $search = trim((string) request()->query('q', ''));
    $selectedMajor = request()->query('major_id');
    $showAll = request()->boolean('semua');

    $stats = [
        'posts_count' => InternshipPost::open()
            ->whereHas('company.user', fn ($q) => $q->where('approval_status', User::STATUS_APPROVED))
            ->count(),
        'companies_count' => User::where('role', User::ROLE_COMPANY)
            ->where('approval_status', User::STATUS_APPROVED)
            ->count(),
        'students_count' => StudentProfile::count(),
        'majors_count' => Major::count(),
    ];

    $postsQuery = InternshipPost::open()
        ->whereHas('company.user', fn ($q) => $q->where('approval_status', User::STATUS_APPROVED))
        ->when($selectedMajor, fn ($q) => $q->where('major_id', (int) $selectedMajor))
        ->when($search !== '', function ($q) use ($search) {
            $pattern = '%'.mb_strtolower($search).'%';
            $q->where(function ($query) use ($pattern) {
                $query->whereRaw('LOWER(title) like ?', [$pattern])
                    ->orWhereRaw('LOWER(description) like ?', [$pattern])
                    ->orWhereHas('company', fn ($company) => $company->whereRaw('LOWER(company_name) like ?', [$pattern]))
                    ->orWhereHas('major', fn ($major) => $major->whereRaw('LOWER(name) like ?', [$pattern]));
            });
        })
        ->with(['company', 'major'])
        ->withCount(['applications as accepted_count' => fn ($q) => $q->accepted()])
        ->latest();

    if (! $showAll && $search === '' && ! $selectedMajor) {
        $postsQuery->take(6);
    }

    $latestPosts = $postsQuery->get();

    $partners = CompanyProfile::query()
        ->whereHas('user', fn ($q) => $q->where('approval_status', User::STATUS_APPROVED))
        ->latest()
        ->take(8)
        ->get();

    $majors = Major::orderBy('name')->get();

    return view('welcome', compact('stats', 'latestPosts', 'majors', 'partners', 'search', 'selectedMajor', 'showAll'));
})->name('home');

/* ---------- Autentikasi ---------- */
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);

    // Registrasi hanya untuk perusahaan. Akun siswa dibuat oleh admin.
    Route::get('/register-company', [RegisterCompanyController::class, 'create'])->name('register.company');
    Route::post('/register-company', [RegisterCompanyController::class, 'store']);

    // Alur lupa & atur ulang kata sandi
    Route::get('/forgot-password', [ForgotPasswordController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'store'])->name('password.email');
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'store'])->name('password.update');
});

Route::post('/logout', [LoginController::class, 'destroy'])->middleware('auth')->name('logout');
Route::get('/dashboard', DashboardController::class)->middleware('auth')->name('dashboard');

/* ---------- Berkas Privat (CV Pelamar) ---------- */
Route::get('/applications/{application}/resume', ApplicationResumeController::class)
    ->middleware('auth')
    ->name('applications.resume');

/* ---------- Admin (pihak sekolah) ---------- */
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::get('/companies', [CompanyApprovalController::class, 'index'])->name('companies.index');
    Route::patch('/companies/{user}/approve', [CompanyApprovalController::class, 'approve'])->name('companies.approve');
    Route::patch('/companies/{user}/reject', [CompanyApprovalController::class, 'reject'])->name('companies.reject');

    Route::get('/students/export', [AdminStudentController::class, 'export'])->name('students.export');
    Route::get('/students/import/template', [AdminStudentController::class, 'importTemplate'])->name('students.import.template');
    Route::post('/students/import', [AdminStudentController::class, 'import'])->name('students.import');
    Route::patch('/students/{student}/reset-password', [AdminStudentController::class, 'resetPassword'])->name('students.reset-password');
    Route::resource('students', AdminStudentController::class)->except('show');

    Route::get('/posts', [AdminPostController::class, 'index'])->name('posts.index');
    Route::get('/applications', [AdminApplicationController::class, 'index'])->name('applications.index');
});

/* ---------- Perusahaan ---------- */
Route::middleware(['auth', 'role:company'])->prefix('company')->name('company.')->group(function () {
    Route::get('/dashboard', [CompanyDashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [CompanyProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [CompanyProfileController::class, 'update'])->name('profile.update');

    Route::resource('posts', CompanyPostController::class)->except('show');
    Route::get('/posts/{post}/applicants', [ApplicantController::class, 'index'])->name('posts.applicants.index');

    Route::patch('/applications/{application}/accept', [ApplicantController::class, 'accept'])->name('applications.accept');
    Route::patch('/applications/{application}/reject', [ApplicantController::class, 'reject'])->name('applications.reject');
});

/* ---------- Siswa ---------- */
Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [StudentProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [StudentProfileController::class, 'update'])->name('profile.update');

    Route::get('/posts', [StudentPostController::class, 'index'])->name('posts.index');
    Route::get('/posts/{post}', [StudentPostController::class, 'show'])->name('posts.show');
    Route::post('/posts/{post}/apply', [StudentApplicationController::class, 'store'])->name('posts.apply');

    Route::get('/applications', [StudentApplicationController::class, 'index'])->name('applications.index');
});
