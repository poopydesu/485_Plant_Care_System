<?php
// PHP-MySQL database server connection using PHP parse_ini_file

// Setup path to a .env file to hold the database login credentials 
// The .env file needs to be at root of folder
$env = parse_ini_file(__DIR__ . '/../.env', false, INI_SCANNER_RAW);

// Error handling if .env isn't found
if ($env === false) {
    die("Error: Unable to load .env file.");
}

// Set database variables
$host = $env['DB_HOST'];
$user = $env['DB_USER'];
$pass = $env['DB_PASS'];
$name = $env['DB_NAME'];
$port = $env['DB_PORT'];
$charset = 'utf8mb4';

// Try to connect to database
$conn = new mysqli($host, $user, $pass, $name, $port);

// Error handling if connection cannot be established
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set character set and handle errors
if (!$conn->set_charset($charset)) {
    die("Error loading charset: " . $conn->error);
}
?>