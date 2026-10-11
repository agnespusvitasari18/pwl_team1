<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InternshipPost extends Model
{
    protected $fillable = [
        'company_id',
        'title',
        'major_id',
        'description',
        'quota',
        'deadline',
    ];

    protected function casts(): array
    {
        return [
            'quota' => 'integer',
            'deadline' => 'date',
        ];
    }

    // ---------- Relasi ----------

    public function company(): BelongsTo
    {
        return $this->belongsTo(CompanyProfile::class, 'company_id');
    }

    public function major(): BelongsTo
    {
        return $this->belongsTo(Major::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class, 'post_id');
    }

    public function applicants(): BelongsToMany
    {
        return $this->belongsToMany(StudentProfile::class, 'applications', 'post_id', 'student_id')
            ->withPivot(['application_date', 'status'])
            ->withTimestamps();
    }

    // ---------- Scope ----------

    /** Lowongan yang deadline-nya belum lewat. */
    public function scopeOpen(Builder $query): Builder
    {
        return $query->whereDate('deadline', '>=', today());
    }

    public function scopeForMajor(Builder $query, int $majorId): Builder
    {
        return $query->where('major_id', $majorId);
    }

    // ---------- Helper ----------

    public function isOpen(): bool
    {
        return $this->deadline->endOfDay()->isFuture();
    }

    public function acceptedCount(): int
    {
        return $this->applications()->where('status', Application::STATUS_ACCEPTED)->count();
    }

    public function remainingQuota(): int
    {
        return max(0, $this->quota - $this->acceptedCount());
    }

    public function hasQuota(): bool
    {
        return $this->remainingQuota() > 0;
    }
}