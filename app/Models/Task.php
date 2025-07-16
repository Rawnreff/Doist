<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

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

    // Priority constants
    const PRIORITY_LOW = 'low';
    const PRIORITY_MEDIUM = 'medium';
    const PRIORITY_HIGH = 'high';

    public static function getPriorityOptions()
    {
        return [
            self::PRIORITY_LOW => 'Low',
            self::PRIORITY_MEDIUM => 'Medium',
            self::PRIORITY_HIGH => 'High'
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scope for active tasks (not deleted)
    public function scopeActive($query)
    {
        return $query->whereNull('deleted_at');
    }

    // Scope for deleted tasks
    public function scopeTrashed($query)
    {
        return $query->whereNotNull('deleted_at');
    }

    // Scope for completed tasks
    public function scopeCompleted($query)
    {
        return $query->where('completed', true);
    }

    // Scope for pending tasks
    public function scopePending($query)
    {
        return $query->where('completed', false);
    }

    // Scope for overdue tasks
    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
                    ->where('completed', false);
    }

    // Check if task is overdue
    public function isOverdue()
    {
        return $this->due_date && 
               $this->due_date->isPast() && 
               !$this->completed;
    }

    // Get the time elapsed since deletion
    public function getTimeSinceDeletion()
    {
        return $this->deleted_at 
            ? Carbon::now()->diffForHumans($this->deleted_at) 
            : null;
    }

    // Get days remaining until permanent deletion (30 days after soft delete)
    public function getDaysUntilPermanentDeletion()
    {
        if (!$this->deleted_at) return null;
        
        $permanentDeletionDate = $this->deleted_at->addDays(30);
        return now()->diffInDays($permanentDeletionDate, false);
    }

    // Get priority color for UI
    public function getPriorityColor()
    {
        return match($this->priority) {
            self::PRIORITY_HIGH => 'red',
            self::PRIORITY_MEDIUM => 'yellow',
            self::PRIORITY_LOW => 'green',
            default => 'gray',
        };
    }

    // Restore the task
    public function restoreTask()
    {
        return $this->restore();
    }

    // Permanently delete the task
    public function permanentDelete()
    {
        return $this->forceDelete();
    }
}   