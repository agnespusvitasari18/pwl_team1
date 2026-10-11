<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\CompanyProfile;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

/** Registrasi khusus perusahaan. Akun siswa dibuat oleh admin, bukan lewat form ini. */
class RegisterCompanyController extends Controller
{
    public function create(): View
    {
        return view('auth.register-company');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'company_name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'address' => ['required', 'string', 'max:255'],
            'contact' => ['required', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($data) {
            // role dan approval_status diisi eksplisit di sini, bukan dari input form.
            $user = new User([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'], // di-hash otomatis oleh cast 'hashed' di User
            ]);
            $user->role = User::ROLE_COMPANY;
            $user->approval_status = User::STATUS_PENDING;
            $user->save();

            CompanyProfile::create([
                'user_id' => $user->id,
                'company_name' => $data['company_name'],
                'description' => $data['description'] ?? null,
                'address' => $data['address'],
                'contact' => $data['contact'],
            ]);
        });

        return redirect()->route('login')
            ->with('status', 'Pendaftaran berhasil. Akun Anda menunggu persetujuan admin sekolah.');
    }
}
