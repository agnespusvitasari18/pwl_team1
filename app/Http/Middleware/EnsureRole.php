<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Pembatas akses berdasarkan peran (RBAC).
 * Pemakaian di route: ->middleware('role:admin') atau ->middleware('role:admin,company')
 */
class EnsureRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        // Akun yang belum/tidak disetujui admin (mis. perusahaan ditolak saat sedang login) dikeluarkan.
        if (! $user->isApproved()) {
            $reason = null;

            if ($user->approval_status === User::STATUS_REJECTED) {
                $user->loadMissing('companyProfile');
                $reason = $user->companyProfile?->rejection_reason;
                $message = $reason
                    ? 'Pendaftaran perusahaan Anda ditolak oleh admin sekolah. Alasan: '.$reason
                    : 'Pendaftaran akun Anda ditolak oleh admin sekolah.';
            } else {
                $message = 'Akun Anda belum disetujui oleh admin sekolah.';
            }

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            $redirect = redirect()->route('login')->withErrors(['email' => $message]);

            if ($reason) {
                $redirect->with('rejection_reason', $reason);
            }

            return $redirect;
        }

        if (! in_array($user->role, $roles, true)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
