@extends('layouts.app')

@section('title', 'Trash')

@section('content')
<div class="trash-container">
    <div class="trash-header">
        <h2>Trash</h2>
        <p>Deleted tasks will be permanently removed after 30 days</p>
    </div>
    
    @if($deletedTasks->isEmpty())
        <div class="empty-trash">
            <i class="bi bi-trash"></i>
            <p>Your trash is empty</p>
        </div>
    @else
        <div class="trash-list">
            @foreach($deletedTasks as $task)
                <div class="trash-item">
                    <div class="task-info">
                        <h4>{{ $task->title }}</h4>
                        <div class="task-meta">
                            @if($task->due_date)
                                <span class="due-date">
                                    <i class="bi bi-calendar"></i>
                                    {{ $task->due_date->format('M d, Y') }}
                                </span>
                            @endif
                            <span class="priority priority-{{ $task->priority }}">
                                {{ ucfirst($task->priority) }} priority
                            </span>
                        </div>
                        <p class="deleted-at">
                            Deleted on {{ $task->deleted_at->format('M d, Y \a\t h:i A') }}
                        </p>
                    </div>
                    
                    <div class="task-actions">
                        <form action="{{ route('trash.restore', $task->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-restore">
                                <i class="bi bi-arrow-counterclockwise"></i> Restore
                            </button>
                        </form>
                        <form action="{{ route('trash.destroy', $task->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-destroy">
                                <i class="bi bi-trash-fill"></i> Delete permanently
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<style>
    .trash-container {
        max-width: 800px;
        margin: 0 auto;
    }
    
    .trash-header {
        margin-bottom: 2rem;
    }
    
    .trash-header h2 {
        font-size: 1.75rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    
    .empty-trash {
        text-align: center;
        padding: 3rem;
        color: var(--text-light);
    }
    
    .empty-trash i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }
    
    .trash-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    
    .trash-item {
        background: var(--card-bg);
        border-radius: var(--radius);
        padding: 1.25rem;
        box-shadow: var(--shadow);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .task-info h4 {
        margin-bottom: 0.5rem;
    }
    
    .task-meta {
        display: flex;
        gap: 1rem;
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
        color: var(--text-light);
    }
    
    .due-date {
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }
    
    .priority {
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.75rem;
    }
    
    .priority-low {
        background-color: #e0f2fe;
        color: #0369a1;
    }
    
    .priority-medium {
        background-color: #fef9c3;
        color: #854d0e;
    }
    
    .priority-high {
        background-color: #fee2e2;
        color: #991b1b;
    }
    
    .deleted-at {
        font-size: 0.75rem;
        color: var(--text-light);
    }
    
    .task-actions {
        display: flex;
        gap: 0.75rem;
    }
    
    .btn-restore, .btn-destroy {
        padding: 0.5rem 1rem;
        border: none;
        border-radius: 4px;
        font-size: 0.875rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s;
    }
    
    .btn-restore {
        background-color: #e0f2fe;
        color: #0369a1;
    }
    
    .btn-restore:hover {
        background-color: #bae6fd;
    }
    
    .btn-destroy {
        background-color: #fee2e2;
        color: #991b1b;
    }
    
    .btn-destroy:hover {
        background-color: #fecaca;
    }
</style>
@endsection