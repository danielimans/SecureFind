@extends('layouts.app')

@section('page-title', 'Profile')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')

<a href="{{ route('dashboard') }}"
   style="display: inline-flex; align-items: center; gap: 6px; color: #2563eb; text-decoration: none; font-size: 16px; font-weight: 600; margin-bottom: 24px;">
    <i class="fas fa-arrow-left"></i> Back to Dashboard
</a>

<div class="profile-container">

    <!-- Page Header -->
    <div class="page-header">
        <h2><i class="fas fa-user-circle"></i> My Profile</h2>
        <p>Manage your account information and personal details</p>
    </div>

    <!-- Profile Card -->
    <div class="card profile-card">

        <!-- Profile Picture Section -->
        <div class="profile-picture-section">
            <div class="profile-picture">
                <i class="fas fa-user"></i>
            </div>
            <div class="profile-info">
                <h3>{{ auth()->user()->name }}</h3>
                <p>{{ auth()->user()->email }}</p>
                <small>Joined {{ auth()->user()->created_at->format('M d, Y') }}</small>
            </div>
        </div>

        <!-- Edit Form -->
        <form id="profileForm" method="POST" action="{{ route('profile.update') }}" class="profile-form">
            @csrf
            @method('PUT')

            <div class="form-section">
                <h4>Basic Information</h4>

                <!-- Full Name -->
                <div class="form-group">
                    <label><i class="fas fa-user"></i> Full Name <span>*</span></label>
                    <input type="text"
                           name="name"
                           value="{{ old('name', auth()->user()->name) }}"
                           placeholder="Enter your full name"
                           required>
                    @error('name')
                        <small class="error-text"><i class="fas fa-exclamation-circle"></i> {{ $message }}</small>
                    @enderror
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label><i class="fas fa-envelope"></i> Email Address <span>*</span></label>
                    <input type="email"
                           name="email"
                           value="{{ old('email', auth()->user()->email) }}"
                           placeholder="Enter your email address"
                           required>
                    @error('email')
                        <small class="error-text"><i class="fas fa-exclamation-circle"></i> {{ $message }}</small>
                    @enderror
                </div>

                <!-- Phone -->
                <div class="form-group">
                    <label><i class="fas fa-phone"></i> Phone Number</label>
                    <input type="tel"
                           name="phone"
                           value="{{ old('phone', auth()->user()->phone ?? '') }}"
                           placeholder="Enter your phone number">
                    @error('phone')
                        <small class="error-text"><i class="fas fa-exclamation-circle"></i> {{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="form-divider"></div>

            <!-- Actions -->
            <div class="form-actions">
                <button type="button" class="btn-cancel" id="cancelBtn">
                    <i class="fas fa-times"></i> Cancel
                </button>
                <button type="submit" class="btn-save">
                    <i class="fas fa-check"></i> Save Changes
                </button>
            </div>
        </form>
    </div>

</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('profileForm');
    const cancelBtn = document.getElementById('cancelBtn');

    // Store original values
    const originalValues = {};

    [...form.elements].forEach(el => {
        if (!el.name || el.type === 'hidden') return;
        originalValues[el.name] = el.value;
    });

    cancelBtn.addEventListener('click', () => {
        let hasChanges = false;

        [...form.elements].forEach(el => {
            if (!el.name || el.type === 'hidden') return;
            if (el.value !== originalValues[el.name]) {
                hasChanges = true;
            }
        });

        if (!hasChanges) return;

        if (!confirm('Discard your changes?')) return;

        // Restore original values
        [...form.elements].forEach(el => {
            if (!el.name || el.type === 'hidden') return;
            el.value = originalValues[el.name];
        });
    });

    // Toast success message
    @if(session('success'))
        const toast = document.createElement('div');
        toast.className = 'toast toast-success';
        toast.innerHTML = `
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
        `;
        document.body.appendChild(toast);
        setTimeout(() => toast.classList.add('show'), 10);
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    @endif
});
</script>
@endsection
