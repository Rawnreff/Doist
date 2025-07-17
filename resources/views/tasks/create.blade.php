@extends('layouts.app')

@section('title', 'Create New Task')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Create New Task</h4>
        </div>
        
        <div class="card-body">
            <form method="POST" action="{{ route('tasks.store') }}">
                @csrf
                
                <div class="mb-3">
                    <label for="title" class="form-label fw-semibold">Title</label>
                    <input type="text" class="form-control" id="title" name="title" required>
                </div>
                
                <div class="mb-3">
                    <label for="description" class="form-label fw-semibold">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                </div>
                
                <div class="mb-3">
                    <label for="due_date" class="form-label fw-semibold">Due Date</label>
                    <input type="datetime-local" class="form-control" id="due_date" name="due_date">
                </div>
                
                <div class="mb-3">
                    <label for="priority" class="form-label fw-semibold">Priority</label>
                    <select class="form-select" id="priority" name="priority">
                        <option value="high">High</option>
                        <option value="medium" selected>Medium</option>
                        <option value="low">Low</option>
                    </select>
                </div>
                
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-check-circle me-2"></i>Create Task
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection