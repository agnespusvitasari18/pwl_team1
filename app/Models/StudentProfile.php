<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StudentProfile extends Model
{
    protected $fillable = [
        'user_id',
        'nisn',
        'school_name',
        'major_id',
        'address',
    ];

    // ---------- Relasi ----------

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function major(): BelongsTo
    {
        return $this->belongsTo(Major::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class, 'student_id');
    }

    /**
     * Lowongan yang pernah dilamar siswa ini (lewat tabel applications).
     */
    public function internshipPosts(): BelongsToMany
    {
        return $this->belongsToMany(InternshipPost::class, 'applications', 'student_id', 'post_id')
            ->withPivot(['application_date', 'status'])
            ->withTimestamps();
    }

    // ---------- Helper ----------

    public function hasApplied(InternshipPost $post): bool
    {
        return $this->applications()->where('post_id', $post->id)->exists();
    }
}