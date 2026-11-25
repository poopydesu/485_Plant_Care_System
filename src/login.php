<?php
require_once __DIR__ . '/../includes/helper.php';

// Check if user is logged in, redirect to their plants if so
if (current_user()) {
    header('Location: my-plants.php');
    exit();
}

// Check for post method and prepare error-handling
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Include database credentials
    require_once __DIR__ . '/../config/database.php';

    // Get user-entered variables
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    // Prepare query
    $stmt = $conn->prepare('SELECT username, password FROM login WHERE username = ?');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $username = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    // Validate credentials
    if ($username && password_verify($password, $username['password'])) {
        session_regenerate_id(true);
        $_SESSION['username'] = $username['username'];
        header('Location: my-plants.php');
        exit();
    }
    $error = 'Invalid username or password.';
}

$pageTitle = 'Plant Care Login';
$bodyId    = 'login';
require __DIR__ . '/../includes/header.php';
?>

    <div class = "main-body">
        <!-- Container to keep UI separate from background -->
        <div class="container">
            <h1>Login</h1>
            <!-- Display error message if issue logging in -->
            <?php if ($error): ?><p class="msg"><?= $error ?></p><?php endif; ?>

            <!-- Login form -->
            <form action="login.php" method="POST">
                <label for="username">Username:</label>
                <div class="input-box">
                    <input type="text" id="username" name="username" required><br>
                    <i class='bx bxs-user'></i>
                </div>

                <label for="password">Password:</label>
                <div class="input-box">
                    <input type = "text" id="password" name="password" required><br>
                    <i class='bx bxs-lock-alt'></i>
                </div>
                
                <input type="submit" name = "Login" value="Submit" class="submit-btn">
            </form>
            <!-- Add a way for users to register -->
            <p>No account? <a href="register.php">Register Here</a></p>
        </div>
    </div>
</body>
</html>
