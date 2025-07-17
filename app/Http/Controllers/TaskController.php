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
        $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'priority' => 'in:low,medium,high'
        ]);

        Auth::user()->tasks()->create($request->all());

        return redirect()->route('tasks.index');
    }
    
    public function create()
    {
        return view('tasks.create');
    }

    public function edit(Task $task)
    {
        $this->authorize('update', $task);
        return view('tasks.edit', compact('task'));
    }

    public function update(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        if ($request->has('completed')) {
            $task->update(['completed' => $request->completed]);
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Task status updated successfully',
                    'redirect' => route('tasks.index')
                ]);
            }
            return back();
        }

        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'priority' => 'required|in:low,medium,high'
        ]);

        $task->update($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Task updated successfully',
                'redirect' => route('tasks.index')
            ]);
        }

        return redirect()->route('tasks.index');
    }

    public function updateNoCsrf(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'priority' => 'required|in:low,medium,high'
        ]);

        $task->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Task updated successfully (no CSRF)',
            'redirect' => route('tasks.index')
        ]);
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