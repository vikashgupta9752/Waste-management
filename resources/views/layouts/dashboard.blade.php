<!DOCTYPE html>
<html lang="en" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Waste Management System - @yield('title')</title>
    
    <!-- Alpine JS -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- External Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/leaflet.heat@0.2.0/dist/leaflet-heat.js"></script>

    <style>
        body { background-color: #f4f7f6; transition: background-color 0.3s; }
        .sidebar { min-height: 100vh; background-color: #2c3e50; color: white; padding-top: 20px;}
        .sidebar a { color: #ecf0f1; text-decoration: none; display: block; padding: 10px 20px; margin-bottom: 5px; }
        .sidebar a:hover, .sidebar a.active { background-color: #1abc9c; border-radius: 4px; }
        .main-content { padding: 20px; }
        .card { border-radius: 10px; border: none; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .stat-card { color: white; padding: 20px; border-radius: 10px; }
        .bg-primary-custom { background-color: #3498db; }
        .bg-success-custom { background-color: #2ecc71; }
        .bg-warning-custom { background-color: #f1c40f; }
        .bg-danger-custom { background-color: #e74c3c; }

        /* Dark Mode overrides for Bootstrap */
        .dark body { background-color: #111827; color: #f3f4f6; }
        .dark .card { background-color: #1f2937; color: #f3f4f6; }
        .dark .table { color: #f3f4f6; border-color: #374151; }
        .dark .table thead th { border-bottom-color: #4b5563; color: #f3f4f6; }
        .dark .form-select, .dark .form-control { background-color: #374151; color: white; border-color: #4b5563; }
        .dark .modal-content { background-color: #1f2937; color: white; }
    </style>
    @yield('head')
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 sidebar d-none d-md-block">
                <h4 class="text-center mb-4"><i class="fa-solid fa-leaf"></i> EcoWaste</h4>
                @yield('sidebar')
                <hr>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="#" onclick="event.preventDefault(); this.closest('form').submit();"><i class="fa-solid fa-sign-out-alt"></i> Logout</a>
                </form>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-10 main-content">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>@yield('title')</h2>
                    <div class="d-flex align-items-center">
                        <button @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)" class="btn btn-outline-secondary me-3 border-0">
                            <i class="fa-solid" :class="darkMode ? 'fa-sun text-warning' : 'fa-moon'"></i>
                        </button>

                        <!-- Notification Bell -->
                        <div class="dropdown me-3">
                            <button class="btn btn-outline-secondary border-0 position-relative" type="button" data-bs-toggle="dropdown">
                                <i class="fa-solid fa-bell"></i>
                                @if(Auth::user()->appNotifications()->where('is_read', false)->count() > 0)
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">
                                        {{ Auth::user()->appNotifications()->where('is_read', false)->count() }}
                                    </span>
                                @endif
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end p-2 shadow" style="width: 300px; border-radius: 12px; border: none;">
                                <li class="dropdown-header border-bottom mb-2 pb-2">Recent Notifications</li>
                                @forelse(Auth::user()->appNotifications()->latest()->take(5)->get() as $notif)
                                    <li>
                                        <div class="dropdown-item small border-bottom pb-2 mb-2">
                                            <div class="fw-bold text-{{ $notif->type }}">{{ $notif->title }}</div>
                                            <div class="text-muted" style="white-space: normal;">{{ $notif->message }}</div>
                                            <div class="text-end" style="font-size: 0.7rem; color: #999;">{{ $notif->created_at->diffForHumans() }}</div>
                                        </div>
                                    </li>
                                @empty
                                    <li class="text-center py-4 text-muted">
                                        <i class="fa-regular fa-bell-slash fa-2x mb-2 d-block opacity-25"></i>
                                        No new notifications
                                    </li>
                                @endforelse
                                <li><a class="dropdown-item text-center small text-primary" href="#">View All Notifications</a></li>
                            </ul>
                        </div>

                        <span class="me-3 fw-bold">Hello, {{ Auth::user()->name }}</span>
                    </div>
                </div>
                
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @yield('scripts')
</body>
</html>
