<?php
// Include database connection
require_once __DIR__ . '/../config/database.php';

// Users to add into the database for our project
$users = [
    "megan" => "1234",
    "rachal"   => "1234",
];

// Prepare SQL statement
$stmt = $conn->prepare("INSERT INTO login (username, password) VALUES (?, ?)");

foreach ($users as $name => $plain) {
    $hash = password_hash($plain, PASSWORD_DEFAULT);

    $stmt->bind_param("ss", $name, $hash);
    $stmt->execute();

    echo "Added $name<br>";
}

$stmt->close();
$conn->close();
?>