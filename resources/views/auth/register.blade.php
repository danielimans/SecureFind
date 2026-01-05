<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SecureFind | Sign Up</title>
    <link rel="stylesheet" href="{{ asset('css/signup.css') }}">
</head>
<body>

<header class="topbar">
    <div class="logo">SecureFind</div>
    <div class="subtitle">Campus Security & Incident Management System</div>
</header>

<main class="container">
    <form method="POST" action="{{ route('register') }}" class="card">
        @csrf

        <h2>Create Your Account</h2>
        <p class="desc">Join SecureFind to submit and track reports</p>

        <!-- Full Name -->
        <label>Full Name</label>
        <input type="text" name="name" placeholder="John Doe" required>

        <!-- Email -->
        <label>Email or University ID</label>
        <input type="email" name="email" placeholder="ci0000@student.uthm.edu.my" required>
        <small>Use your university email or student/staff ID</small>

        <!-- Password -->
        <label>Password</label>
        <input type="password" name="password" required>

        <!-- Confirm -->
        <label>Confirm Password</label>
        <input type="password" name="password_confirmation" required>

        <!-- Role -->
        <label>User Role</label>
        <div class="role-wrapper">
            <label class="role-box">
                <input type="radio" name="role" value="student" required>
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
                <input type="radio" name="role" value="staff">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 11V7a4 4 0 00-8 0v4M5 11h14v10H5V11z"/>
                </svg>
                <span>Staff</span>
            </label>
        </div>

        <button type="submit">Create Account</button>

        <div class="divider"></div>

        <p class="login-link">
            Already have an account?<br>
            <a href="{{ route('login') }}">Sign in to your account</a>
        </p>
    </form>
</main>

</body>
</html>
