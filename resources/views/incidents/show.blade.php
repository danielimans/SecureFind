@extends('layouts.app')

@section('page-title', 'Incident Details')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/incidents-lists.css') }}">
@endsection

@section('content')

<a href="{{ route('incidents.list') }}" style="display: inline-flex; align-items: center; gap: 6px; color: #2563eb; text-decoration: none; font-size: 16px; font-weight: 600; margin-bottom: 24px;">
    <i class="fas fa-arrow-left"></i> Back to All Incidents
</a>

<div class="incidents-container">

    <!-- Incident Details Card -->
    <div class="card" style="padding: 28px;">
        
        <!-- Header -->
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 28px; padding-bottom: 20px; border-bottom: 2px solid #e5e7eb;">
            <div>
                <h2 style="margin: 0; font-size: 28px; font-weight: 700; color: #111827;">{{ $incident->incident_type }}</h2>
                <p style="margin: 8px 0 0 0; color: #6b7280; font-size: 15px;">ID: #{{ str_pad($incident->id, 5, '0', STR_PAD_LEFT) }}</p>
            </div>
            <span class="incident-status status-{{ $incident->status }}" style="font-size: 14px; padding: 8px 14px;">
                {{ ucfirst($incident->status) }}
            </span>
        </div>

        <!-- Description Section -->
        <div style="margin-bottom: 28px;">
            <h3 style="margin: 0 0 12px 0; font-size: 16px; font-weight: 700; color: #111827;">
                <i class="fas fa-align-left" style="color: #2563eb; margin-right: 8px;"></i> Description
            </h3>
            <p style="margin: 0; color: #4b5563; font-size: 15px; line-height: 1.8;">
                {{ $incident->description }}
            </p>
        </div>

        <!-- Details Grid -->
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px; margin-bottom: 28px; padding: 20px; background: #f9fafb; border-radius: 10px;">
            
            <!-- Location -->
            <div>
                <p style="margin: 0 0 8px 0; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase;">Location</p>
                <p style="margin: 0; font-size: 16px; font-weight: 600; color: #111827; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-map-marker-alt" style="color: #2563eb;"></i>
                    {{ $incident->location }}
                </p>
            </div>

            <!-- Date & Time -->
            <div>
                <p style="margin: 0 0 8px 0; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase;">Date & Time</p>
                <p style="margin: 0; font-size: 16px; font-weight: 600; color: #111827; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-calendar-alt" style="color: #2563eb;"></i>
                    {{ $incident->incident_date->format('M d, Y H:i A') }}
                </p>
            </div>

            <!-- Status -->
            <div>
                <p style="margin: 0 0 8px 0; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase;">Current Status</p>
                <div style="display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-check-circle" style="color: #2563eb; font-size: 16px;"></i>
                    <span style="font-size: 15px; font-weight: 600; color: #111827;">{{ ucfirst($incident->status) }}</span>
                </div>
            </div>
        </div>

        <!-- Evidence Section -->
        @if($incident->evidence)
            <div style="margin-bottom: 28px;">
                <h3 style="margin: 0 0 16px 0; font-size: 16px; font-weight: 700; color: #111827;">
                    <i class="fas fa-paperclip" style="color: #2563eb; margin-right: 8px;"></i> Evidence Files
                </h3>
                <div class="evidence-list" style="display: flex; flex-wrap: wrap; gap: 12px;">
                    @php
                        $files = json_decode($incident->evidence, true);
                        if (is_array($files)) {
                            foreach ($files as $file) {
                                echo '<a href="' . asset('storage/' . $file) . '" target="_blank" class="evidence-link" style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 16px; background: #f0f9ff; border: 1.5px solid #bfdbfe; border-radius: 8px; color: #2563eb; text-decoration: none; font-size: 14px; font-weight: 600; transition: all 0.2s ease;">
                                    <i class="fas fa-download" style="font-size: 13px;"></i> ' . basename($file) . '
                                </a>';
                            }
                        }
                    @endphp
                </div>
            </div>
        @endif

        <!-- Action Buttons -->
        <div style="display: flex; gap: 12px; padding-top: 20px; border-top: 1px solid #e5e7eb;">
            <a href="{{ route('dashboard') }}" class="btn-view-details" style="background: #6b7280;">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

</div>

<style>
    .evidence-link:hover {
        background: #2563eb !important;
        color: #ffffff !important;
        border-color: #2563eb !important;
    }
</style>

@endsection