<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subdivision extends Model
{
    protected $table = 'schools';

    public $timestamps = false;

    protected $fillable = [
        'schoolname',
        'slug',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function freeExams(): HasMany
    {
        return $this->hasMany(FreeExam::class, 'subdivision_id');
    }
}
