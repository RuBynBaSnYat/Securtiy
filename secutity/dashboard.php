<?php
session_start(); // Start session
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cybersecurity Dashboard - Welcome</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #fff;
        }

        header {
            background-color: rgba(239, 125, 125, 0.8);
            color: red;
            padding: 10px;
            text-align: left;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        nav {
            font-weight: bolder;
            color: #fff;
            padding: 10px;
            text-align: center;
        }

        .dashboard-content {
            padding: 20px;
        }

        .info-section {
            background-color: rgba(70, 70, 70, 0.8);
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            color: #fff;
        }

        .info-section h2 {
            color: #0e79eb;
            font-family: 'Times New Roman', serif;
        }

        .info-section p {
            color: #0e0113;
            font-family: 'Verdana', sans-serif;
        }
    </style>
</head>
<body>

<header>
    <h1>Cybersecurity</h1>
    <nav>
        <a class="logout-link" href="logout.php">Logout (<?php echo isset($_SESSION['username']) ? $_SESSION['username'] : ''; ?>)</a>
    </nav>
</header>

<div class="dashboard-content">
    <div class="info-section">
        <h2>Welcome, <?php echo isset($_SESSION['username']) ? $_SESSION['username'] : ''; ?></h2>
        <p>Email: <?php echo isset($_SESSION['email']) ? $_SESSION['email'] : ''; ?></p>
    </div>
</div>

</body>
</html>
