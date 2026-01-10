@extends('layouts.app')

@section('page-title', 'My Reports')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/my-report.css') }}">
@endsection

@section('content')

<a href="{{ route('dashboard') }}" style="display: inline-flex; align-items: center; gap: 6px; color: #2563eb; text-decoration: none; font-size: 16px; font-weight: 600; margin-bottom: 24px;">
    <i class="fas fa-arrow-left"></i> Back to Dashboard
</a>

<div class="reports-container">

    <!-- Page Header -->
    <div class="reports-header">
        <div class="header-content">
            <h1><i class="fas fa-file-alt"></i> My Reports</h1>
            <p>View all your incident and lost & found reports</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('incidents.report') }}" class="btn-primary">
                <i class="fas fa-plus"></i> New Incident Report
            </a>
            <a href="{{ route('lostfound.report') }}" class="btn-secondary">
                <i class="fas fa-plus"></i> New Lost & Found
            </a>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="filters-section">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" id="searchInput" placeholder="Search reports...">
        </div>
        <div class="filter-tabs">
            <button class="tab-btn active" data-filter="all">
                <i class="fas fa-list"></i> All Reports
            </button>
            <button class="tab-btn" data-filter="incident">
                <i class="fas fa-exclamation-triangle"></i> Incidents
            </button>
            <button class="tab-btn" data-filter="lostfound">
                <i class="fas fa-search"></i> Lost & Found
            </button>
        </div>
    </div>

    <!-- Reports Grid -->
    <div class="reports-grid">

        @forelse($allReports as $report)
            <div class="report-card" data-type="{{ $report['type'] }}">
                <div class="report-header">
                    <div class="report-title">
                        <h3>{{ $report['title'] }}</h3>
                        <p class="report-id">ID: {{ $report['id'] }}</p>
                    </div>
                    <span class="report-badge {{ $report['type'] }}">
                        @if($report['type'] === 'incident')
                            <i class="fas fa-exclamation-triangle"></i> Incident
                        @else
                            <i class="fas fa-search"></i> Lost & Found
                        @endif
                    </span>
                </div>

                <div class="report-content">
                    <p>{{ Str::limit($report['description'], 120) }}</p>
                </div>

                <div class="report-meta">
                    <div class="meta-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>{{ $report['location'] }}</span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-calendar-alt"></i>
                        <span>{{ $report['date'] }}</span>
                    </div>
                </div>

                <div class="report-status">
                    <span class="status-badge {{ $report['status'] }}">
                        {{ ucfirst($report['status']) }}
                    </span>
                </div>

                <div class="report-actions">
                    <a href="#reportModal{{ $loop->index }}" class="btn-icon view-btn" title="View Details" data-id="{{ $report['id'] }}">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a href="#" class="btn-icon edit-btn" title="Edit" data-id="{{ $report['id'] }}">
                        <i class="fas fa-edit"></i>
                    </a>
                    <a href="#" class="btn-icon delete-btn" title="Delete" data-id="{{ $report['id'] }}">
                        <i class="fas fa-trash"></i>
                    </a>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <h3>No Reports Yet</h3>
                <p>You haven't submitted any reports. Use the buttons above to get started.</p>
            </div>
        @endforelse

    </div>

</div>

@endsection

@section('scripts')
<script src="{{ asset('js/my-report.js') }}"></script>
@endsection