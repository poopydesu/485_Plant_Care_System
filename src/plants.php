<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}
require 'db.php';
$stmt = $conn->prepare("SELECT plant_id, scientific_name, common_name, image FROM plants WHERE owner = ? ORDER BY plant_id");
$stmt->bind_param("s", $_SESSION['username']);
$stmt->execute();
$plants = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <script>window.PLANTS = <?= json_encode($plants, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;</script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Megan Warren">
    <meta name="author" content="Rachal Thornton">
    <title>Plant Care Plants</title>
    <link rel="icon" type="image/png" href="assets/favicon.png">
    <link rel="stylesheet" href="style.css">
    <script src="plants.js" defer></script>
</head>

<body id="index">
    <!-- Nav bar to hold login, view plants, and about us buttons -->
    <nav>
        <!-- Logo/favicon in top left with placeholder name -->
        <a href="index.html"><img src="assets/favicon.png" alt="Site Logo" width="40" height="40"><span
                class="logo-text">Plant Care System</span></a>

        <!-- Form to allow PHP POST method -->
        <form method="POST" action="index.php">
            <button type="submit" class="nav-btn" name="index-btn-home">Home</button>
            <button type="submit" class="nav-btn" name="index-btn-login">Login</button>
            <button type="submit" class="nav-btn" name="index-btn-about">About Us</button>
        </form>
    </nav>
    
    <!-- popup for adding, removing, and editing plants -->
    <dialog id="plantDialog">
        <form id="plantForm" method="dialog">
            <h2 id="dialogTitle">Add plant</h2>
            <label>Common Name
                <input id="fCName" maxlength="50" required>
            </label>
            <label>Scientific Name
                <input id="fSName" maxlength="50">
            </label>
            <label>Upload Image
                <input id="fImg" type="file" accept="image/*">
            </label>
            <div class="actions">
                <button type="button" id="cancelBtn">Cancel</button>
                <button type="submit" class="primary">Save plant</button>
            </div>
        </form>
    </dialog>

    <div class="no-flex-body">
        <h1>Click on a plant to learn more about it!</h1>
        <div class="tools">
            <button id="addBtn" title="Add plant" aria-label="Add plant">+</button>
            <button id="removeBtn" title="Remove selected plant" aria-label="Remove selected plant" disabled>&minus;</button>
            <button id="editBtn" title="Edit selected plant" aria-label="Edit selected plant" disabled>&#9998;</button>
        </div>

        <!-- experimental list for plants -->
        <ul id="plantList" role="listbox" aria-label="Plants" onclick="window.location='plant-info.html';"></ul>
    
    </div>
</body>

</html>