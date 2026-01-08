@extends('layouts.app')

@section('content')

<!-- ===================== STATS ===================== -->
<div class="stats-grid">

    <div class="stat-card stat-danger">
        <div class="stat-left">
            <span class="stat-title">Active Incidents</span>
            <span class="stat-value">{{ $activeIncidents }}</span>
            <span class="stat-trend {{ $activeIncidentsTrend }}">
                {{ $activeIncidentsLastWeek > 0 ? '+' . $activeIncidentsLastWeek : '0' }} this week
            </span>
        </div>
        <div class="stat-icon stat-danger">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
    </div>

    <div class="stat-card stat-warning">
        <div class="stat-left">
            <span class="stat-title">Lost Items</span>
            <span class="stat-value">{{ $lostItems }}</span>
            <span class="stat-trend {{ $lostItemsTrend }}">
                {{ $lostItemsLastWeek > 0 ? '+' . $lostItemsLastWeek : '0' }} this week
            </span>
        </div>
        <div class="stat-icon stat-warning">
            <i class="fas fa-box"></i>
        </div>
    </div>

    <div class="stat-card stat-success">
        <div class="stat-left">
            <span class="stat-title">Found Items</span>
            <span class="stat-value">{{ $foundItems }}</span>
            <span class="stat-trend {{ $foundItemsTrend }}">
                {{ $foundItemsLastWeek > 0 ? '+' . $foundItemsLastWeek : '0' }} this week
            </span>
        </div>
        <div class="stat-icon stat-success">
            <i class="fas fa-check-circle"></i>
        </div>
    </div>

    <div class="stat-card stat-neutral">
        <div class="stat-left">
            <span class="stat-title">My Reports</span>
            <span class="stat-value">{{ $myReportsTotal }}</span>
            <span class="stat-sub">{{ $pendingReports }} pending review</span>
        </div>
        <div class="stat-icon stat-neutral">
            <i class="fas fa-file-alt"></i>
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
                <h3><i class="fas fa-exclamation-circle"></i> Recent Incidents</h3>
            </div>

            <div class="incident-list">
                @forelse($recentIncidents as $incident)
                    <div class="incident-row">
                        <div class="incident-content">
                            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 8px;">
                                <strong>{{ $incident->incident_type }}</strong>
                                <span class="incident-status" style="
                                    font-size: 11px;
                                    padding: 4px 8px;
                                    border-radius: 4px;
                                    font-weight: 600;
                                    @if($incident->status === 'active') background-color: #ff4757; color: white;
                                    @elseif($incident->status === 'pending') background-color: #ffa502; color: white;
                                    @elseif($incident->status === 'resolved') background-color: #2ed573; color: white;
                                    @else background-color: #ddd; color: #333;
                                    @endif
                                ">
                                    {{ ucfirst($incident->status ?? 'pending') }}
                                </span>
                            </div>
                            
                            <p style="margin: 8px 0; color: #666; font-size: 14px;">
                                {{ Str::limit($incident->description, 100) }}
                            </p>

                            <div class="incident-meta">
                                <span><i class="fas fa-map-marker-alt"></i> {{ $incident->location }}</span>
                                <span><i class="fas fa-clock"></i> {{ \Carbon\Carbon::parse($incident->incident_date)->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div style="text-align: center; padding: 40px 20px; color: #999;">
                        <p style="font-size: 16px; margin-bottom: 10px;">
                            <i class="fas fa-inbox"></i> No incidents reported yet
                        </p>
                        <p style="font-size: 13px;">Start by reporting an incident to get started</p>
                    </div>
                @endforelse
            </div>

            @if($recentIncidents->count() > 0)
                <div style="border-top: 1px solid #eee; padding-top: 12px; text-align: center;">
                    <a href="{{ route('reports.index') }}" style="color: #007bff; text-decoration: none; font-size: 13px;">
                        View all incidents <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            @endif
        </div>

        <!-- Lost & Found Updates -->
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-search"></i> Lost & Found Updates</h3>
                <a href="{{ route('lostfound.report') }}" class="view-all">View all <i class="fas fa-arrow-right"></i></a>
            </div>

            <div class="lf-list">
                @forelse($recentLostFound as $item)
                    <div class="lf-item">
                        <div class="lf-left">
                            <div class="lf-image">
                                @if($item->image)
                                    <img src="{{ asset('storage/' . $item->image) }}" 
                                         alt="{{ $item->item_name }}" 
                                         loading="lazy"
                                         onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                                    <i class="fas fa-image" style="display:none;"></i>
                                @else
                                    <i class="fas fa-image"></i>
                                @endif
                            </div>
                            <div class="lf-info">
                                <strong>{{ $item->item_name }}</strong>
                                <p>{{ Str::limit($item->description, 60) }}</p>
                                <span class="lf-time">
                                    <i class="fas fa-clock"></i> 
                                    {{ \Carbon\Carbon::parse($item->event_datetime)->format('M d, g:i A') }}
                                </span>
                            </div>
                        </div>
                        <span class="lf-status {{ $item->item_status }}">
                            @if($item->item_status === 'found')
                                <i class="fas fa-check-circle"></i> FOUND
                            @else
                                <i class="fas fa-search"></i> LOST
                            @endif
                        </span>
                    </div>
                @empty
                    <div style="text-align: center; padding: 30px 20px; color: #999;">
                        <p style="font-size: 14px;">
                            <i class="fas fa-inbox"></i> No lost & found items yet
                        </p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- RIGHT COLUMN -->
    <div class="dashboard-right">

        <!-- Quick Actions -->
        <div class="card quick-actions">
            <h3><i class="fas fa-zap"></i> Quick Actions</h3>

            <!-- Report Incident -->
            <a href="{{ route('incidents.report') }}" class="action-btn action-incident">
                <i class="fas fa-exclamation-circle"></i>
                Report Incident
            </a>

            <!-- Report Lost Item -->
            <a href="{{ route('lostfound.report') }}" class="action-btn action-lost">
                <i class="fas fa-search"></i>
                Report Lost Item
            </a>
        </div>

        <!-- Emergency Contacts -->
        <div class="card emergency-contacts">
            <h3><i class="fas fa-phone-alt"></i> Emergency Contacts</h3>

            <div class="emergency-row emergency-security">
                <span class="emergency-icon"><i class="fas fa-phone"></i></span>
                <span class="emergency-text">
                    <strong>Bahagian Keselamatan UTHM</strong>
                    <span class="number">(07) 453-7146</span>
                </span>
            </div>

            <div class="emergency-row emergency-health">
                <span class="emergency-icon"><i class="fas fa-hospital"></i></span>
                <span class="emergency-text">
                    <strong>Pusat Kesihatan Universiti (PKU)</strong>
                    <span class="number">(60) 19 868-7854</span>
                </span>
            </div>

            <div class="emergency-row emergency-fire">
                <span class="emergency-icon"><i class="fas fa-fire"></i></span>
                <span class="emergency-text">
                    <strong>Jabatan Bomba Dan Penyelamat Ayer Hitam</strong>
                    <span class="number">(07) 758-2206</span>
                </span>
            </div>
        </div>
    </div>
</div>

@endsection