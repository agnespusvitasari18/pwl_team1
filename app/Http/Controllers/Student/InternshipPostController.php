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

        // Bawaan: lowongan sesuai jurusan siswa. Pilih "Semua" di filter (major_id kosong) untuk semua jurusan.
        $majorId = $request->has('major_id') ? $request->input('major_id') : $student->major_id;

        $posts = InternshipPost::query()
            ->open()
            ->whereHas('company.user', fn ($q) => $q->where('approval_status', User::STATUS_APPROVED))
            ->when($majorId, fn ($q) => $q->forMajor((int) $majorId))
            ->with(['company', 'major'])
            ->latest()
            ->paginate(9)
            ->withQueryString();

        return view('student.posts.index', [
            'posts' => $posts,
            'majors' => Major::orderBy('name')->get(),
            'selectedMajor' => $majorId,
            'appliedPostIds' => $student->applications()->pluck('post_id')->all(),
        ]);
    }

    public function show(Request $request, InternshipPost $post): View
    {
        $student = $this->student($request);

        $post->load(['company.user', 'major']);
        abort_unless($post->company->user->isApproved(), 404);

        return view('student.posts.show', [
            'post' => $post,
            'hasApplied' => $student->hasApplied($post),
            'remainingQuota' => $post->remainingQuota(),
        ]);
    }

    private function student(Request $request)
    {
        $student = $request->user()->studentProfile;
        abort_unless($student, 403, 'Profil siswa belum tersedia. Hubungi admin sekolah.');

        return $student;
    }
}
