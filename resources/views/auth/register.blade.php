<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SecureFind | Sign Up</title>
    <link rel="stylesheet" href="{{ asset('css/signup.css') }}">
</head>
<body>

<header class="topbar">
    <div class="logo">SecureFind</div>
    <div class="subtitle">Campus Security & Incident Management System</div>
</header>

<main class="container">
    <form method="POST" action="{{ route('register') }}" class="card" id="registerForm">
        @csrf

        <h2>Create Your Account</h2>
        <p class="desc">Join SecureFind to submit and track reports</p>

        <!-- Full Name -->
        <label for="name">Full Name</label>
        <input type="text" id="name" name="name" placeholder="John Doe" value="{{ old('name') }}" required>
        @error('name')
            <div class="error-message show">{{ $message }}</div>
        @else
            <div class="error-message" id="nameError"></div>
        @enderror

        <!-- Email -->
        <label for="email">Email or University ID</label>
        <input type="email" id="email" name="email" placeholder="ci0000@student.uthm.edu.my" value="{{ old('email') }}" required>
        <small>Use your university email or student/staff ID</small>
        @error('email')
            <div class="error-message show">{{ $message }}</div>
        @else
            <div class="error-message" id="emailError"></div>
        @enderror

        <div class="field">
            <label>Phone Number</label>
            <input type="text" name="phone" placeholder="01X-XXXXXXX" required>
        </div>

        <!-- Password -->
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
        @error('password')
            <div class="error-message show">{{ $message }}</div>
        @else
            <div class="error-message" id="passwordError"></div>
        @enderror

        <!-- Confirm Password -->
        <label for="passwordConfirm">Confirm Password</label>
        <input type="password" id="passwordConfirm" name="password_confirmation" required>
        @error('password_confirmation')
            <div class="error-message show">{{ $message }}</div>
        @else
            <div class="error-message" id="confirmError"></div>
        @enderror

        <!-- Role -->
        <label>User Role</label>
        <div class="role-wrapper">
            <label class="role-box">
                <input type="radio" name="role" value="student" {{ old('role') === 'student' ? 'checked' : '' }} required>
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 14l9-5-9-5-9 5 9 5z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 14l6.16-3.422A12.083 12.083 0 0112 21.5a12.083 12.083 0 01-6.16-10.922L12 14z"/>
                </svg>
                <span>Student</span>
            </label>

            <label class="role-box">
                <input type="radio" name="role" value="staff" {{ old('role') === 'staff' ? 'checked' : '' }}>
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 11V7a4 4 0 00-8 0v4M5 11h14v10H5V11z"/>
                </svg>
                <span>Staff</span>
            </label>
        </div>
        @error('role')
            <div class="error-message show">{{ $message }}</div>
        @else
            <div class="error-message" id="roleError"></div>
        @enderror

        <button type="submit">Create Account</button>

        <div class="divider"></div>

        <p class="login-link">
            Already have an account?<br>
            <a href="{{ route('login') }}">Sign in to your account</a>
        </p>
    </form>
</main>

<script src="{{ asset('js/register-validation.js') }}"></script>

</body>
</html>