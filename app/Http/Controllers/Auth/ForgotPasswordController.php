<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\StudentProfile;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class ForgotPasswordController extends Controller
{
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'string', 'max:255'],
        ], [
            'email.required' => 'Masukkan alamat email atau NISN akun Anda.',
        ]);

        $identifier = trim($validated['email']);
        $email = $this->resolveEmail($identifier);

        if (! $email) {
            return back()
                ->withErrors(['email' => 'Akun dengan email atau NISN tersebut tidak ditemukan.'])
                ->onlyInput('email');
        }

        $status = Password::sendResetLink(['email' => $email]);

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('status', 'Tautan atur ulang kata sandi telah dikirim ke email terdaftar ('.$this->maskEmail($email).'). Silakan periksa kotak masuk atau log email.');
        }

        return back()
            ->withErrors(['email' => __($status)])
            ->onlyInput('email');
    }

    private function resolveEmail(string $identifier): ?string
    {
        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            return User::whereRaw('LOWER(email) = ?', [mb_strtolower($identifier)])->value('email');
        }

        return StudentProfile::where('nisn', $identifier)
            ->with('user')
            ->first()
            ?->user
            ?->email;
    }

    private function maskEmail(string $email): string
    {
        [$local, $domain] = explode('@', $email, 2);
        $visible = mb_substr($local, 0, min(2, mb_strlen($local)));

        return $visible.'***@'.$domain;
    }
}
