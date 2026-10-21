<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FeatureFlag extends Model
{
    use HasFactory;

    protected $fillable = ['project_id', 'name', 'key', 'is_enabled'];

    protected $casts = [
        'is_enabled' => 'boolean',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function rules(): HasMany
    {
        return $this->hasMany(FlagRule::class);
    }
}
