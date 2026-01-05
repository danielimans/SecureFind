<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SecureFind | Login</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>

<header class="topbar">
    <div class="logo">SecureFind</div>
    <div class="subtitle">Campus Security & Incident Management System</div>
</header>

<main class="container">
    <form method="POST" action="{{ route('login') }}" class="card">
        @csrf

        <h2>Welcome Back</h2>
        <p class="desc">Login to access SecureFind</p>

        <!-- Email -->
        <label>Email or University ID</label>
        <input type="email" name="email" placeholder="Enter your email or university ID" required autofocus>

        <!-- Password -->
        <label>Password</label>
        <input type="password" name="password" required>

        <!-- Remember -->
        <label class="remember">
            <input type="checkbox" name="remember">
            Remember me on this device
        </label>

        <button type="submit">Login</button>

        <div class="divider"></div>

        <div class="links">
            <a href="{{ route('password.request') }}">Forgot Password?</a>
            <span>New to SecureFind? <a href="{{ route('register') }}">Sign Up</a></span>
        </div>
    </form>
</main>

</body>
</html>
