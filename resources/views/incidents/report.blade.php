@extends('layouts.app')

@section('page-title', 'Report Incident')

@section('content')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/report.css') }}">
@endpush

<div class="report-container">

    <!-- Page Header -->
    <div class="report-header">
        <h2>Report Incident</h2>
        <p>Please provide detailed information about the incident you wish to report.</p>
    </div>

    <!-- Form Card -->
    <div class="card report-card">
        <form>

            <!-- Incident Type -->
            <div class="form-group">
                <label>Incident Type <span>*</span></label>
                <select>
                    <option selected disabled>Select incident type</option>
                    <option>Suspicious Activity</option>
                    <option>Unauthorized Access</option>
                    <option>Vandalism</option>
                    <option>Noise Complaint</option>
                    <option>Other</option>
                </select>
            </div>

            <!-- Location -->
            <div class="form-group">
                <label>Location <span>*</span></label>
                <input type="text" placeholder="Enter the location where the incident occurred">
            </div>

            <!-- Date & Time -->
            <div class="form-row">
                <div class="form-group">
                    <label>Date <span>*</span></label>
                    <input type="date">
                </div>

                <div class="form-group">
                    <label>Time <span>*</span></label>
                    <input type="time">
                </div>
            </div>

            <!-- Description -->
            <div class="form-group">
                <label>Description <span>*</span></label>
                <textarea rows="5"
                    placeholder="Please provide a detailed description of the incident"></textarea>
                <small>Minimum 50 characters required</small>
            </div>

            <!-- Upload Evidence -->
            <div class="form-group">
                <label>Upload Evidence (Optional)</label>

                <div class="upload-box">
                    <div class="upload-icon">
                        ⬆️
                    </div>
                    <p><strong>Click to upload</strong> or drag and drop</p>
                    <small>PNG, JPG, PDF up to 10MB</small>
                    <button type="button" class="btn-outline">Browse Files</button>
                </div>

                <small>You can upload photos, videos, or documents related to the incident.</small>
            </div>

            <!-- Actions -->
            <div class="form-actions">
                <div class="right-actions">
                    <button type="button" class="btn-text">Cancel</button>
                    <button type="submit" class="btn-primary">Submit Report →</button>
                </div>
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
            <a href="dashboard">📞 Call Support</a>
        </div>
    </div>

</div>

@endsection
