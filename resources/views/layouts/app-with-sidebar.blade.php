<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doist | @yield('title')</title>
    @include('partials.styles')
    @if(isset($tasks))
        <meta name="tasks" content="{{ json_encode($tasks) }}">
    @endif
</head>
<body> pomodoro
    <div class="app-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <a href="{{ route('tasks.index') }}" class="sidebar-brand">Doist</a>
            </div>
            <nav class="sidebar-nav">
                <a href="{{ route('tasks.index') }}" class="nav-item">
                    <i class="bi bi-check-circle"></i> Tasks
                </a>
                <a href="{{ route('calendar') }}" class="nav-item">
                    <i class="bi bi-calendar"></i> Calendar
                </a>
                <a href="{{ route('podomoro') }}" class="nav-item">
                    <i class="bi bi-clock"></i> Podomoro
                </a>
            </nav>
            <div class="sidebar-footer">
                <div class="user-info" style="display: flex; align-items: center; gap: 8px;">
                    <div class="user-avatar">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}`
                    </div>
                    <span class="user-name">{{ Auth::user()->name }}</span>
                </div>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-logout">
                        <i class="bi bi-box-arrow-right"></i> Sign Out
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Content -->
        <main class="main-content">
            @yield('content')
        </main>
    </div>

    @include('partials.scripts')
</body>
</html>