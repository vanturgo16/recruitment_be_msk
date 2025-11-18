<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Two-Factor Authentication Code</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            background-color: #f8f9fa;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .email-container {
            max-width: 500px;
            margin: auto;
            background: #fff;
            border-radius: 10px;
            border: 1px solid #e6e6e6;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            padding: 25px;
        }
        .logo {
            text-align: center;
            margin-bottom: 15px;
        }
        .logo img {
            height: 50px;
        }
        .code {
            text-align: center;
            font-size: 32px;
            font-weight: bold;
            letter-spacing: 5px;
            color: #dc3545;
            margin: 20px 0;
        }
        .footer {
            margin-top: 25px;
            font-size: 13px;
            color: #777;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <p>Dear <strong>{{ $user->name }}</strong>,</p>

        <p>To complete your login, please use the following 2FA verification code:</p>

        <div class="code">{{ $code }}</div>

        <p style="text-align:center;">⚠️ This code will expire in <strong>{{ $expired }} seconds</strong>.</p>

        <p>If you did not attempt to log in, please ignore this email or contact your system administrator.</p>

        <div class="footer">
            &copy; {{ date('Y') }} PT Mitra Sendang Kemakmuran Banten<br>
            This is an automated message — please do not reply.
        </div>
    </div>
</body>
</html>
