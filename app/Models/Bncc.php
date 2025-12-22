<?php

namespace App\Models;

use App\Enums\Stage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use InvalidArgumentException;
use Znck\Eloquent\Traits\BelongsToThrough;

class Bncc extends Model
{
    /** @use HasFactory<\Database\Factories\BnccFactory> */
    use HasFactory;
    use BelongsToThrough;

    /**
     * Boot the model.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::saving(function (Bncc $bncc) {
            $stage = $bncc->stage;

            if ($stage === Stage::EF) {
                // EF stage must not have competence_id
                if ($bncc->competence_id !== null) {
                    throw new InvalidArgumentException('Bncc with stage EF must not have a competence_id.');
                }
            } elseif ($stage === Stage::EM) {
                // EM stage requires competence_id
                if ($bncc->competence_id === null) {
                    throw new InvalidArgumentException('Bncc with stage EM must have a competence_id.');
                }
            }
        });
    }

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
        'competence_id',
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
     * Get the competence that owns the bncc.
     */
    public function competence(): BelongsTo
    {
        return $this->belongsTo(Competence::class);
    }

    /**
     * Get the area through competence (for EM stage).
     */
    public function area()
    {
        return $this->belongsToThrough(Area::class, Competence::class);
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
