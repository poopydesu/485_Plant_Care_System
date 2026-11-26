<?php
require_once __DIR__ . '/helper.php';
// Shared header and nav bar for all pages

// Set variables for each use, otherwise default 
$pageTitle = $pageTitle ?? 'Plant Care System';
$bodyId    = $bodyId ?? 'index';
?>


<!DOCTYPE html>
<html lang="en">

<!-- Shared header for all pages -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Megan Warren">
    <meta name="author" content="Rachal Thornton">
    <title><?=($pageTitle)?></title>
    <link rel="icon" type="image/png" href="assets/favicon.png">
    <link rel="stylesheet" href="style.css">
</head>

<!-- Define the body id for the page to add specific CSS, if wanted -->
<body id="<?=$bodyId?>">
    <!-- Nav bar w/extra buttons for logged-in users -->
    <nav>
        <!-- Favicon -->
        <a href="index.php"><img src="assets/favicon.png" alt="Site Logo" width="40" height="40"><span class="logo-text">Plant Care System</span></a>

        <!-- Nav links -->
        <div class="nav-links">
            <a class="nav-btn" href="index.php">Home</a>
            <a class="nav-btn" href="plants-directory.php">View Plants</a>
            
            <!-- If user is logged in, offer them their plants and logout buttons -->
            <?php if (current_user()): ?>
                <a class="nav-btn" href="my-plants.php">My Plants</a>
                <a class="nav-btn" href="logout.php">Logout</a>
            
            <!-- If a user isn't logged in, offer them the login button -->
            <?php else: ?>
                <a class="nav-btn" href="login.php">Login</a>
            
            <!-- Always end in about button -->
            <?php endif; ?>
            <a class="nav-btn" href="about.php">About Us</a>
        </div>
    </nav>

<!-- Need to end with the closing body and html tags in the respective pages HTML files,
 otherwise it will result in an error -->
