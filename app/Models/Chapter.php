<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Chapter extends Model
{
    /** @use HasFactory<\Database\Factories\ChapterFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'topic_id',
    ];

    /**
     * Get the topic that owns the chapter.
     */
    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }

    /**
     * Get the subjects for the chapter.
     */
    public function subjects(): HasMany
    {
        return $this->hasMany(Subject::class);
    }
}


