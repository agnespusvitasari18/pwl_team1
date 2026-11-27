<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@pkloka.test'],
            [
                'name' => 'Admin '.config('pkloka.school_name'),
                'password' => 'password',
                'role' => User::ROLE_ADMIN,
                'approval_status' => User::STATUS_APPROVED,
            ]
        );
    }
}
