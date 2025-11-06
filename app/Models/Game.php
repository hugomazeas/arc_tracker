<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Game extends Model
{
    protected $fillable = [
        'player_id',
        'arrow_data',
        'base_score',
        'bonus_score',
        'total_score',
    ];

    protected $casts = [
        'arrow_data' => 'array',
    ];

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }
}
