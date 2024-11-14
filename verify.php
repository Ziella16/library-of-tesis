<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification Code</title>
    <link rel="stylesheet" href="http://localhost/myproject/frontend/public/style3.css">
</head>
<body>
    <div class="container">
        <h2>Email Verification</h2>

        <!-- Email Input Form -->
        <div id="email-form">
            <label for="email">Enter your email:</label>
            <input type="email" id="email" name="email" placeholder="you@example.com" required>
            <button onclick="sendVerificationCode()">Send Verification Code</button>
        </div>

        <!-- Verification Code Form -->
        <div id="verification-form" style="display: none;">
            <label for="code">Enter Verification Code:</label>
            <input type="text" id="code" name="code" placeholder="Enter your code" required>
            <button onclick="verifyCode()">Verify Code</button>
        </div>

        <p id="message"></p>
    </div>
    <script src="http://localhost/myproject/frontend/public/script2.js"></script>
</body>
</html>
