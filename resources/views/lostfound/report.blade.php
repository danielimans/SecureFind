@extends('layouts.app')

@section('page-title', 'Lost & Found')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/lost-found.css') }}">

{{-- Leaflet --}}
<link
    rel="stylesheet"
    href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
/>
@endsection

@section('content')

<a href="{{ route('dashboard') }}"
   style="display: inline-flex; align-items: center; gap: 6px; color: #2563eb; text-decoration: none; font-size: 16px; font-weight: 600; margin-bottom: 24px;">
    <i class="fas fa-arrow-left"></i> Back to Dashboard
</a>

<div class="lf-page">

    <h1><i class="fas fa-search"></i> Report Lost & Found</h1>
    <p class="subtitle">
        Submit details about a lost or found item to help it get back to its owner.
    </p>

    @if (session('success'))
        <div data-success-message="{{ session('success') }}" style="display:none;"></div>
    @endif

    <form id="lostFoundForm"
          method="POST"
          action="{{ route('lostfound.store') }}"
          enctype="multipart/form-data"
          novalidate>
        @csrf

        <div class="lf-card">

            {{-- Item Status --}}
            <div class="lf-group">
                <label><i class="fas fa-info-circle"></i> Item Status <span>*</span></label>
                <select id="itemStatus" name="status" required>
                    <option value="">Select status</option>
                    <option value="lost" {{ old('status') === 'lost' ? 'selected' : '' }}>Lost Item</option>
                    <option value="found" {{ old('status') === 'found' ? 'selected' : '' }}>Found Item</option>
                </select>

                <div class="lf-status-badge" id="statusBadge">
                    <i class="fas fa-tag"></i> Select Status
                </div>
            </div>

            {{-- Item Name --}}
            <div class="lf-group">
                <label id="itemNameLabel"><i class="fas fa-box"></i> Item Name <span>*</span></label>
                <input id="itemName"
                       type="text"
                       name="item_name"
                       value="{{ old('item_name') }}"
                       placeholder="e.g. Black Backpack, iPhone"
                       required>
            </div>

            {{-- Category --}}
            <div class="lf-group">
                <label><i class="fas fa-list"></i> Category</label>
                <select name="category">
                    <option value="">Select category</option>
                    <option value="Electronics">Electronics</option>
                    <option value="ID Card">ID Card</option>
                    <option value="Bag">Bag</option>
                    <option value="Documents">Documents</option>
                    <option value="Others">Others</option>
                </select>
            </div>

            {{-- LOCATION --}}
            <div class="lf-group">
                <label id="locationLabel">
                    <i class="fas fa-map-marker-alt"></i> Lost / Found Location <span>*</span>
                </label>

                <div id="lfMap"
                     style="height:320px; border-radius:12px; border:1.5px solid #d1d5db; margin-bottom:12px;">
                </div>

                <input
                    type="text"
                    id="location"
                    name="location"
                    value="{{ old('location') }}"
                    placeholder="Click map or type location (e.g. Library Block A)"
                    required
                >

                <div class="lf-help">
                    <i class="fas fa-info-circle"></i>
                    Click on the map or manually enter the location
                </div>
            </div>

            {{-- Date & Time --}}
            <div class="lf-row">
                <div class="lf-group">
                    <label><i class="fas fa-calendar-alt"></i> Date <span>*</span></label>
                    <input type="date" name="date" value="{{ old('date') }}" required>
                </div>

                <div class="lf-group">
                    <label><i class="fas fa-clock"></i> Time</label>
                    <input type="time" name="time" value="{{ old('time') }}">
                </div>
            </div>

            {{-- Description --}}
            <div class="lf-group">
                <label><i class="fas fa-pen"></i> Description <span>*</span></label>
                <textarea name="description" required>{{ old('description') }}</textarea>
                <div class="lf-help">
                    <i class="fas fa-info-circle"></i> Minimum 20 characters
                </div>
            </div>

            {{-- Upload --}}
            <div class="lf-group">
                <label><i class="fas fa-image"></i> Upload Image (Optional)</label>
                <div class="lf-upload" id="uploadBox">
                    <div class="upload-icon"><i class="fas fa-cloud-upload-alt"></i></div>
                    <strong>Click to upload or drag and drop</strong>
                    <span>PNG, JPG up to 10MB</span>

                    <input type="file" name="image" id="imageInput" hidden accept="image/*">
                    <img id="previewImage"
                         style="display:none; margin-top:12px; max-width:140px; border-radius:8px;">
                </div>
            </div>

            {{-- Actions --}}
            <div class="lf-actions">
                <button type="button" class="lf-cancel" id="clearFormBtn">
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
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="{{ asset('js/lost-found.js') }}"></script>

<script>
/* =========================
   CAMPUS MAP + LOCATION NAME
========================= */

// Campus bounds (SW, NE)
const campusBounds = L.latLngBounds(
    [1.8450, 103.0710], // SW
    [1.8700, 103.0930]  // NE
);

// Init map
const map = L.map('lfMap', {
    maxBounds: campusBounds,
    maxBoundsViscosity: 1.0
}).setView([1.8575, 103.0820], 16);

// Tiles
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
}).addTo(map);

let marker;
const locationInput = document.getElementById('location');

// Click on map
map.on('click', async function (e) {

    if (!campusBounds.contains(e.latlng)) {
        showToast('Please select a location within campus area', 'error');
        return;
    }

    // Marker
    if (marker) {
        marker.setLatLng(e.latlng);
    } else {
        marker = L.marker(e.latlng).addTo(map);
    }

    const lat = e.latlng.lat;
    const lng = e.latlng.lng;

    // Temporary text while loading
    locationInput.value = 'Fetching location name...';

    try {
        // Reverse geocoding (OpenStreetMap Nominatim)
        const response = await fetch(
            `https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`
        );

        const data = await response.json();

        if (data && data.display_name) {
            locationInput.value = data.display_name;
        } else {
            locationInput.value = 'Location name not available';
        }

    } catch (error) {
        locationInput.value = 'Unable to fetch location name';
    }
});
</script>
@endsection
