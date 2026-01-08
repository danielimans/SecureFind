<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SecureFind</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    @yield('styles')
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>

    <div class="app-layout">

        <!-- SIDEBAR -->
        <aside class="sidebar">
            <div class="sidebar-brand">
                <i class="fas fa-shield-alt"></i> SecureFind
            </div>

            <nav class="sidebar-menu">
                <a href="{{ route('dashboard') }}"
                    class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="fas fa-home"></i><span>Dashboard</span>
                </a>

                <a href="{{ route('incidents.report') }}"
                    class="sidebar-link {{ request()->routeIs('incidents.report') ? 'active' : '' }}">
                        <i class="fas fa-exclamation-triangle"></i><span>Incident</span>
                </a>

                <a href="{{ route('lostfound.report') }}" 
                    class="sidebar-link {{ request()->routeIs('lostfound.report') ? 'active' : '' }}">
                    <i class="fas fa-box"></i><span>Lost & Found</span>
                </a>

                <a href="{{ route('reports.index') }}"
                    class="sidebar-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                    <i class="fas fa-file-alt"></i><span>My Reports</span>
                </a>

                <div class="sidebar-divider"></div>

                <a href="{{ route('profile.edit') }}"
                    class="sidebar-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                    <i class="fas fa-user"></i><span>Profile</span>
                </a>

                <a href="{{ route('settings.index') }}"
                    class="sidebar-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                    <i class="fas fa-cog"></i><span>Settings</span>
                </a>
            </nav>

            <div class="sidebar-user">
                <strong>{{ auth()->user()->name }}</strong>
                <small>{{ ucfirst(auth()->user()->role) }}</small>
            </div>
        </aside>

        <!-- MAIN -->
        <div class="main">

            <!-- TOPBAR -->
            <header class="topbar">
                <h2 class="page-title">
                    @yield('page-title', 'Dashboard')
                </h2>

                <div class="topbar-actions">

                    <!-- Settings -->
                    <div class="settings-dropdown">
                        <button class="icon-btn" id="settingsToggle">
                            <i class="fas fa-cog"></i>
                        </button>

                        <div class="dropdown-menu" id="settingsMenu">
                            <div class="dropdown-header">
                                <strong>{{ auth()->user()->name }}</strong>
                                <small>{{ auth()->user()->email }}</small>
                            </div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item logout">
                                    <i class="fas fa-sign-out-alt"></i><span>Logout</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- CONTENT -->
            <main class="content">
                @yield('content')
            </main>

            <!-- Footer -->
            @include('components.footer')
        </div>
    </div>

    <script>
        const toggle = document.getElementById('settingsToggle');
        const menu = document.getElementById('settingsMenu');

        toggle.addEventListener('click', (e) => {
            e.stopPropagation();
            menu.style.display = menu.style.display === 'flex' ? 'none' : 'flex';
        });

        document.addEventListener('click', () => {
            menu.style.display = 'none';
        });
    </script>

    @yield('scripts')

</body>
</html>