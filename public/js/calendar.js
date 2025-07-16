document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    const currentMonthEl = document.getElementById('currentMonth');
    const prevMonthBtn = document.getElementById('prevMonth');
    const nextMonthBtn = document.getElementById('nextMonth');
    const todayBtn = document.getElementById('todayBtn');
    const selectedDateEl = document.getElementById('selectedDate');
    const dateTasksEl = document.getElementById('dateTasks');
    
    let currentDate = new Date();
    let tasks = JSON.parse(document.querySelector('meta[name="tasks"]').content);
    
    // Initialize calendar
    renderCalendar(currentDate);
    
    // Navigation
    prevMonthBtn.addEventListener('click', () => {
        currentDate.setMonth(currentDate.getMonth() - 1);
        renderCalendar(currentDate);
    });
    
    nextMonthBtn.addEventListener('click', () => {
        currentDate.setMonth(currentDate.getMonth() + 1);
        renderCalendar(currentDate);
    });
    
    todayBtn.addEventListener('click', () => {
        currentDate = new Date();
        renderCalendar(currentDate);
    });
    
    function renderCalendar(date) {
        // Update month header
        currentMonthEl.textContent = date.toLocaleString('default', { 
            month: 'long', 
            year: 'numeric' 
        });
        
        // Clear previous calendar
        calendarEl.innerHTML = '';
        
        // Add day headers
        const days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        days.forEach(day => {
            const dayHeader = document.createElement('div');
            dayHeader.className = 'calendar-day-header';
            dayHeader.textContent = day;
            calendarEl.appendChild(dayHeader);
        });
        
        // Get first day of month and total days
        const firstDay = new Date(date.getFullYear(), date.getMonth(), 1);
        const lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0);
        const daysInMonth = lastDay.getDate();
        const startingDay = firstDay.getDay();
        
        // Add empty cells for days before first day of month
        for (let i = 0; i < startingDay; i++) {
            const emptyDay = document.createElement('div');
            emptyDay.className = 'calendar-day empty';
            calendarEl.appendChild(emptyDay);
        }
        
        // Add days of month
        const today = new Date();
        for (let i = 1; i <= daysInMonth; i++) {
            const dayEl = document.createElement('div');
            dayEl.className = 'calendar-day';
            
            // Highlight today
            if (date.getFullYear() === today.getFullYear() && 
                date.getMonth() === today.getMonth() && 
                i === today.getDate()) {
                dayEl.classList.add('today');
            }
            
            // Day number
            const dayNumber = document.createElement('div');
            dayNumber.className = 'calendar-day-number';
            dayNumber.textContent = i;
            dayEl.appendChild(dayNumber);
            
            // Tasks for this day
            const currentDateStr = `${date.getFullYear()}-${(date.getMonth() + 1).toString().padStart(2, '0')}-${i.toString().padStart(2, '0')}`;
            const dayTasks = tasks.filter(task => task.start === currentDateStr);
            
            if (dayTasks.length > 0) {
                const tasksContainer = document.createElement('div');
                tasksContainer.className = 'calendar-day-tasks';
                
                dayTasks.forEach(task => {
                    const taskDot = document.createElement('div');
                    taskDot.className = 'calendar-task-dot';
                    taskDot.style.backgroundColor = task.color;
                    tasksContainer.appendChild(taskDot);
                });
                
                dayEl.appendChild(tasksContainer);
            }
            
            // Click event to show tasks for this day
            dayEl.addEventListener('click', () => {
                showTasksForDate(currentDateStr);
            });
            
            calendarEl.appendChild(dayEl);
        }
    }
    
    function showTasksForDate(dateStr) {
        const date = new Date(dateStr);
        selectedDateEl.textContent = date.toLocaleDateString('en-US', { 
            weekday: 'long', 
            month: 'long', 
            day: 'numeric' 
        });
        
        const filteredTasks = tasks.filter(task => task.start === dateStr);
        
        if (filteredTasks.length > 0) {
            dateTasksEl.innerHTML = filteredTasks.map(task => `
                <div class="calendar-task-item" data-task-id="${task.id}">
                    <input type="checkbox" ${task.completed ? 'checked' : ''}>
                    <span class="task-title ${task.completed ? 'completed' : ''}">
                        ${task.title}
                    </span>
                    <span class="task-priority" style="background-color: ${task.color}20; color: ${task.color}">
                        ${task.priority}
                    </span>
                </div>
            `).join('');
        } else {
            dateTasksEl.innerHTML = '<p>No tasks for this day</p>';
        }
    }
    
    // Initialize with today's tasks
    const todayStr = new Date().toISOString().split('T')[0];
    showTasksForDate(todayStr);
});