<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\CompanyProfile;
use App\Models\InternshipPost;
use App\Models\Major;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/** Perusahaan menambah, mengubah, dan menghapus lowongan PKL miliknya. */
class InternshipPostController extends Controller
{
    public function index(Request $request): View
    {
        $company = $this->company($request);

        $posts = $company->internshipPosts()
            ->with('major')
            ->withCount('applications')
            ->latest()
            ->paginate(10);

        return view('company.posts.index', compact('posts'));
    }

    public function create(): View
    {
        return view('company.posts.create', ['majors' => Major::orderBy('name')->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $company = $this->company($request);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'major_id' => ['required', 'exists:majors,id'],
            'description' => ['required', 'string'],
            'quota' => ['required', 'integer', 'min:1'],
            'deadline' => ['required', 'date', 'after_or_equal:today'],
        ]);

        $company->internshipPosts()->create($data);

        return redirect()->route('company.posts.index')->with('success', 'Lowongan berhasil dibuat.');
    }

    public function edit(Request $request, InternshipPost $post): View
    {
        $this->authorizePost($request, $post);

        return view('company.posts.edit', [
            'post' => $post,
            'majors' => Major::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, InternshipPost $post): RedirectResponse
    {
        $this->authorizePost($request, $post);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'major_id' => ['required', 'exists:majors,id'],
            'description' => ['required', 'string'],
            // Kuota tidak boleh lebih kecil dari jumlah siswa yang sudah diterima.
            'quota' => ['required', 'integer', 'min:'.max(1, $post->acceptedCount())],
            'deadline' => ['required', 'date'],
        ]);

        $post->update($data);

        return redirect()->route('company.posts.index')->with('success', 'Lowongan diperbarui.');
    }

    public function destroy(Request $request, InternshipPost $post): RedirectResponse
    {
        $this->authorizePost($request, $post);

        $post->delete(); // pendaftaran terkait ikut terhapus (cascade)

        return redirect()->route('company.posts.index')->with('success', 'Lowongan dihapus.');
    }

    private function company(Request $request): CompanyProfile
    {
        $company = $request->user()->companyProfile;
        abort_unless($company, 403, 'Profil perusahaan tidak ditemukan.');

        return $company;
    }

    /** Perusahaan hanya boleh mengubah lowongannya sendiri. */
    private function authorizePost(Request $request, InternshipPost $post): void
    {
        abort_unless($post->company_id === $this->company($request)->id, 403);
    }
}
