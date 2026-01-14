@extends('layouts.app')

@section('page-title', 'Incident Details')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/incidents-details.css') }}">
@endsection

@section('content')

<a href="{{ route('incidents.list') }}" class="back-link">
    <i class="fas fa-arrow-left"></i> Back to All Incidents
</a>

<div class="incidents-container">

    <div class="card incident-details-card">
        
        <!-- Header -->
        <div class="incident-details-header">
            <div>
                <h2 class="incident-details-title">
                    @if($incident->incident_type === 'Other' && $incident->custom_incident_type)
                        {{ $incident->custom_incident_type }}
                    @else
                        {{ $incident->incident_type }}
                    @endif
                </h2>
                <p class="incident-details-id">ID: #{{ str_pad($incident->id, 5, '0', STR_PAD_LEFT) }}</p>
            </div>
            <span class="incident-status status-{{ $incident->status }}">
                {{ ucfirst($incident->status) }}
            </span>
        </div>

        <!-- Description -->
        <div class="description-section">
            <h3 class="section-title">
                <i class="fas fa-align-left"></i> Description
            </h3>
            <p class="description-text">
                {{ $incident->description }}
            </p>
        </div>

        <!-- Details Grid -->
        <div class="details-grid">

            <div class="detail-field">
                <p class="detail-label">Incident Type</p>
                <p class="detail-value">
                    <i class="fas fa-list"></i>
                    @if($incident->incident_type === 'Other' && $incident->custom_incident_type)
                        {{ $incident->custom_incident_type }} <span class="type-badge">(Other)</span>
                    @else
                        {{ $incident->incident_type }}
                    @endif
                </p>
            </div>

            <div class="detail-field">
                <p class="detail-label">Location</p>
                <p class="detail-value">
                    <i class="fas fa-map-marker-alt"></i>
                    {{ $incident->location }}
                </p>
            </div>

            <div class="detail-field">
                <p class="detail-label">Date & Time</p>
                <p class="detail-value">
                    <i class="fas fa-calendar-alt"></i>
                    {{ $incident->incident_date->format('M d, Y h:i A') }}
                </p>
            </div>

            <div class="detail-field">
                <p class="detail-label">Current Status</p>
                <div class="status-display">
                    <i class="fas fa-check-circle"></i>
                    <span>{{ ucfirst($incident->status) }}</span>
                </div>
            </div>

            <div class="detail-field">
                <p class="detail-label">Created Date</p>
                <p class="detail-value">
                    <i class="fas fa-clock"></i>
                    {{ $incident->created_at->format('M d, Y') }}
                </p>
            </div>

        </div>

        <!-- ============================
             Evidence Section
        ============================ -->
        @if($incident->evidence)
        <div class="evidence-section">
            <h3 class="section-title">
                <i class="fas fa-paperclip"></i> Evidence Files
            </h3>

            <div class="evidence-grid">
                @php
                    $files = json_decode($incident->evidence, true);
                @endphp

                @foreach($files as $file)
                    @php
                        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                        $url = asset('storage/' . $file);
                    @endphp

                    <div class="evidence-item">

                        @if(in_array($ext, ['jpg','jpeg','png','gif']))
                            <a href="{{ $url }}" target="_blank">
                                <img src="{{ $url }}" alt="Evidence">
                            </a>
                        @elseif($ext === 'pdf')
                            <div class="evidence-pdf">
                                <i class="fas fa-file-pdf"></i>
                                <a href="{{ $url }}" target="_blank">Open PDF</a>
                            </div>
                        @else
                            <div class="evidence-file">
                                <i class="fas fa-file"></i>
                                <a href="{{ $url }}" target="_blank">{{ basename($file) }}</a>
                            </div>
                        @endif

                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Footer -->
        <div class="action-buttons-footer">
            <a href="{{ route('incidents.list') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>

    </div>

</div>

@endsection
