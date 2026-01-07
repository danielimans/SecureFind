@extends('layouts.app')

@section('content')
<div class="card" style="max-width:500px;margin:auto">
    <h3>Change Password</h3>

    <form method="POST" action="{{ route('password.update') }}">
        @csrf

        <div class="form-group">
            <label>Current Password</label>
            <input type="password" name="current_password" required>
        </div>

        <div class="form-group">
            <label>New Password</label>
            <input type="password" name="password" required>
        </div>

        <div class="form-group">
            <label>Confirm Password</label>
            <input type="password" name="password_confirmation" required>
        </div>

        <button class="btn-primary">Update Password</button>
    </form>
</div>
@endsection
