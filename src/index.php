<?php
// Redirect page to correct destination
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Login page redirect
    if (isset($_POST['index-btn-login'])) {
        header("Location: login.html");
        exit();

    // Plants page redirect
    } elseif (isset($_POST['index-btn-plants'])) {
        header("Location: plants.html");
        exit();

    // About page redirect
    } elseif (isset($_POST['index-btn-about'])) {
        header("Location: about.html");
        exit();
    }
}
?>