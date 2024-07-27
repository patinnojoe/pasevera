<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralAnalysis extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'completed_tasks',
        'current_streak',
        'longest_streak',
        'status_ranking',
        'current_badge',
        'username'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
