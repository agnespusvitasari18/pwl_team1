<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
        $company = $request->user()->companyProfile;
        abort_unless($company, 403, 'Profil perusahaan tidak ditemukan.');

        $data = $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'address' => ['required', 'string', 'max:255'],
            'contact' => ['required', 'string', 'max:255'],
        ]);

        $company->update($data);

        return back()->with('success', 'Profil perusahaan diperbarui.');
    }
}
