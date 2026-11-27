<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InternshipPost;
use App\Models\Major;
use Illuminate\Http\Request;
use Illuminate\View\View;

/** Admin memantau seluruh lowongan PKL dari seluruh perusahaan. */
class InternshipPostController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->input('q', ''));
        $majorId = $request->input('major_id');
        $status = $request->input('status', 'all'); // 'all', 'open', 'closed'

        $posts = InternshipPost::query()
            ->with(['company.user', 'major'])
            ->withCount([
                'applications',
                'applications as accepted_count' => fn ($q) => $q->accepted(),
            ])
            ->when($search !== '', function ($q) use ($search) {
                $pattern = '%'.mb_strtolower($search).'%';
                $q->where(function ($sub) use ($pattern) {
                    $sub->whereRaw('LOWER(title) like ?', [$pattern])
                        ->orWhereHas('company', fn ($c) => $c->whereRaw('LOWER(company_name) like ?', [$pattern]));
                });
            })
            ->when($majorId, fn ($q) => $q->where('major_id', (int) $majorId))
            ->when($status === 'open', fn ($q) => $q->open())
            ->when($status === 'closed', fn ($q) => $q->whereDate('deadline', '<', today()))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $majors = Major::orderBy('name')->get();

        return view('admin.posts.index', compact('posts', 'majors', 'search', 'majorId', 'status'));
    }
}
