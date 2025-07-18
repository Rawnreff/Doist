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
                        <h4 class="task-title">{{ $task->title }}</h4>
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
                                <i class="bi bi-trash-fill"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    <div id="delete-confirmation-modal" class="modal-overlay" style="display: none;">
        <div class="modal-box">
            <h5>Are you sure?</h5>
            <p>This task will be permanently deleted and cannot be recovered.</p>
            <div class="modal-buttons">
                <button class="btn-cancel" onclick="closeModal()">Cancel</button>
                <form id="delete-form" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-confirm">Yes, Delete</button>
                </form>
            </div>
        </div>
    </div>

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
        padding: 1.6rem;
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

    .task-title {
        max-width: 480px;
        margin-bottom: 0.5rem;
        font-weight: 500;
        word-wrap: break-word;
        white-space: collapse;
    }

    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(17, 24, 39, 0.75);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 999;
    }

    .modal-box {
        background: white;
        padding: 2rem;
        border-radius: 12px;
        width: 90%;
        max-width: 400px;
        text-align: center;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        animation: scaleIn 0.3s ease-in-out;
    }

    .modal-box h5 {
        font-size: 1.25rem;
        margin-bottom: 0.5rem;
        color: #111827;
    }

    .modal-box p {
        color: #6b7280;
        margin-bottom: 1.25rem;
    }

    .modal-buttons {
        display: flex;
        justify-content: space-between;
        gap: 1rem;
    }

    .btn-cancel {
        background-color: #f3f4f6;
        color: #374151;
        padding: 0.5rem 1rem;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        flex: 1;
    }

    .btn-confirm {
        background-color: #ef4444;
        color: white;
        padding: 0.5rem 1rem;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        flex: 1;
    }

    .btn-cancel:hover {
        background-color: #e5e7eb;
    }

    .btn-confirm:hover {
        background-color: #dc2626;
    }

    @keyframes scaleIn {
        from {
            transform: scale(0.9);
            opacity: 0;
        }
        to {
            transform: scale(1);
            opacity: 1;
        }
    }
</style>

<script>
    function openDeleteModal(formAction) {
        const modal = document.getElementById('delete-confirmation-modal');
        const deleteForm = document.getElementById('delete-form');
        deleteForm.action = formAction;
        modal.style.display = 'flex';
    }

    function closeModal() {
        const modal = document.getElementById('delete-confirmation-modal');
        modal.style.display = 'none';
    }

    // Intercept delete button clicks
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.btn-destroy').forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                const form = this.closest('form');
                openDeleteModal(form.action);
            });
        });
    });
</script>
@endsection