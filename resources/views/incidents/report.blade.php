@extends('layouts.app')

@section('page-title', 'Report Incident')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/report.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css"/>
@endsection

@section('content')

<a href="{{ route('dashboard') }}" style="display: inline-flex; align-items: center; gap: 6px; color: #2563eb; text-decoration: none; font-size: 16px; font-weight: 600; margin-bottom: 24px;">
    <i class="fas fa-arrow-left"></i> Back to Dashboard
</a>

<div class="report-container">

    <div class="report-header">
        <h2><i class="fas fa-exclamation-triangle"></i> Report Incident</h2>
        <p>Please provide detailed information about the incident you wish to report.</p>
    </div>

    <div class="card report-card">

        <form id="incidentForm" method="POST" action="{{ route('incidents.store') }}" enctype="multipart/form-data" novalidate>
            @csrf

            <!-- Incident Type -->
            <div class="form-group">
                <label><i class="fas fa-list"></i> Incident Type <span>*</span></label>
                <select name="incident_type" id="incidentType" required>
                    <option selected disabled>Select incident type</option>
                    <option value="Suspicious Activity">Suspicious Activity</option>
                    <option value="Unauthorized Access">Unauthorized Access</option>
                    <option value="Vandalism">Vandalism</option>
                    <option value="Theft">Theft</option>
                    <option value="Other">Other</option>
                </select>
            </div>

            <div class="form-group" id="customIncidentTypeGroup" style="display:none;">
                <label><i class="fas fa-keyboard"></i> Specify Incident Type <span>*</span></label>
                <input type="text" name="custom_incident_type" id="customIncidentType" maxlength="100">
            </div>

            <!-- Incident Location -->
            <div class="form-group">
                <label><i class="fas fa-map-marked-alt"></i> Incident Location <span>*</span></label>
                <small>Click on the map to select where the incident occurred</small>

                <div id="incidentMap"></div>

                <!-- MUST be called location for Laravel -->
                <input type="text"
                       name="location"
                       id="incidentLocation"
                       placeholder="Click on the map or type the location"
                       required>

                <input type="hidden" name="latitude" id="incident_lat">
                <input type="hidden" name="longitude" id="incident_lng">
            </div>

            <!-- Date & Time -->
            <div class="form-row">
                <div class="form-group">
                    <label><i class="fas fa-calendar-alt"></i> Date <span>*</span></label>
                    <input type="date" name="incident_date" id="incident_date" required>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-clock"></i> Time</label>
                    <input type="time" name="incident_time" id="incident_time">
                </div>
            </div>

            <!-- Description -->
            <div class="form-group">
                <label><i class="fas fa-pen"></i> Description <span>*</span></label>
                <textarea name="description" rows="5" minlength="30" required
                          placeholder="Describe what happened (minimum 30 characters)"></textarea>
                <small><i class="fas fa-info-circle"></i> Minimum 30 characters required</small>
            </div>

            <!-- Evidence -->
            <div class="form-group">
                <label><i class="fas fa-file-upload"></i> Upload Evidence (Optional)</label>

                <div class="upload-box" id="uploadBox">
                    <div class="upload-icon"><i class="fas fa-cloud-upload-alt"></i></div>
                    <p><strong>Click to upload</strong> or drag and drop</p>
                    <small>PNG, JPG, PDF up to 10MB</small>
                    <input type="file" name="evidence[]" id="evidenceInput" multiple hidden>
                </div>

                <div id="filesList"></div>
            </div>

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

    <div class="card help-card">
        <strong><i class="fas fa-shield-alt"></i> Security Notice</strong>
        <p style="color:#b91c1c;font-weight:600;">
            False reporting is a disciplinary offence. All submissions are logged and audited.
        </p>
    </div>

</div>

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
<script src="{{ asset('js/incident-report.js') }}"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {

    document.getElementById('incident_date').max = new Date().toISOString().split('T')[0];

    const map = L.map('incidentMap').setView([1.8572606, 103.0820799], 16); // UTHM center
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

    let marker;

    map.on('click', e => {
        const lat = e.latlng.lat;
        const lng = e.latlng.lng;

        if (marker) map.removeLayer(marker);
        marker = L.marker([lat, lng]).addTo(map);

        document.getElementById('incident_lat').value = lat;
        document.getElementById('incident_lng').value = lng;

        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`)
            .then(r => r.json())
            .then(data => {
                const addr = data.address || {};
                const place =
                    addr.building ||
                    addr.amenity ||
                    addr.university ||
                    addr.road ||
                    data.display_name;

                document.getElementById('incidentLocation').value = place + ', UTHM';
            });
    });
});
</script>
@endsection

@endsection
