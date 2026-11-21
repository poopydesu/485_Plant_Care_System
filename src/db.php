<?php
// PHP-DB connection, replace with credentials per-system
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$conn = new mysqli("localhost", "root", "12345678", "PlantCareSystem", 3000);
$conn->set_charset("utf8mb4");
