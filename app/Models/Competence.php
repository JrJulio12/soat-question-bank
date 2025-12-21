<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Competence extends Model
{
    /** @use HasFactory<\Database\Factories\CompetenceFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'area_id',
        'code',
        'description',
    ];

    /**
     * Get the area that owns the competence.
     */
    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    /**
     * Get the bnccs for the competence.
     */
    public function bnccs(): HasMany
    {
        return $this->hasMany(Bncc::class);
    }
}
