<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAnalysis extends Model
{
    use HasFactory;
    protected $table = 'user_analysis';
    protected $fillable = [
        'user_id',
        'current_badge',
        'current_streak',
        'longest_streak',
        'tasks_completed',
        'most_productive_day',
        'status_ranking',
        'description',
        'qualification'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
