<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Application extends Model
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_ACCEPTED = 'accepted';
    public const STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'student_id',
        'post_id',
        'application_date',
        'status',
        'resume_file',
        'portfolio_url',
        'notes',
        'rejection_reason',
    ];

    protected function casts(): array
    {
        return [
            'application_date' => 'date',
        ];
    }

    // ---------- Relasi ----------

    public function student(): BelongsTo
    {
        return $this->belongsTo(StudentProfile::class, 'student_id');
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(InternshipPost::class, 'post_id');
    }

    // ---------- Scope ----------

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeAccepted(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_ACCEPTED);
    }

    public function scopeRejected(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_REJECTED);
    }
}