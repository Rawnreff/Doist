<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doist @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/app.css">
    <style>
        :root {
            --primary: #4f46e5;
            --primary-light: #6366f1;
            --text: #1f2937;
            --text-light: #6b7280;
            --bg: #f9fafb;
            --card-bg: #ffffff;
            --border: #e5e7eb;
            --radius: 12px;
            --shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg);
            color: var(--text);
            display: flex;
            min-height: 100vh;
        }
        
        /* Vertical Navbar */
        .sidebar {
            width: 250px;
            background: var(--card-bg);
            box-shadow: var(--shadow);
            display: flex;
            flex-direction: column;
            padding: 1.5rem 0;
            height: 100vh;
            position: fixed;
        }
        
        .sidebar-header {
            padding: 0 1.5rem 1.5rem;
            border-bottom: 1px solid var(--border);
        }
        
        .sidebar-brand {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--primary);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .nav-menu {
            flex: 1;
            padding: 1.5rem;
        }
        
        .nav-item {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: var(--text-light);
            text-decoration: none;
            border-radius: var(--radius);
            margin-bottom: 0.5rem;
            transition: all 0.2s;
        }
        
        .nav-item:hover, .nav-item.active {
            background-color: var(--bg);
            color: var(--primary);
        }
        
        .nav-item i {
            margin-right: 0.75rem;
            font-size: 1.1rem;
            width: 20px;
            text-align: center;
        }
        
        /* User Profile */
        .user-profile {
            padding: 1.5rem;
            border-top: 1px solid var(--border);
            position: relative;
        }
        
        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .user-avatar:hover {
            transform: scale(1.05);
        }
        
        .logout-popup {
            position: absolute;
            bottom: 80px;
            left: 50%;
            transform: translateX(-50%);
            background: var(--card-bg);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: 0.75rem 1rem;
            width: 160px;
            display: none;
            z-index: 10;
        }
        
        .logout-popup.show {
            display: block;
        }
        
        .btn-logout {
            width: 100%;
            padding: 0.5rem;
            background: none;
            border: none;
            color: var(--text);
            text-align: left;
            cursor: pointer;
            border-radius: 4px;
        }
        
        .btn-logout:hover {
            background-color: var(--bg);
            color: var(--primary);
        }
        
        /* Main Content */
        .main-content {
            margin-left: 250px;
            flex: 1;
            padding: 2rem;
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Vertical Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <a href="{{ route('tasks.index') }}" class="sidebar-brand">
                <i class="bi bi-check2-circle"></i>
                <span>Doist</span>
            </a>
        </div>
        
        <div class="nav-menu">
            <a href="{{ route('tasks.index') }}" class="nav-item {{ request()->routeIs('tasks.index') ? 'active' : '' }}">
                <i class="bi bi-list-task"></i>
                <span>Tasks</span>
            </a>
            <a href="{{ route('podomoro') }}" class="nav-item {{ request()->routeIs('podomoro') ? 'active' : '' }}">
                <i class="bi bi-clock"></i>
                <span>Focus</span>
            </a>
            <a href="{{ route('calendar') }}" class="nav-item {{ request()->routeIs('calendar') ? 'active' : '' }}">
                <i class="bi bi-calendar"></i>
                <span>Calendar</span>
            </a>
            <a href="{{ route('trash.index') }}" class="nav-item {{ request()->routeIs('trash.index') ? 'active' : '' }}">
                <i class="bi bi-trash"></i>
                <span>Trash</span>
                @if(($deletedTasksCount ?? 0) > 0)
                    <span class="badge bg-danger ms-auto">{{ $deletedTasksCount }}</span>
                @endif
            </a>
        </div>
        
        <div class="user-profile">
            @auth
                <div class="user-avatar" id="userAvatar">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="logout-popup" id="logoutPopup">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-logout">
                            <i class="bi bi-box-arrow-right"></i> Sign Out
                        </button>
                    </form>
                </div>
            @endauth
        </div>
    </div>

    <!-- Main Content -->
    <main class="main-content">
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
    <script>
        // Toggle logout popup
        document.getElementById('userAvatar')?.addEventListener('click', function() {
            document.getElementById('logoutPopup').classList.toggle('show');
        });
        
        // Close popup when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.user-profile')) {
                const popup = document.getElementById('logoutPopup');
                if (popup) popup.classList.remove('show');
            }
        });
    </script>
</body>
</html>