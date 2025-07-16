<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CalendarController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $tasks = Auth::user()->tasks()
            ->select('id', 'title', 'due_date', 'priority', 'completed')
            ->get()
            ->map(function ($task) {
                return [
                    'id' => $task->id,
                    'title' => $task->title,
                    'start' => $task->due_date ? $task->due_date->format('Y-m-d') : null,
                    'color' => $this->getPriorityColor($task->priority),
                    'completed' => $task->completed
                ];
            });

        return view('calendar.index', compact('tasks'));
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