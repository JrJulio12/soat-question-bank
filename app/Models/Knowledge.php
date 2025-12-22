<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Knowledge extends Model
{
    protected $table = 'knowledges';
    
    /** @use HasFactory<\Database\Factories\KnowledgeFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'unit_id',
        'name',
    ];

    /**
     * Get the unit that owns the knowledge.
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    /**
     * Get the bnccs for the knowledge.
     */
    public function bnccs(): BelongsToMany
    {
        return $this->belongsToMany(Bncc::class);
    }
}
