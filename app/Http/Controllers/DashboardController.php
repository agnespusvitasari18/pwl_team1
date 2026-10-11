<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /** Mengarahkan pengguna ke halaman utama sesuai perannya. */
    public function __invoke(Request $request): RedirectResponse
    {
        $user = $request->user();

        return match (true) {
            $user->isAdmin() => redirect()->route('admin.dashboard'),
            $user->isCompany() => redirect()->route('company.posts.index'),
            default => redirect()->route('student.posts.index'),
        };
    }
}
