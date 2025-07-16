<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $query = Auth::user()->tasks();
        
        // Filter by completion status
        if ($request->has('status')) {
            $status = $request->status;
            if ($status === 'completed') {
                $query->where('completed', true);
            } elseif ($status === 'pending') {
                $query->where('completed', false);
            }
        }
        
        // Filter by priority
        if ($request->has('priority') && in_array($request->priority, ['low', 'medium', 'high'])) {
            $query->where('priority', $request->priority);
        }
        
        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        // Sorting
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'oldest':
                $query->oldest();
                break;
            case 'due_date':
                $query->orderBy('due_date');
                break;
            case 'priority':
                $query->orderByRaw("FIELD(priority, 'high', 'medium', 'low')");
                break;
            case 'title':
                $query->orderBy('title');
                break;
            default:
                $query->latest();
        }
        
        $tasks = $query->get();
        
        return view('tasks.index', compact('tasks'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date|after_or_equal:today',
            'priority' => 'required|in:low,medium,high'
        ]);

        Auth::user()->tasks()->create($validated);

        return redirect()->route('tasks.index')->with('success', 'Task created successfully!');
    }

    public function edit(Task $task)
    {
        $this->authorize('update', $task);
        return view('tasks.edit', compact('task'));
    }

    public function update(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        // Handle checkbox update
        if ($request->has('completed')) {
            $request->validate(['completed' => 'boolean']);
            $task->update(['completed' => $request->completed]);
            return back()->with('success', 'Task status updated!');
        }

        // Handle full task update
        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date|after_or_equal:today',
            'priority' => 'required|in:low,medium,high'
        ]);

        $task->update($validated);

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully!');
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);
        $task->delete();
        return back()->with('success', 'Task deleted successfully!');
    }

    public function toggleComplete(Task $task)
    {
        $this->authorize('update', $task);
        $task->update(['completed' => !$task->completed]);
        return response()->json(['completed' => $task->completed]);
    }
}