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

    <!-- Incident Details Card -->
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

        <!-- Description Section -->
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
            
            <!-- Incident Type -->
            <div class="detail-field">
                <p class="detail-label">Incident Type</p>
                <p class="detail-value">
                    <i class="fas fa-list"></i>
                    @if($incident->incident_type === 'Other' && $incident->custom_incident_type)
                        <span>{{ $incident->custom_incident_type }} <span class="type-badge">(Other)</span></span>
                    @else
                        {{ $incident->incident_type }}
                    @endif
                </p>
            </div>

            <!-- Location -->
            <div class="detail-field">
                <p class="detail-label">Location</p>
                <p class="detail-value">
                    <i class="fas fa-map-marker-alt"></i>
                    {{ $incident->location }}
                </p>
            </div>

            <!-- Date & Time -->
            <div class="detail-field">
                <p class="detail-label">Date & Time</p>
                <p class="detail-value">
                    <i class="fas fa-calendar-alt"></i>
                    {{ $incident->incident_date->format('M d, Y H:i A') }}
                </p>
            </div>

            <!-- Status -->
            <div class="detail-field">
                <p class="detail-label">Current Status</p>
                <div class="status-display">
                    <i class="fas fa-check-circle"></i>
                    <span>{{ ucfirst($incident->status) }}</span>
                </div>
            </div>

            <!-- Created Date -->
            <div class="detail-field">
                <p class="detail-label">Created Date</p>
                <p class="detail-value">
                    <i class="fas fa-clock"></i>
                    {{ $incident->created_at->format('M d, Y') }}
                </p>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons-footer">
            <a href="{{ route('incidents.list') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>

</div>

@endsection