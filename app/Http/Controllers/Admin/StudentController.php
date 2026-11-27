<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Major;
use App\Models\StudentProfile;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

/** Admin mengelola data master siswa (akun users + student_profiles), impor CSV, reset sandi, dan ekspor rekap. */
class StudentController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->input('q', ''));
        $placement = $request->input('penempatan'); // 'sudah' | 'belum' | kosong = semua
        $majorId = $request->input('major_id');

        $students = $this->filteredStudentsQuery($search, $placement, $majorId)
            ->paginate(15)
            ->withQueryString();

        $majors = Major::orderBy('name')->get();

        return view('admin.students.index', compact('students', 'majors', 'search', 'placement', 'majorId'));
    }

    public function create(): View
    {
        return view('admin.students.create', ['majors' => Major::orderBy('name')->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', Password::min(8)],
            'nisn' => ['required', 'string', 'max:20', 'unique:student_profiles,nisn'],
            'school_name' => ['nullable', 'string', 'max:255'],
            'major_id' => ['required', 'exists:majors,id'],
            'address' => ['nullable', 'string', 'max:1000'],
            'phone' => ['nullable', 'string', 'max:30'],
        ]);

        DB::transaction(function () use ($data) {
            $user = new User([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'], // di-hash oleh cast 'hashed'
            ]);
            $user->role = User::ROLE_STUDENT;
            $user->approval_status = User::STATUS_APPROVED;
            $user->save();

            StudentProfile::create([
                'user_id' => $user->id,
                'nisn' => $data['nisn'],
                'school_name' => ($data['school_name'] ?? null) ?: config('pkloka.school_name'),
                'major_id' => $data['major_id'],
                'address' => $data['address'] ?? null,
                'phone' => $data['phone'] ?? null,
            ]);
        });

        return redirect()->route('admin.students.index')->with('success', 'Akun siswa berhasil dibuat.');
    }

    public function edit(StudentProfile $student): View
    {
        $student->load('user');

        return view('admin.students.edit', [
            'student' => $student,
            'majors' => Major::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, StudentProfile $student): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($student->user_id)],
            'password' => ['nullable', Password::min(8)],
            'nisn' => ['required', 'string', 'max:20', Rule::unique('student_profiles', 'nisn')->ignore($student->id)],
            'school_name' => ['nullable', 'string', 'max:255'],
            'major_id' => ['required', 'exists:majors,id'],
            'address' => ['nullable', 'string', 'max:1000'],
            'phone' => ['nullable', 'string', 'max:30'],
        ]);

        DB::transaction(function () use ($data, $student) {
            $user = $student->user;
            $user->name = $data['name'];
            $user->email = $data['email'];
            if (! empty($data['password'])) {
                $user->password = $data['password'];
            }
            $user->save();

            $student->update([
                'nisn' => $data['nisn'],
                'school_name' => ($data['school_name'] ?? null) ?: config('pkloka.school_name'),
                'major_id' => $data['major_id'],
                'address' => $data['address'] ?? null,
                'phone' => $data['phone'] ?? null,
            ]);
        });

        return redirect()->route('admin.students.index')->with('success', 'Data siswa diperbarui.');
    }

    public function destroy(StudentProfile $student): RedirectResponse
    {
        // Menghapus akun ikut menghapus profil dan pendaftaran siswa (cascade di migration).
        $student->user->delete();

        return redirect()->route('admin.students.index')->with('success', 'Akun siswa dihapus.');
    }

    /** Admin mengatur ulang kata sandi siswa (kustom atau otomatis). */
    public function resetPassword(Request $request, StudentProfile $student): RedirectResponse
    {
        $validated = $request->validate([
            'new_password' => ['nullable', 'string', 'min:8', 'max:100'],
        ], [
            'new_password.min' => 'Kata sandi baru minimal 8 karakter.',
        ]);

        $newPassword = ! empty($validated['new_password'])
            ? $validated['new_password']
            : 'PKL'.Str::upper(Str::random(3)).random_int(1000, 9999);

        $student->loadMissing('user');
        $user = $student->user;

        DB::transaction(function () use ($user, $newPassword) {
            $user->forceFill([
                'password' => $newPassword,
                'remember_token' => Str::random(60),
            ])->save();

            DB::table('password_reset_tokens')->where('email', $user->email)->delete();
        });

        return back()
            ->with('success', "Kata sandi untuk siswa {$user->name} berhasil diatur ulang.")
            ->with('reset_credentials', [
                'name' => $user->name,
                'email' => $user->email,
                'nisn' => $student->nisn,
                'password' => $newPassword,
            ]);
    }

    /** Unduh contoh template CSV untuk impor massal akun siswa. */
    public function importTemplate(): StreamedResponse
    {
        $schoolName = config('pkloka.school_name');

        return response()->streamDownload(function () use ($schoolName) {
            $handle = fopen('php://output', 'w');
            // UTF-8 BOM agar Excel langsung membaca karakter UTF-8 dengan benar.
            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, ['name', 'email', 'nisn', 'major', 'school_name', 'phone', 'address', 'password']);
            fputcsv($handle, [
                'Andi Pratama',
                'andi.pratama@siswa.test',
                '0069900101',
                'Pengembangan Perangkat Lunak',
                $schoolName,
                '0812-7000-1101',
                'Jl. Teuku Umar No. 10, Bandar Lampung',
                'password123',
            ]);
            fputcsv($handle, [
                'Dewi Anggraini',
                'dewi.anggraini@siswa.test',
                '0069900102',
                'Desain Komunikasi Visual',
                $schoolName,
                '0857-7000-1102',
                'Jl. Kartini No. 25, Bandar Lampung',
                '',
            ]);

            fclose($handle);
        }, 'template-impor-siswa-pkloka.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /** Impor akun siswa secara massal dari berkas CSV. */
    public function import(Request $request): RedirectResponse
    {
        $request->validate([
            'csv_file' => ['required', 'file', 'mimes:csv,txt', 'max:2048'],
        ], [
            'csv_file.required' => 'Pilih berkas CSV terlebih dahulu.',
            'csv_file.mimes' => 'Berkas impor harus berformat CSV (.csv).',
            'csv_file.max' => 'Ukuran berkas CSV maksimal 2 MB.',
        ]);

        $file = $request->file('csv_file');
        $rawContent = file_get_contents($file->getRealPath());

        if ($rawContent === false || trim($rawContent) === '') {
            return back()->with('error', 'Berkas CSV kosong atau tidak dapat dibaca.');
        }

        // Hapus UTF-8 BOM jika ada.
        $rawContent = preg_replace('/^\xEF\xBB\xBF/', '', $rawContent);
        $lines = preg_split('/\r\n|\r|\n/', trim($rawContent));

        if (! $lines || count($lines) < 2) {
            return back()->with('error', 'Berkas CSV harus berisi baris header dan minimal satu baris data siswa.');
        }

        // Deteksi pemisah kolom (koma atau titik koma dari Excel Indonesia).
        $delimiter = substr_count($lines[0], ';') > substr_count($lines[0], ',') ? ';' : ',';
        $rawHeaders = str_getcsv($lines[0], $delimiter);
        $headers = array_map(fn ($h) => $this->normalizeCsvHeader((string) $h), $rawHeaders);

        $requiredHeaders = ['name', 'email', 'nisn', 'major'];
        $missingHeaders = array_diff($requiredHeaders, $headers);

        if (! empty($missingHeaders)) {
            return back()->with(
                'error',
                'Header CSV tidak lengkap. Kolom wajib: name, email, nisn, major (atau nama, email, nisn, jurusan).'
            );
        }

        $majors = Major::all();
        $majorsByName = $majors->mapWithKeys(fn ($m) => [mb_strtolower(trim($m->name)) => $m->id]);
        $majorsById = $majors->pluck('id', 'id');

        $imported = 0;
        $errors = [];
        $seenEmails = [];
        $seenNisns = [];

        foreach (array_slice($lines, 1) as $index => $line) {
            $rowNumber = $index + 2;

            if (trim($line) === '') {
                continue;
            }

            $values = str_getcsv($line, $delimiter);
            $row = [];
            foreach ($headers as $colIdx => $key) {
                if ($key !== '') {
                    $row[$key] = isset($values[$colIdx]) ? trim((string) $values[$colIdx]) : '';
                }
            }

            $majorInput = $row['major'] ?? '';
            $majorId = null;
            if (ctype_digit($majorInput) && $majorsById->has((int) $majorInput)) {
                $majorId = (int) $majorInput;
            } elseif ($majorsByName->has(mb_strtolower($majorInput))) {
                $majorId = $majorsByName->get(mb_strtolower($majorInput));
            }

            $emailLower = mb_strtolower($row['email'] ?? '');
            $nisn = $row['nisn'] ?? '';

            if ($emailLower !== '' && isset($seenEmails[$emailLower])) {
                $errors[] = "Baris {$rowNumber}: Email '{$row['email']}' duplikat di dalam file CSV.";

                continue;
            }

            if ($nisn !== '' && isset($seenNisns[$nisn])) {
                $errors[] = "Baris {$rowNumber}: NISN '{$nisn}' duplikat di dalam file CSV.";

                continue;
            }

            $passwordValue = ($row['password'] ?? '') !== ''
                ? $row['password']
                : (strlen($nisn) >= 8 ? $nisn : config('pkloka.default_student_password', 'password'));

            $validator = Validator::make([
                'name' => $row['name'] ?? '',
                'email' => $row['email'] ?? '',
                'nisn' => $nisn,
                'major_id' => $majorId,
                'school_name' => $row['school_name'] ?? '',
                'phone' => $row['phone'] ?? '',
                'address' => $row['address'] ?? '',
                'password' => $passwordValue,
            ], [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255', 'unique:users,email'],
                'nisn' => ['required', 'string', 'max:20', 'unique:student_profiles,nisn'],
                'major_id' => ['required', 'exists:majors,id'],
                'school_name' => ['nullable', 'string', 'max:255'],
                'phone' => ['nullable', 'string', 'max:30'],
                'address' => ['nullable', 'string', 'max:1000'],
                'password' => ['required', 'string', 'min:8'],
            ], [
                'major_id.required' => "Jurusan '{$majorInput}' tidak ditemukan di master jurusan.",
            ]);

            if ($validator->fails()) {
                $errors[] = "Baris {$rowNumber}: ".implode(' ', $validator->errors()->all());

                continue;
            }

            $validData = $validator->validated();

            DB::transaction(function () use ($validData) {
                $user = new User([
                    'name' => $validData['name'],
                    'email' => $validData['email'],
                    'password' => $validData['password'],
                ]);
                $user->role = User::ROLE_STUDENT;
                $user->approval_status = User::STATUS_APPROVED;
                $user->save();

                StudentProfile::create([
                    'user_id' => $user->id,
                    'nisn' => $validData['nisn'],
                    'school_name' => ($validData['school_name'] ?? null) ?: config('pkloka.school_name'),
                    'major_id' => $validData['major_id'],
                    'phone' => ($validData['phone'] ?? null) ?: null,
                    'address' => ($validData['address'] ?? null) ?: null,
                ]);
            });

            $seenEmails[$emailLower] = true;
            $seenNisns[$nisn] = true;
            $imported++;
        }

        if ($imported === 0 && ! empty($errors)) {
            return redirect()->route('admin.students.index')
                ->with('error', 'Tidak ada data siswa yang berhasil diimpor. Periksa daftar kesalahan di bawah.')
                ->with('import_errors', $errors);
        }

        $message = "Berhasil mengimpor {$imported} akun siswa.";
        if (! empty($errors)) {
            $message .= ' '.count($errors).' baris dilewati karena tidak valid.';
        }

        return redirect()->route('admin.students.index')
            ->with('success', $message)
            ->with('import_errors', $errors);
    }

    /** Ekspor rekap penempatan siswa (sudah ditempatkan vs belum ditempatkan) ke format CSV. */
    public function export(Request $request): StreamedResponse
    {
        $search = trim((string) $request->input('q', ''));
        $placement = $request->input('penempatan');
        $majorId = $request->input('major_id');

        $students = $this->filteredStudentsQuery($search, $placement, $majorId)->get();

        $suffix = match ($placement) {
            'sudah' => 'sudah-ditempatkan',
            'belum' => 'belum-ditempatkan',
            default => 'semua',
        };
        $filename = 'rekap-penempatan-siswa-'.$suffix.'-'.now()->format('Y-m-d').'.csv';

        return response()->streamDownload(function () use ($students) {
            $handle = fopen('php://output', 'w');
            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, [
                'No',
                'NISN',
                'Nama Siswa',
                'Email',
                'No. Kontak',
                'Jurusan',
                'Asal Sekolah',
                'Status Penempatan',
                'Perusahaan PKL',
                'Posisi Lowongan',
                'Tanggal Diterima',
                'Total Lamaran',
            ]);

            foreach ($students as $index => $student) {
                $acceptedApp = $student->applications->firstWhere('status', Application::STATUS_ACCEPTED);

                fputcsv($handle, [
                    $index + 1,
                    $this->safeCsvCell($student->nisn),
                    $this->safeCsvCell($student->user->name),
                    $this->safeCsvCell($student->user->email),
                    $this->safeCsvCell($student->phone ?? '-'),
                    $this->safeCsvCell($student->major->name),
                    $this->safeCsvCell($student->school_name ?? config('pkloka.school_name')),
                    $acceptedApp ? 'Sudah Ditempatkan' : 'Belum Ditempatkan',
                    $this->safeCsvCell($acceptedApp?->post?->company?->company_name ?? '-'),
                    $this->safeCsvCell($acceptedApp?->post?->title ?? '-'),
                    $acceptedApp?->application_date?->format('Y-m-d') ?? '-',
                    $student->applications_count,
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    private function filteredStudentsQuery(string $search, ?string $placement, mixed $majorId)
    {
        return StudentProfile::query()
            ->with([
                'user',
                'major',
                'applications' => fn ($q) => $q->accepted()->with('post.company'),
            ])
            ->withCount([
                'applications',
                'applications as accepted_count' => fn ($q) => $q->accepted(),
            ])
            ->when($search !== '', function ($q) use ($search) {
                $pattern = '%'.mb_strtolower($search).'%';
                $q->where(function ($sub) use ($pattern) {
                    $sub->whereRaw('LOWER(nisn) like ?', [$pattern])
                        ->orWhereHas('user', fn ($u) => $u->whereRaw('LOWER(name) like ?', [$pattern])
                            ->orWhereRaw('LOWER(email) like ?', [$pattern]));
                });
            })
            ->when($majorId, fn ($q) => $q->where('major_id', (int) $majorId))
            ->when($placement === 'sudah', fn ($q) => $q->whereHas('applications', fn ($a) => $a->accepted()))
            ->when($placement === 'belum', fn ($q) => $q->whereDoesntHave('applications', fn ($a) => $a->accepted()))
            ->latest();
    }

    private function normalizeCsvHeader(string $header): string
    {
        $clean = mb_strtolower(trim($header));
        $clean = str_replace([' ', '-'], '_', $clean);

        return match ($clean) {
            'nama', 'nama_lengkap', 'nama_siswa', 'name' => 'name',
            'email', 'alamat_email' => 'email',
            'nisn' => 'nisn',
            'jurusan', 'major', 'major_id', 'kompetensi_keahlian' => 'major',
            'sekolah', 'asal_sekolah', 'nama_sekolah', 'school_name' => 'school_name',
            'telepon', 'no_hp', 'no_telp', 'hp', 'kontak', 'phone' => 'phone',
            'alamat', 'address' => 'address',
            'kata_sandi', 'sandi', 'password' => 'password',
            default => $clean,
        };
    }

    private function safeCsvCell(string $value): string
    {
        if ($value !== '' && in_array($value[0], ['=', '+', '-', '@', "\t", "\r"], true)) {
            return "'".$value;
        }

        return $value;
    }
}
