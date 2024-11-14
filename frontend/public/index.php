<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website With Login & Registration | Codehal</title>
    <link rel="stylesheet" href="style.css">
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</head>
<body>
    <div class="video-background">
        <video autoplay muted loop id="background-video">
            <source src="abstract-connected-dots.1920x1080.mp4" type="video/mp4">
        </video>
    </div>

    <header>
        <h2 class="logo">
            <img src="LogoAdtec.png" alt="Logo" class="logo-img">
        </h2>
        <nav class="navigation">
            <a href="#" aria-label="About Us">About</a>
            <a href="#" aria-label="Our Services">Services</a>
            <a href="#" aria-label="Contact Us">Contact</a>
            <button class="btnLogin-popup" aria-label="Open Login Popup">Login</button>
        </nav>
    </header>

    <div class="wrapper">
        <button class="icon-close" aria-label="Close Popup">
            <ion-icon name="close-outline"></ion-icon>
        </button>

        <!-- Login Form -->
        <div class="form-box login">
            <h2>Login</h2>
            <form id="loginForm" method="POST" action="../../Login_verification.php">
                <div class="input-box">
                    <span class="icon">
                        <ion-icon name="mail-outline"></ion-icon>
                    </span>
                    <input type="email" id="email" name="email" required aria-label="Email">
                    <label for="email">Email</label>
                </div>
                <div class="input-box">
                    <span class="icon">
                        <ion-icon name="lock-closed-outline"></ion-icon>
                    </span>
                    <input type="password" id="loginPassword" name="password" required aria-label="Password">
                    <label for="password">Password</label>
                    <span class="toggle-password" onclick="togglePasswordVisibility('loginPassword', this)">
                    <ion-icon name="eye-outline"></ion-icon>
                </div>
                <div class="remember-forgot">
                    <label>
                        <input type="checkbox" name="remember" aria-label="Remember me">
                        Remember me
                    </label>
                    <a href="#" aria-label="Forgot Password?">Forgot Password?</a>
                </div>
                <button type="submit" class="btn">Login</button>
                <div class="login-register">
                    <p>Don't have an account? 
                        <a href="#" class="register-link" aria-label="Register Here">Register</a>
                    </p>
                </div>
            </form>
        </div>

        <!-- Registration Form -->
        <div class="form-box register">
            <h2>Registration</h2>
            <form id="registrationForm" method="POST" action="http://localhost/myproject/register.php">
                <div class="input-box">
                    <span class="icon">
                        <ion-icon name="person-outline"></ion-icon>
                    </span>
                    <input type="text" id="registerUsername" name="username" required aria-label="Username">
                    <label for="registerUsername">Username</label>
                </div>
                <div class="input-box">
                    <span class="icon">
                        <ion-icon name="mail-outline"></ion-icon>
                    </span>
                    <input type="email" id="registerEmail" name="email" required aria-label="Email">
                    <label for="registerEmail">Email</label>
                </div>
                <div class="input-box">
                    <span class="icon">
                        <ion-icon name="lock-closed-outline"></ion-icon>
                    </span>
                    <input type="password" id="registerPassword" name="password" required aria-label="Password">
                    <label for="registerPassword">Password</label>
                    <span class="toggle-password" onclick="togglePasswordVisibility('registerPassword', this)">
                    <ion-icon name="eye-outline"></ion-icon>
                </div>
                <div class="remember-forgot">
                    <label>
                        <input type="checkbox" required aria-label="Agree to terms">
                        I agree to the terms & conditions
                    </label>
                </div>
                <button type="submit" class="btn">Register</button>
            </form>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>
