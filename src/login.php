<?php
session_start();
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Include database connection 
require_once __DIR__ . '/../config/database.php';

// Check for post method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: plants.php");
    exit();
}

// Look up user in database
$stmt = $conn->prepare("SELECT username, password FROM login WHERE username = ?");
$stmt->bind_param("s", $_POST['username']);
$stmt->execute();

$user = $stmt->get_result()->fetch_assoc();

// Verify user login credentials
if ($user && password_verify($_POST['password'], $user['password'])) {
    session_regenerate_id(true);
    $_SESSION['username'] = $user['username'];
    
    // Redirect
    header("Location: plants.php");
} else {
    header("Location: login.html?login=failed");
}

$stmt->close();
exit();