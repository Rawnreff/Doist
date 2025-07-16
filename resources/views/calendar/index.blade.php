@extends('layouts.app-with-sidebar')

@section('title', 'Calendar')

@section('content')
<div class="calendar-page">
    <div class="calendar-container">
        <div class="calendar-header">
            <h2>Task Calendar</h2>
            <div class="calendar-actions">
                <button id="prevMonth" class="calendar-nav-btn">
                    <i class="bi bi-chevron-left"></i>
                </button>
                <h3 id="currentMonth">July 2023</h3>
                <button id="nextMonth" class="calendar-nav-btn">
                    <i class="bi bi-chevron-right"></i>
                </button>
                <button id="todayBtn" class="today-btn">Today</button>
            </div>
        </div>
        
        <div id="calendar" class="calendar-grid"></div>
        
        <div class="task-list-sidebar">
            <h3>Tasks for <span id="selectedDate">Today</span></h3>
            <div id="dateTasks">
                @foreach(Auth::user()->tasks->where('due_date', today()) as $task)
                    <div class="calendar-task-item" data-task-id="{{ $task->id }}">
                        <input type="checkbox" {{ $task->completed ? 'checked' : '' }}>
                        <span class="task-title {{ $task->completed ? 'completed' : '' }}">
                            {{ $task->title }}
                        </span>
                        <span class="task-priority priority-{{ $task->priority }}">
                            {{ ucfirst($task->priority) }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection