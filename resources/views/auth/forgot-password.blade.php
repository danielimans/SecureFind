<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password – SecureFind</title>
    <style>
        body {
            background: #f3f4f6;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        .card {
            max-width: 420px;
            margin: 80px auto;
            background: white;
            padding: 32px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        }

        h2 {
            text-align: center;
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        p {
            text-align: center;
            color: #6b7280;
            font-size: 14px;
            margin-bottom: 24px;
        }

        .field {
            margin-bottom: 16px;
        }

        .field label {
            display: block;
            font-size: 13px;
            margin-bottom: 6px;
            font-weight: 600;
        }

        .field input {
            width: 94%;
            padding: 10px;      
            line-height: 1.4;       
            border-radius: 6px;
            border: 1px solid #d1d5db;
            font-size: 14px;
        }

        .field input:focus {
            border-color: #2563eb;
            outline: none;
        }

        .btn {
            width: 100%;
            background: #2563eb;
            color: white;
            padding: 12px;
            border-radius: 6px;
            border: none;
            font-weight: 600;
            cursor: pointer;
        }

        .btn:hover {
            background: #1d4ed8;
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
            padding: 10px 14px;
            border-radius: 6px;
            margin-bottom: 16px;
            font-size: 14px;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            padding: 10px 14px;
            border-radius: 6px;
            margin-bottom: 16px;
            font-size: 14px;
        }

        .back {
            display: block;
            text-align: center;
            margin-top: 18px;
            font-size: 14px;
            color: #2563eb;
            text-decoration: none;
        }
    </style>
</head>

<body>

<div class="card">
    <h2>Forgot Password</h2>
    <p>Enter your email and we will send you a password reset link.</p>

    @if (session('status'))
        <div class="alert-success">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert-error">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="field">
            <label>Email Address</label>
            <input type="email" name="email" required autofocus>
        </div>

        <button class="btn" type="submit">
            Send Reset Link
        </button>
    </form>

    <a class="back" href="{{ route('login') }}">Back to login</a>
</div>

</body>
</html>
