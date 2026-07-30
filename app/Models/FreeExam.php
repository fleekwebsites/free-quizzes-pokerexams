<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FreeExam extends Model
{
    protected $fillable = [
        'subdivision_id',
        'course_id',
        'subject_id',
        'slug',
        'title',
        'question_count',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function subdivision(): BelongsTo
    {
        return $this->belongsTo(Subdivision::class, 'subdivision_id');
    }

    public function questions(): HasMany
    {
        return $this->hasMany(FreeQuestion::class, 'free_exam_id')->orderBy('id');
    }
}
