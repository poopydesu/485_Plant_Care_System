<?php
// Logout by removing session
require_once __DIR__ . '/../includes/helper.php';

session_unset();
session_destroy();

// Redirect to homepage
header('Location: index.php');
exit();
