<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FreeQuestion extends Model
{
    protected $fillable = [
        'free_exam_id',
        'extract',
        'question',
        'choiceA',
        'choiceB',
        'choiceC',
        'choiceD',
        'choiceE',
        'choiceF',
        'choiceG',
        'correctAnswer',
        'rationale',
        'image',
        'qtype',
        'heading',
        'slug',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function exam(): BelongsTo
    {
        return $this->belongsTo(FreeExam::class, 'free_exam_id');
    }
}
