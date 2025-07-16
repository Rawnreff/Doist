<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrashController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $deletedTasks = Auth::user()->tasks()->onlyTrashed()->get();
        $deletedTasksCount = $deletedTasks->count();
        
        return view('trash.index', compact('deletedTasks', 'deletedTasksCount'));
    }

    public function restore($id)
    {
        $task = Auth::user()->tasks()->onlyTrashed()->findOrFail($id);
        $task->restore();
        
        return redirect()->route('trash.index')->with('success', 'Task restored successfully');
    }

    public function destroy($id)
    {
        $task = Auth::user()->tasks()->onlyTrashed()->findOrFail($id);
        $task->forceDelete();
        
        return redirect()->route('trash.index')->with('success', 'Task permanently deleted');
    }
}