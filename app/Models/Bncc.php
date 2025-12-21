<?php

namespace App\Models;

use App\Enums\Stage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
                // EF stage requires knowledge_id and must not have competence_id
                if ($bncc->knowledge_id === null) {
                    throw new InvalidArgumentException('Bncc with stage EF must have a knowledge_id.');
                }
                if ($bncc->competence_id !== null) {
                    throw new InvalidArgumentException('Bncc with stage EF must not have a competence_id.');
                }
            } elseif ($stage === Stage::EM) {
                // EM stage requires competence_id and must not have knowledge_id
                if ($bncc->competence_id === null) {
                    throw new InvalidArgumentException('Bncc with stage EM must have a competence_id.');
                }
                if ($bncc->knowledge_id !== null) {
                    throw new InvalidArgumentException('Bncc with stage EM must not have a knowledge_id.');
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
        'serie_id',
        'discipline_id',
        'knowledge_id',
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
     * Get the serie that owns the bncc.
     */
    public function serie(): BelongsTo
    {
        return $this->belongsTo(Serie::class);
    }

    /**
     * Get the discipline that owns the bncc.
     */
    public function discipline(): BelongsTo
    {
        return $this->belongsTo(Discipline::class);
    }

    /**
     * Get the knowledge that owns the bncc.
     */
    public function knowledge(): BelongsTo
    {
        return $this->belongsTo(Knowledge::class);
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
     * Get the unit through knowledge (for EF stage).
     */
    public function unit()
    {
        return $this->belongsToThrough(Unit::class, Knowledge::class);
    }
}
