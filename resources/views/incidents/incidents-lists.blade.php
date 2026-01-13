@extends('layouts.app')

@section('page-title', 'All Incidents')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/incidents-lists.css') }}">
@endsection

@section('content')

<a href="{{ route('dashboard') }}" class="back-link">
    <i class="fas fa-arrow-left"></i> Back to Dashboard
</a>

<div class="incidents-container">

    <!-- Page Header -->
    <div class="page-header">
        <h2><i class="fas fa-exclamation-circle"></i> All Incidents</h2>
        <p>View incidents reported in the last 30 days</p>
    </div>

    <!-- Filters & Search -->
    <div class="card filters-card">
        <div class="filters-row">
            <div class="filter-group">
                <label><i class="fas fa-search"></i> Search</label>
                <input type="text" id="searchInput" placeholder="Search by description, location, type...">
            </div>

            <div class="filter-group">
                <label><i class="fas fa-list"></i> Incident Type</label>
                <select id="typeFilter">
                    <option value="">All Types</option>
                    <option value="Suspicious Activity">Suspicious Activity</option>
                    <option value="Unauthorized Access">Unauthorized Access</option>
                    <option value="Vandalism">Vandalism</option>
                    <option value="Theft">Theft</option>
                    <option value="Other">Other</option>
                </select>
            </div>

            <div class="filter-group">
                <label><i class="fas fa-tag"></i> Status</label>
                <select id="statusFilter">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="pending">Pending</option>
                    <option value="resolved">Resolved</option>
                </select>
            </div>

            <button class="btn-filter-reset" onclick="resetFilters()">
                <i class="fas fa-redo"></i> Reset
            </button>
        </div>
    </div>

    <!-- Stats -->
    <div class="incidents-stats">
        <div class="stat-box">
            <span class="stat-label">Total Incidents</span>
            <span class="stat-value">{{ $totalIncidents }}</span>
        </div>
        <div class="stat-box">
            <span class="stat-label">Active</span>
            <span class="stat-value">{{ $activeCount }}</span>
        </div>
        <div class="stat-box">
            <span class="stat-label">Pending</span>
            <span class="stat-value">{{ $pendingCount }}</span>
        </div>
        <div class="stat-box">
            <span class="stat-label">Resolved</span>
            <span class="stat-value">{{ $resolvedCount }}</span>
        </div>
    </div>

    <!-- Incidents List -->
    <div class="card incidents-list-card">
        @forelse($incidents as $incident)
            <div class="incident-card"
                data-type="{{ $incident->incident_type }}"
                data-custom-type="{{ $incident->custom_incident_type }}"
                data-status="{{ $incident->status }}">

                <div class="incident-header">
                    <div class="incident-title-section">
                        <h3>
                            @if($incident->incident_type === 'Other' && $incident->custom_incident_type)
                                {{ $incident->custom_incident_type }}
                            @else
                                {{ $incident->incident_type }}
                            @endif
                        </h3>
                        <span class="incident-status status-{{ $incident->status }}">
                            {{ ucfirst($incident->status) }}
                        </span>
                    </div>
                    <span class="incident-date">
                        <i class="fas fa-calendar-alt"></i>
                        {{ $incident->incident_date->format('M d, Y') }}
                    </span>
                </div>

                <p class="incident-description">
                    {{ $incident->description }}
                </p>

                <div class="incident-details">
                    <div class="detail-item">
                        <i class="fas fa-list"></i>
                        <span><strong>Type:</strong>
                            @if($incident->incident_type === 'Other' && $incident->custom_incident_type)
                                {{ $incident->custom_incident_type }}
                                <span class="type-other-badge">(Other)</span>
                            @else
                                {{ $incident->incident_type }}
                            @endif
                        </span>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span><strong>Location:</strong> {{ $incident->location }}</span>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-clock"></i>
                        <span><strong>Reported:</strong> {{ $incident->incident_date->diffForHumans() }}</span>
                    </div>
                </div>

                @if($incident->evidence)
                    <div class="incident-evidence">
                        <strong><i class="fas fa-paperclip"></i> Evidence Files:</strong>
                        <div class="evidence-list">
                            @php
                                $files = json_decode($incident->evidence, true);
                                if (is_array($files)) {
                                    foreach ($files as $file) {
                                        echo '<a href="' . asset('storage/' . $file) . '" target="_blank" class="evidence-link">
                                            <i class="fas fa-file"></i> ' . basename($file) . '
                                        </a>';
                                    }
                                }
                            @endphp
                        </div>
                    </div>
                @endif

                <div class="incident-footer">
                    <a href="{{ route('incidents.show', $incident->id) }}" class="btn-view-details">
                        View Details <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <h3>No Incidents Found</h3>
                <p>There are no incidents in the last 30 days matching your filters.</p>
            </div>
        @endforelse
    </div>

    @if($incidents->hasPages())
        <div class="pagination-section">
            {{ $incidents->links() }}
        </div>
    @endif

</div>

@endsection

@section('scripts')
<script src="{{ asset('js/incidents-lists.js') }}"></script>
@endsection
