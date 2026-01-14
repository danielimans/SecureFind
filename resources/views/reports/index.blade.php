@extends('layouts.app')

@section('page-title', 'My Reports')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/my-report.css') }}">
@endsection

@section('content')

<a href="{{ route('dashboard') }}"
   style="display: inline-flex; align-items: center; gap: 6px; color: #2563eb; text-decoration: none; font-size: 16px; font-weight: 600; margin-bottom: 24px;">
    <i class="fas fa-arrow-left"></i> Back to Dashboard
</a>

<div class="reports-container">

    <!-- Header -->
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

    <!-- Filters -->
    <div class="filters-section">

        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" id="searchInput" placeholder="Search by title or description...">
        </div>

        <div class="filter-tabs">
            <button class="tab-btn active" data-filter="all">All Reports</button>
            <button class="tab-btn" data-filter="incident">Incidents</button>
            <button class="tab-btn" data-filter="lostfound">Lost & Found</button>
        </div>

    </div>

    <!-- Reports Grid -->
    <div class="reports-grid">

        @forelse($allReports as $report)

            @php
                $realId = $report['real_id'] ?? null;
                $type   = $report['type'] ?? 'unknown';
            @endphp

            <div class="report-card"
                 data-type="{{ $type }}"
                 data-status="{{ $report['status'] ?? 'unknown' }}">

                <!-- Header -->
                <div class="report-header">
                    <div class="report-title">
                        <h3>{{ $report['title'] ?? 'Untitled Report' }}</h3>
                        <p class="report-id">ID: {{ $report['id'] ?? 'N/A' }}</p>
                    </div>

                    <span class="report-badge {{ $type }}">
                        {{ $type === 'lostfound' ? 'Lost & Found' : 'Incident' }}
                    </span>
                </div>

                <!-- Description -->
                <p class="report-description">
                    {{ \Illuminate\Support\Str::limit($report['description'] ?? 'No description provided.', 120) }}
                </p>

                <!-- Meta -->
                <div class="report-meta">
                    <div class="meta-item">
                        <i class="fas fa-map-marker-alt"></i>
                        {{ $report['location'] ?? 'Unknown location' }}
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-calendar"></i>
                        {{ $report['date'] ?? '-' }}
                    </div>
                </div>

                <!-- Status -->
                <div class="report-status">
                    <span class="status-badge {{ $report['status'] ?? 'pending' }}">
                        {{ ucfirst($report['status'] ?? 'pending') }}
                    </span>
                </div>

                <!-- Actions -->
                <div class="report-actions">

                    @if($realId)

                        {{-- VIEW --}}
                        <a href="{{ $type === 'incident'
                            ? route('incidents.show', $realId)
                            : route('lostfound.show', $realId) }}"
                           class="btn-icon"
                           title="View">
                            <i class="fas fa-eye"></i>
                        </a>

                        {{-- EDIT --}}
                        <a href="{{ $type === 'incident'
                            ? route('incidents.edit', $realId)
                            : route('lostfound.edit', $realId) }}"
                           class="btn-icon"
                           title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>

                        {{-- DELETE --}}
                        <form method="POST"
                              action="{{ $type === 'incident'
                                    ? route('incidents.destroy', $realId)
                                    : route('lostfound.destroy', $realId) }}"
                              onsubmit="return confirm('Delete this report? This action cannot be undone.')">
                            @csrf
                            @method('DELETE')

                            <button type="submit" class="btn-icon delete-btn" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>

                    @else
                        {{-- Safety fallback --}}
                        <span class="text-muted" style="font-size: 13px;">
                            Actions unavailable
                        </span>
                    @endif

                </div>

            </div>

        @empty
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <h3>No Reports Found</h3>
                <p>You haven’t submitted any reports yet.</p>
            </div>
        @endforelse

    </div>

</div>

@endsection

@section('scripts')
<script src="{{ asset('js/my-report.js') }}"></script>
@endsection
