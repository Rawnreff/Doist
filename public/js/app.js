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