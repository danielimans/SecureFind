@extends('layouts.app')

@section('page-title', 'Lost & Found Details')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/lostfounds-lists.css') }}">
@endsection

@section('content')

<a href="{{ route('lostfound.list') }}" style="display: inline-flex; align-items: center; gap: 6px; color: #2563eb; text-decoration: none; font-size: 16px; font-weight: 600; margin-bottom: 24px;">
    <i class="fas fa-arrow-left"></i> Back to All Lost & Found
</a>

<div class="detail-container">
    <div class="item-card card">
        <div class="card-header-custom">
            <h2>{{ $lostFound->item_name }}</h2>
            <span class="status-badge {{ $lostFound->item_status === 'lost' ? 'lost' : 'found' }}">
                {{ ucfirst($lostFound->item_status) }}
            </span>
        </div>

        <div class="card-body-custom">
            @if($lostFound->image_url)
                <img src="{{ $lostFound->image_url }}" alt="{{ $lostFound->item_name }}">
            @else
                <div class="no-image">No image available</div>
            @endif

            <div class="detail-section">
                <div class="detail-label">Description</div>
                <div class="detail-value">{{ $lostFound->description }}</div>
            </div>

            <div class="divider"></div>

            <div class="detail-section">
                <div class="detail-label">Location</div>
                <div class="detail-value">{{ $lostFound->location }}</div>
            </div>

            <div class="divider"></div>

            <div class="detail-section">
                <div class="detail-label">Category</div>
                <div class="detail-value">{{ $lostFound->item_category ?? 'Uncategorized' }}</div>
            </div>

            <div class="divider"></div>

            <div class="detail-section">
                <div class="detail-label">Reported</div>
                <div class="detail-value">{{ $lostFound->created_at->format('d M Y H:i') }}</div>
            </div>

            @if($lostFound->event_datetime)
                <div class="divider"></div>

                <div class="detail-section">
                    <div class="detail-label">Event Date/Time</div>
                    <div class="detail-value">{{ \Carbon\Carbon::parse($lostFound->event_datetime)->format('d M Y H:i') }}</div>
                </div>
            @endif

            <div class="action-buttons">
                <a href="{{ route('lostfound.list') }}" class="btn-custom btn-back">← Back to List</a>
            </div>
        </div>
    </div>
</div>

@endsection