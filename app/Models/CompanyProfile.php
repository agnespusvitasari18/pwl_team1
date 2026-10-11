<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class CompanyProfile extends Model
{
    protected $fillable = [
        'user_id',
        'company_name',
        'description',
        'address',
        'contact',
    ];

    // ---------- Relasi ----------

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function internshipPosts(): HasMany
    {
        return $this->hasMany(InternshipPost::class, 'company_id');
    }

    /**
     * Semua lamaran yang masuk ke lowongan milik perusahaan ini.
     */
    public function applications(): HasManyThrough
    {
        return $this->hasManyThrough(
            Application::class,
            InternshipPost::class,
            'company_id', // FK di internship_posts
            'post_id',    // FK di applications
        );
    }
}