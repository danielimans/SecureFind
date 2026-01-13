@extends('layouts.app')

@section('page-title', 'Report Incident')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/report.css') }}">
@endsection

@section('content')

<a href="{{ route('dashboard') }}" style="display: inline-flex; align-items: center; gap: 6px; color: #2563eb; text-decoration: none; font-size: 16px; font-weight: 600; margin-bottom: 24px;">
    <i class="fas fa-arrow-left"></i> Back to Dashboard
</a>

<div class="report-container">

    <!-- Page Header -->
    <div class="report-header">
        <h2><i class="fas fa-exclamation-triangle"></i> Report Incident</h2>
        <p>Please provide detailed information about the incident you wish to report.</p>
    </div>

    <!-- Form Card -->
    <div class="card report-card">

        <form id="incidentForm"
              method="POST"
              action="{{ route('incidents.store') }}"
              enctype="multipart/form-data"
              novalidate>

            @csrf

            <!-- Incident Type -->
            <div class="form-group">
                <label><i class="fas fa-list"></i> Incident Type <span>*</span></label>

                <select name="incident_type" id="incidentType" required>
                    <option selected disabled>Select incident type</option>
                    <option value="Suspicious Activity" {{ old('incident_type')=='Suspicious Activity' ? 'selected' : '' }}>
                        Suspicious Activity
                    </option>
                    <option value="Unauthorized Access" {{ old('incident_type')=='Unauthorized Access' ? 'selected' : '' }}>
                        Unauthorized Access
                    </option>
                    <option value="Vandalism" {{ old('incident_type')=='Vandalism' ? 'selected' : '' }}>
                        Vandalism
                    </option>
                    <option value="Theft" {{ old('incident_type')=='Theft' ? 'selected' : '' }}>
                        Theft
                    </option>
                    <option value="Other" {{ old('incident_type')=='Other' ? 'selected' : '' }}>
                        Other
                    </option>
                </select>

                @error('incident_type')
                    <small class="error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</small>
                @enderror
            </div>

            <!-- Custom Incident Type Input -->
            <div class="form-group" id="customIncidentTypeGroup" style="display: none;">
                <label><i class="fas fa-keyboard"></i> Specify Incident Type <span>*</span></label>

                <input type="text"
                       name="custom_incident_type"
                       id="customIncidentType"
                       value="{{ old('custom_incident_type') }}"
                       placeholder="Please describe your incident type"
                       maxlength="100">

                @error('custom_incident_type')
                    <small class="error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</small>
                @enderror
            </div>

            <!-- Location -->
            <div class="form-group">
               <label><i class="fas fa-map-marker-alt"></i> Location <span>*</span></label>

                <input type="text"
                       name="location"
                       value="{{ old('location') }}"
                       placeholder="Enter the location where the incident occurred"
                       required>

                @error('location')
                    <small class="error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</small>
                @enderror

                <input type="hidden" name="latitude" id="lat">
                <input type="hidden" name="longitude" id="lng">
            </div>

            <button type="button" onclick="detectLocation()" class="btn-secondary">
            📍 Use My Location
            </button>

            <!-- Date & Time -->
            <div class="form-row">
                <div class="form-group">
                    <label><i class="fas fa-calendar-alt"></i> Date <span>*</span></label>

                    <input type="date"
                           name="incident_date"
                           value="{{ old('incident_date') }}"
                           required>

                    @error('incident_date')
                        <small class="error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label><i class="fas fa-clock"></i> Time</label>

                    <input type="time"
                           name="incident_time"
                           value="{{ old('incident_time') }}">
                </div>
            </div>

            <!-- Description -->
            <div class="form-group">
                <label><i class="fas fa-pen"></i> Description <span>*</span></label>

                <textarea name="description"
                          rows="5"
                          placeholder="Please provide a detailed description of the incident"
                          required>{{ old('description') }}</textarea>

                <small><i class="fas fa-info-circle"></i> Minimum 30 characters required</small>

                @error('description')
                    <small class="error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</small>
                @enderror
            </div>

            <!-- Upload Evidence -->
            <div class="form-group">
                <label><i class="fas fa-file-upload"></i> Upload Evidence (Optional)</label>

                <div class="upload-box" id="uploadBox">
                    <div class="upload-icon">
                        <i class="fas fa-cloud-upload-alt"></i>
                    </div>
                    <p><strong>Click to upload</strong> or drag and drop</p>
                    <small>PNG, JPG, PDF up to 10MB</small>

                    <input type="file"
                           name="evidence[]"
                           id="evidenceInput"
                           accept=".jpg,.jpeg,.png,.pdf"
                           multiple
                           hidden>
                </div>

                <!-- Files List -->
                <div id="filesList"></div>

                <small><i class="fas fa-lightbulb"></i> You can upload multiple photos or documents related to the incident.</small>

                @error('evidence')
                    <small class="error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</small>
                @enderror
            </div>

            <!-- Actions -->
            <div class="form-actions">
                <button type="button" class="btn-cancel" id="cancelBtn">
                   <i class="fas fa-times"></i> Cancel
                </button>

                <button type="submit" class="btn-primary">
                    <i class="fas fa-paper-plane"></i> Submit Report
                </button>
            </div>

        </form>
    </div>

    <!-- Help Card -->
    <div class="card help-card">
        <strong><i class="fas fa-question-circle"></i> Need Help?</strong>
        <p>
            If you require immediate assistance or have questions about reporting an incident,
            please contact our support team.
        </p>

        <div class="help-actions">
            <a href="{{ route('dashboard') }}"><i class="fas fa-phone"></i> Call Support</a>
        </div>
    </div>
</div>

@section('scripts')
<script src="{{ asset('js/incident-report.js') }}"></script>
@endsection

@endsection