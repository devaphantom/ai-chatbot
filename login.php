<?php
require 'config.php';
session_start();

$error = '';

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Basic validation
    if (!$email || !$password) {
        $error = "Please enter both email and password.";
    } else {
        // Fetch user from database
        $stmt = $pdo->prepare("SELECT id, password FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        // Verify user exists and password is correct
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id']; // Store user id in session
            header("Location: dashboard.php");  // Redirect to chat dashboard
            exit;
        } else {
            $error = "Invalid email or password.";
        }
    }
}

// If login fails, show error and link back
if ($error) {
    echo "<p style='color:red;'>$error</p>";
    echo "<p><a href='login.html'>Go back to login</a></p>";
}
?>
