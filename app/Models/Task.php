<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_name',
        'task_status',
        'task',
        'user_id'

    ];
    public function  userTask(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
