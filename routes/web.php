<?php

use App\Http\Controllers\Admin\CompanyApprovalController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\StudentController as AdminStudentController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterCompanyController;
use App\Http\Controllers\Company\ApplicantController;
use App\Http\Controllers\Company\InternshipPostController as CompanyPostController;
use App\Http\Controllers\Company\ProfileController as CompanyProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Student\ApplicationController as StudentApplicationController;
use App\Http\Controllers\Student\InternshipPostController as StudentPostController;
use App\Http\Controllers\Student\ProfileController as StudentProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

/* ---------- Autentikasi ---------- */
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);

    // Registrasi hanya untuk perusahaan. Akun siswa dibuat oleh admin.
    Route::get('/register-company', [RegisterCompanyController::class, 'create'])->name('register.company');
    Route::post('/register-company', [RegisterCompanyController::class, 'store']);
});

Route::post('/logout', [LoginController::class, 'destroy'])->middleware('auth')->name('logout');
Route::get('/dashboard', DashboardController::class)->middleware('auth')->name('dashboard');

/* ---------- Admin (pihak sekolah) ---------- */
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::get('/companies', [CompanyApprovalController::class, 'index'])->name('companies.index');
    Route::patch('/companies/{user}/approve', [CompanyApprovalController::class, 'approve'])->name('companies.approve');
    Route::patch('/companies/{user}/reject', [CompanyApprovalController::class, 'reject'])->name('companies.reject');

    Route::resource('students', AdminStudentController::class)->except('show');
});

/* ---------- Perusahaan ---------- */
Route::middleware(['auth', 'role:company'])->prefix('company')->name('company.')->group(function () {
    Route::get('/profile', [CompanyProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [CompanyProfileController::class, 'update'])->name('profile.update');

    Route::resource('posts', CompanyPostController::class)->except('show');
    Route::get('/posts/{post}/applicants', [ApplicantController::class, 'index'])->name('posts.applicants.index');

    Route::patch('/applications/{application}/accept', [ApplicantController::class, 'accept'])->name('applications.accept');
    Route::patch('/applications/{application}/reject', [ApplicantController::class, 'reject'])->name('applications.reject');
});

/* ---------- Siswa ---------- */
Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/profile', [StudentProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [StudentProfileController::class, 'update'])->name('profile.update');

    Route::get('/posts', [StudentPostController::class, 'index'])->name('posts.index');
    Route::get('/posts/{post}', [StudentPostController::class, 'show'])->name('posts.show');
    Route::post('/posts/{post}/apply', [StudentApplicationController::class, 'store'])->name('posts.apply');

    Route::get('/applications', [StudentApplicationController::class, 'index'])->name('applications.index');
});
