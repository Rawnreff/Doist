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
@endsection