@extends('layouts.app')

@section('title', 'Pomodoro Timer')

@section('content')
<div class="pomodoro-app">
    <div class="pomodoro-card">
        <div class="pomodoro-header">
            <h2>Focus Timer</h2>
            <div class="pomodoro-tabs">
                <button class="tab-btn active" data-mode="pomodoro">Pomodoro</button>
                <button class="tab-btn" data-mode="short-break">Short Break</button>
                <button class="tab-btn" data-mode="long-break">Long Break</button>
            </div>
        </div>

        <div class="pomodoro-display">
            <span id="minutes">25</span>:<span id="seconds">00</span>
        </div>

        <div class="pomodoro-controls">
            <button id="startBtn" class="control-btn start-btn">    
                <i class="bi bi-play-fill"></i> Start
            </button>
            <button id="resetBtn" class="control-btn reset-btn">
                <i class="bi bi-arrow-counterclockwise"></i> Reset
            </button>
        </div>

        <div class="pomodoro-session">
            <span id="sessionCount">Session: 1/4</span>
        </div>

        <div class="pomodoro-task">
            <h4>Current Task</h4>
            <div class="select-wrapper">
                <select id="taskSelect" class="task-input">
                    <option value="">Select a task to focus on</option>
                    @foreach($pendingTasks as $task)
                        <option value="{{ $task->id }}">{{ $task->title }}</option>
                    @endforeach
                </select>
                <i class="bi bi-chevron-down dropdown-icon"></i>
            </div>
        </div>

        <div class="pomodoro-slider-time">
            <label for="customSlider">Custom Timer (1–180 minutes)</label>
            <input type="range" id="customSlider" min="1" max="180" value="25" />
            <div class="slider-value"><span id="sliderValue">25</span> min</div>
            <button id="applyCustomTime" class="control-btn custom-btn">Use Custom Time</button>
        </div>
    </div>

    <div id="webNotification" class="web-notification-popup">
        <div class="notification-content">
            <h3>Timer Selesai!</h3>
            <p id="notificationMessage"></p>
            <div class="notification-actions">
                <button id="closeNotification" class="notification-btn close-btn">Tutup</button>
                <button id="repeatNotification" class="notification-btn repeat-btn">Ulangi Waktu</button>
            </div>
        </div>
    </div>
</div>

<style>
    :root {
        --primary: #4F46E5; /* Indigo */
        --primary-dark: #4338CA;
        --accent: #FDE047; /* Yellow */
        --bg: #F3F4F6; /* Light Gray */
        --card-bg: #FFFFFF;
        --text: #1F2937; /* Dark Gray */
        --text-light: #6B7280;
        --border: #E5E7EB;
        --shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        --radius: 0.75rem;
    }

    /* Dark Mode Variables (optional, if you have a dark mode toggle) */
    .dark-mode {
        --bg: #1A202C; /* Darker background */
        --card-bg: #2D3748; /* Darker card */
        --text: #E2E8F0; /* Lighter text */
        --text-light: #A0AEC0;
        --border: #4A5568;
    }

    body {
        font-family: 'Inter', sans-serif;
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        background-color: var(--bg);
        color: var(--text);
    }

    .pomodoro-app {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: calc(100vh - 120px);
        padding: 2rem;
        background-color: var(--bg);
        position: relative; /* Penting untuk notifikasi popup */
    }

    .pomodoro-card {
        width: 100%;
        max-width: 500px;
        background: var(--card-bg);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        padding: 2rem;
        text-align: center;
    }

    .pomodoro-header h2 {
        font-size: 1.75rem;
        color: var(--text);
        margin-bottom: 1rem;
    }

    .pomodoro-tabs {
        display: flex;
        justify-content: center;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
    }

    .tab-btn {
        padding: 0.5rem 1rem;
        border: none;
        border-radius: 50px;
        background-color: var(--bg);
        color: var(--text-light);
        cursor: pointer;
        font-size: 0.875rem;
        transition: all 0.3s ease;
    }

    .tab-btn.active {
        background-color: var(--primary);
        color: white;
    }

    .pomodoro-display {
        font-size: 5rem;
        font-family: 'Inter', monospace;
        color: var(--text);
        margin: 1.5rem 0;
        line-height: 1;
    }

    .pomodoro-controls {
        display: flex;
        justify-content: center;
        gap: 1.25rem;
        margin: 2rem 0;
    }

    .control-btn {
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 50px;
        font-size: 0.95rem;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
    }

    .start-btn {
        background-color: var(--primary);
        color: white;
        box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3);
    }

    .reset-btn {
        background-color: var(--bg);
        color: var(--text-light);
    }

    .pomodoro-session {
        font-size: 0.875rem;
        color: var(--text-light);
        margin-bottom: 2rem;
    }

    .pomodoro-task {
        margin-top: 2rem;
        text-align: left;
    }

    .pomodoro-task h4 {
        font-size: 1.15rem; /* Ukuran font lebih kecil */
        color: var(--text);
        margin-bottom: 0.75rem; /* Sesuaikan margin bawah */
        font-weight: 600; /* Sedikit lebih tebal dari teks biasa tapi tidak sebesar h2 */
    }

    .select-wrapper {
        position: relative;
        width: 100%;
    }

    .task-input {
        width: 100%;
        padding: 0.875rem 1rem;
        border: 1px solid var(--border);
        border-radius: 8px;
        background-color: var(--bg);
        color: var(--text);
        font-size: 0.9375rem;
        margin-bottom: 1rem;
        -webkit-appearance: none; /* Hapus gaya default browser untuk select */
        -moz-appearance: none;
        appearance: none;
        cursor: pointer;
        padding-right: 2.5rem; /* Tambahkan padding agar ikon tidak menutupi teks */
    }

    .task-input:focus {
        outline: none;
        border-color: var(--primary); /* Warna border saat fokus */
        box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.2); /* Efek shadow saat fokus */
    }

    .dropdown-icon {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-75%); /* Sesuaikan posisi vertikal ikon */
        color: var(--text-light);
        pointer-events: none; /* Agar klik tetap tembus ke select */
        font-size: 0.9rem;
    }

    .pomodoro-slider-time {
        text-align: left;
        margin-top: 1rem;
    }

    .pomodoro-slider-time label {
        font-size: 0.875rem;
        font-weight: 500;
        color: var(--text-light);
        display: block;
        margin-bottom: 0.5rem;
    }

    .pomodoro-slider-time input[type="range"] {
        width: 100%;
        -webkit-appearance: none;
        background: var(--bg);
        height: 6px;
        border-radius: 5px;
        outline: none;
        margin-bottom: 0.5rem;
    }

    .pomodoro-slider-time input[type="range"]::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 18px;
        height: 18px;
        background: var(--primary);
        border-radius: 50%;
        cursor: pointer;
        box-shadow: 0 0 4px rgba(0,0,0,0.2);
    }

    .slider-value {
        font-size: 0.875rem;
        color: var(--text-light);
        margin-bottom: 1rem;
    }

    .custom-btn {
        background-color: var(--primary);
        color: white;
        width: 100%;
        justify-content: center;
    }

    .custom-btn:hover {
        background-color: var(--primary-dark);
    }

    /* --- Web Notification Styling --- */
    .web-notification-popup {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.6); /* Overlay gelap */
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 1000;
        opacity: 0; /* Dimulai tersembunyi */
        visibility: hidden;
        transition: opacity 0.3s ease, visibility 0.3s ease;
    }

    .web-notification-popup.show {
        opacity: 1;
        visibility: visible;
    }

    .notification-content {
        background: var(--card-bg);
        padding: 2.5rem;
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        text-align: center;
        max-width: 400px;
        width: 90%;
        transform: translateY(-20px); /* Efek masuk dari atas */
        transition: transform 0.3s ease;
    }

    .web-notification-popup.show .notification-content {
        transform: translateY(0);
    }

    .notification-content h3 {
        font-size: 1.8rem;
        color: var(--primary);
        margin-bottom: 1rem;
    }

    .notification-content p {
        font-size: 1.1rem;
        color: var(--text);
        margin-bottom: 2rem;
    }

    .notification-actions {
        display: flex;
        justify-content: center;
        gap: 1rem;
    }

    .notification-btn {
        padding: 0.8rem 1.8rem;
        border: none;
        border-radius: 50px;
        font-size: 0.95rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .close-btn {
        background-color: var(--bg);
        color: var(--text-light);
        border: 1px solid var(--border);
    }

    .close-btn:hover {
        background-color: var(--border);
    }

    .repeat-btn {
        background-color: var(--primary);
        color: white;
        box-shadow: 0 4px 10px rgba(79, 70, 229, 0.2);
    }

    .repeat-btn:hover {
        background-color: var(--primary-dark);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const minutesDisplay = document.getElementById('minutes');
        const secondsDisplay = document.getElementById('seconds');
        const startBtn = document.getElementById('startBtn');
        const resetBtn = document.getElementById('resetBtn');
        const sessionCount = document.getElementById('sessionCount');
        const tabButtons = document.querySelectorAll('.tab-btn');
        const timerDisplay = document.querySelector('.pomodoro-display');

        const taskSelect = document.getElementById('taskSelect');
        const slider = document.getElementById('customSlider');
        const sliderValue = document.getElementById('sliderValue');
        const applyCustomBtn = document.getElementById('applyCustomTime');

        // Notifikasi web elemen
        const webNotification = document.getElementById('webNotification');
        const notificationMessage = document.getElementById('notificationMessage');
        const closeNotificationBtn = document.getElementById('closeNotification');
        const repeatNotificationBtn = document.getElementById('repeatNotification');

        let timer;
        let minutes = 25;
        let seconds = 0;
        let isRunning = false;
        let currentMode = 'pomodoro';
        let sessionCountValue = 1;
        let lastCustomTime = 25; // Untuk menyimpan waktu kustom terakhir yang digunakan

        const modes = {
            pomodoro: { minutes: 25, color: 'var(--primary)', notification: 'Waktu fokus telah berakhir! Saatnya istirahat atau lanjutkan.' },
            'short-break': { minutes: 5, color: '#10B981', notification: 'Waktu istirahat singkat telah selesai! Kembali fokus.' },
            'long-break': { minutes: 15, color: '#3B82F6', notification: 'Waktu istirahat panjang telah selesai! Kembali fokus.' },
            'custom': { minutes: 25, color: 'var(--primary)', notification: 'Waktu kustom telah selesai!' } // Default untuk kustom
        };

        updateDisplay();

        tabButtons.forEach(button => {
            button.addEventListener('click', function () {
                if (isRunning) return;
                tabButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                currentMode = this.dataset.mode;
                minutes = modes[currentMode].minutes;
                seconds = 0;
                updateDisplay();
                timerDisplay.style.color = modes[currentMode].color;
            });
        });

        startBtn.addEventListener('click', function () {
            isRunning ? pauseTimer() : startTimer();
        });

        resetBtn.addEventListener('click', resetTimer);

        applyCustomBtn.addEventListener('click', () => {
            if (isRunning) return;
            minutes = parseInt(slider.value);
            lastCustomTime = minutes; // Simpan waktu kustom
            seconds = 0;
            currentMode = 'custom';
            updateDisplay();
            tabButtons.forEach(btn => btn.classList.remove('active')); // Hapus active dari tab lain
            // Tambahkan kelas active ke tombol "Use Custom Time" jika ingin visualnya menonjol
            // applyCustomBtn.classList.add('active'); // Opsional
        });

        slider.addEventListener('input', function () {
            sliderValue.textContent = this.value;
        });

        // Event listener untuk tombol notifikasi
        closeNotificationBtn.addEventListener('click', () => {
            hideWebNotification();
        });

        repeatNotificationBtn.addEventListener('click', () => {
            hideWebNotification();
            resetTimer(); // Reset timer ke waktu mode saat ini
            startTimer(); // Mulai lagi timer
        });

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
            if (currentMode === 'custom') {
                minutes = lastCustomTime; // Gunakan waktu kustom terakhir
            } else {
                minutes = modes[currentMode]?.minutes ?? 25;
            }
            seconds = 0;
            updateDisplay();
            startBtn.innerHTML = '<i class="bi bi-play-fill"></i> Start';
            // Pastikan tab yang benar tetap aktif setelah reset
            if (currentMode !== 'custom') {
                tabButtons.forEach(btn => btn.classList.remove('active'));
                document.querySelector(`.tab-btn[data-mode="${currentMode}"]`)?.classList.add('active');
            }
        }

        function updateDisplay() {
            minutesDisplay.textContent = minutes.toString().padStart(2, '0');
            secondsDisplay.textContent = seconds.toString().padStart(2, '0');
        }

        function timerComplete() {
            clearInterval(timer);
            isRunning = false;
            playCompletionSound();
            showCompletionNotification(); // Menampilkan notifikasi web
            
            // Logika untuk transisi mode setelah timer selesai
            if (currentMode === 'pomodoro') {
                sessionCountValue++;
                currentMode = (sessionCountValue % 4 === 0) ? 'long-break' : 'short-break';
            } else if (currentMode === 'short-break' || currentMode === 'long-break') {
                currentMode = 'pomodoro';
            }
            // Jika mode kustom, tetap di mode kustom atau biarkan pengguna memilih
            
            updateSessionDisplay();
            startBtn.innerHTML = '<i class="bi bi-play-fill"></i> Start'; // Kembali ke tombol start
            
            // Set ulang waktu untuk mode berikutnya atau waktu kustom jika mode custom
            if (currentMode === 'custom') {
                minutes = lastCustomTime;
            } else {
                minutes = modes[currentMode]?.minutes ?? 25;
            }
            seconds = 0;
            updateDisplay();
        }

        function handleSessionProgression() {
            // Logika ini dipindahkan ke timerComplete untuk eksekusi yang lebih tepat
        }

        function updateSessionDisplay() {
            if (currentMode === 'pomodoro') {
                sessionCount.textContent = `Session: ${Math.min(sessionCountValue, 4)}/4`;
            } else {
                 sessionCount.textContent = `Break Time`; // Teks untuk waktu istirahat
            }
            // Perbarui visual tab aktif
            tabButtons.forEach(btn => btn.classList.remove('active'));
            const nextModeButton = document.querySelector(`.tab-btn[data-mode="${currentMode}"]`);
            if (nextModeButton) {
                nextModeButton.classList.add('active');
            }
        }

        function playCompletionSound() {
            const audio = new Audio('https://assets.mixkit.co/sfx/preview/mixkit-alarm-digital-clock-beep-989.mp3');
            audio.play().catch(() => {});
        }

        function showCompletionNotification() {
            const message = modes[currentMode]?.notification ?? 'Waktu selesai!';
            notificationMessage.textContent = message;
            webNotification.classList.add('show'); // Tampilkan notifikasi popup
            
            // Jika ada notifikasi browser (opsional, karena sudah ada popup kustom)
            if (Notification.permission === 'granted') {
                new Notification('Timer Selesai!', {
                    body: message,
                    icon: '/favicon.ico'
                });
            } else if (Notification.permission !== 'denied') {
                Notification.requestPermission();
            }
        }

        function hideWebNotification() {
            webNotification.classList.remove('show'); // Sembunyikan notifikasi popup
        }
    });
</script>
@endsection