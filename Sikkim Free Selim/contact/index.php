<?php
// Start session to store admin login status
session_start();

// Check if the admin is already logged in (optional)
if (isset($_SESSION['admin_id'])) {
    header('Location: dashboard.php'); // Redirect to dashboard if already logged in
    exit();
}

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $admin_id = $_POST['admin_id'];
    $password = $_POST['password'];

    // Sample admin credentials (for testing purposes)
    $valid_admin_id = 'SK'; // Admin ID
    $valid_password = 'JAAT'; // Admin password

    // Check if the entered credentials are correct
    if ($admin_id === $valid_admin_id && $password === $valid_password) {
        $_SESSION['admin_id'] = $admin_id; // Store the admin ID in session
        header('Location: dashboard.php'); // Redirect to the admin dashboard
        exit();
    } else {
        $error_message = 'Invalid admin ID or password!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background-color: #444;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }
        .login-container h2 {
            color: #ffcc00;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }
        .form-group label {
            display: block;
            color: #fff;
            font-size: 1.1em;
            margin-bottom: 5px;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            font-size: 1em;
            border: 1px solid #666;
            border-radius: 5px;
            background-color: #333;
            color: #fff;
        }
        .btn {
            width: 100%;
            padding: 12px;
            background-color: #ffcc00;
            color: #333;
            border: none;
            border-radius: 5px;
            font-size: 1.2em;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #e6b800;
        }
        .error-message {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>Admin Login</h2>
    <?php if (isset($error_message)): ?>
        <p class="error-message"><?= $error_message ?></p>
    <?php endif; ?>
    <form method="POST">
        <div class="form-group">
            <label for="admin_id">Admin ID</label>
            <input type="text" id="admin_id" name="admin_id" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit" class="btn">Login</button>
    </form>
</div>

</body>
</html>
