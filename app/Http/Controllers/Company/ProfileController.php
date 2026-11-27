<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

/** Perusahaan melengkapi dan memperbarui profilnya. */
class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        $company = $request->user()->companyProfile;
        abort_unless($company, 403, 'Profil perusahaan tidak ditemukan.');

        return view('company.profile.edit', compact('company'));
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();
        $company = $user->companyProfile;
        abort_unless($company, 403, 'Profil perusahaan tidak ditemukan.');

        $passwordRules = ['nullable', Password::min(8)];
        if ($request->filled('password_confirmation')) {
            $passwordRules[] = 'confirmed';
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'company_name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'address' => ['required', 'string', 'max:255'],
            'contact' => ['required', 'string', 'max:255'],
            'password' => $passwordRules,
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $user->name = $data['name'];
        if (! empty($data['password'])) {
            $user->password = $data['password'];
        }
        $user->save();

        $oldLogoPath = $company->logo_path;
        $newLogoPath = $request->file('logo')?->store('company-logos', 'public');

        $company->update([
            'company_name' => $data['company_name'],
            'description' => $data['description'] ?? null,
            'address' => $data['address'],
            'contact' => $data['contact'],
            'logo_path' => $newLogoPath ?? $oldLogoPath,
        ]);

        if ($newLogoPath && $oldLogoPath) {
            Storage::disk('public')->delete($oldLogoPath);
        }

        return back()->with('success', 'Profil perusahaan berhasil diperbarui.');
    }
}
