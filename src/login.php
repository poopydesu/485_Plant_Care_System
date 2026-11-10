<?php
session_start();
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login.html");
    exit();
}

$conn = new mysqli("localhost", "root", "12345678", "PlantCareSystem", 3000);

$stmt = $conn->prepare("SELECT username, password FROM login WHERE username = ?");
$stmt->bind_param("s", $_POST['username']);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if ($user && password_verify($_POST['password'], $user['password'])) {
    session_regenerate_id(true);
    $_SESSION['username'] = $user['username'];
    header("Location: plants.html");
} else {
    header("Location: login.html?login=failed");
}
exit();