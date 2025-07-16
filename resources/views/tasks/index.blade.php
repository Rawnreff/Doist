@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="task-card">
            <div class="task-header">
                <h4>✨ My Tasks</h4>
                <div class="task-count">{{ $tasks->count() }} tasks</div>
            </div>
            
            <!-- Add Task Form -->
            <form action="{{ route('tasks.store') }}" method="POST" class="task-form">
                @csrf
                <div class="input-group">
                    <input 
                        type="text" 
                        name="title" 
                        class="task-input"
                        placeholder="What needs to be done?"
                        required
                    >
                    <button type="submit" class="btn-add">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                        </svg>
                        Add
                    </button>
                </div>
                <div class="task-meta">
                    <input 
                        type="date" 
                        name="due_date" 
                        class="date-picker"
                    >
                    <select name="priority" class="priority-select">
                        <option value="low">Low Priority</option>
                        <option value="medium" selected>Medium</option>
                        <option value="high">High Priority</option>
                    </select>
                </div>
            </form>

            <!-- Tambahkan di bawah task-header -->
<div class="task-controls">
    <!-- Filter by Status -->
    <div class="filter-group">
        <span class="filter-label">Status:</span>
        <a href="{{ request()->fullUrlWithQuery(['status' => '']) }}" 
           class="filter-select {{ !request()->has('status') ? 'active-filter' : '' }}">
            All
        </a>
        <a href="{{ request()->fullUrlWithQuery(['status' => 'pending']) }}" 
           class="filter-select {{ request()->input('status') === 'pending' ? 'active-filter' : '' }}">
            Pending
        </a>
        <a href="{{ request()->fullUrlWithQuery(['status' => 'completed']) }}" 
           class="filter-select {{ request()->input('status') === 'completed' ? 'active-filter' : '' }}">
            Completed
        </a>
    </div>
    
    <!-- Filter by Priority -->
    <div class="filter-group">
        <span class="filter-label">Priority:</span>
        <a href="{{ request()->fullUrlWithQuery(['priority' => '']) }}" 
           class="filter-select {{ !request()->has('priority') ? 'active-filter' : '' }}">
            All
        </a>
        <a href="{{ request()->fullUrlWithQuery(['priority' => 'high']) }}" 
           class="filter-select {{ request()->input('priority') === 'high' ? 'active-filter' : '' }}">
            High
        </a>
        <a href="{{ request()->fullUrlWithQuery(['priority' => 'medium']) }}" 
           class="filter-select {{ request()->input('priority') === 'medium' ? 'active-filter' : '' }}">
            Medium
        </a>
        <a href="{{ request()->fullUrlWithQuery(['priority' => 'low']) }}" 
           class="filter-select {{ request()->input('priority') === 'low' ? 'active-filter' : '' }}">
            Low
        </a>
    </div>
    
    <!-- Sort Options -->
    <div class="filter-group">
        <span class="filter-label">Sort by:</span>
        <a href="{{ request()->fullUrlWithQuery(['sort' => 'latest']) }}" 
           class="filter-select {{ request()->input('sort') === 'latest' || !request()->has('sort') ? 'active-filter' : '' }}">
            Newest
        </a>
        <a href="{{ request()->fullUrlWithQuery(['sort' => 'oldest']) }}" 
           class="filter-select {{ request()->input('sort') === 'oldest' ? 'active-filter' : '' }}">
            Oldest
        </a>
        <a href="{{ request()->fullUrlWithQuery(['sort' => 'due_date']) }}" 
           class="filter-select {{ request()->input('sort') === 'due_date' ? 'active-filter' : '' }}">
            Due Date
        </a>
        <a href="{{ request()->fullUrlWithQuery(['sort' => 'priority']) }}" 
           class="filter-select {{ request()->input('sort') === 'priority' ? 'active-filter' : '' }}">
            Priority
        </a>
    </div>
</div>

            <!-- Task List -->
            <ul class="task-list">
                @foreach ($tasks as $task)
                <li class="task-item {{ $task->completed ? 'completed' : '' }}">
                    <!-- Update Form (for checkbox) -->
                    <form id="update-form-{{ $task->id }}" 
                          action="{{ route('tasks.update', $task) }}" 
                          method="POST"
                          class="d-inline">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="completed" value="{{ $task->completed ? 0 : 1 }}">
                        
                        <!-- Checkbox -->
                        <label class="task-checkbox">
                            <input 
                                type="checkbox"
                                {{ $task->completed ? 'checked' : '' }}
                                onchange="
                                    this.form.querySelector('input[name=\'completed\']').value = this.checked ? 1 : 0;
                                    this.form.submit();
                                "
                            >
                            <span class="checkmark"></span>
                        </label>
                    </form>
                    
                    <!-- Task Content -->
                    <div class="task-body">
                        <span class="task-title">{{ $task->title }}</span>
                        <div class="task-tags">
                            @if($task->due_date)
                            <span class="task-due">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                                    <polyline points="17 21 17 13 7 13 7 21"></polyline>
                                    <polyline points="7 3 7 8 15 8"></polyline>
                                </svg>
                                {{ $task->due_date->format('M j') }}
                            </span>
                            @endif
                            <span class="task-priority priority-{{ $task->priority }}">
                                {{ ucfirst($task->priority) }}
                            </span>
                        </div>
                    </div>
                    
                    <!-- Delete Button -->
                    <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="task-delete">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-delete">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="M3 6h18"></path>
                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                            </svg>
                        </button>
                    </form>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

<style>
    /* Checkbox Styles */
    .task-checkbox {
        position: relative;
        display: inline-block;
        cursor: pointer;
        margin-right: 12px;
    }
    
    .task-checkbox input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
        height: 0;
        width: 0;
    }
    
    .checkmark {
        position: relative;
        display: inline-block;
        width: 20px;
        height: 20px;
        border: 2px solid #6366f1;
        border-radius: 4px;
        transition: all 0.3s;
    }
    
    .task-checkbox input:checked ~ .checkmark {
        background-color: #6366f1;
    }
    
    .checkmark:after {
        content: "";
        position: absolute;
        display: none;
        left: 6px;
        top: 2px;
        width: 5px;
        height: 10px;
        border: solid white;
        border-width: 0 2px 2px 0;
        transform: rotate(45deg);
    }
    
    .task-checkbox input:checked ~ .checkmark:after {
        display: block;
    }
    
    /* Completed Task Style */
    .task-item.completed .task-title {
        text-decoration: line-through;
        color: #94a3b8;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add animation class after page load
    document.querySelectorAll('.task-item').forEach((item, index) => {
        item.style.animationDelay = `${index * 0.1}s`;
        item.classList.add('animate-in');
    });
});
</script>

<!-- Pomodoro Timer -->
<div class="pomodoro-container">
    <div class="pomodoro-card" id="pomodoroCard">
        <div class="pomodoro-header">
            <h4 class="pomodoro-title">Pomodoro Timer</h4>
            <button class="pomodoro-close" id="pomodoroClose">&times;</button>
        </div>
        <div class="pomodoro-timer" id="pomodoroTimer">25:00</div>
        <div class="pomodoro-controls">
            <button class="pomodoro-btn pomodoro-start" id="pomodoroStart">Start</button>
            <button class="pomodoro-btn pomodoro-reset" id="pomodoroReset">Reset</button>
        </div>
        <div class="pomodoro-sessions">
            <div class="pomodoro-session">
                <div class="pomodoro-session-label">Session</div>
                <div class="pomodoro-session-value" id="pomodoroSession">1/4</div>
            </div>
            <div class="pomodoro-session">
                <div class="pomodoro-session-label">Status</div>
                <div class="pomodoro-session-value" id="pomodoroStatus">Ready</div>
            </div>
        </div>
    </div>
    <button class="pomodoro-toggle" id="pomodoroToggle">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"></circle>
            <polyline points="12 6 12 12 16 14"></polyline>
        </svg>
    </button>
</div>

<script>
// Pomodoro Timer Logic
document.addEventListener('DOMContentLoaded', function() {
    const pomodoroToggle = document.getElementById('pomodoroToggle');
    const pomodoroCard = document.getElementById('pomodoroCard');
    const pomodoroClose = document.getElementById('pomodoroClose');
    const pomodoroTimer = document.getElementById('pomodoroTimer');
    const pomodoroStart = document.getElementById('pomodoroStart');
    const pomodoroReset = document.getElementById('pomodoroReset');
    const pomodoroSession = document.getElementById('pomodoroSession');
    const pomodoroStatus = document.getElementById('pomodoroStatus');
    
    let timer;
    let minutes = 25;
    let seconds = 0;
    let isRunning = false;
    let sessionCount = 1;
    let isBreak = false;
    
    // Toggle Pomodoro Card
    pomodoroToggle.addEventListener('click', function() {
        pomodoroCard.classList.toggle('active');
    });
    
    pomodoroClose.addEventListener('click', function() {
        pomodoroCard.classList.remove('active');
    });
    
    // Update Timer Display
    function updateDisplay() {
        const displayMinutes = minutes < 10 ? `0${minutes}` : minutes;
        const displaySeconds = seconds < 10 ? `0${seconds}` : seconds;
        pomodoroTimer.textContent = `${displayMinutes}:${displaySeconds}`;
    }
    
    // Start/Pause Timer
    pomodoroStart.addEventListener('click', function() {
        if (!isRunning) {
            // Start the timer
            isRunning = true;
            pomodoroStart.textContent = 'Pause';
            pomodoroStatus.textContent = isBreak ? 'Break' : 'Working';
            
            timer = setInterval(function() {
                if (seconds === 0) {
                    if (minutes === 0) {
                        // Timer completed
                        clearInterval(timer);
                        isRunning = false;
                        
                        // Play sound
                        const audio = new Audio('https://assets.mixkit.co/sfx/preview/mixkit-alarm-digital-clock-beep-989.mp3');
                        audio.play();
                        
                        if (!isBreak) {
                            // Work session completed
                            if (sessionCount % 4 === 0) {
                                // Long break after 4 sessions
                                minutes = 15;
                                pomodoroStatus.textContent = 'Long Break';
                            } else {
                                // Short break
                                minutes = 5;
                                pomodoroStatus.textContent = 'Short Break';
                            }
                            isBreak = true;
                        } else {
                            // Break completed
                            minutes = 25;
                            isBreak = false;
                            sessionCount++;
                            pomodoroSession.textContent = `${sessionCount > 4 ? 4 : sessionCount}/4`;
                            pomodoroStatus.textContent = 'Working';
                        }
                        
                        seconds = 0;
                        updateDisplay();
                        pomodoroStart.textContent = 'Start';
                        
                        // Show notification
                        if (Notification.permission === 'granted') {
                            new Notification(isBreak ? 'Break Time!' : 'Back to Work!', {
                                body: isBreak ? 'Take a break!' : 'Time to focus!'
                            });
                        }
                    } else {
                        minutes--;
                        seconds = 59;
                    }
                } else {
                    seconds--;
                }
                updateDisplay();
            }, 1000);
        } else {
            // Pause the timer
            clearInterval(timer);
            isRunning = false;
            pomodoroStart.textContent = 'Start';
            pomodoroStatus.textContent = 'Paused';
        }
    });
    
    // Reset Timer
    pomodoroReset.addEventListener('click', function() {
        clearInterval(timer);
        isRunning = false;
        minutes = 25;
        seconds = 0;
        sessionCount = 1;
        isBreak = false;
        pomodoroStart.textContent = 'Start';
        pomodoroStatus.textContent = 'Ready';
        pomodoroSession.textContent = '1/4';
        updateDisplay();
    });
    
    // Initialize
    updateDisplay();
    
    // Request notification permission
    if (Notification.permission !== 'granted') {
        Notification.requestPermission();
    }
});
</script>
@endsection