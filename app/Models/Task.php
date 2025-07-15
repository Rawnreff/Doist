<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'completed',
        'due_date',
        'priority'
    ];

    protected $casts = [
        'completed' => 'boolean',
        'due_date' => 'date'
    ];

    protected $attributes = [
        'completed' => false,
        'priority' => 'medium'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}