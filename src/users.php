<?php
$conn = new mysqli("localhost", "root", "123", "PlantCareSystem", 3306);

$users = [
    "megan" => "1234",
    "rachal"   => "1234",
];

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