<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\StudentProfile;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Email atau NISN wajib diisi.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        $login = trim($validated['email']);
        $throttleKey = mb_strtolower($login).'|'.$request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 10)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            return back()
                ->withErrors(['email' => "Terlalu banyak percobaan masuk. Coba lagi dalam {$seconds} detik."])
                ->onlyInput('email');
        }

        // Dukung login menggunakan email (semua peran) atau NISN (siswa).
        $user = $this->resolveUser($login);

        if (! $user || ! Auth::attempt(['email' => $user->email, 'password' => $validated['password']], $request->boolean('remember'))) {
            RateLimiter::hit($throttleKey, 60);

            return back()
                ->withErrors(['email' => 'Email/NISN atau kata sandi salah.'])
                ->onlyInput('email');
        }

        RateLimiter::clear($throttleKey);
        $user = Auth::user();

        // Perusahaan baru harus disetujui admin sekolah sebelum bisa masuk.
        if (! $user->isApproved()) {
            $reason = null;

            if ($user->approval_status === User::STATUS_PENDING) {
                $message = 'Akun perusahaan Anda masih menunggu persetujuan admin sekolah.';
            } else {
                $user->loadMissing('companyProfile');
                $reason = $user->companyProfile?->rejection_reason;
                $message = $reason
                    ? 'Pendaftaran perusahaan Anda ditolak oleh admin sekolah. Alasan: '.$reason
                    : 'Pendaftaran akun Anda ditolak oleh admin sekolah.';
            }

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            $response = back()->withErrors(['email' => $message])->onlyInput('email');

            if ($reason) {
                $response->with('rejection_reason', $reason);
            }

            return $response;
        }

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    private function resolveUser(string $login): ?User
    {
        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            return User::whereRaw('LOWER(email) = ?', [mb_strtolower($login)])->first();
        }

        $studentProfile = StudentProfile::where('nisn', $login)->with('user')->first();

        return $studentProfile?->user;
    }
}
