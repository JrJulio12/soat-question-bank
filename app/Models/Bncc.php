
<?php

namespace App\Models;

use App\Enums\Stage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
}
