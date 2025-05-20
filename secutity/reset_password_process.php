<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
    <style>
           body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color:lightblue;
        }

        h2 {
            color: #333;
        }

        .success-message {
            color: #4CAF50;
            font-size: 18px;
            margin-bottom: 20px;
        }

        form {
            display: inline-block;
        }

        button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }
    </style>
    <script>
        function displayErrorMessage(message) {
            alert(message);
        }
    </script>
</head>
<body>
<?php
include('db_connection.php');



if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["token"])) {
    $token = $_POST["token"];

    // Validate the token and process the password reset
    $checkTokenQuery = "SELECT * FROM sign_up WHERE reset_token = ?";
    $stmtCheckToken = $conn->prepare($checkTokenQuery);
    $stmtCheckToken->bind_param("s", $token);
    $stmtCheckToken->execute();
    $resultCheckToken = $stmtCheckToken->get_result();

    if ($resultCheckToken->num_rows > 0) {
       

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["new-password"]) && isset($_POST["confirm-password"])) {
            $newPassword = $_POST["new-password"];
            $confirmPassword = $_POST["confirm-password"];

          
            $isValidPassword = validatePassword($newPassword);

            if ($isValidPassword && $newPassword === $confirmPassword) {
             
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

                // Update the user's password and clear the reset token
                $updatePasswordQuery = "UPDATE sign_up SET password = ?, reset_token = NULL WHERE reset_token = ?";
                $stmtUpdatePassword = $conn->prepare($updatePasswordQuery);
                $stmtUpdatePassword->bind_param("ss", $hashedPassword, $token);
                $stmtUpdatePassword->execute();

                // Check if the password was successfully updated
                if ($stmtUpdatePassword->affected_rows > 0) {
                    echo "Password reset successful!";
                    echo '<form action="login.php">
                    <button type="submit">Return to Login</button>
                  </form>';
                } else {
                    echo '<script>
                        displayErrorMessage("Failed to reset password. Please try again.");
                        window.location.href = "reset_password.php?token=' . $token . '";
                    </script>';
                }

                $stmtUpdatePassword->close();
            } else {
                
                echo '<script>
                    displayErrorMessage("Invalid password. Please ensure it meets the criteria.");
                    window.location.href = "reset_password.php?token=' . $token . '";
                </script>';
            }
        } else {
            echo "New password and confirm password not set.";
        }
    } else {
        // Invalid or expired token
        echo '<script>
            displayErrorMessage("Invalid or expired token.");
            window.location.href = "reset_password.php?token=' . $token . '";
        </script>';
    }

    $stmtCheckToken->close();
}


function validatePassword($password) {
    $minLength = 8;
    $hasUppercase = preg_match('/[A-Z]/', $password);
    $hasLowercase = preg_match('/[a-z]/', $password);
    $hasNumber = preg_match('/\d/', $password);
    $hasSpecialChar = preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password);

    return $hasUppercase && $hasLowercase && $hasNumber && $hasSpecialChar && strlen($password) >= $minLength;
}
?>
</body>
</html>
