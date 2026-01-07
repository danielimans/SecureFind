@extends('layouts.app')

@section('page-title', 'Lost & Found')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/lost-found.css') }}">
@endsection

@section('content')
<div class="lf-page">

    <h1><i class="fas fa-search"></i> Report Lost & Found</h1>
    <p class="subtitle">
        Submit details about a lost or found item to help it get back to its owner.
    </p>

    <form id="lostFoundForm" method="POST" action="{{ route('lostfound.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="lf-card">

            <!-- Item Status -->
            <div class="lf-group">
                <label><i class="fas fa-info-circle"></i> Item Status <span>*</span></label>
                <select id="itemStatus" name="status" required>
                    <option value="">Select status</option>
                    <option value="lost">Lost Item</option>
                    <option value="found">Found Item</option>
                </select>
                <div class="lf-status-badge" id="statusBadge">
                    <i class="fas fa-tag"></i> Select Status
                </div>
                @error('status') <small class="error-text"><i class="fas fa-exclamation-circle"></i> {{ $message }}</small>@enderror
            </div>

            <!-- Item Name -->
            <div class="lf-group">
                <label id="itemNameLabel"><i class="fas fa-box"></i> Item Name <span>*</span></label>
                <input id="itemName" type="text" name="item_name" placeholder="e.g. Black Backpack, iPhone" required>
                @error('item_name') <small class="error-text"><i class="fas fa-exclamation-circle"></i> {{ $message }}</small>@enderror
            </div>

            <!-- Category -->
            <div class="lf-group">
                <label><i class="fas fa-list"></i> Category</label>
                <select name="category">
                    <option>Electronics</option>
                    <option>Wallet</option>
                    <option>Bag</option>
                    <option>Documents</option>
                    <option>Others</option>
                </select>
            </div>

            <!-- Location -->
            <div class="lf-group">
                <label id="locationLabel"><i class="fas fa-map-marker-alt"></i> Location <span>*</span></label>
                <input id="locationInput" type="text" name="location" placeholder="Where the item was lost or found" required>
                @error('location')<small class="error-text"><i class="fas fa-exclamation-circle"></i> {{ $message }}</small>@enderror
            </div>

            <div class="location-actions">
                <button id="useLocation" type="button" name="current_location" class="use-location-btn">
                    <i class="fas fa-location-arrow"></i> Use Current Location
                </button>
                <small id="locationStatus"></small>
            </div>

            <!-- Date & Time -->
            <div class="lf-row">
                <div class="lf-group">
                    <label><i class="fas fa-calendar-alt"></i> Date <span>*</span></label>
                    <input type="date" name="date" required>
                    @error('date')<small class="error-text"><i class="fas fa-exclamation-circle"></i> {{ $message }}</small>@enderror
                </div>

                <div class="lf-group">
                    <label><i class="fas fa-clock"></i> Time</label>
                    <input type="time" name="time">
                </div>
            </div>

            <!-- Description -->
            <div class="lf-group">
                <label><i class="fas fa-pen"></i> Description <span>*</span></label>
                <textarea name="description" placeholder="Provide details such as color, brand, unique marks" required></textarea>
                <div class="lf-help"><i class="fas fa-info-circle"></i> Minimum 20 characters</div>
                @error('description')<small class="error-text"><i class="fas fa-exclamation-circle"></i> {{ $message }}</small>@enderror
            </div>

            <!-- Upload -->
            <div class="lf-group">
                <label><i class="fas fa-image"></i> Upload Image (Optional)</label>
                <div class="lf-upload" id="uploadBox">
                    <div class="upload-icon">
                        <i class="fas fa-cloud-upload-alt"></i>
                    </div>
                    <strong>Click to upload or drag and drop</strong>
                    <span>PNG, JPG up to 10MB</span>

                    <input type="file" name="image" id="imageInput" hidden accept="image/*">
                    <img id="previewImage" style="display:none; margin-top:12px; max-width:140px;">
                </div>
            </div>

            <!-- Actions -->
            <div class="lf-actions">
                <button type="button" class="lf-cancel" onclick="history.back()">
                    <i class="fas fa-times"></i> Cancel
                </button>
                <button type="submit" class="lf-submit">
                    <i class="fas fa-check-circle"></i> Submit Report
                    <span class="arrow"><i class="fas fa-arrow-right"></i></span>
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/lost-found.js') }}"></script>
@endsection