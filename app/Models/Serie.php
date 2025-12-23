<?php

namespace App\Models;

use App\Enums\Stage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Serie extends Model
{
    /** @use HasFactory<\Database\Factories\SerieFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'stage',
        'name',
        'order',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'stage' => Stage::class,
            'order' => 'integer',
        ];
    }

    /**
     * Get the bnccs for the serie.
     */
    public function bnccs(): BelongsToMany
    {
        return $this->belongsToMany(Bncc::class);
    }
}

