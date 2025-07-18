@extends('layouts.app')

@section('title', 'Calendar')

@section('content')
<div class="container-fluid p-4">
    <div class="calendar-container">
        <!-- Header -->
        <div class="calendar-header">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h4 class="mb-0 fw-bold">Task Calendar</h4>
                    <p class="mb-0 opacity-75">Organize and track your tasks efficiently</p>
                </div>
                <a href="{{ route('tasks.create') }}" class="btn btn-light btn-sm" id="addEventBtn">
                    <i class="bi bi-plus-circle me-2"></i>Add Task
                </a>
            </div>

            <div class="calendar-stats mt-3">
                <div class="stat-item d-flex align-items-center">
                    <i class="bi bi-clock me-3"></i>
                    <div>
                        <div class="fw-semibold">{{ $pendingCount }}</div>
                        <div class="small opacity-75">Pending</div>
                    </div>
                </div>
                <div class="stat-item d-flex align-items-center">
                    <i class="bi bi-check-circle me-3"></i>
                    <div>
                        <div class="fw-semibold">{{ $completedCount }}</div>
                        <div class="small opacity-75">Completed</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Calendar Body -->
        <div class="position-relative">
            <div id="modernCalendar" class="modern-calendar"></div>
            <div id="calendarLoading" class="calendar-loading d-none">
                <div class="text-center">
                    <div class="spinner"></div>
                    <p class="mt-3 text-muted">Loading calendar...</p>
                </div>
            </div>
        </div>

        <!-- Legend -->
        <div class="legend">
            <div class="d-flex justify-content-center gap-4 flex-wrap">
                <div class="legend-item">
                    <span class="legend-dot bg-danger"></span><span class="fw-medium">High Priority</span>
                </div>
                <div class="legend-item">
                    <span class="legend-dot bg-warning"></span><span class="fw-medium">Medium Priority</span>
                </div>
                <div class="legend-item">
                    <span class="legend-dot bg-success"></span><span class="fw-medium">Low Priority</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Detail Task Modal -->
<div class="modal" id="modernTaskModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0 pb-0 position-relative">
                <div class="priority-indicator" id="modalPriorityIndicator"></div>
                <div class="w-100 text-end">
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
            </div>
            <div class="modal-body pt-0 px-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <h4 id="modernTaskModalTitle" class="mb-0 fw-bold"></h4>
                    <span id="modernTaskModalStatus" class="badge rounded-pill fs-6"></span>
                </div>
                <div class="task-meta mb-4">
                    <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-calendar-event text-muted me-2"></i>
                        <span id="modernTaskModalDueDate" class="fw-medium"></span>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="bi bi-flag text-muted me-2"></i>
                        <span id="modernTaskModalPriority" class="badge"></span>
                    </div>
                </div>
                <div class="task-description">
                    <h6 class="text-muted text-uppercase small fw-bold mb-2">Description</h6>
                    <p id="modernTaskModalDescription" class="mb-0"></p>
                </div>
            </div>
            <div class="modal-footer border-0 bg-light rounded-bottom">
                <a id="modernTaskEditLink" href="#" class="btn btn-primary">
                    <i class="bi bi-pencil me-2"></i>Edit Task
                </a>
                <button type="button" id="markCompletedBtn" class="btn btn-success">
                    <i class="bi bi-check-circle me-2"></i>Mark as Completed
                </button>
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Task Modal -->
<div class="modal" id="modernEditTaskModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold mb-0">Edit Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editTaskForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body pt-0 px-4">
                    <div class="mb-3">
                        <label for="edit_title" class="form-label">Task Title</label>
                        <input type="text" class="form-control" id="edit_title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Description</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="edit_due_date" class="form-label">Due Date</label>
                            <input type="date" class="form-control" id="edit_due_date" name="due_date">
                        </div>
                        <div class="col-md-6">
                            <label for="edit_priority" class="form-label">Priority</label>
                            <select class="form-select" id="edit_priority" name="priority">
                                <option value="low">Low Priority</option>
                                <option value="medium">Medium</option>
                                <option value="high">High Priority</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light rounded-bottom">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-2"></i>Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- CSS -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>

<style>
    /* Enhanced Calendar Container */
    .calendar-container {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        overflow: hidden;
    }

    /* Calendar Header Improvements */
    .calendar-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1.5rem;
    }

    .calendar-stats {
        display: flex;
        gap: 1rem;
        margin-top: 1rem;
    }

    .stat-item {
        background: rgba(255, 255, 255, 0.2);
        padding: 0.5rem 1rem;
        border-radius: 8px;
        backdrop-filter: blur(10px);
    }

    /* Modern Calendar Styles */
    #modernCalendar {
        min-height: 650px;
        background: white;
    }

    /* FullCalendar Overrides */
    .fc {
        --fc-border-color: #e5e7eb;
        --fc-today-bg-color: rgba(79, 70, 229, 0.08);
        --fc-neutral-bg-color: #f9fafb;
        --fc-page-bg-color: white;
        --fc-event-border-color: transparent;
    }

    /* Toolbar Styling */
    .fc .fc-toolbar.fc-header-toolbar {
        margin-bottom: 0;
        padding: 1.5rem 2rem;
        background: white;
        border-bottom: 2px solid var(--border);
    }

    .fc .fc-toolbar-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1f2937; /* Changed to dark text */
        text-transform: capitalize;
    }

    .fc .fc-button {
        background: white;
        border: 2px solid var(--border);
        color: #1f2937; /* Changed to dark text */
        font-size: 0.9rem;
        font-weight: 600;
        text-transform: capitalize;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        transition: all 0.3s ease;
        margin: 0 0.25rem;
    }

    .fc .fc-button:hover {
        background: var(--primary);
        border-color: var(--primary);
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
    }

    .fc .fc-button-primary:not(:disabled).fc-button-active {
        background: var(--primary);
        border-color: var(--primary);
        color: white;
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
    }

    .fc .fc-button:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    /* Day Grid Improvements */
    .fc .fc-daygrid-day-frame {
        padding: 0.75rem;
        min-height: 70px;
        border-radius: 4px;
        transition: background-color 0.2s;
    }

    .fc .fc-daygrid-day-frame:hover {
        background-color: rgba(79, 70, 229, 0.02);
    }

    .fc .fc-daygrid-day-top {
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem;
        margin-bottom: 0.5rem;
    }

    .fc .fc-daygrid-day-number {
        font-weight: 600;
        color: #1f2937; /* Changed to dark text */
        font-size: 1rem;
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
        transition: all 0.2s;
    }

    .fc .fc-day-today .fc-daygrid-day-number {
        background: var(--primary);
        color: white;
        font-weight: 700;
    }

    .fc .fc-daygrid-day.fc-day-today {
        background-color: var(--fc-today-bg-color);
        border: 2px solid var(--primary);
    }

    .fc .fc-col-header-cell {
        background: var(--bg-gray);
        border-bottom: 2px solid var(--border);
        padding: 1rem;
    }

    .fc .fc-col-header-cell-cushion {
        font-weight: 700;
        color: #1f2937; /* Changed to dark text */
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }

    /* Event Styling */
    .fc-event {
        border: none !important;
        border-radius: 8px;
        font-size: 0.8rem;
        padding: 0.4rem 0.6rem;
        margin: 0.15rem 0;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        cursor: pointer;
        transition: all 0.2s ease;
        font-weight: 500;
    }

    .fc-event:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .fc-event .fc-event-main {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        max-width: calc(100% - 1px);
    }

    .fc-event .fc-event-title {
        font-weight: 600;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        flex: 1;
        color: #1f2937; /* Changed to dark text */
    }

    /* Priority Colors - Enhanced */
    .event-high {
        background: linear-gradient(135deg, #fee2e2, #fecaca);
        color: #991b1b;
        border-left: 4px solid #ef4444;
    }

    .event-medium {
        background: linear-gradient(135deg, #fef3c7, #fed7aa);
        color: #92400e;
        border-left: 4px solid #f59e0b;
    }

    .event-low {
        background: linear-gradient(135deg, #d1fae5, #a7f3d0);
        color: #065f46;
        border-left: 4px solid #10b981;
    }

    .event-default {
        background: linear-gradient(135deg, #e0e7ff, #c7d2fe);
        color: #3730a3;
        border-left: 4px solid #6366f1;
    }

    /* Priority Icons */
    .priority-icon {
        font-size: 0.9rem;
        font-weight: bold;
    }

    .priority-icon.high::before {
        content: "🔴";
    }

    .priority-icon.medium::before {
        content: "🟡";
    }

    .priority-icon.low::before {
        content: "🟢";
    }

    .priority-icon.default::before {
        content: "🔵";
    }

    /* Legend Enhanced */
    .legend {
        background: white;
        padding: 1rem 2rem;
        border-top: 2px solid var(--border);
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem;
        border-radius: 6px;
        transition: background-color 0.2s;
    }

    .legend-item:hover {
        background: var(--bg-gray);
    }

    .legend-dot {
        width: 16px;
        height: 16px;
        border-radius: 50%;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    /* Modal Improvements */
    .modal-content {
        border: none;
        border-radius: 16px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }

    /* Remove modal backdrop */
    .modal-backdrop {
        display: none !important;
    }

    .priority-indicator {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 6px;
        border-radius: 16px 16px 0 0;
    }

    .priority-indicator.high {
        background: linear-gradient(90deg, #ef4444, #dc2626);
    }

    .priority-indicator.medium {
        background: linear-gradient(90deg, #f59e0b, #d97706);
    }

    .priority-indicator.low {
        background: linear-gradient(90deg, #10b981, #059669);
    }

    .priority-indicator.default {
        background: linear-gradient(90deg, #6366f1, #4f46e5);
    }

    .task-meta {
        background: var(--bg-gray);
        border-radius: 12px;
        padding: 1rem;
        border: 1px solid var(--border);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .fc .fc-toolbar.fc-header-toolbar {
            padding: 1rem;
            flex-direction: column;
            gap: 1rem;
        }

        .fc .fc-toolbar-title {
            font-size: 1.25rem;
        }

        .fc .fc-daygrid-day-frame {
            min-height: 60px;
            padding: 0.5rem;
        }

        .calendar-stats {
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .stat-item {
            flex: 1;
            min-width: 120px;
        }
    }

    /* Loading State */
    .calendar-loading {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 400px;
    }

    .spinner {
        width: 40px;
        height: 40px;
        border: 4px solid var(--border);
        border-top: 4px solid var(--primary);
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    .event-completed {
        background: linear-gradient(135deg, #e5e7eb, #f3f4f6) !important;
        color: #111827 !important;
        border-left: 4px solid #6b7280;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const calendarEl = document.getElementById('modernCalendar');
        const loadingEl = document.getElementById('calendarLoading');
        const tasks = @json($tasks);

        loadingEl.classList.remove('d-none');

        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,listWeek'
            },
            views: {
                timeGridWeek: {
                    dayHeaderFormat: { weekday: 'short', day: 'numeric' }
                },
                listWeek: { buttonText: 'List' }
            },
            events: tasks.map(task => ({
                ...task,
                className: task.completed 
                    ? 'event-completed'
                    : `event-${task.priority || 'default'}`
            })),
            eventClick: function (info) {
                info.jsEvent.preventDefault();
                showTaskModal(info.event);
            },
            eventContent: function (arg) {
                const priority = arg.event.extendedProps.priority || 'default';
                return {
                    html: `
                        <div class="fc-event-main">
                            <span class="priority-icon ${priority}"></span>
                            <span class="fc-event-title">${arg.event.title}</span>
                        </div>`
                };
            },
            height: 'auto',
            nowIndicator: true,
            navLinks: true,
            editable: false,
            selectable: false,
            dayMaxEvents: 3,
            eventDidMount: function (info) {
                info.el.title = info.event.title + 
                    (info.event.extendedProps.description ? ` - ${info.event.extendedProps.description}` : '');
            }
        });

        setTimeout(() => {
            loadingEl.classList.add('d-none');
            calendar.render();
        }, 500);

        function formatDate(date) {
            return new Date(date).toLocaleDateString('en-US', {
                weekday: 'short',
                month: 'short',
                day: 'numeric',
                year: 'numeric'
            });
        }

        function getPriorityClass(priority) {
            return {
                'high': 'bg-danger',
                'medium': 'bg-warning',
                'low': 'bg-success',
                'default': 'bg-primary'
            }[priority] || 'bg-secondary';
        }

        function showTaskModal(task) {
            const modal = new bootstrap.Modal(document.getElementById('modernTaskModal'), {
                backdrop: false
            });

            const priority = task.extendedProps.priority || 'default';
            document.getElementById('modernTaskModalTitle').textContent = task.title;
            document.getElementById('modernTaskModalDescription').textContent =
                task.extendedProps.description || 'No description provided';
            document.getElementById('modalPriorityIndicator').className = `priority-indicator ${priority}`;
            document.getElementById('modernTaskModalPriority').textContent = priority.charAt(0).toUpperCase() + priority.slice(1);
            document.getElementById('modernTaskModalPriority').className = 'badge ' + getPriorityClass(priority);
            document.getElementById('modernTaskModalDueDate').textContent =
                task.start ? formatDate(task.start) : 'No due date';
            document.getElementById('modernTaskModalStatus').textContent =
                task.extendedProps.completed ? 'Completed' : 'Pending';
            document.getElementById('modernTaskModalStatus').className =
                'badge rounded-pill ' + (task.extendedProps.completed ? 'bg-success' : 'bg-warning');

            const completedBtn = document.getElementById('markCompletedBtn');
            if (task.extendedProps.completed) {
                completedBtn.classList.add('d-none');
            } else {
                completedBtn.classList.remove('d-none');
                completedBtn.onclick = function () {
                    markTaskCompleted(task.id);
                };
            }

            const editBtn = document.getElementById('modernTaskEditLink');
            editBtn.href = '#';
            editBtn.onclick = function (e) {
                e.preventDefault();
                showEditModal(task);
                modal.hide();
            };

            modal.show();
        }

        function showEditModal(task) {
            const modal = new bootstrap.Modal(document.getElementById('modernEditTaskModal'), {
                backdrop: false
            });

            document.getElementById('edit_title').value = task.title;
            document.getElementById('edit_description').value = task.extendedProps.description || '';
            document.getElementById('edit_due_date').value = task.start ? new Date(task.start).toISOString().split('T')[0] : '';
            document.getElementById('edit_priority').value = task.extendedProps.priority || 'default';

            const form = document.getElementById('editTaskForm');
            form.action = `/tasks/${task.id}`;

            modal.show();
        }

        function markTaskCompleted(taskId) {
            fetch(`/tasks/${taskId}/complete`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(res => {
                if (!res.ok) throw new Error("Failed to mark task as completed");
                return res.json();
            })
            .then(() => location.reload())
            .catch(err => {
                console.error(err);
                alert('Gagal menyelesaikan task.');
            });
        }

        document.getElementById('editTaskForm')?.addEventListener('submit', function (e) {
            e.preventDefault();

            const form = e.target;
            const taskId = form.action.split('/').pop();
            const formData = new FormData(form);
            formData.append('_method', 'PATCH');

            fetch(`/tasks/${taskId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(res => {
                if (!res.ok) return res.text().then(text => { throw new Error(text); });
                return res.json();
            })
            .then(() => location.reload())
            .catch(err => {
                console.error(err);
                alert('Failed to update task.');
            });
        });
    });
</script>
@endsection
