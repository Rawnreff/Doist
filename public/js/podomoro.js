document.addEventListener('DOMContentLoaded', function() {
    const minutesDisplay = document.getElementById('minutes');
    const secondsDisplay = document.getElementById('seconds');
    const startBtn = document.getElementById('startBtn');
    const resetBtn = document.getElementById('resetBtn');
    const sessionCount = document.getElementById('sessionCount');
    const modeButtons = document.querySelectorAll('.mode-btn');
    const taskSelect = document.getElementById('taskSelect');
    
    let timer;
    let minutes = 25;
    let seconds = 0;
    let isRunning = false;
    let currentMode = 'podomoro';
    let sessionsCompleted = 0;
    
    // Mode presets
    const modes = {
        podomoro: { minutes: 25, color: '#ef4444' },
        'short-break': { minutes: 5, color: '#10b981' },
        'long-break': { minutes: 15, color: '#3b82f6' }
    };
    
    // Initialize
    updateDisplay();
    
    // Mode selection
    modeButtons.forEach(button => {
        button.addEventListener('click', function() {
            if (isRunning) return;
            
            modeButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            currentMode = this.dataset.mode;
            
            minutes = modes[currentMode].minutes;
            seconds = 0;
            updateDisplay();
        });
    });
    
    // Start/Pause button
    startBtn.addEventListener('click', function() {
        if (isRunning) {
            pauseTimer();
        } else {
            startTimer();
        }
    });
    
    // Reset button
    resetBtn.addEventListener('click', resetTimer);
    
    function startTimer() {
        if (isRunning) return;
        
        isRunning = true;
        startBtn.innerHTML = '<i class="bi bi-pause-fill"></i> Pause';
        
        timer = setInterval(() => {
            if (seconds === 0) {
                if (minutes === 0) {
                    timerComplete();
                    return;
                }
                minutes--;
                seconds = 59;
            } else {
                seconds--;
            }
            
            updateDisplay();
        }, 1000);
    }
    
    function pauseTimer() {
        clearInterval(timer);
        isRunning = false;
        startBtn.innerHTML = '<i class="bi bi-play-fill"></i> Start';
    }
    
    function resetTimer() {
        clearInterval(timer);
        isRunning = false;
        minutes = modes[currentMode].minutes;
        seconds = 0;
        startBtn.innerHTML = '<i class="bi bi-play-fill"></i> Start';
        updateDisplay();
    }
    
    function timerComplete() {
        clearInterval(timer);
        isRunning = false;
        
        // Play sound
        const audio = new Audio('https://assets.mixkit.co/sfx/preview/mixkit-alarm-digital-clock-beep-989.mp3');
        audio.play();
        
        // Update session count for podomoro mode
        if (currentMode === 'podomoro') {
            sessionsCompleted++;
            const sessionNumber = (sessionsCompleted % 4) + 1;
            sessionCount.textContent = `Session: ${sessionNumber}/4`;
            
            // Switch to break after podomoro
            if (sessionNumber === 4) {
                currentMode = 'long-break';
            } else {
                currentMode = 'short-break';
            }
            
            // Update active button
            modeButtons.forEach(btn => btn.classList.remove('active'));
            document.querySelector(`.mode-btn[data-mode="${currentMode}"]`).classList.add('active');
        } else {
            // Switch back to podomoro after break
            currentMode = 'podomoro';
            modeButtons.forEach(btn => btn.classList.remove('active'));
            document.querySelector('.mode-btn[data-mode="podomoro"]').classList.add('active');
        }
        
        minutes = modes[currentMode].minutes;
        seconds = 0;
        updateDisplay();
        startBtn.innerHTML = '<i class="bi bi-play-fill"></i> Start';
        
        // Show notification
        if (Notification.permission === 'granted') {
            new Notification('Timer Completed!', {
                body: currentMode === 'podomoro' ? 'Time for a break!' : 'Time to work!'
            });
        }
    }
    
    function updateDisplay() {
        minutesDisplay.textContent = minutes.toString().padStart(2, '0');
        secondsDisplay.textContent = seconds.toString().padStart(2, '0');
    }
    
    // Request notification permission
    if (Notification.permission !== 'granted') {
        Notification.requestPermission();
    }
    
    // Task selection
    taskSelect.addEventListener('change', function() {
        if (this.value) {
            // You could add logic to highlight the selected task
            console.log(`Selected task: ${this.value}`);
        }
    });
});