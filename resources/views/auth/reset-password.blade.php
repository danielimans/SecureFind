<!DOCTYPE html>
<html>
<head>
    <title>Reset Password – SecureFind</title>
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
            width: 100%;
            padding: 12px 14px;
            border-radius: 6px;
            border: 1px solid #d1d5db;
            font-size: 14px;
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

        .alert {
            background: #fee2e2;
            color: #991b1b;
            padding: 10px 14px;
            border-radius: 6px;
            margin-bottom: 16px;
            font-size: 14px;
        }
    </style>
</head>

<body>

<div class="card">
    <h2>Reset Password</h2>

    @if ($errors->any())
        <div class="alert">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.update') }}">
        @csrf

        <input type="hidden" name="token" value="{{ request()->route('token') }}">
        <input type="hidden" name="email" value="{{ request('email') }}">

        <div class="field">
            <label>New Password</label>
            <input type="password" name="password" required>
        </div>

        <div class="field">
            <label>Confirm Password</label>
            <input type="password" name="password_confirmation" required>
        </div>

        <button class="btn">Reset Password</button>
    </form>
</div>

</body>
</html>
