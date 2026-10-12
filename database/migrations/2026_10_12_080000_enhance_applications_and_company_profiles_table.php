<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->string('resume_file')->nullable()->after('application_date');
            $table->string('portfolio_url')->nullable()->after('resume_file');
            $table->text('notes')->nullable()->after('portfolio_url');
            $table->text('rejection_reason')->nullable()->after('status');
        });

        Schema::table('company_profiles', function (Blueprint $table) {
            $table->text('rejection_reason')->nullable()->after('contact');
        });
    }

    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn(['resume_file', 'portfolio_url', 'notes', 'rejection_reason']);
        });

        Schema::table('company_profiles', function (Blueprint $table) {
            $table->dropColumn(['rejection_reason']);
        });
    }
};
