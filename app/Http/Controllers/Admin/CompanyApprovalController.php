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
        if (! in_array($status, ['pending', 'approved', 'rejected', 'all'], true)) {
            $status = User::STATUS_PENDING;
        }

        $countsByStatus = User::query()
            ->where('role', User::ROLE_COMPANY)
            ->selectRaw('approval_status, count(*) as total')
            ->groupBy('approval_status')
            ->pluck('total', 'approval_status');

        $statusCounts = [
            'pending' => (int) ($countsByStatus[User::STATUS_PENDING] ?? 0),
            'approved' => (int) ($countsByStatus[User::STATUS_APPROVED] ?? 0),
            'rejected' => (int) ($countsByStatus[User::STATUS_REJECTED] ?? 0),
            'all' => (int) $countsByStatus->sum(),
        ];

        $companies = User::query()
            ->where('role', User::ROLE_COMPANY)
            ->when($status !== 'all', fn ($q) => $q->where('approval_status', $status))
            ->with('companyProfile')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.companies.index', compact('companies', 'status', 'statusCounts'));
    }

    public function approve(User $user): RedirectResponse
    {
        abort_unless($user->isCompany(), 404);

        $user->approval_status = User::STATUS_APPROVED;
        $user->save();

        // Bersihkan alasan penolakan sebelumnya jika perusahaan disetujui kembali.
        if ($user->companyProfile && $user->companyProfile->rejection_reason !== null) {
            $user->companyProfile->update(['rejection_reason' => null]);
        }

        return back()->with('success', 'Perusahaan disetujui dan kini dapat masuk ke sistem.');
    }

    public function reject(Request $request, User $user): RedirectResponse
    {
        abort_unless($user->isCompany(), 404);

        $data = $request->validate([
            'rejection_reason' => ['required', 'string', 'min:5', 'max:1000'],
        ], [
            'rejection_reason.required' => 'Alasan penolakan perusahaan wajib diisi.',
            'rejection_reason.min' => 'Alasan penolakan minimal 5 karakter agar jelas bagi perusahaan.',
        ]);

        $user->approval_status = User::STATUS_REJECTED;
        $user->save();

        if ($user->companyProfile) {
            $user->companyProfile->update([
                'rejection_reason' => trim($data['rejection_reason']),
            ]);
        }

        return back()->with('success', 'Perusahaan ditolak beserta alasan penolakannya.');
    }
}
