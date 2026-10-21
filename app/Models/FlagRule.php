<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FlagRule extends Model
{
    use HasFactory;

    protected $fillable = ['feature_flag_id', 'attribute', 'operator', 'values'];

    protected $casts = [
        'values' => 'array', // Automatically casts JSON column to PHP array
    ];

    public function featureFlag(): BelongsTo
    {
        return $this->belongsTo(FeatureFlag::class);
    }
}
