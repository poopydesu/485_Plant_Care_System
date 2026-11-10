<?php
$conn = new mysqli("localhost", "root", "12345678", "PlantCareSystem", 3000);

$users = [
    "alice" => "1234",
    "bob"   => "1234",
    "carol" => "1234",
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