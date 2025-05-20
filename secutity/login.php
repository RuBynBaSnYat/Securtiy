<?php
session_start(); 

include('db_connection.php');

$recaptchaError = ''; 
$loginSuccess = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verify reCAPTCHA
    $recaptchaSecretKey = "6LdrE0EpAAAAAEJwBEIhsez5v0syhAs811p2uju_"; 
    $recaptchaResponse = $_POST['g-recaptcha-response'];

    $recaptchaUrl = "https://www.google.com/recaptcha/api/siteverify";
    $recaptchaData = [
        'secret' => $recaptchaSecretKey,
        'response' => $recaptchaResponse
    ];

    $recaptchaOptions = [
        'http' => [
            'method' => 'POST',
            'header' => 'Content-type: application/x-www-form-urlencoded',
            'content' => http_build_query($recaptchaData),
        ]
    ];

    $recaptchaContext = stream_context_create($recaptchaOptions);
    $recaptchaResult = json_decode(file_get_contents($recaptchaUrl, false, $recaptchaContext), true);

    if (!$recaptchaResult['success']) {
        $recaptchaError = "reCAPTCHA verification failed. Please try again.";
    }

    if (isset($_POST["login_username"]) && isset($_POST["login_password"]) && empty($recaptchaError)) {
        $loginUsername = $_POST["login_username"];
        $loginPassword = $_POST["login_password"];

        $sql = "SELECT * FROM sign_up WHERE username='$loginUsername' OR email='$loginUsername'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($loginPassword, $row["password"])) {
                $_SESSION['username'] = $row['username'];
                $_SESSION['email'] = $row['email'];
                
                // Set a flag for login success
                $loginSuccess = true;
            } else {
                echo "Invalid password";
            }
        } else {
            echo "User not found";
        }
    } elseif (isset($_POST["forgot"])) {
        // Forgot Password attempt
        $forgotEmail = $_POST["email"];

        if (filter_var($forgotEmail, FILTER_VALIDATE_EMAIL)) {
            // Check if the email exists in the database
            $checkEmailQuery = "SELECT * FROM sign_up WHERE email = ?";
            $stmtCheckEmail = $conn->prepare($checkEmailQuery);
            $stmtCheckEmail->bind_param("s", $forgotEmail);
            $stmtCheckEmail->execute();
            $resultCheckEmail = $stmtCheckEmail->get_result();

            if ($resultCheckEmail->num_rows > 0) {
                // Email exists, proceed with password reset

                // Generate a random token
                $token = bin2hex(random_bytes(32));

                // Update the user's record with the token
                $updateQuery = "UPDATE sign_up SET reset_token = ? WHERE email = ?";
                $stmt = $conn->prepare($updateQuery);
                $stmt->bind_param("ss", $token, $forgotEmail);
                $stmt->execute();

                // Send an email with the reset link containing the token
                $resetLink = "http://localhost/simple/reset_password_process.php?token=$token";
                $subject = "Password Reset";
                $message = "Click the following link to reset your password: $resetLink";
                mail($forgotEmail, $subject, $message);

                echo "An email has been sent with instructions to reset your password.";
            } else {
                echo "Email not found.";
            }

            $stmtCheckEmail->close();
        } else {
            echo "Invalid email address.";
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="login.css" rel="stylesheet">
  
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script>
        <?php if ($loginSuccess) : ?>
            alert("Login successful!");
            window.location.href = "dashboard.php";
        <?php endif; ?>
    </script>
</head>
<body>
    <div class="login_form">
        <h1>Login</h1>
        <div class="container">
            <div class="main">
                <div class="content">
                    <h2>Log In</h2>
                    <?php if (!empty($recaptchaError)) : ?>
                        <p style="color: red;"><?php echo $recaptchaError; ?></p>
                    <?php endif; ?>
                    <form method="post" action="login.php">
                        <input type="text" name="login_username" placeholder="User Name" required autofocus>
                        <input type="password" name="login_password" placeholder="User Password" required autofocus>
                        <div class="g-recaptcha" data-sitekey="6LdrE0EpAAAAAGZogSq8AbbGdQuYej2NoOrdmTry"></div>
                        <button class="btn" type="submit">Login</button>
                    </form>
                    <p class="account">Don't Have An Account? <a href="signup.php">Register</a></p>
                    <p class="forget-password"><a href="forget_password.php">Forgot Password?</a></p>
                </div>
                <div class="log-img">
                    <img src="Img/login.jpg" alt="">
                </div>
            </div>
        </div>
    </div>
</body>
</html>
