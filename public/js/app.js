import '../../resources/js/bootstrap';

// Toggle Task Completion
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.task-checkbox input');
    
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const taskId = this.getAttribute('data-task-id');
            const isChecked = this.checked ? 1 : 0;
            const form = document.getElementById(`update-form-${taskId}`);
            
            // Update hidden input value
            form.querySelector('input[name="completed"]').value = isChecked;
            
            // Submit form
            form.submit();
            
            // Toggle completed class
            const taskItem = this.closest('.task-item');
            taskItem.classList.toggle('completed', this.checked);
        });
    });
});

function openEditModal(taskId) {
    const modal = document.getElementById('editModal');
    const form = document.getElementById('editTaskForm');
    const taskItem = document.querySelector(`[data-task-id="${taskId}"]`);
    
    // Set form action URL
    form.action = `/tasks/${taskId}`;
    
    // Set form values
    document.getElementById('edit_title').value = taskItem.querySelector('.task-title').textContent.trim();
    document.getElementById('edit_description').value = taskItem.getAttribute('data-description') || '';
    
    // Show modal
    modal.style.display = 'block';
}

function closeEditModal() {
    const modal = document.getElementById('editModal');
    modal.style.display = 'none';
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('editModal');
    if (event.target == modal) {
        modal.style.display = 'none';
    }
}