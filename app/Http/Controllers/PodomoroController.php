<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class PodomoroController extends Controller
{
    public function index()
    {
        $pendingTasks = auth()->user()->tasks()
                        ->where('completed', false)
                        ->orderBy('due_date')
                        ->get();
                        
        return view('podomoro.index', compact('pendingTasks'));
    }
}