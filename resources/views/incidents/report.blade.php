@extends('layouts.app')

@section('page-title', 'Incident')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/report.css') }}">
@endsection

@section('content')

<div class="report-container">

    <!-- Page Header -->
    <div class="report-header">
        <h2>Report Incident</h2>
        <p>Please provide detailed information about the incident you wish to report.</p>
    </div>

    <!-- Form Card -->
    <div class="card report-card">

        <!-- 🔴 ADD: method, action, enctype -->
        <form method="POST"
              action="{{ route('incidents.store') }}"
              enctype="multipart/form-data">

            @csrf <!-- 🔴 ADD -->

            <!-- Incident Type -->
            <div class="form-group">
                <label>Incident Type <span>*</span></label>

                <!-- 🔴 ADD: name + old() -->
                <select name="incident_type" required>
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
                    <option value="Noise Complaint" {{ old('incident_type')=='Noise Complaint' ? 'selected' : '' }}>
                        Noise Complaint
                    </option>
                    <option value="Other" {{ old('incident_type')=='Other' ? 'selected' : '' }}>
                        Other
                    </option>
                </select>

                @error('incident_type')
                    <small class="error">{{ $message }}</small>
                @enderror
            </div>

            <!-- Location -->
            <div class="form-group">
                <label>Location <span>*</span></label>

                <!-- 🔴 ADD: name + value -->
                <input type="text"
                       name="location"
                       value="{{ old('location') }}"
                       placeholder="Enter the location where the incident occurred"
                       required>

                @error('location')
                    <small class="error">{{ $message }}</small>
                @enderror
            </div>

            <!-- Date & Time -->
            <div class="form-row">
                <div class="form-group">
                    <label>Date <span>*</span></label>

                    <!-- 🔴 ADD: name -->
                    <input type="date"
                           name="incident_date"
                           value="{{ old('incident_date') }}"
                           required>

                    @error('incident_date')
                        <small class="error">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Time <span>*</span></label>

                    <!-- 🔴 OPTIONAL: name -->
                    <input type="time"
                           name="incident_time"
                           value="{{ old('incident_time') }}">
                </div>
            </div>

            <!-- Description -->
            <div class="form-group">
                <label>Description <span>*</span></label>

                <!-- 🔴 ADD: name -->
                <textarea name="description"
                          rows="5"
                          placeholder="Please provide a detailed description of the incident"
                          required>{{ old('description') }}</textarea>

                <small>Minimum 50 characters required</small>

                @error('description')
                    <small class="error">{{ $message }}</small>
                @enderror
            </div>

            <!-- Upload Evidence -->
            <div class="form-group">
                <label>Upload Evidence (Optional)</label>

                <div class="upload-box">
                    <div class="upload-icon">⬆️</div>
                    <p><strong>Click to upload</strong> or drag and drop</p>
                    <small>PNG, JPG, PDF up to 10MB</small>

                    <!-- 🔴 ADD: real file input -->
                    <input type="file"
                           name="evidence"
                           id="evidenceInput"
                           accept=".jpg,.jpeg,.png,.pdf"
                           hidden>

                    <button id="browseEvidence" type="button" class="btn-outline">
                        Browse Files
                    </button>
                </div>

                <small>You can upload photos, videos, or documents related to the incident.</small>

                @error('evidence')
                    <small class="error">{{ $message }}</small>
                @enderror
            </div>

            <!-- Actions -->
            <div class="form-actions">
                <button type="button" class="btn-cancel">
                    Cancel
                </button>

                <!-- 🔴 SUBMIT -->
                <button type="submit" class="btn-primary">
                    Submit Report
                    <span class="arrow">→</span>
                </button>
            </div>

        </form>
    </div>

    <!-- Help Card -->
    <div class="card help-card">
        <strong>Need Help?</strong>
        <p>
            If you require immediate assistance or have questions about reporting an incident,
            please contact our support team.
        </p>

        <div class="help-actions">
            <a href="{{ route('dashboard') }}">📞 Call Support</a>
        </div>
    </div>
</div>

@section('scripts')
<script src="{{ asset('js/incident-report.js') }}"></script>
@endsection

@endsection
