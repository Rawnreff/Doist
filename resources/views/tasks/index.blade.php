@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
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
                    <textarea 
                        name="description" 
                        class="task-description"
                        placeholder="Description (optional)"
                        rows="2"
                    ></textarea>
                    <input 
                        type="date" 
                        name="due_date" 
                        class="date-picker"
                    >
                    <select name="priority" class="priority-select">
                        <option value="low">Low</option>
                        <option value="medium" selected>Medium</option>
                        <option value="high">High</option>
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
                    Latest
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
        <li class="task-item {{ $task->completed ? 'completed' : '' }}" data-task-id="{{ $task->id }}" data-description="{{ $task->description }}">
    <!-- Checkbox -->
    <label class="task-checkbox">
        <input 
            type="checkbox"
            {{ $task->completed ? 'checked' : '' }}
            onchange="event.preventDefault();
                     document.getElementById('complete-form-{{ $task->id }}').submit();"
        >
        <span class="checkmark"></span>
    </label>
    
    <!-- Task Content -->
    <div class="task-body">
        <span class="task-title">{{ $task->title }}</span>
        @if($task->description)
            <p class="task-description-text">{{ $task->description }}</p>
        @endif
        <div class="task-meta">
            <span class="task-priority priority-{{ $task->priority }}">
                {{ ucfirst($task->priority) }}
            </span>
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
        </div>
    </div>
    
    <!-- Action Buttons -->
    <div class="task-actions-todo">
        <!-- Edit Button -->
        <button class="btn-edit" onclick="openEditModal({{ $task->id }})">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
            </svg>
        </button>
            
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
    </div>
    
    <!-- Hidden form for completing tasks -->
    <form id="complete-form-{{ $task->id }}" action="{{ route('tasks.update', $task) }}" method="POST">
        @csrf
        @method('PATCH')
        <input type="hidden" name="completed" value="{{ $task->completed ? 0 : 1 }}">
    </form>
</li>
                @endforeach
            </ul>

            <!-- Edit Task Modal -->
            <div class="modal" id="editModal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5>Edit Task</h5>
                        <button type="button" class="modal-close" onclick="closeEditModal()">&times;</button>
                    </div>
                    <form id="editTaskForm" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="edit_title">Task Title</label>
                                <input type="text" id="edit_title" name="title" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_description">Description</label>
                                <textarea id="edit_description" name="description" class="form-control" rows="3"></textarea>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="edit_due_date">Due Date</label>
                                    <input type="date" id="edit_due_date" name="due_date" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="edit_priority">Priority</label>
                                    <select id="edit_priority" name="priority" class="form-control">
                                        <option value="low">Low Priority</option>
                                        <option value="medium">Medium</option>
                                        <option value="high">High Priority</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn-cancel" onclick="closeEditModal()">Cancel</button>
                            <button type="submit" class="btn-save">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
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

<!-- podomoro Timer -->
<div class="podomoro-container">
    <div class="podomoro-card" id="podomoroCard">
        <div class="podomoro-header">
            <h4 class="podomoro-title">Podomoro Timer</h4>
            <button class="podomoro-close" id="podomoroClose">&times;</button>
        </div>
        <div class="podomoro-timer" id="podomoroTimer">25:00</div>
        <div class="podomoro-controls">
            <button class="podomoro-btn podomoro-start" id="podomoroStart">Start</button>
            <button class="podomoro-btn podomoro-reset" id="podomoroReset">Reset</button>
        </div>
        <div class="podomoro-sessions">
            <div class="podomoro-session">
                <div class="podomoro-session-label">Session</div>
                <div class="podomoro-session-value" id="podomoroSession">1/4</div>
            </div>
            <div class="podomoro-session">
                <div class="podomoro-session-label">Status</div>
                <div class="podomoro-session-value" id="podomoroStatus">Ready</div>
            </div>
        </div>
    </div>
    <button class="podomoro-toggle" id="podomoroToggle">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"></circle>
            <polyline points="12 6 12 12 16 14"></polyline>
        </svg>
    </button>
</div>

<script>
// podomoro Timer Logic
document.addEventListener('DOMContentLoaded', function() {
    const podomoroToggle = document.getElementById('podomoroToggle');
    const podomoroCard = document.getElementById('podomoroCard');
    const podomoroClose = document.getElementById('podomoroClose');
    const podomoroTimer = document.getElementById('podomoroTimer');
    const podomoroStart = document.getElementById('podomoroStart');
    const podomoroReset = document.getElementById('podomoroReset');
    const podomoroSession = document.getElementById('podomoroSession');
    const podomoroStatus = document.getElementById('podomoroStatus');
    
    let timer;
    let minutes = 25;
    let seconds = 0;
    let isRunning = false;
    let sessionCount = 1;
    let isBreak = false;
    
    // Toggle podomoro Card
    podomoroToggle.addEventListener('click', function() {
        podomoroCard.classList.toggle('active');
    });
    
    podomoroClose.addEventListener('click', function() {
        podomoroCard.classList.remove('active');
    });
    
    // Update Timer Display
    function updateDisplay() {
        const displayMinutes = minutes < 10 ? `0${minutes}` : minutes;
        const displaySeconds = seconds < 10 ? `0${seconds}` : seconds;
        podomoroTimer.textContent = `${displayMinutes}:${displaySeconds}`;
    }
    
    // Start/Pause Timer
    podomoroStart.addEventListener('click', function() {
        if (!isRunning) {
            // Start the timer
            isRunning = true;
            podomoroStart.textContent = 'Pause';
            podomoroStatus.textContent = isBreak ? 'Break' : 'Working';
            
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
                                podomoroStatus.textContent = 'Long Break';
                            } else {
                                // Short break
                                minutes = 5;
                                podomoroStatus.textContent = 'Short Break';
                            }
                            isBreak = true;
                        } else {
                            // Break completed
                            minutes = 25;
                            isBreak = false;
                            sessionCount++;
                            podomoroSession.textContent = `${sessionCount > 4 ? 4 : sessionCount}/4`;
                            podomoroStatus.textContent = 'Working';
                        }
                        
                        seconds = 0;
                        updateDisplay();
                        podomoroStart.textContent = 'Start';
                        
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
            podomoroStart.textContent = 'Start';
            podomoroStatus.textContent = 'Paused';
        }
    });
    
    // Reset Timer
    podomoroReset.addEventListener('click', function() {
        clearInterval(timer);
        isRunning = false;
        minutes = 25;
        seconds = 0;
        sessionCount = 1;
        isBreak = false;
        podomoroStart.textContent = 'Start';
        podomoroStatus.textContent = 'Ready';
        podomoroSession.textContent = '1/4';
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
<script>
// Edit Task Modal Functions
function openEditModal(taskId) {
    const modal = document.getElementById('editModal');
    const form = document.getElementById('editTaskForm');
    
    // Fetch task data
    const taskItem = document.querySelector(`.task-item[data-task-id="${taskId}"]`);
    const title = taskItem.querySelector('.task-title').textContent;
    const description = taskItem.dataset.description || '';
    const priority = taskItem.querySelector('.task-priority').classList[1].replace('priority-', '');
    
    // Set form action dengan route yang benar
    form.action = `/tasks/${taskId}`;
    
    // Fill form fields
    document.getElementById('edit_title').value = title;
    document.getElementById('edit_description').value = description;
    document.getElementById('edit_priority').value = priority;
    
    // Handle due date - perlu diperbaiki
    const dueDateElement = taskItem.querySelector('.task-due');
    if (dueDateElement) {
        const dueDateText = dueDateElement.textContent.trim();
        // Asumsi format: "Mmm d" (e.g. "Jul 17")
        const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        const parts = dueDateText.split(' ');
        const month = months.indexOf(parts[0]);
        const day = parts[1];
        const year = new Date().getFullYear();
        const formattedDate = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
        document.getElementById('edit_due_date').value = formattedDate;
    } else {
        document.getElementById('edit_due_date').value = '';
    }
    
    // Show modal
    modal.style.display = 'flex';
}

function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('editModal');
    if (event.target == modal) {
        closeEditModal();
    }
}
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const editForm = document.getElementById('editTaskForm');
    
    if (editForm) {
        editForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get the task ID from the form action
            const taskId = editForm.action.split('/').filter(Boolean).pop();
            
            // Create form data
            const formData = new FormData(editForm);
            
            // Add method override
            formData.append('_method', 'PATCH');
            
            fetch(`/tasks/${taskId}`, {
                method: 'POST', // Important: Use POST for method spoofing
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    return response.text().then(text => { throw new Error(text) });
                }
                return response.json();
            })
            .then(data => {
                if (data.redirect) {
                    window.location.href = data.redirect;
                } else {
                    window.location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error updating task. See console for details.');
            });
        });
    }
});
</script>
@endsection