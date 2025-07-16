@extends('layouts.app')

@section('title', 'Podomoro Timer')

@section('content')
<div class="podomoro-container">
    <div class="podomoro-card">
        <div class="podomoro-header">
            <h2>Podomoro Timer</h2>
            <div class="podomoro-modes">
                <button class="mode-btn active" data-mode="podomoro">Podomoro</button>
                <button class="mode-btn" data-mode="short-break">Short Break</button>
                <button class="mode-btn" data-mode="long-break">Long Break</button>
            </div>
        </div>
        
        <div class="podomoro-timer">
            <span id="minutes">25</span>:<span id="seconds">00</span>
        </div>
        
        <div class="podomoro-controls">
            <button id="startBtn" class="podomoro-btn start-btn">
                <i class="bi bi-play-fill"></i> Start
            </button>
            <button id="resetBtn" class="podomoro-btn reset-btn">
                <i class="bi bi-arrow-counterclockwise"></i> Reset
            </button>
        </div>
        
        <div class="podomoro-session">
            <span id="sessionCount">Session: 1/4</span>
        </div>
        
        <div class="podomoro-task">
            <h4>Current Task</h4>
            <select id="taskSelect" class="task-select">
                <option value="">Select a task to focus on</option>
                @foreach($pendingTasks as $task)
                    <option value="{{ $task->id }}">{{ $task->title }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
<style>
    /* Podomoro Timer Styles */
    .podomoro-container {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: calc(100vh - 120px);
        padding: 2rem;
        background-color: var(--bg);
    }
    
    .podomoro-card {
        width: 100%;
        max-width: 500px;
        background: var(--card-bg);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        padding: 2.5rem;
        text-align: center;
    }
    
    .podomoro-header {
        margin-bottom: 2rem;
    }
    
    .podomoro-header h2 {
        font-size: 1.75rem;
        font-weight: 600;
        color: var(--text);
        margin-bottom: 1.5rem;
    }
    
    .podomoro-modes {
        display: flex;
        justify-content: center;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
    }
    
    .mode-btn {
        padding: 0.5rem 1rem;
        border: none;
        border-radius: 50px;
        background-color: var(--bg);
        color: var(--text-light);
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .mode-btn.active {
        background-color: var(--primary);
        color: white;
    }
    
    .podomoro-timer {
        font-size: 5rem;
        font-weight: 700;
        font-family: 'Inter', monospace;
        color: var(--text);
        margin: 1.5rem 0;
        line-height: 1;
    }
    
    .podomoro-controls {
        display: flex;
        justify-content: center;
        gap: 1.5rem;
        margin: 2rem 0;
    }
    
    .podomoro-btn {
        padding: 0.75rem 1.75rem;
        border: none;
        border-radius: 50px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
    }
    
    .start-btn {
        background-color: var(--primary);
        color: white;
        box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3);
    }
    
    .start-btn:hover {
        background-color: var(--primary-dark);
        transform: translateY(-2px);
    }
    
    .reset-btn {
        background-color: var(--bg);
        color: var(--text-light);
    }
    
    .reset-btn:hover {
        background-color: #e5e7eb;
    }
    
    .podomoro-session {
        font-size: 0.875rem;
        color: var(--text-light);
        margin-bottom: 2rem;
    }
    
    .podomoro-task {
        margin-top: 2rem;
        text-align: left;
    }
    
    .podomoro-task h4 {
        font-size: 1rem;
        color: var(--text-light);
        margin-bottom: 0.75rem;
    }
    
    .task-select {
        width: 100%;
        padding: 0.875rem 1rem;
        border: 1px solid var(--border);
        border-radius: 8px;
        background-color: var(--bg);
        color: var(--text);
        font-size: 0.9375rem;
        transition: all 0.3s;
    }
    
    .task-select:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }
    
    /* Animation for timer */
    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }
    
    .podomoro-timer.active {
        animation: pulse 1s infinite;
        color: var(--primary);
    }
    
    /* Responsive Design */
    @media (max-width: 576px) {
        .podomoro-container {
            padding: 1rem;
        }
        
        .podomoro-card {
            padding: 1.5rem;
        }
        
        .podomoro-timer {
            font-size: 3.5rem;
        }
        
        .podomoro-controls {
            flex-direction: column;
            gap: 1rem;
        }
        
        .podomoro-btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const minutesDisplay = document.getElementById('minutes');
        const secondsDisplay = document.getElementById('seconds');
        const startBtn = document.getElementById('startBtn');
        const resetBtn = document.getElementById('resetBtn');
        const sessionCount = document.getElementById('sessionCount');
        const modeButtons = document.querySelectorAll('.mode-btn');
        const timerDisplay = document.querySelector('.podomoro-timer');
        
        let timer;
        let minutes = 25;
        let seconds = 0;
        let isRunning = false;
        let currentMode = 'podomoro';
        let sessionCountValue = 1;
        
        // Mode presets
        const modes = {
            podomoro: { minutes: 25, color: 'var(--primary)' },
            'short-break': { minutes: 5, color: '#10B981' },
            'long-break': { minutes: 15, color: '#3B82F6' }
        };
        
        // Initialize
        updateDisplay();
        
        // Mode selection
        modeButtons.forEach(button => {
            button.addEventListener('click', function() {
                if (isRunning) return;
                
                modeButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                currentMode = this.dataset.mode;
                
                minutes = modes[currentMode].minutes;
                seconds = 0;
                updateDisplay();
                
                // Change timer color based on mode
                timerDisplay.style.color = modes[currentMode].color;
            });
        });
        
        // Start/Pause button
        startBtn.addEventListener('click', function() {
            if (isRunning) {
                pauseTimer();
            } else {
                startTimer();
            }
        });
        
        // Reset button
        resetBtn.addEventListener('click', resetTimer);
        
        function startTimer() {
            if (isRunning) return;
            
            isRunning = true;
            startBtn.innerHTML = '<i class="bi bi-pause-fill"></i> Pause';
            timerDisplay.classList.add('active');
            
            timer = setInterval(() => {
                if (seconds === 0) {
                    if (minutes === 0) {
                        timerComplete();
                        return;
                    }
                    minutes--;
                    seconds = 59;
                } else {
                    seconds--;
                }
                
                updateDisplay();
            }, 1000);
        }
        
        function pauseTimer() {
            clearInterval(timer);
            isRunning = false;
            startBtn.innerHTML = '<i class="bi bi-play-fill"></i> Start';
            timerDisplay.classList.remove('active');
        }
        
        function resetTimer() {
            clearInterval(timer);
            isRunning = false;
            minutes = modes[currentMode].minutes;
            seconds = 0;
            startBtn.innerHTML = '<i class="bi bi-play-fill"></i> Start';
            timerDisplay.classList.remove('active');
            updateDisplay();
        }
        
        function timerComplete() {
            clearInterval(timer);
            isRunning = false;
            
            // Play sound
            const audio = new Audio('https://assets.mixkit.co/sfx/preview/mixkit-alarm-digital-clock-beep-989.mp3');
            audio.play();
            
            // Update session count for podomoro mode
            if (currentMode === 'podomoro') {
                sessionCountValue++;
                sessionCount.textContent = `Session: ${Math.min(sessionCountValue, 4)}/4`;
                
                // Switch to break after podomoro
                if (sessionCountValue % 4 === 0) {
                    currentMode = 'long-break';
                } else {
                    currentMode = 'short-break';
                }
            } else {
                // Switch back to podomoro after break
                currentMode = 'podomoro';
            }
            
            // Update active button
            document.querySelector(`.mode-btn[data-mode="${currentMode}"]`).click();
            
            // Show notification
            if (Notification.permission === 'granted') {
                new Notification('Timer Completed!', {
                    body: currentMode === 'podomoro' ? 'Time to focus!' : 'Time for a break!'
                });
            }
        }
        
        function updateDisplay() {
            minutesDisplay.textContent = minutes.toString().padStart(2, '0');
            secondsDisplay.textContent = seconds.toString().padStart(2, '0');
        }
        
        // Request notification permission
        if (Notification.permission !== 'granted') {
            Notification.requestPermission();
        }
    });
</script>
@endsection