<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug'];

    public function environments(): HasMany
    {
        return $this->hasMany(Environment::class);
    }

    public function featureFlags(): HasMany
    {
        return $this->hasMany(FeatureFlag::class);
    }
}
