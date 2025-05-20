<!-- signup.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <link rel="stylesheet" href="signup.css">
    <title>Password Strength Checker and Signup Form with reCAPTCHA</title>
</head>
<body>
    
<div class="signup-container">
    <h2>SIGN UP</h2>
    <form id="signup-form" action="signup_process.php" method="post" onsubmit="return submitForm()">
        <div class="form-group">
            <label for="username" style="color: green; font-weight:bold">USER NAME:</label>
            <input type="text" id="username" name="username" placeholder="Enter User Name" required oninput="checkPasswordValidity()">
        </div>

        <div class="form-group">
            <label for="email" style="color: green; font-weight:bold;">EMAIL:</label>
            <input type="email" id="email" name="email" placeholder="Enter Your Email" required oninput="checkPasswordValidity()">
        </div>

        <div class="form-group">
            <label for="password" style="color: green; font-weight: bold;">PASSWORD:</label>
            <input type="password" id="password" name="password" placeholder="Enter Your Password" required oninput="checkPasswordMatch(); checkPasswordStrength(); checkPasswordValidity()">
            <div id="password-strength-meter" class="password-strength-meter">
                <div class="strength-bar" id="bar-1"></div>
                <div class="strength-bar" id="bar-2"></div>
                <div class="strength-bar" id="bar-3"></div>
            </div>
            <p id="password-strength" class="error-message"></p>
        </div>

        <div class="form-group">
            <label for="confirm-password" style="color: green; font-weight: bold;">CONFIRM PASSWORD:</label>
            <input type="password" id="confirm-password" name="confirm-password" placeholder="Confirm Your password" required oninput="checkPasswordMatch()">
            <p id="password-match-error" class="error-message"></p>
        </div>

        <p id="password-validation-error" class="error-message"></p>

        <div class="g-recaptcha" data-sitekey="6LdrE0EpAAAAAGZogSq8AbbGdQuYej2NoOrdmTry"></div>

        <button type="submit">Sign Up</button>
    </form>
</div>

<script src="script.js"></script>
</body>
</html>
