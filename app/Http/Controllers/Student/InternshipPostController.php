<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\InternshipPost;
use App\Models\Major;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

/** Siswa mencari dan melihat lowongan PKL (hanya dari perusahaan yang sudah disetujui). */
class InternshipPostController extends Controller
{
    public function index(Request $request): View
    {
        $student = $this->student($request);

        // Bawaan: lowongan sesuai jurusan siswa jika tidak ada parameter khusus.
        // Jika parameter major_id = 'all' atau '', tampilkan semua.
        $requestedMajor = $request->query('major_id');
        $selectedMajor = null;

        if ($request->has('major_id')) {
            $selectedMajor = ($requestedMajor === 'all' || $requestedMajor === '') ? null : (int) $requestedMajor;
        } else {
            $selectedMajor = $student->major_id;
        }

        $search = trim((string) $request->input('q', ''));

        $posts = InternshipPost::query()
            ->open()
            ->whereHas('company.user', fn ($q) => $q->where('approval_status', User::STATUS_APPROVED))
            ->when($selectedMajor, fn ($q) => $q->forMajor($selectedMajor))
            ->when($search !== '', function ($q) use ($search) {
                $pattern = '%'.mb_strtolower($search).'%';
                $q->where(function ($sub) use ($pattern) {
                    $sub->whereRaw('LOWER(title) like ?', [$pattern])
                        ->orWhereRaw('LOWER(description) like ?', [$pattern])
                        ->orWhereHas('company', fn ($c) => $c->whereRaw('LOWER(company_name) like ?', [$pattern]));
                });
            })
            ->with(['company', 'major'])
            ->withCount(['applications as accepted_count' => fn ($q) => $q->accepted()])
            ->latest()
            ->paginate(9)
            ->withQueryString();

        $isAccepted = $student->applications()->accepted()->exists();

        return view('student.posts.index', [
            'student' => $student,
            'posts' => $posts,
            'majors' => Major::orderBy('name')->get(),
            'selectedMajor' => $selectedMajor,
            'search' => $search,
            'appliedPostIds' => $student->applications()->pluck('post_id')->all(),
            'isAccepted' => $isAccepted,
        ]);
    }

    public function show(Request $request, InternshipPost $post): View
    {
        $student = $this->student($request);

        $post->load(['company.user', 'major']);
        abort_unless($post->company->user->isApproved(), 404);

        return view('student.posts.show', [
            'post' => $post,
            'student' => $student,
            'hasApplied' => $student->hasApplied($post),
            'remainingQuota' => $post->remainingQuota(),
            'isAccepted' => $student->applications()->accepted()->exists(),
        ]);
    }

    private function student(Request $request)
    {
        $student = $request->user()->studentProfile;
        abort_unless($student, 403, 'Profil siswa belum tersedia. Hubungi admin sekolah.');

        return $student;
    }
}
