<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CalendarController extends Controller
{
    public function index()
    {
        $tasks = Auth::user()->tasks()
            ->select('id', 'title', 'description', 'due_date', 'priority', 'completed')
            ->get()
            ->map(function ($task) {
                return [
                    'id' => $task->id,
                    'title' => $task->title,
                    'description' => $task->description,
                    'start' => $task->due_date ? $task->due_date->format('Y-m-d') : null,
                    'color' => $this->getPriorityColor($task->priority),
                    'priority' => $task->priority,
                    'completed' => $task->completed,
                    'extendedProps' => [
                        'description' => $task->description,
                        'priority' => $task->priority
                    ]
                ];
            });

        return view('calendar.index', [
            'tasks' => $tasks,
            'pendingCount' => Auth::user()->tasks()->where('completed', false)->count(),
            'completedCount' => Auth::user()->tasks()->where('completed', true)->count()
        ]);
    }

    private function getPriorityColor($priority)
    {
        return match ($priority) {
            'high' => '#ef4444',
            'medium' => '#f59e0b',
            'low' => '#10b981',
            default => '#6366f1',
        };
    }
}