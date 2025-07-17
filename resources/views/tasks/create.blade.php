@extends('layouts.app')

@section('title', 'Create New Task')

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow-lg kalender-wrapper">
        <div class="card-header kalender-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0 fw-bold text-white">Create New Task</h4>
                    <p class="mb-0 text-white opacity-75">Add a new task to your calendar</p>
                </div>
                <a href="{{ route('calendar') }}" class="btn btn-light btn-sm">
                    <i class="bi bi-arrow-left me-2"></i>Back to Calendar
                </a>
            </div>
        </div>

        <div class="card-body p-4">
            <form method="POST" action="{{ route('tasks.store') }}">
                @csrf
                
                <div class="mb-4">
                    <label for="title" class="form-label fw-semibold">Task Title</label>
                    <input type="text" class="form-control form-control-lg" id="title" name="title" required
                           placeholder="Enter task title">
                </div>
                
                <div class="mb-4">
                    <label for="description" class="form-label fw-semibold">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="4"
                              placeholder="Add detailed description (optional)"></textarea>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label for="due_date" class="form-label fw-semibold">Due Date</label>
                        <input type="datetime-local" class="form-control" id="due_date" name="due_date">
                    </div>
                    
                    <div class="col-md-6 mb-4">
                        <label for="priority" class="form-label fw-semibold">Priority</label>
                        <select class="form-select" id="priority" name="priority">
                            <option value="high" class="text-danger"> High Priority</option>
                            <option value="medium" selected class="text-warning">Medium Priority</option>
                            <option value="low" class="text-success">Low Priority</option>
                        </select>
                    </div>
                </div>
                
                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-primary px-4 py-2">
                        <i class="bi bi-plus-circle me-2"></i>Create Task
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .form-control, .form-select {
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 0.25rem rgba(99, 102, 241, 0.25);
    }

    textarea.form-control {
        resize: none;
        height: auto;
        border-radius: 8px;
        border: 2px solid #e5e7eb;
        padding: 0.75rem 1rem;
        font-size: 1.1rem;
        line-height: 1.5;
        background-color: white;
        box-shadow: none;
    }

    .form-control-lg {
        font-size: 1.1rem;
        padding: 1rem 1.25rem;
    }
    
    .card {
        border: none;
        border-radius: 16px;
    }
    
    .kalender-wrapper {
        background: white;
        border-radius: 16px;
        overflow: hidden;
    }
    
    .kalender-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1.5rem;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .card-body {
            padding: 1.5rem;
        }
        
        .form-control, .form-select {
            padding: 0.5rem 0.75rem;
        }
    }
</style>
@endsection