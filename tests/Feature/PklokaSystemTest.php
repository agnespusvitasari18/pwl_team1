<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\CompanyProfile;
use App\Models\InternshipPost;
use App\Models\Major;
use App\Models\StudentProfile;
use App\Models\User;
use App\Notifications\ResetPasswordNotification;
use Database\Seeders\MajorSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PklokaSystemTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(MajorSeeder::class);
    }

    private function createAdmin(string $email = 'admin@test.com'): User
    {
        return User::create([
            'name' => 'Admin Test',
            'email' => $email,
            'password' => 'password',
            'role' => User::ROLE_ADMIN,
            'approval_status' => User::STATUS_APPROVED,
        ]);
    }

    /** @return array{0: User, 1: CompanyProfile} */
    private function createCompany(
        string $email = 'comp@test.com',
        string $companyName = 'PT Mitra Lampung',
        string $approvalStatus = User::STATUS_APPROVED,
        ?string $rejectionReason = null
    ): array {
        $user = User::create([
            'name' => 'PIC '.$companyName,
            'email' => $email,
            'password' => 'password',
            'role' => User::ROLE_COMPANY,
            'approval_status' => $approvalStatus,
        ]);

        $profile = CompanyProfile::create([
            'user_id' => $user->id,
            'company_name' => $companyName,
            'description' => 'Perusahaan teknologi di Bandar Lampung',
            'address' => 'Jl. Sudirman No. 1, Bandar Lampung',
            'contact' => '0812-7000-0001',
            'rejection_reason' => $rejectionReason,
        ]);

        return [$user, $profile];
    }

    /** @return array{0: User, 1: StudentProfile} */
    private function createStudent(
        Major $major,
        string $email = 'siswa@test.com',
        string $nisn = '0061111111',
        string $name = 'Siswa Uji'
    ): array {
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => 'password',
            'role' => User::ROLE_STUDENT,
            'approval_status' => User::STATUS_APPROVED,
        ]);

        $profile = StudentProfile::create([
            'user_id' => $user->id,
            'nisn' => $nisn,
            'school_name' => config('pkloka.school_name'),
            'major_id' => $major->id,
            'address' => 'Jl. Kartini, Bandar Lampung',
            'phone' => '0812-7111-1111',
        ]);

        return [$user, $profile];
    }

    public function test_guest_can_see_public_and_auth_pages(): void
    {
        $this->get('/')->assertStatus(200)->assertSee('PKLoka')->assertSee(config('pkloka.school_name'));
        $this->get('/login')->assertStatus(200)->assertSee('Email atau NISN')->assertSee('Lupa kata sandi?');
        $this->get('/forgot-password')->assertStatus(200)->assertSee('Lupa Kata Sandi?');
        $this->get('/register-company')->assertStatus(200)->assertSee('Formulir Registrasi Perusahaan');
    }

    public function test_student_can_login_with_email_or_nisn(): void
    {
        $major = Major::first();
        [$studentUser, $student] = $this->createStudent($major, 'budi@siswa.test', '0065554443', 'Budi Santoso');

        // Login menggunakan NISN
        $this->post('/login', [
            'email' => $student->nisn,
            'password' => 'password',
        ])->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($studentUser);

        $this->post('/logout')->assertRedirect(route('login'));
        $this->assertGuest();

        // Login menggunakan Email
        $this->post('/login', [
            'email' => $studentUser->email,
            'password' => 'password',
        ])->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($studentUser);

        $this->post('/logout');

        // Salah sandi ditolak
        $this->post('/login', [
            'email' => $student->nisn,
            'password' => 'salahpassword',
        ])->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_role_based_access_control_isolates_admin_company_and_student(): void
    {
        $major = Major::first();
        $admin = $this->createAdmin();
        [$compAUser, $compA] = $this->createCompany('compa@test.com', 'PT Alpha Lampung');
        [$compBUser, $compB] = $this->createCompany('compb@test.com', 'PT Beta Lampung');
        [$studentUser, $student] = $this->createStudent($major);

        $postB = InternshipPost::create([
            'company_id' => $compB->id,
            'major_id' => $major->id,
            'title' => 'Lowongan Beta',
            'description' => 'Deskripsi Beta',
            'quota' => 2,
            'deadline' => now()->addDays(15)->toDateString(),
        ]);

        $appB = Application::create([
            'student_id' => $student->id,
            'post_id' => $postB->id,
            'application_date' => today(),
            'status' => Application::STATUS_PENDING,
        ]);

        // Guest diarahkan ke login
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
        $this->get(route('company.dashboard'))->assertRedirect(route('login'));
        $this->get(route('student.dashboard'))->assertRedirect(route('login'));

        // Siswa tidak boleh akses admin maupun perusahaan
        $this->actingAs($studentUser)->get(route('admin.dashboard'))->assertStatus(403)->assertSee('Akses Ditolak');
        $this->actingAs($studentUser)->get(route('company.dashboard'))->assertStatus(403);

        // Perusahaan tidak boleh akses admin maupun siswa
        $this->actingAs($compAUser)->get(route('admin.dashboard'))->assertStatus(403);
        $this->actingAs($compAUser)->get(route('student.dashboard'))->assertStatus(403);

        // Admin tidak boleh akses dashboard perusahaan maupun siswa
        $this->actingAs($admin)->get(route('company.dashboard'))->assertStatus(403);
        $this->actingAs($admin)->get(route('student.dashboard'))->assertStatus(403);

        // Perusahaan A tidak boleh mengelola lowongan atau pelamar Perusahaan B
        $this->actingAs($compAUser)->get(route('company.posts.edit', $postB))->assertStatus(403);
        $this->actingAs($compAUser)->delete(route('company.posts.destroy', $postB))->assertStatus(403);
        $this->actingAs($compAUser)->get(route('company.posts.applicants.index', $postB))->assertStatus(403);
        $this->actingAs($compAUser)->patch(route('company.applications.accept', $appB))->assertStatus(403);
        $this->actingAs($compAUser)->patch(route('company.applications.reject', $appB))->assertStatus(403);

        // Halaman 404 berbahasa Indonesia
        $this->get('/halaman-tidak-ada-xyz')->assertStatus(404)->assertSee('Halaman Tidak Ditemukan');
    }

    public function test_company_register_reject_with_reason_then_approve_and_login(): void
    {
        $admin = $this->createAdmin();

        // 1. Perusahaan mendaftar mandiri
        $this->post('/register-company', [
            'name' => 'John PIC',
            'email' => 'john@company.test',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'company_name' => 'PT Cipta Karya Lampung',
            'address' => 'Jl. Merdeka No. 10, Bandar Lampung',
            'contact' => '0812-7456-7890',
            'description' => 'Software agency',
        ])->assertRedirect('/login')->assertSessionHas('status');

        $companyUser = User::where('email', 'john@company.test')->firstOrFail();
        $this->assertEquals(User::STATUS_PENDING, $companyUser->approval_status);

        // Tidak bisa login saat pending
        $this->post('/login', [
            'email' => 'john@company.test',
            'password' => 'secret123',
        ])->assertSessionHasErrors('email');
        $this->assertGuest();

        // 2. Admin menolak tanpa alasan -> gagal validasi
        $this->actingAs($admin)
            ->patch(route('admin.companies.reject', $companyUser), ['rejection_reason' => ''])
            ->assertSessionHasErrors('rejection_reason');

        // 3. Admin menolak dengan alasan yang jelas
        $reason = 'Alamat kantor dan bidang keahlian belum tercantum lengkap.';
        $this->actingAs($admin)
            ->patch(route('admin.companies.reject', $companyUser), ['rejection_reason' => $reason])
            ->assertSessionHas('success');

        $companyUser->refresh();
        $this->assertEquals(User::STATUS_REJECTED, $companyUser->approval_status);
        $this->assertEquals($reason, $companyUser->companyProfile->rejection_reason);

        // Logout admin, lalu perusahaan mencoba login -> ditolak dan melihat alasan penolakan
        $this->post('/logout');
        $loginRejected = $this->post('/login', [
            'email' => 'john@company.test',
            'password' => 'secret123',
        ]);
        $loginRejected->assertSessionHasErrors('email');
        $loginRejected->assertSessionHas('rejection_reason', $reason);
        $this->assertGuest();

        // 4. Admin menyetujui perusahaan -> alasan penolakan dibersihkan
        $this->actingAs($admin)
            ->patch(route('admin.companies.approve', $companyUser))
            ->assertSessionHas('success');

        $companyUser->refresh();
        $this->assertEquals(User::STATUS_APPROVED, $companyUser->approval_status);
        $this->assertNull($companyUser->companyProfile->rejection_reason);

        // 5. Perusahaan kini bisa login dan membuka dashboard perusahaan
        $this->post('/logout');
        $this->post('/login', [
            'email' => 'john@company.test',
            'password' => 'secret123',
        ])->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($companyUser);
        $this->get(route('company.dashboard'))->assertStatus(200)->assertSee('PT Cipta Karya Lampung');
    }

    public function test_application_validation_and_private_cv_storage_authorization(): void
    {
        Storage::fake('local');
        Storage::fake('public');

        $major1 = Major::first();
        $major2 = Major::skip(1)->first();

        $admin = $this->createAdmin();
        [$comp1User, $comp1] = $this->createCompany('c1@test.com', 'PT Satu Lampung');
        [$comp2User, $comp2] = $this->createCompany('c2@test.com', 'PT Dua Lampung');
        [, $pendingComp] = $this->createCompany('pending@test.com', 'PT Pending', User::STATUS_PENDING);

        [$student1User, $student1] = $this->createStudent($major1, 's1@test.com', '0061000001', 'Siswa Satu');
        [$student2User] = $this->createStudent($major1, 's2@test.com', '0061000002', 'Siswa Dua');
        [$studentDiffMajorUser] = $this->createStudent($major2, 's3@test.com', '0061000003', 'Siswa Beda Jurusan');

        $openPost = InternshipPost::create([
            'company_id' => $comp1->id,
            'major_id' => $major1->id,
            'title' => 'Junior Web Dev',
            'description' => 'Deskripsi',
            'quota' => 2,
            'deadline' => now()->addDays(10)->toDateString(),
        ]);

        $expiredPost = InternshipPost::create([
            'company_id' => $comp1->id,
            'major_id' => $major1->id,
            'title' => 'Lowongan Tutup',
            'description' => 'Deskripsi',
            'quota' => 2,
            'deadline' => now()->subDay()->toDateString(),
        ]);

        $pendingCompanyPost = InternshipPost::create([
            'company_id' => $pendingComp->id,
            'major_id' => $major1->id,
            'title' => 'Lowongan Belum Diverifikasi',
            'description' => 'Deskripsi',
            'quota' => 2,
            'deadline' => now()->addDays(10)->toDateString(),
        ]);

        // Tidak boleh melamar ke perusahaan belum disetujui (404)
        $this->actingAs($student1User)
            ->post(route('student.posts.apply', $pendingCompanyPost))
            ->assertStatus(404);

        // Tidak boleh melamar ke jurusan berbeda
        $this->actingAs($studentDiffMajorUser)
            ->post(route('student.posts.apply', $openPost))
            ->assertSessionHas('error');

        // Tidak boleh melamar ke lowongan yang lewat batas waktu
        $this->actingAs($student1User)
            ->post(route('student.posts.apply', $expiredPost))
            ->assertSessionHas('error');

        // Tidak boleh unggah berkas non-PDF
        $this->actingAs($student1User)
            ->post(route('student.posts.apply', $openPost), [
                'resume_file' => UploadedFile::fake()->create('cv.docx', 100, 'application/msword'),
            ])
            ->assertSessionHasErrors('resume_file');

        // Melamar dengan CV PDF valid -> tersimpan di disk privat ('local'), BUKAN 'public'
        $pdfFile = UploadedFile::fake()->create('cv-siswa.pdf', 200, 'application/pdf');
        $this->actingAs($student1User)
            ->post(route('student.posts.apply', $openPost), [
                'notes' => 'Siap belajar',
                'portfolio_url' => 'https://github.com/siswasatu',
                'resume_file' => $pdfFile,
            ])
            ->assertRedirect(route('student.applications.index'))
            ->assertSessionHas('success');

        $application = Application::where('student_id', $student1->id)
            ->where('post_id', $openPost->id)
            ->firstOrFail();

        $this->assertNotNull($application->resume_file);
        Storage::disk('local')->assertExists($application->resume_file);
        Storage::disk('public')->assertMissing($application->resume_file);

        // Tidak boleh melamar dua kali pada lowongan yang sama
        $this->actingAs($student1User)
            ->post(route('student.posts.apply', $openPost))
            ->assertSessionHas('error');

        // Uji hak akses route CV privat
        $resumeUrl = route('applications.resume', $application);
        $this->actingAs($student1User)->get($resumeUrl)->assertStatus(200); // Siswa pemilik
        $this->actingAs($comp1User)->get($resumeUrl)->assertStatus(200);    // Perusahaan pemilik lowongan
        $this->actingAs($admin)->get($resumeUrl)->assertStatus(200);        // Admin sekolah

        $this->actingAs($student2User)->get($resumeUrl)->assertStatus(403); // Siswa lain -> ditolak
        $this->actingAs($comp2User)->get($resumeUrl)->assertStatus(403);    // Perusahaan lain -> ditolak

        $this->post('/logout');
        $this->get($resumeUrl)->assertRedirect(route('login'));             // Tamu -> diarahkan ke login
    }

    public function test_accepting_applicant_enforces_quota_auto_rejects_pending_and_locks_student(): void
    {
        $major = Major::first();
        [$comp1User, $comp1] = $this->createCompany('c1@test.com', 'PT Satu');
        [$comp2User, $comp2] = $this->createCompany('c2@test.com', 'PT Dua');

        $post1 = InternshipPost::create([
            'company_id' => $comp1->id,
            'major_id' => $major->id,
            'title' => 'Posisi Kuota 1',
            'description' => 'Desc',
            'quota' => 1,
            'deadline' => now()->addDays(10)->toDateString(),
        ]);

        $post2 = InternshipPost::create([
            'company_id' => $comp2->id,
            'major_id' => $major->id,
            'title' => 'Posisi 2',
            'description' => 'Desc',
            'quota' => 2,
            'deadline' => now()->addDays(10)->toDateString(),
        ]);

        $post3 = InternshipPost::create([
            'company_id' => $comp2->id,
            'major_id' => $major->id,
            'title' => 'Posisi 3',
            'description' => 'Desc',
            'quota' => 2,
            'deadline' => now()->addDays(10)->toDateString(),
        ]);

        [$student1User, $student1] = $this->createStudent($major, 's1@test.com', '0062000001', 'Siswa Pertama');
        [$student2User, $student2] = $this->createStudent($major, 's2@test.com', '0062000002', 'Siswa Kedua');

        $app1Post1 = Application::create([
            'student_id' => $student1->id,
            'post_id' => $post1->id,
            'application_date' => today(),
            'status' => Application::STATUS_PENDING,
        ]);

        $app1Post2 = Application::create([
            'student_id' => $student1->id,
            'post_id' => $post2->id,
            'application_date' => today(),
            'status' => Application::STATUS_PENDING,
        ]);

        $app2Post1 = Application::create([
            'student_id' => $student2->id,
            'post_id' => $post1->id,
            'application_date' => today(),
            'status' => Application::STATUS_PENDING,
        ]);

        // Perusahaan 1 menerima Siswa 1 -> kuota 1 terisi penuh, lamaran Siswa 1 di PT Dua otomatis ditolak
        $this->actingAs($comp1User)
            ->patch(route('company.applications.accept', $app1Post1))
            ->assertSessionHas('success');

        $this->assertEquals(Application::STATUS_ACCEPTED, $app1Post1->fresh()->status);
        $this->assertEquals(Application::STATUS_REJECTED, $app1Post2->fresh()->status);
        $this->assertStringContainsString('Otomatis dibatalkan', (string) $app1Post2->fresh()->rejection_reason);

        // Kuota post1 sudah penuh -> Perusahaan 1 tidak dapat menerima Siswa 2
        $this->actingAs($comp1User)
            ->patch(route('company.applications.accept', $app2Post1))
            ->assertSessionHas('error', 'Kuota lowongan sudah penuh.');
        $this->assertEquals(Application::STATUS_PENDING, $app2Post1->fresh()->status);

        // Siswa 1 terkunci -> tidak boleh melamar ke lowongan lain
        $this->actingAs($student1User)
            ->post(route('student.posts.apply', $post3))
            ->assertSessionHas('error');

        // Siswa 2 tidak bisa melamar ke post1 yang kuotanya sudah penuh (buat siswa 3 untuk uji kuota penuh)
        [$student3User] = $this->createStudent($major, 's3@test.com', '0062000003', 'Siswa Ketiga');
        $this->actingAs($student3User)
            ->post(route('student.posts.apply', $post1))
            ->assertSessionHas('error', 'Kuota lowongan ini sudah penuh.');

        // Perusahaan 1 tidak boleh menghapus lowongan yang sudah memiliki siswa diterima
        $this->actingAs($comp1User)
            ->delete(route('company.posts.destroy', $post1))
            ->assertSessionHas('error');
        $this->assertDatabaseHas('internship_posts', ['id' => $post1->id]);

        // Perusahaan 1 menolak Siswa 2 dengan alasan -> alasan terlihat siswa
        $this->actingAs($comp1User)
            ->patch(route('company.applications.reject', $app2Post1), [
                'rejection_reason' => 'Kuota gelombang ini sudah terpenuhi.',
            ])
            ->assertSessionHas('success');
        $this->assertEquals('Kuota gelombang ini sudah terpenuhi.', $app2Post1->fresh()->rejection_reason);

        $this->actingAs($student2User)
            ->get(route('student.applications.index'))
            ->assertStatus(200)
            ->assertSee('Kuota gelombang ini sudah terpenuhi.');
    }

    public function test_password_reset_flow_works_for_email_and_nisn(): void
    {
        Notification::fake();

        $major = Major::first();
        [$studentUser, $student] = $this->createStudent($major, 'reset@siswa.test', '0063334445', 'Siswa Reset');

        // Permintaan reset menggunakan NISN
        $this->post(route('password.email'), ['email' => $student->nisn])
            ->assertSessionHas('status');

        $capturedToken = null;
        Notification::assertSentTo($studentUser, ResetPasswordNotification::class, function ($notification) use (&$capturedToken) {
            $capturedToken = $notification->token;

            return true;
        });

        $this->assertNotNull($capturedToken);

        // Buka halaman reset password
        $this->get(route('password.reset', ['token' => $capturedToken, 'email' => $studentUser->email]))
            ->assertStatus(200)
            ->assertSee('Atur Ulang Kata Sandi');

        // Token tidak valid ditolak
        $this->post(route('password.update'), [
            'token' => 'token-salah',
            'email' => $studentUser->email,
            'password' => 'sandibaru123',
            'password_confirmation' => 'sandibaru123',
        ])->assertSessionHasErrors('email');

        // Reset dengan token valid berhasil
        $this->post(route('password.update'), [
            'token' => $capturedToken,
            'email' => $studentUser->email,
            'password' => 'sandibaru123',
            'password_confirmation' => 'sandibaru123',
        ])->assertRedirect(route('login'))->assertSessionHas('status');

        $this->assertTrue(Hash::check('sandibaru123', $studentUser->fresh()->password));

        // Login menggunakan kata sandi baru
        $this->post('/login', [
            'email' => $student->nisn,
            'password' => 'sandibaru123',
        ])->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($studentUser);
    }

    public function test_admin_can_crud_reset_password_import_csv_and_export_recap(): void
    {
        $admin = $this->createAdmin();
        $majorPplg = Major::where('name', 'like', '%Perangkat Lunak%')->firstOrFail();
        $majorDkv = Major::where('name', 'like', '%Desain Komunikasi%')->firstOrFail();

        // 1. CRUD Tambah Siswa
        $this->actingAs($admin)
            ->post(route('admin.students.store'), [
                'name' => 'Siswa Manual',
                'email' => 'manual@siswa.test',
                'password' => 'password123',
                'nisn' => '0069990001',
                'major_id' => $majorPplg->id,
                'phone' => '0812-7999-0001',
                'address' => 'Bandar Lampung',
            ])
            ->assertRedirect(route('admin.students.index'));

        $studentProfile = StudentProfile::where('nisn', '0069990001')->firstOrFail();
        $this->assertEquals(config('pkloka.school_name'), $studentProfile->school_name);

        // 2. Admin Reset Sandi Siswa (kustom & otomatis)
        $this->actingAs($admin)
            ->patch(route('admin.students.reset-password', $studentProfile), [
                'new_password' => 'ResetOlehAdmin99',
            ])
            ->assertSessionHas('success')
            ->assertSessionHas('reset_credentials');

        $this->assertTrue(Hash::check('ResetOlehAdmin99', $studentProfile->user->fresh()->password));

        // Reset otomatis tanpa mengisi new_password
        $resetAutoResp = $this->actingAs($admin)
            ->patch(route('admin.students.reset-password', $studentProfile), ['new_password' => '']);
        $resetAutoResp->assertSessionHas('reset_credentials');
        $generatedPass = session('reset_credentials.password');
        $this->assertNotEmpty($generatedPass);
        $this->assertTrue(Hash::check($generatedPass, $studentProfile->user->fresh()->password));

        // 3. Unduh template CSV
        $this->actingAs($admin)
            ->get(route('admin.students.import.template'))
            ->assertStatus(200)
            ->assertHeader('content-type', 'text/csv; charset=UTF-8');

        // 4. Impor CSV (2 baris valid + 2 baris tidak valid: duplikat NISN & jurusan tidak dikenal)
        $csvContent = implode("\n", [
            'name,email,nisn,major,school_name,phone,address,password',
            "Citra Kirana,citra@siswa.test,0069990002,{$majorPplg->name},SMKN 4 Bandar Lampung,081270001,Enggal,citra1234",
            "Doni Saputra,doni@siswa.test,0069990003,{$majorDkv->name},,081270002,Kedaton,",
            "Duplikat NISN,dup@siswa.test,0069990001,{$majorPplg->name},SMKN 4 Bandar Lampung,,,pass12345",
            'Jurusan Salah,salah@siswa.test,0069990004,Jurusan Tidak Ada,SMKN 4 Bandar Lampung,,,pass12345',
        ]);

        $csvUpload = UploadedFile::fake()->createWithContent('siswa.csv', $csvContent);

        $this->actingAs($admin)
            ->post(route('admin.students.import'), ['csv_file' => $csvUpload])
            ->assertRedirect(route('admin.students.index'))
            ->assertSessionHas('success')
            ->assertSessionHas('import_errors');

        $this->assertDatabaseHas('student_profiles', ['nisn' => '0069990002']);
        $this->assertDatabaseHas('student_profiles', ['nisn' => '0069990003']);
        $this->assertDatabaseMissing('student_profiles', ['nisn' => '0069990004']);

        // Siswa hasil impor dengan kolom password kosong bisa login dengan NISN sebagai sandi awal
        $doniUser = User::where('email', 'doni@siswa.test')->firstOrFail();
        $this->assertTrue(Hash::check('0069990003', $doniUser->password));

        // 5. Ekspor Rekap Penempatan (ditempatkan vs belum)
        [, $comp] = $this->createCompany('mitra@test.com', 'PT Mitra Ekspor Lampung');
        $post = InternshipPost::create([
            'company_id' => $comp->id,
            'major_id' => $majorPplg->id,
            'title' => 'Developer Intern',
            'description' => 'Desc',
            'quota' => 2,
            'deadline' => now()->addDays(20)->toDateString(),
        ]);
        Application::create([
            'student_id' => $studentProfile->id,
            'post_id' => $post->id,
            'application_date' => today(),
            'status' => Application::STATUS_ACCEPTED,
        ]);

        $exportAll = $this->actingAs($admin)->get(route('admin.students.export'));
        $exportAll->assertStatus(200);
        $csvAll = $exportAll->streamedContent();
        $this->assertStringContainsString('Siswa Manual', $csvAll);
        $this->assertStringContainsString('Sudah Ditempatkan', $csvAll);
        $this->assertStringContainsString('PT Mitra Ekspor Lampung', $csvAll);
        $this->assertStringContainsString('Citra Kirana', $csvAll);
        $this->assertStringContainsString('Belum Ditempatkan', $csvAll);

        $exportPlaced = $this->actingAs($admin)->get(route('admin.students.export', ['penempatan' => 'sudah']));
        $csvPlaced = $exportPlaced->streamedContent();
        $this->assertStringContainsString('Siswa Manual', $csvPlaced);
        $this->assertStringNotContainsString('Citra Kirana', $csvPlaced);

        $exportUnplaced = $this->actingAs($admin)->get(route('admin.students.export', ['penempatan' => 'belum']));
        $csvUnplaced = $exportUnplaced->streamedContent();
        $this->assertStringContainsString('Citra Kirana', $csvUnplaced);
        $this->assertStringNotContainsString('Siswa Manual', $csvUnplaced);
    }

    public function test_all_role_dashboards_and_pages_render_with_real_data(): void
    {
        $major = Major::first();
        $admin = $this->createAdmin();
        [$compUser, $comp] = $this->createCompany('comp@test.com', 'PT Tech Bandar Lampung');
        [$studentUser, $student] = $this->createStudent($major, 'student@test.com', '1234567890', 'Siswa Test');

        $post = InternshipPost::create([
            'company_id' => $comp->id,
            'major_id' => $major->id,
            'title' => 'Web Dev Lampung',
            'description' => 'Test Desc',
            'quota' => 2,
            'deadline' => now()->addDays(15)->toDateString(),
        ]);

        Application::create([
            'student_id' => $student->id,
            'post_id' => $post->id,
            'application_date' => today(),
            'status' => Application::STATUS_ACCEPTED,
        ]);

        // Admin pages + real dashboard analytics
        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertStatus(200)
            ->assertSee('Penempatan PKL per Bulan')
            ->assertSee('Persentase Status Lamaran')
            ->assertSee('Tren Penyerapan Siswa per Jurusan')
            ->assertSee('100%');
        $this->actingAs($admin)->get(route('admin.students.index'))->assertStatus(200);
        $this->actingAs($admin)->get(route('admin.students.create'))->assertStatus(200);
        $this->actingAs($admin)->get(route('admin.students.edit', $student))->assertStatus(200);
        $this->actingAs($admin)->get(route('admin.companies.index'))->assertStatus(200);
        $this->actingAs($admin)->get(route('admin.posts.index'))->assertStatus(200);
        $this->actingAs($admin)->get(route('admin.applications.index'))->assertStatus(200);

        // Company pages
        $this->actingAs($compUser)->get(route('company.dashboard'))->assertStatus(200)->assertSee('Pelamar Terbaru');
        $this->actingAs($compUser)->get(route('company.posts.index'))->assertStatus(200);
        $this->actingAs($compUser)->get(route('company.posts.create'))->assertStatus(200);
        $this->actingAs($compUser)->get(route('company.posts.edit', $post))->assertStatus(200);
        $this->actingAs($compUser)->get(route('company.posts.applicants.index', $post))->assertStatus(200);
        $this->actingAs($compUser)->get(route('company.profile.edit'))->assertStatus(200);

        // Student pages
        $this->actingAs($studentUser)->get(route('student.dashboard'))->assertStatus(200);
        $this->actingAs($studentUser)->get(route('student.posts.index'))->assertStatus(200);
        $this->actingAs($studentUser)->get(route('student.posts.show', $post))->assertStatus(200);
        $this->actingAs($studentUser)->get(route('student.applications.index'))->assertStatus(200);
        $this->actingAs($studentUser)->get(route('student.profile.edit'))->assertStatus(200);
    }
}
