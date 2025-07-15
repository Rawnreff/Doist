<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Auth::user()->tasks()->latest()->get();
        return view('tasks.index', compact('tasks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'due_date' => 'nullable|date',
            'priority' => 'in:low,medium,high'
        ]);

        Auth::user()->tasks()->create($request->all());

        return redirect()->route('tasks.index');
    }

    public function update(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        $task->update([
            'completed' => $request->has('completed')
        ]);

        return back();
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);

        $task->delete();
        return back();
    }
}