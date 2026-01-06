@extends('layouts.app')

@section('content')

<!-- ===================== STATS ===================== -->
<div class="stats-grid">

    <div class="stat-card stat-danger">
        <div class="stat-left">
            <span class="stat-title">Active Incidents</span>
            <span class="stat-value">12</span>
            <span class="stat-trend up">+2 from last week</span>
        </div>
        <div class="stat-icon stat-danger">
            ⚠️
        </div>
    </div>

    <div class="stat-card stat-warning">
        <div class="stat-left">
            <span class="stat-title">Lost Items</span>
            <span class="stat-value">34</span>
            <span class="stat-trend down">-5 from last week</span>
        </div>
        <div class="stat-icon stat-warning">
            📦
        </div>
    </div>

    {{--  
    <div class="stat-card stat-success">
        <div class="stat-left">
            <span class="stat-title">Found Items</span>
            <span class="stat-value">28</span>
            <span class="stat-trend up">+8 from last week</span>
        </div>
        <div class="stat-icon stat-success">
            ✔️
        </div>
    </div> --}}

    <div class="stat-card stat-neutral">
        <div class="stat-left">
            <span class="stat-title">My Reports</span>
            <span class="stat-value">5</span>
            <span class="stat-sub">2 pending review</span>
        </div>
                <div class="stat-icon stat-neutral">
            📄
        </div>
    </div>
</div>

<!-- ===================== DASHBOARD GRID ===================== -->
<div class="dashboard-grid">

    <!-- LEFT COLUMN -->
    <div class="dashboard-left">

        <!-- Recent Incidents -->
        <div class="card">
            <div class="card-header">
                <h3>Recent Incidents</h3>
                <a href="#" class="view-all">View all →</a>
            </div>

            <div class="incident-list">

                <!-- ITEM -->
                <div class="incident-row">
                    <div class="incident-content">
                        <strong>Suspicious Activity – Library Building</strong>
                        <p>Unidentified person loitering near main entrance</p>

                        <div class="incident-meta">
                            <span>📍 Main Library, 2nd Floor</span>
                            <span>🕒 15 minutes ago</span>
                        </div>
                    </div>
                </div>

                <!-- ITEM -->
                <div class="incident-row">
                    <div class="incident-content">
                        <strong>Unauthorized Access Attempt</strong>
                        <p>Failed keycard access to restricted area</p>

                        <div class="incident-meta">
                            <span>📍 FSKTM, MTW</span>
                            <span>🕒 1 hour ago</span>
                        </div>
                    </div>
                </div>

                <!-- ITEM -->
                <div class="incident-row">
                    <div class="incident-content">
                        <strong>Noise Complaint</strong>
                        <p>Loud music reported in dormitory area</p>

                        <div class="incident-meta">
                            <span>📍 TSN, Block C</span>
                            <span>🕒 2 hours ago</span>
                        </div>
                    </div>
                </div>

                <!-- ITEM -->
                <div class="incident-row">
                    <div class="incident-content">
                        <strong>Vandalism Report</strong>
                        <p>Graffiti found on exterior wall</p>

                        <div class="incident-meta">
                            <span>📍 FKMP</span>
                            <span>🕒 3 hours ago</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{--  
        <!-- Lost & Found Updates -->
        <div class="card">
            <div class="card-header">
                <h3>Lost & Found Updates</h3>
                <a href="#" class="view-all">View all →</a>
            </div>

            <!-- ITEM -->
            <div class="lf-item">
                <div class="lf-left">
                    <div class="lf-image">Backpack<br>Image</div>
                    <div class="lf-info">
                        <strong>Black Backpack</strong>
                        <p>Found in Auditorium, FSKTM</p>
                        <span class="lf-time">🕒 Today, 2:30 PM</span>
                    </div>
                </div>
                <span class="lf-status found">FOUND</span>
            </div>

            <!-- ITEM -->
            <div class="lf-item">
                <div class="lf-left">
                    <div class="lf-image">Laptop<br>Image</div>
                    <div class="lf-info">
                        <strong>ASUS Vivobook 16"</strong>
                        <p>Lost near HEPA Cafeteria</p>
                        <span class="lf-time">🕒 Today, 11:45 AM</span>
                    </div>
                </div>
                <span class="lf-status lost">LOST</span>
            </div>

            <!-- ITEM -->
            <div class="lf-item">
                <div class="lf-left">
                    <div class="lf-image">Wallet<br>Image</div>
                    <div class="lf-info">
                        <strong>Brown Leather Wallet</strong>
                        <p>Found in Parking B8</p>
                        <span class="lf-time">🕒 Yesterday, 4:15 PM</span>
                    </div>
                </div>
                <span class="lf-status found">FOUND</span>
            </div>
        </div> --}}
    </div>

    <!-- RIGHT COLUMN -->
    <div class="dashboard-right">

        <!-- Quick Actions -->
        <div class="card quick-actions">
            <h3>Quick Actions</h3>

            <!-- Report Incident -->
            <a href="{{ route('incidents.report') }}" class="action-btn action-incident">
                <span class="qa-icon">⚠️</span>
                Report Incident
            </a>

            <!-- Report Lost Item -->
            <a href="{{ route('lostfound.report') }}" class="action-btn action-lost">
                <span class="qa-icon">📦</span>
                Report Lost Item
            </a>

            {{--<!-- Search Found Items -->
            <a href="{{ route('found.index') }}" class="action-btn action-search">
                <span class="qa-icon">🔍</span>
                Search Found Items
            </a>--}}
        </div>

        <!-- Emergency Contacts -->
        <div class="card emergency-contacts">
            <h3>Emergency Contacts</h3>

            <div class="emergency-row">
                <span class="emergency-icon">📞</span>
                <span class="emergency-text">
                    <strong>Bahagian Keselamatan UTHM</strong>
                    <span class="number">(07) 453-7146</span>
                </span>
            </div>

            <div class="emergency-row">
                <span class="emergency-icon">🏥</span>
                <span class="emergency-text">
                    <strong>Pusat Kesihatan Universiti (PKU)</strong>
                    <span class="number">(60) 19 868-7854</span>
                </span>
            </div>

            <div class="emergency-row">
                <span class="emergency-icon">🔥</span>
                <span class="emergency-text">
                    <strong>Jabatan Bomba Dan Penyelamat Ayer Hitam</strong>
                    <span class="number">(07) 758-2206</span>
                </span>
            </div>
        </div>
    </div>
</div>

@endsection
