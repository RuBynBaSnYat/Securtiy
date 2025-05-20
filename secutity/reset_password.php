<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: lightblue;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        fieldset {
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            border: 2px solid lightblue;
            width: 300px;
            text-align: center;
        }

        legend {
            color: #333;
            font-size: 1.2em;
            padding: 0 10px;
            background-color: lightblue;
            border-radius: 5px;
            border: 2px solid #fff;
        }

        label {
            display: block;
            margin-top: 10px;
            margin-bottom: 5px;
            color: #555;
        }

        input {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            box-sizing: border-box;
        }

        button {
            background-color: #4CAF50;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        .error-message {
            color: #ff0000;
            margin-top: 10px;
        }
    </style>
</head>
<body>
<?php
include('db_connection.php');

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["token"])) {
    $token = $_GET["token"];

    // Check if the token exists in the database
    $checkTokenQuery = "SELECT * FROM sign_up WHERE reset_token = ?";
    $stmtCheckToken = $conn->prepare($checkTokenQuery);
    $stmtCheckToken->bind_param("s", $token);
    $stmtCheckToken->execute();
    $resultCheckToken = $stmtCheckToken->get_result();

    if ($resultCheckToken->num_rows > 0) {
        // Token is valid, allow the user to reset the password
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Reset Password</title>
        </head>
        <body>
            <fieldset>
                <legend>Reset Password</legend>
                <form method="post" action="reset_password_process.php" onsubmit="return validatePassword()">
                    <input type="hidden" name="token" value="<?php echo $token; ?>">
                    <label for="new-password">New Password:</label>
                    <input type="password" id="new-password" name="new-password" placeholder="Enter Your New Password" required oninput="checkPasswordMatch(); checkPasswordStrength(); checkPasswordValidity()">
                    <p id="password-strength" class="error-message"></p>
                    <label for="confirm-password">Confirm Password:</label>
                    <input type="password" id="confirm-password" name="confirm-password" placeholder="Confirm Your New Password" required oninput="checkPasswordMatch()">
                    <p id="password-match-error" class="error-message"></p>
                    <p id="password-validation-error" class="error-message"></p>
                    <button type="submit">Reset Password</button>
                </form>
            </fieldset>
           <script>
                function checkPasswordStrength() {
                    var newPassword = document.getElementById('new-password').value;
                    var strengthMeter = document.getElementById('password-strength');

                    // Reset the meter
                    strengthMeter.innerHTML = '';

                    // Define criteria
                    var minLength = 8;
                    var hasUppercase = /[A-Z]/.test(newPassword);
                    var hasLowercase = /[a-z]/.test(newPassword);
                    var hasNumber = /\d/.test(newPassword);
                    var hasSpecialChar = /[!@#$%^&*(),.?":{}|<>]/.test(newPassword);

                    // Check each criteria and update the meter
                    if (newPassword.length >= minLength) {
                        strengthMeter.innerHTML += '<span style="color: green;">&#9989 Length is at least ' + minLength + ' characters.</span><br>';
                    } else {
                        strengthMeter.innerHTML += '<span style="color: red;">&#10060 Length should be at least ' + minLength + ' characters.</span><br>';
                    }

                    if (hasUppercase) {
                        strengthMeter.innerHTML += '<span style="color: green;">&#9989 Contains uppercase letters.</span><br>';
                    } else {
                        strengthMeter.innerHTML += '<span style="color: red;">&#10060 Should contain at least one uppercase letter.</span><br>';
                    }

                    if (hasLowercase) {
                        strengthMeter.innerHTML += '<span style="color: green;">&#9989 Contains lowercase letters.</span><br>';
                    } else {
                        strengthMeter.innerHTML += '<span style="color: red;">&#10060 Should contain at least one lowercase letter.</span><br>';
                    }

                    if (hasNumber) {
                        strengthMeter.innerHTML += '<span style="color: green;">&#9989 Contains numbers.</span><br>';
                    } else {
                        strengthMeter.innerHTML += '<span style="color: red;">&#10060 Should contain at least one number.</span><br>';
                    }

                    if (hasSpecialChar) {
                        strengthMeter.innerHTML += '<span style="color: green;">&#9989 Contains special characters.</span><br>';
                    } else {
                        strengthMeter.innerHTML += '<span style="color: red;">&#10060 Should contain at least one special character.</span><br>';
                    }
                }

                function checkPasswordMatch() {
                    var newPassword = document.getElementById('new-password').value;
                    var confirmPassword = document.getElementById('confirm-password').value;
                    var matchErrorElement = document.getElementById('password-match-error');

                    if (newPassword === confirmPassword) {
                        matchErrorElement.innerHTML = '<span style="color: green;">Passwords match</span>'; // Passwords match, clear error message
                    } else {
                        matchErrorElement.innerHTML = '<span style="color: red;">Passwords do not match</span>';
                    }
                }

                function checkPasswordValidity() {
                    var username = document.getElementById('username').value.toLowerCase();
                    var email = document.getElementById('email').value.toLowerCase();
                    var newPassword = document.getElementById('new-password').value;
                    var validityErrorElement = document.getElementById('password-validation-error');

                    // Check if the password contains the username or email
                    if (newPassword.includes(username) || newPassword.includes(email)) {
                        validityErrorElement.innerHTML = '<span style="color: red;">Password cannot contain username or email</span>';
                    } else {
                        validityErrorElement.innerHTML = '';
                    }
                }

                function validatePassword() {
                    var newPassword = document.getElementById('new-password').value;
                    var confirmPassword = document.getElementById('confirm-password').value;
                    var username = document.getElementById('username').value.toLowerCase();
                    var email = document.getElementById('email').value.toLowerCase();
                    var validityErrorElement = document.getElementById('password-validation-error');

                    // Perform additional password policy checks
                    if (newPassword.length < 8) {
                        validityErrorElement.innerHTML = '<span style="color: red;">Password should be at least 8 characters long</span>';
                        return false;
                    }

                    // Check if the password contains the username or email
                    if (newPassword.includes(username) || newPassword.includes(email)) {
                        validityErrorElement.innerHTML = '<span style="color: red;">Password cannot contain username or email</span>';
                        return false;
                    }

                    // Check other password policy requirements as needed

                    // If all checks pass, return true to allow form submission
                    return true;
                }
            </script>
        </body>
        </html>
        <?php
    } else {
        // Invalid token
        echo "Invalid token.";
    }

    $stmtCheckToken->close();
} else {
    // Token not provided in the URL
    echo "Token not provided.";
}

$conn->close();
?>
</body>
</html>
