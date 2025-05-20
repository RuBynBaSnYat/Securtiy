<?php
include('db_connection.php');

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verify reCAPTCHA
    $recaptchaSecretKey = "6LdrE0EpAAAAAEJwBEIhsez5v0syhAs811p2uju_"; 
    $recaptchaResponse = $_POST['g-recaptcha-response']; // Retrieve reCAPTCHA response from the form data

    $recaptchaUrl = 'https://www.google.com/recaptcha/api/siteverify';
    $recaptchaData = [
        'secret' => $recaptchaSecretKey,
        'response' => $recaptchaResponse,
    ];

    $options = [
        'http' => [
            'header' => 'Content-type: application/x-www-form-urlencoded',
            'method' => 'POST',
            'content' => http_build_query($recaptchaData),
        ],
    ];

    $context = stream_context_create($options);
    $recaptchaResult = file_get_contents($recaptchaUrl, false, $context);
    $recaptchaResult = json_decode($recaptchaResult);

    if (!$recaptchaResult->success) {
        die("reCAPTCHA verification failed.");
    }

    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Hash the password using the default algorithm (currently bcrypt)
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    if (empty($username) || empty($email) || empty($password)) {
        echo "All fields are required.";
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email address.";
        exit;
    }

    // Insert the new user into the database
    $insertStmt = $conn->prepare("INSERT INTO sign_up (username, email, password) VALUES (?, ?, ?)");
    $insertStmt->bind_param("sss", $username, $email, $hashedPassword);

    // set parameters and execute
    if ($insertStmt->execute()) {
        // Redirect to the home page after successful signup
        echo "Signup successful!";
        header("Location: login.php");
        exit(); // Ensure that script execution stops after the redirect
    } else {
        echo "Error: " . $insertStmt->error;
    }

    $insertStmt->close();
}

$conn->close();
?>
