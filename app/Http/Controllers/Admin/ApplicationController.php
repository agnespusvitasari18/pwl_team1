<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Major;
use Illuminate\Http\Request;
use Illuminate\View\View;

/** Admin memantau seluruh pengajuan PKL siswa di sistem. */
class ApplicationController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->input('q', ''));
        $status = $request->input('status', 'all'); // 'all', 'pending', 'accepted', 'rejected'
        $majorId = $request->input('major_id');

        $applications = Application::query()
            ->with(['student.user', 'student.major', 'post.company'])
            ->when($status !== 'all', fn ($q) => $q->where('status', $status))
            ->when($majorId, fn ($q) => $q->whereHas('student', fn ($s) => $s->where('major_id', (int) $majorId)))
            ->when($search !== '', function ($q) use ($search) {
                $pattern = '%'.mb_strtolower($search).'%';
                $q->where(function ($sub) use ($pattern) {
                    $sub->whereHas('student.user', fn ($u) => $u->whereRaw('LOWER(name) like ?', [$pattern]))
                        ->orWhereHas('student', fn ($s) => $s->whereRaw('LOWER(nisn) like ?', [$pattern]))
                        ->orWhereHas('post', fn ($p) => $p->whereRaw('LOWER(title) like ?', [$pattern]))
                        ->orWhereHas('post.company', fn ($c) => $c->whereRaw('LOWER(company_name) like ?', [$pattern]));
                });
            })
            ->latest('applications.created_at')
            ->paginate(15)
            ->withQueryString();

        $majors = Major::orderBy('name')->get();

        return view('admin.applications.index', compact('applications', 'majors', 'search', 'status', 'majorId'));
    }
}
