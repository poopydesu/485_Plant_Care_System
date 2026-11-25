<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$conn = new mysqli("localhost", "root", "123", "PlantCareSystem", 3306);
$conn->set_charset("utf8mb4");
