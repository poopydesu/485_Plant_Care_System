<?php
// Helper PHP functions for the pages

// Session declaration and error handling
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Get username of logged-in user, or null if not logged in
function current_user(): ?string {
    return $_SESSION['username'] ?? null;
}

// Redirect to login page if users try to access something they must be logged in for
function require_login(): void {
    if (!current_user()) {
        header('Location: login.php');
        exit();
    }
}

// HTML special chars function, helps prevent XSS attacks, I think Zhang mentioned wanting some type of XSS prevention? 
function h(?string $string): string {
    return htmlspecialchars($string ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

