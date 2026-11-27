<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\CompanyProfile;
use App\Models\InternshipPost;
use App\Models\Major;
use App\Models\StudentProfile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $schoolName = config('pkloka.school_name', 'SMKN 4 Bandar Lampung');

        // 1. Pastikan Admin Sekolah
        User::firstOrCreate(
            ['email' => 'admin@pkloka.test'],
            [
                'name' => 'Admin '.$schoolName,
                'password' => Hash::make('password'),
                'role' => User::ROLE_ADMIN,
                'approval_status' => User::STATUS_APPROVED,
            ]
        );

        // Ambil ID Jurusan
        $pplg = Major::where('name', 'like', '%Perangkat Lunak%')->first();
        $dkv = Major::where('name', 'like', '%Desain Komunikasi%')->first();
        $hotel = Major::where('name', 'like', '%Perhotelan%')->first();
        $boga = Major::where('name', 'like', '%Tata Boga%')->first();
        $akl = Major::where('name', 'like', '%Akuntansi%')->first();

        // 2. Perusahaan 1: PT Solusi Teknologi Nusantara (Approved)
        $userComp1 = User::firstOrCreate(
            ['email' => 'tech@solusi.test'],
            [
                'name' => 'Budi Hartono (HRD)',
                'password' => Hash::make('password'),
                'role' => User::ROLE_COMPANY,
                'approval_status' => User::STATUS_APPROVED,
            ]
        );

        $comp1 = CompanyProfile::firstOrCreate(
            ['user_id' => $userComp1->id],
            [
                'company_name' => 'PT Solusi Teknologi Nusantara',
                'description' => 'Perusahaan software house di Bandar Lampung yang berfokus pada pengembangan aplikasi web, mobile, dan transformasi digital instansi serta UMKM.',
                'address' => 'Jl. Jenderal Sudirman No. 88, Enggal, Kota Bandar Lampung',
                'contact' => '0812-7234-5678 (HR Department)',
            ]
        );

        $postPplg1 = InternshipPost::firstOrCreate(
            ['company_id' => $comp1->id, 'title' => 'Junior Web Developer (Laravel & Tailwind)'],
            [
                'major_id' => $pplg->id,
                'description' => "Persyaratan:\n1. Memahami dasar pemrograman PHP, HTML, CSS, JavaScript.\n2. Mengenal dasar framework Laravel dan database relasional.\n3. Disiplin, memiliki rasa ingin tahu tinggi, dan mampu bekerja dalam tim.\n\nTugas Utama:\n- Membantu tim engineer mengembangkan fitur modul web.\n- Melakukan pengujian fitur dan dokumentasi kode.",
                'quota' => 3,
                'deadline' => now()->addDays(30)->toDateString(),
            ]
        );

        $postDkv1 = InternshipPost::firstOrCreate(
            ['company_id' => $comp1->id, 'title' => 'UI/UX & Graphic Designer Intern'],
            [
                'major_id' => $dkv->id,
                'description' => "Persyaratan:\n1. Mampu mengoperasikan Figma dan Adobe Illustrator/Photoshop.\n2. Memiliki pemahaman tentang tata letak, tipografi, dan hierarki visual.\n3. Membawa portofolio desain visual sederhana.",
                'quota' => 2,
                'deadline' => now()->addDays(25)->toDateString(),
            ]
        );

        // 3. Perusahaan 2: CV Kreasi Media Lampung (Approved)
        $userComp2 = User::firstOrCreate(
            ['email' => 'kreasi@media.test'],
            [
                'name' => 'Maya Anggraini (Lead Creative)',
                'password' => Hash::make('password'),
                'role' => User::ROLE_COMPANY,
                'approval_status' => User::STATUS_APPROVED,
            ]
        );

        $comp2 = CompanyProfile::firstOrCreate(
            ['user_id' => $userComp2->id],
            [
                'company_name' => 'CV Kreasi Media Digital Lampung',
                'description' => 'Creative agency dan rumah produksi di Kedaton, Bandar Lampung yang melayani branding, desain grafis, animasi 2D/3D, dan pengembangan konten digital.',
                'address' => 'Jl. Zainal Abidin Pagar Alam No. 45, Kedaton, Kota Bandar Lampung',
                'contact' => '0857-6811-2233 (Studio Kreasi)',
            ]
        );

        $postDkv2 = InternshipPost::firstOrCreate(
            ['company_id' => $comp2->id, 'title' => 'Junior 2D Animator & Illustrator'],
            [
                'major_id' => $dkv->id,
                'description' => "Persyaratan:\n1. Menguasai perangkat lunak Adobe After Effects dan Illustrator.\n2. Memahami prinsip dasar animasi dan ilustrasi vektor.\n3. Bersedia belajar membuat aset grafis kampanye digital.",
                'quota' => 2,
                'deadline' => now()->addDays(20)->toDateString(),
            ]
        );

        $postPplg2 = InternshipPost::firstOrCreate(
            ['company_id' => $comp2->id, 'title' => 'Frontend & CMS Support Intern'],
            [
                'major_id' => $pplg->id,
                'description' => "Persyaratan:\n1. Siswa jurusan Pengembangan Perangkat Lunak.\n2. Terbiasa dengan HTML, CSS responsif, dan dasar JavaScript.\n3. Rapi dalam mengelola konten website klien.",
                'quota' => 2,
                'deadline' => now()->addDays(28)->toDateString(),
            ]
        );

        // 4. Perusahaan 3: Hotel Grand Lampung & Convention (Approved)
        $userComp3 = User::firstOrCreate(
            ['email' => 'hrd@grandlampung.test'],
            [
                'name' => 'Hendra Pratama (HR Manager)',
                'password' => Hash::make('password'),
                'role' => User::ROLE_COMPANY,
                'approval_status' => User::STATUS_APPROVED,
            ]
        );

        $comp3 = CompanyProfile::firstOrCreate(
            ['user_id' => $userComp3->id],
            [
                'company_name' => 'Hotel Grand Lampung & Convention',
                'description' => 'Hotel bintang 4 terkemuka di pusat Kota Bandar Lampung dengan fasilitas convention hall, restoran internasional, dan standar pelayanan perhotelan profesional.',
                'address' => 'Jl. Raden Intan No. 16, Tanjung Karang Pusat, Kota Bandar Lampung',
                'contact' => '0721-256222 (Ext. HRD 104)',
            ]
        );

        $postHotel = InternshipPost::firstOrCreate(
            ['company_id' => $comp3->id, 'title' => 'Front Office & Hospitality Intern'],
            [
                'major_id' => $hotel->id,
                'description' => "Kualifikasi:\n1. Siswa jurusan Perhotelan berpenampilan rapi dan ramah.\n2. Memiliki keterampilan komunikasi yang baik (Bahasa Indonesia & dasar Bahasa Inggris).\n3. Siap mematuhi standar grooming perhotelan.",
                'quota' => 4,
                'deadline' => now()->addDays(40)->toDateString(),
            ]
        );

        $postBoga = InternshipPost::firstOrCreate(
            ['company_id' => $comp3->id, 'title' => 'Pastry & Kitchen Assistant Intern'],
            [
                'major_id' => $boga->id,
                'description' => "Kualifikasi:\n1. Siswa jurusan Tata Boga dengan minat kuat di bidang pastry & bakery.\n2. Memahami standar sanitasi dan keamanan pangan.\n3. Disiplin, cekatan, dan mampu bekerja dalam ritme dapur hotel.",
                'quota' => 2,
                'deadline' => now()->addDays(35)->toDateString(),
            ]
        );

        // 5. Perusahaan 4: KAP Marwah & Rekan Bandar Lampung (Approved)
        $userComp4 = User::firstOrCreate(
            ['email' => 'kap@marwah.test'],
            [
                'name' => 'Siti Marwah, SE, Ak (Managing Partner)',
                'password' => Hash::make('password'),
                'role' => User::ROLE_COMPANY,
                'approval_status' => User::STATUS_APPROVED,
            ]
        );

        $comp4 = CompanyProfile::firstOrCreate(
            ['user_id' => $userComp4->id],
            [
                'company_name' => 'Kantor Akuntan Publik Marwah & Rekan',
                'description' => 'Firma jasa akuntansi, audit laporan keuangan, dan konsultasi perpajakan terpercaya di Kota Bandar Lampung sejak tahun 2014.',
                'address' => 'Jl. Teuku Umar No. 112, Kedaton, Kota Bandar Lampung',
                'contact' => '0813-6987-5432 (Office Admin)',
            ]
        );

        $postAkl = InternshipPost::firstOrCreate(
            ['company_id' => $comp4->id, 'title' => 'Staf Administrasi & Pembukuan Keuangan'],
            [
                'major_id' => $akl->id,
                'description' => "Persyaratan:\n1. Siswa jurusan Akuntansi dan Keuangan Lembaga.\n2. Menguasai Microsoft Excel dasar (VLOOKUP, SUMIF, Pivot Table).\n3. Teliti, jujur, dan bertanggung jawab terhadap kerahasiaan data.",
                'quota' => 2,
                'deadline' => now()->addDays(18)->toDateString(),
            ]
        );

        // 6. Perusahaan 5: PT Logistik Niaga Lampung (PENDING - untuk demo verifikasi admin)
        $userComp5 = User::firstOrCreate(
            ['email' => 'info@logistikniaga.test'],
            [
                'name' => 'Denny Kurniawan (Manajer Operasional)',
                'password' => Hash::make('password'),
                'role' => User::ROLE_COMPANY,
                'approval_status' => User::STATUS_PENDING,
            ]
        );

        CompanyProfile::firstOrCreate(
            ['user_id' => $userComp5->id],
            [
                'company_name' => 'PT Logistik Niaga Lampung',
                'description' => 'Penyedia jasa pengiriman kargo dan pergudangan terpadu lintas Sumatra dan Jawa yang beroperasi dekat Pelabuhan Panjang.',
                'address' => 'Jl. Soekarno Hatta Km. 8, Panjang, Kota Bandar Lampung',
                'contact' => '0821-7944-5566 (Customer Relations)',
            ]
        );

        // 7. Perusahaan 6: CV Berkah Jaya Mandiri (REJECTED - untuk demo alasan penolakan)
        $userComp6 = User::firstOrCreate(
            ['email' => 'cs@berkahjaya.test'],
            [
                'name' => 'Rudi Santoso (Pemilik Usaha)',
                'password' => Hash::make('password'),
                'role' => User::ROLE_COMPANY,
                'approval_status' => User::STATUS_REJECTED,
            ]
        );

        CompanyProfile::firstOrCreate(
            ['user_id' => $userComp6->id],
            [
                'company_name' => 'CV Berkah Jaya Mandiri',
                'description' => 'Usaha perdagangan umum dan distribusi perlengkapan kantor.',
                'address' => 'Jl. Pangeran Antasari No. 50, Kedamaian, Kota Bandar Lampung',
                'contact' => '0852-6900-1122',
                'rejection_reason' => 'Profil usaha dan rencana pembimbingan siswa belum melampirkan bidang keahlian yang selaras dengan kompetensi keahlian '.$schoolName.'.',
            ]
        );

        // 8. Siswa 1: Ahmad Fauzan (PPLG - Diterima di PT Solusi Teknologi Nusantara)
        $userSiswa1 = User::firstOrCreate(
            ['email' => 'ahmad@siswa.test'],
            [
                'name' => 'Ahmad Fauzan',
                'password' => Hash::make('password'),
                'role' => User::ROLE_STUDENT,
                'approval_status' => User::STATUS_APPROVED,
            ]
        );

        $student1 = StudentProfile::firstOrCreate(
            ['user_id' => $userSiswa1->id],
            [
                'nisn' => '0061234501',
                'school_name' => $schoolName,
                'major_id' => $pplg->id,
                'address' => 'Jl. Pangeran Antasari Gg. Melati No. 12, Sukabumi, Bandar Lampung',
                'phone' => '0812-7100-0101',
            ]
        );

        Application::firstOrCreate(
            ['student_id' => $student1->id, 'post_id' => $postPplg1->id],
            [
                'application_date' => now()->subMonths(2)->toDateString(),
                'status' => Application::STATUS_ACCEPTED,
                'portfolio_url' => 'https://github.com/ahmadfauzan',
                'notes' => 'Saya antusias mempraktikkan kemampuan pengembangan web Laravel yang telah saya pelajari di '.$schoolName.'.',
            ]
        );

        Application::firstOrCreate(
            ['student_id' => $student1->id, 'post_id' => $postPplg2->id],
            [
                'application_date' => now()->subMonths(2)->subDays(2)->toDateString(),
                'status' => Application::STATUS_REJECTED,
                'notes' => 'Saya ingin belajar pengelolaan website dan frontend modern.',
                'rejection_reason' => 'Otomatis dibatalkan karena siswa telah diterima di tempat PKL lain.',
            ]
        );

        // 9. Siswa 2: Siti Nurhaliza (DKV - Pending di PT Solusi Teknologi)
        $userSiswa2 = User::firstOrCreate(
            ['email' => 'siti@siswa.test'],
            [
                'name' => 'Siti Nurhaliza',
                'password' => Hash::make('password'),
                'role' => User::ROLE_STUDENT,
                'approval_status' => User::STATUS_APPROVED,
            ]
        );

        $student2 = StudentProfile::firstOrCreate(
            ['user_id' => $userSiswa2->id],
            [
                'nisn' => '0061234502',
                'school_name' => $schoolName,
                'major_id' => $dkv->id,
                'address' => 'Jl. Kartini No. 34, Tanjung Karang Pusat, Bandar Lampung',
                'phone' => '0857-7200-0202',
            ]
        );

        Application::firstOrCreate(
            ['student_id' => $student2->id, 'post_id' => $postDkv1->id],
            [
                'application_date' => now()->subDays(2)->toDateString(),
                'status' => Application::STATUS_PENDING,
                'portfolio_url' => 'https://behance.net/sitinurhaliza',
                'notes' => 'Berikut portofolio desain antarmuka aplikasi dan ilustrasi digital yang pernah saya kerjakan.',
            ]
        );

        // 10. Siswa 3: Dimas Arya (PPLG - Pending di PT Solusi Teknologi)
        $userSiswa3 = User::firstOrCreate(
            ['email' => 'dimas@siswa.test'],
            [
                'name' => 'Dimas Arya',
                'password' => Hash::make('password'),
                'role' => User::ROLE_STUDENT,
                'approval_status' => User::STATUS_APPROVED,
            ]
        );

        $student3 = StudentProfile::firstOrCreate(
            ['user_id' => $userSiswa3->id],
            [
                'nisn' => '0061234503',
                'school_name' => $schoolName,
                'major_id' => $pplg->id,
                'address' => 'Jl. Teuku Umar No. 78, Kedaton, Bandar Lampung',
                'phone' => '0821-7300-0303',
            ]
        );

        Application::firstOrCreate(
            ['student_id' => $student3->id, 'post_id' => $postPplg1->id],
            [
                'application_date' => now()->subDay()->toDateString(),
                'status' => Application::STATUS_PENDING,
                'portfolio_url' => 'https://dimasarya.dev',
                'notes' => 'Saya menguasai dasar Tailwind CSS dan integrasi backend Laravel. Siap belajar dan berkontribusi.',
            ]
        );

        // 11. Siswa 4: Rina Fitriani (Perhotelan - Diterima di Hotel Grand Lampung)
        $userSiswa4 = User::firstOrCreate(
            ['email' => 'rina@siswa.test'],
            [
                'name' => 'Rina Fitriani',
                'password' => Hash::make('password'),
                'role' => User::ROLE_STUDENT,
                'approval_status' => User::STATUS_APPROVED,
            ]
        );

        $student4 = StudentProfile::firstOrCreate(
            ['user_id' => $userSiswa4->id],
            [
                'nisn' => '0061234504',
                'school_name' => $schoolName,
                'major_id' => $hotel->id,
                'address' => 'Jl. Pramuka No. 20, Rajabasa, Bandar Lampung',
                'phone' => '0813-7400-0404',
            ]
        );

        Application::firstOrCreate(
            ['student_id' => $student4->id, 'post_id' => $postHotel->id],
            [
                'application_date' => now()->subMonth()->toDateString(),
                'status' => Application::STATUS_ACCEPTED,
                'notes' => 'Memiliki pengalaman praktik front office sekolah dan lomba kompetensi siswa bidang perhotelan.',
            ]
        );

        // 12. Siswa 5: Bima Sakti (PPLG - Belum Melamar Sama Sekali)
        $userSiswa5 = User::firstOrCreate(
            ['email' => 'bima@siswa.test'],
            [
                'name' => 'Bima Sakti',
                'password' => Hash::make('password'),
                'role' => User::ROLE_STUDENT,
                'approval_status' => User::STATUS_APPROVED,
            ]
        );

        StudentProfile::firstOrCreate(
            ['user_id' => $userSiswa5->id],
            [
                'nisn' => '0061234505',
                'school_name' => $schoolName,
                'major_id' => $pplg->id,
                'address' => 'Jl. Gajah Mada No. 5, Tanjung Karang Timur, Bandar Lampung',
                'phone' => '0852-7500-0505',
            ]
        );

        // 13. Siswa 6: Nadia Kusuma (AKL - Diterima bulan ini di KAP Marwah & Rekan)
        $userSiswa6 = User::firstOrCreate(
            ['email' => 'nadia@siswa.test'],
            [
                'name' => 'Nadia Kusuma',
                'password' => Hash::make('password'),
                'role' => User::ROLE_STUDENT,
                'approval_status' => User::STATUS_APPROVED,
            ]
        );

        $student6 = StudentProfile::firstOrCreate(
            ['user_id' => $userSiswa6->id],
            [
                'nisn' => '0061234506',
                'school_name' => $schoolName,
                'major_id' => $akl->id,
                'address' => 'Jl. Sultan Agung No. 19, Way Halim, Bandar Lampung',
                'phone' => '0819-7600-0606',
            ]
        );

        Application::firstOrCreate(
            ['student_id' => $student6->id, 'post_id' => $postAkl->id],
            [
                'application_date' => now()->subDays(3)->toDateString(),
                'status' => Application::STATUS_ACCEPTED,
                'notes' => 'Menguasai pembukuan akuntansi dasar, jurnal umum, dan spreadsheet laporan keuangan.',
            ]
        );

        // 14. Siswa 7: Putri Ayu Lestari (DKV - Diterima 3 bulan lalu di CV Kreasi Media Digital Lampung)
        $userSiswa7 = User::firstOrCreate(
            ['email' => 'putri@siswa.test'],
            [
                'name' => 'Putri Ayu Lestari',
                'password' => Hash::make('password'),
                'role' => User::ROLE_STUDENT,
                'approval_status' => User::STATUS_APPROVED,
            ]
        );

        $student7 = StudentProfile::firstOrCreate(
            ['user_id' => $userSiswa7->id],
            [
                'nisn' => '0061234507',
                'school_name' => $schoolName,
                'major_id' => $dkv->id,
                'address' => 'Jl. Kimaja No. 27, Way Halim, Bandar Lampung',
                'phone' => '0856-7700-0707',
            ]
        );

        Application::firstOrCreate(
            ['student_id' => $student7->id, 'post_id' => $postDkv2->id],
            [
                'application_date' => now()->subMonths(3)->toDateString(),
                'status' => Application::STATUS_ACCEPTED,
                'portfolio_url' => 'https://dribbble.com/putriayu',
                'notes' => 'Berpengalaman membuat motion graphic dan ilustrasi untuk media sosial sekolah.',
            ]
        );

        // 15. Siswa 8: Reza Pahlevi (Tata Boga - Ditolak di Hotel Grand Lampung)
        $userSiswa8 = User::firstOrCreate(
            ['email' => 'reza@siswa.test'],
            [
                'name' => 'Reza Pahlevi',
                'password' => Hash::make('password'),
                'role' => User::ROLE_STUDENT,
                'approval_status' => User::STATUS_APPROVED,
            ]
        );

        $student8 = StudentProfile::firstOrCreate(
            ['user_id' => $userSiswa8->id],
            [
                'nisn' => '0061234508',
                'school_name' => $schoolName,
                'major_id' => $boga->id,
                'address' => 'Jl. Cut Nyak Dien No. 14, Palapa, Bandar Lampung',
                'phone' => '0822-7800-0808',
            ]
        );

        Application::firstOrCreate(
            ['student_id' => $student8->id, 'post_id' => $postBoga->id],
            [
                'application_date' => now()->subDays(6)->toDateString(),
                'status' => Application::STATUS_REJECTED,
                'notes' => 'Saya berminat belajar pengolahan pastry dan roti di dapur hotel.',
                'rejection_reason' => 'Jadwal shift dapur belum sesuai dengan jadwal sekolah pada gelombang ini. Silakan daftar pada gelombang berikutnya.',
            ]
        );
    }
}
