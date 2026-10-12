<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/** Admin menyetujui atau menolak akun perusahaan yang mendaftar. */
class CompanyApprovalController extends Controller
{
    public function index(Request $request): View
    {
        // Default menampilkan yang menunggu persetujuan. ?status=all untuk semua.
        $status = $request->input('status', User::STATUS_PENDING);

        $companies = User::query()
            ->where('role', User::ROLE_COMPANY)
            ->when($status !== 'all', fn ($q) => $q->where('approval_status', $status))
            ->with('companyProfile')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.companies.index', compact('companies', 'status'));
    }

    public function approve(User $user): RedirectResponse
    {
        return $this->setStatus($user, User::STATUS_APPROVED, 'Perusahaan disetujui.');
    }

    public function reject(Request $request, User $user): RedirectResponse
    {
        abort_unless($user->isCompany(), 404);

        $user->approval_status = User::STATUS_REJECTED;
        $user->save();

        if ($user->companyProfile && $request->filled('rejection_reason')) {
            $user->companyProfile->update([
                'rejection_reason' => $request->input('rejection_reason'),
            ]);
        }

        return back()->with('success', 'Perusahaan ditolak.');
    }

    private function setStatus(User $user, string $status, string $message): RedirectResponse
    {
        abort_unless($user->isCompany(), 404);

        // approval_status sengaja tidak lewat mass assignment.
        $user->approval_status = $status;
        $user->save();

        return back()->with('success', $message);
    }
}
