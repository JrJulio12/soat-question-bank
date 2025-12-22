<?php

namespace App\Models;

use App\Enums\Stage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Bncc extends Model
{
    /** @use HasFactory<\Database\Factories\BnccFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'stage',
        'code',
        'description',
        'discipline_id',
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
        ];
    }

    /**
     * Get the series associated with the bncc.
     */
    public function series(): BelongsToMany
    {
        return $this->belongsToMany(Serie::class);
    }

    /**
     * Get the discipline that owns the bncc.
     */
    public function discipline(): BelongsTo
    {
        return $this->belongsTo(Discipline::class);
    }

    /**
     * Get the knowledges associated with the bncc.
     */
    public function knowledges(): BelongsToMany
    {
        return $this->belongsToMany(Knowledge::class);
    }

    /**
     * Get all units from all associated knowledges (for EF stage).
     */
    public function units()
    {
        return $this->knowledges()
            ->with('unit')
            ->get()
            ->pluck('unit')
            ->filter()
            ->unique('id')
            ->values();
    }

    /**
     * Get the questions associated with the BNCC.
     */
    public function questions(): BelongsToMany
    {
        return $this->belongsToMany(Question::class);
    }
}
