<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SecureFind</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    @stack('styles')
</head>
<body>

<div class="app-layout">

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="sidebar-brand">SecureFind</div>

        <nav class="sidebar-menu">
            <a href="{{ route('dashboard') }}"
                class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <span class="icon">
                        <!-- Dashboard -->
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-width="2"
                                d="M3 12l9-9 9 9M4 10v10a1 1 0 001 1h5V14h4v7h5a1 1 0 001-1V10"/>
                        </svg>
                    </span>
                    Dashboard
            </a>

            <a href="{{ route('incidents.report') }}"
                class="{{ request()->routeIs('incidents.report') ? 'active' : '' }}">

                    <span class="icon">
                        <!-- Report Incident -->
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L12.71 3.86a2 2 0 00-2.42 0z"/>
                            <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                d="M12 9v4m0 4h.01"/>
                        </svg>
                    </span>

                    Report Incident
            </a>

            {{-- 
            <a href=<a href="{{ route('lostfound.index') }}">
                <span class="icon">
                    <!-- Lost & Found -->
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-width="2" d="M3 7h18M3 12h18M3 17h18"/>
                    </svg>
                </span>
                Lost &amp; Found
            </a> --}}

            <a href="{{ route('reports.index') }}">
                <span class="icon">
                    <!-- My Reports -->
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-width="2" d="M9 12h6m-6 4h6M7 4h10a2 2 0 012 2v12a2 2 0 01-2 2H7a2 2 0 01-2-2V6a2 2 0 012-2z"/>
                    </svg>
                </span>
                My Reports
            </a> 

            {{--  
            <a href="{{ route('notifications.index') }}" class="has-badge">
                <span class="icon">
                    <!-- Notification Bell -->
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-width="2"
                            d="M15 17h5l-1.4-1.4A2 2 0 0118 14V11a6 6 0 10-12 0v3a2 2 0 01-.6 1.4L4 17h5"/>
                        <path stroke-width="2"
                            d="M9 17a3 3 0 006 0"/>
                    </svg>
                </span>

                <span class="menu-text">Notifications</span>

                <!-- Badge number -->
                <span class="badge-count">3</span>
            </a> 

            <a href="{{ route('profile.edit') }}" class="sidebar-link">
                <span class="icon">
                    <!-- User / Settings icon -->
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <circle cx="12" cy="7" r="4" stroke-width="2"/>
                        <path stroke-width="2" d="M5.5 21a6.5 6.5 0 0113 0"/>
                    </svg>
                </span>
                Profile
            </a> --}}
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

                {{--  
                <!-- Notifications -->
                <div class="icon-btn has-dot">
                    <span class="dot"></span>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-width="2"
                            d="M15 17h5l-1.4-1.4A2 2 0 0118 14V11a6 6 0 10-12 0v3a2 2 0 01-.6 1.4L4 17h5"/>
                        <path stroke-width="2"
                            d="M9 17a3 3 0 006 0"/>
                    </svg>
                </div> --}}

                <!-- Settings -->
                <div class="icon-btn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-width="2"
                            d="M12 15a3 3 0 100-6 3 3 0 000 6z"/>
                        <path stroke-width="2"
                            d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 01-2.83 2.83l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09a1.65 1.65 0 00-1-1.51 1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83-2.83l.06-.06a1.65 1.65 0 00.33-1.82 1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09a1.65 1.65 0 001.51-1 1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06a1.65 1.65 0 001.82.33h0A1.65 1.65 0 009 3.09V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51h0a1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06a1.65 1.65 0 00-.33 1.82v0A1.65 1.65 0 0019.91 11H20a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z"/>
                    </svg>
                </div>

                <!-- User -->
                <div class="user-info">
                    <span>Daniel Iman</span>
                </div>
            </div>
        </header>

        <!-- CONTENT -->
        <main class="content">
            @yield('content')
        </main>

    </div>
</div>

</body>
</html>
