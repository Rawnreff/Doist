@extends('layouts.app')

@section('content')
<div class="container-fluid px-0">
    <!-- Hero Section -->
    <div class="hero-section bg-gradient-primary text-white py-5">
        <div class="container text-center py-5">
            <h1 class="display-4 fw-bold mb-3">Welcome to NovaTask</h1>
            <p class="lead mb-4">Your personal productivity companion to organize and conquer daily tasks</p>
            <div class="d-flex justify-content-center gap-3">
                <a href="{{ route('tasks.index') }}" class="btn btn-light btn-lg px-4">
                    Go to Tasks <i class="bi bi-arrow-right ms-2"></i>
                </a>
                <a href="#features" class="btn btn-outline-light btn-lg px-4">
                    Explore Features
                </a>
            </div>
        </div>
    </div>

    <!-- User Stats Dashboard -->
    <div class="container py-5">
        <div class="row g-4 mb-5">
            <div class="col-md-6">
                <div class="welcome-card p-4 h-100">
                    <h2><i class="bi bi-graph-up me-2"></i> Quick Start</h2>
                    <p class="text-muted">Here's what you can do to get started:</p>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item bg-transparent">
                            <i class="bi bi-plus-circle-fill text-primary me-2"></i>
                            Create your first task
                        </li>
                        <li class="list-group-item bg-transparent">
                            <i class="bi bi-star-fill text-warning me-2"></i>
                            Set priority levels
                        </li>
                        <li class="list-group-item bg-transparent">
                            <i class="bi bi-calendar-check-fill text-success me-2"></i>
                            Schedule due dates
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-md-6">
                <div class="welcome-card p-4 h-100">
                    <h2><i class="bi bi-trophy-fill me-2"></i> Your Progress</h2>
                    <div class="text-center py-3">
                        <div class="progress-circle mx-auto mb-3" data-value="0">
                            <svg class="progress-circle-chart" viewBox="0 0 36 36">
                                <path class="progress-circle-bg"
                                    d="M18 2.0845
                                    a 15.9155 15.9155 0 0 1 0 31.831
                                    a 15.9155 15.9155 0 0 1 0 -31.831"
                                />
                                <path class="progress-circle-fill"
                                    stroke-dasharray="0, 100"
                                    d="M18 2.0845
                                    a 15.9155 15.9155 0 0 1 0 31.831
                                    a 15.9155 15.9155 0 0 1 0 -31.831"
                                />
                            </svg>
                            <span class="progress-circle-value">0%</span>
                        </div>
                        <p class="text-muted">Start completing tasks to see your progress</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Features Section -->
        <div id="features" class="py-5">
            <h2 class="text-center mb-5">Powerful Features</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-card p-4 h-100">
                        <div class="feature-icon bg-primary bg-opacity-10 text-primary rounded-circle p-3 mb-3">
                            <i class="bi bi-list-check fs-2"></i>
                        </div>
                        <h3>Task Organization</h3>
                        <p class="text-muted">Easily categorize and prioritize your tasks with tags and labels.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card p-4 h-100">
                        <div class="feature-icon bg-success bg-opacity-10 text-success rounded-circle p-3 mb-3">
                            <i class="bi bi-bell-fill fs-2"></i>
                        </div>
                        <h3>Smart Reminders</h3>
                        <p class="text-muted">Get notified before important deadlines and scheduled tasks.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card p-4 h-100">
                        <div class="feature-icon bg-info bg-opacity-10 text-info rounded-circle p-3 mb-3">
                            <i class="bi bi-device-phone fs-2"></i>
                        </div>
                        <h3>Cross-Platform Sync</h3>
                        <p class="text-muted">Access your tasks anywhere, anytime on all your devices.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Start CTA -->
    <div class="bg-light py-5">
        <div class="container text-center py-3">
            <h2 class="mb-4">Ready to boost your productivity?</h2>
            <a href="{{ route('tasks.index') }}" class="btn btn-primary btn-lg px-5">
                Start Using Tasks <i class="bi bi-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</div>

<style>
    .hero-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 0 0 20px 20px;
    }
    
    .welcome-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        border: 1px solid rgba(0,0,0,0.05);
    }
    
    .feature-card {
        border-radius: 12px;
        transition: all 0.3s ease;
        border: 1px solid rgba(0,0,0,0.05);
    }
    
    .feature-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
    }
    
    .btn-outline-light:hover {
        background: rgba(255,255,255,0.1);
    }
    
    /* Progress circle styles */
    .progress-circle {
        width: 120px;
        height: 120px;
        position: relative;
    }
    
    .progress-circle-chart {
        width: 100%;
        height: 100%;
    }
    
    .progress-circle-bg {
        fill: none;
        stroke: #e6e6e6;
        stroke-width: 3;
    }
    
    .progress-circle-fill {
        fill: none;
        stroke: #4f46e5;
        stroke-width: 3;
        stroke-linecap: round;
        transition: stroke-dasharray 0.6s ease;
    }
    
    .progress-circle-value {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 1.2rem;
        font-weight: bold;
        color: #4f46e5;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animate progress circle
    const progressCircle = document.querySelector('.progress-circle');
    const progressValue = progressCircle.querySelector('.progress-circle-value');
    const progressFill = progressCircle.querySelector('.progress-circle-fill');
    
    // Simulate progress animation
    setTimeout(() => {
        progressFill.setAttribute('stroke-dasharray', '25, 100');
        progressValue.textContent = '25%';
    }, 500);
});
</script>
@endsection