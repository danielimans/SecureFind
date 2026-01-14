@extends('layouts.app')

@section('page-title', 'Edit Incident')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/edit-report.css') }}">
@endsection

@section('content')

<div class="report-container">

    <!-- Page Header -->
    <div class="page-header">
        <h1><i class="fas fa-pen"></i> Edit Incident</h1>
        <p>You can update incident information below</p>
    </div>

    <!-- Card -->
    <div class="report-card">

        <form method="POST" action="{{ route('incidents.update', $incident->id) }}">
            @csrf
            @method('PUT')

            <!-- INCIDENT TYPE -->
            <div class="form-group">
                <label>Incident Type</label>
                <select name="type" required>
                    <option value="Suspicious Activity" {{ $incident->type == 'Suspicious Activity' ? 'selected' : '' }}>
                        Suspicious Activity
                    </option>
                    <option value="Theft" {{ $incident->type == 'Theft' ? 'selected' : '' }}>
                        Theft
                    </option>
                    <option value="Vandalism" {{ $incident->type == 'Vandalism' ? 'selected' : '' }}>
                        Vandalism
                    </option>
                </select>
            </div>

            <!-- INCIDENT LOCATION -->
            <div class="form-group">
                <label>Incident Location</label>
                <input type="text"
                       name="location"
                       value="{{ $incident->location }}"
                       required>
            </div>

            <!-- DATE & TIME -->
            <div class="form-row">
                <div class="form-group">
                    <label>Date</label>
                    <input type="date"
                           name="date"
                           value="{{ \Carbon\Carbon::parse($incident->date)->format('Y-m-d') }}"
                           required>
                </div>

                <div class="form-group">
                    <label>Time</label>
                    <input type="time"
                           name="time"
                           value="{{ \Carbon\Carbon::parse($incident->date)->format('H:i') }}"
                           required>
                </div>
            </div>

            <!-- DESCRIPTION -->
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" required>{{ $incident->description }}</textarea>
            </div>

            <!-- ACTIONS -->
            <div class="form-actions">
                <a href="{{ route('dashboard') }}" class="btn-secondary">
                    Cancel
                </a>

                <button type="submit" class="btn-primary">
                    <i class="fas fa-save"></i> Save Changes
                </button>
            </div>

        </form>

    </div>
</div>

@endsection
