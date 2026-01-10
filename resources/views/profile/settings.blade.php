@extends('layouts.app')

@section('page-title', 'Account Settings')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/settings.css') }}">
@endsection

@section('content')

<!-- CSRF Token (IMPORTANT for API calls) -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<a href="{{ route('dashboard') }}" style="display: inline-flex; align-items: center; gap: 6px; color: #2563eb; text-decoration: none; font-size: 16px; font-weight: 600; margin-bottom: 24px;">
    <i class="fas fa-arrow-left"></i> Back to Dashboard
</a>

<div class="settings-container">

    <!-- Page Header -->
    <div class="page-header">
        <h2><i class="fas fa-sliders-h"></i> Settings</h2>
        <p>Configure your application preferences and security options</p>
    </div>

    <!-- Account Settings -->
    <div class="settings-card">
        <div class="settings-header">
            <h3><i class="fas fa-lock"></i> Account Security</h3>
        </div>

        <!-- Change Password -->
        <div class="settings-item">
            <div class="settings-info">
                <h4>Change Password</h4>
                <p>Update your password to keep your account secure</p>
            </div>
            <button type="button" class="btn-setting" onclick="togglePasswordModal()">
                <i class="fas fa-key"></i> Change Password
            </button>
        </div>
    </div>

    <!-- Notification Settings -->
    <div class="settings-card">
        <div class="settings-header">
            <h3><i class="fas fa-bell"></i> Notifications</h3>
        </div>

        <!-- Email Notifications -->
        <div class="settings-item">
            <div class="settings-info">
                <h4>Email Notifications</h4>
                <p>Receive email updates about your reports and activities</p>
            </div>
            <label class="toggle-switch">
                <input type="checkbox" id="emailNotifications" checked>
                <span class="toggle-slider"></span>
            </label>
        </div>

        <!-- Report Updates -->
        <div class="settings-item">
            <div class="settings-info">
                <h4>Report Updates</h4>
                <p>Get notified when your reports have updates</p>
            </div>
            <label class="toggle-switch">
                <input type="checkbox" id="reportUpdates" checked>
                <span class="toggle-slider"></span>
            </label>
        </div>

        <!-- Lost & Found Matches -->
        <div class="settings-item">
            <div class="settings-info">
                <h4>Lost & Found Matches</h4>
                <p>Receive notifications when potential matches are found</p>
            </div>
            <label class="toggle-switch">
                <input type="checkbox" id="lostFoundMatches" checked>
                <span class="toggle-slider"></span>
            </label>
        </div>
    </div>

    <!-- Appearance Settings -->
    <div class="settings-card">
        <div class="settings-header">
            <h3><i class="fas fa-paint-brush"></i> Appearance</h3>
        </div>

        <!-- Theme -->
        <div class="settings-item">
            <div class="settings-info">
                <h4>Theme</h4>
                <p>Choose your preferred theme</p>
            </div>
            <select id="themeSelect" class="theme-select">
                <option value="light">Light</option>
                <option value="dark">Dark</option>
                <option value="auto" selected>Auto</option>
            </select>
        </div>
    </div>

</div>

<!-- Change Password Modal -->
<div id="passwordModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-key"></i> Change Password</h3>
            <button type="button" class="modal-close" onclick="togglePasswordModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form method="POST" action="{{ route('password.update') }}" class="modal-form">
            @csrf
            @method('PUT')

            <!-- Current Password -->
            <div class="form-group">
                <label><i class="fas fa-lock"></i> Current Password <span>*</span></label>
                <input type="password" name="current_password" placeholder="Enter your current password" required>
                @error('current_password')
                    <small class="error-text"><i class="fas fa-exclamation-circle"></i> {{ $message }}</small>
                @enderror
            </div>

            <!-- New Password -->
            <div class="form-group">
                <label><i class="fas fa-lock"></i> New Password <span>*</span></label>
                <input type="password" name="password" placeholder="Enter a new password" required>
                @error('password')
                    <small class="error-text"><i class="fas fa-exclamation-circle"></i> {{ $message }}</small>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="form-group">
                <label><i class="fas fa-lock"></i> Confirm Password <span>*</span></label>
                <input type="password" name="password_confirmation" placeholder="Confirm your new password" required>
            </div>

            <!-- Actions -->
            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="togglePasswordModal()">Cancel</button>
                <button type="submit" class="btn-save">Update Password</button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script src="{{ asset('js/settings.js') }}"></script>
<script>
    function togglePasswordModal() {
        const modal = document.getElementById('passwordModal');
        modal.classList.toggle('active');
    }

    // Close modal when clicking outside
    window.addEventListener('click', (e) => {
        const modal = document.getElementById('passwordModal');
        if (e.target === modal) {
            modal.classList.remove('active');
        }
    });

    // Show toast if there's a success message
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
</script>
@endsection