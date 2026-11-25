<?php
// Register an account
require_once __DIR__ . '/../includes/helper.php';

// If user is already logged in, redirect to their plants
if (current_user()) {
    header('Location: my-plants.php');
    exit();
}

// Check for post method and error handling
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Include database credentials
    require_once __DIR__ . '/../config/database.php';

    // Get username and password from user-entered info
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm'] ?? '';

    // Error handling
    // Blank username and/or password
    if ($username === '' || $password === '') {
        $error = 'Please fill in all fields.';
    // Password confirmation doesn't match
    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match.';
    // Username is primary key, so no duplicates
    } else {
        // Check if username already exists
        $check = $conn->prepare('SELECT username FROM login WHERE username = ?');
        $check->bind_param('s', $username);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $error = 'That username is already taken.';
        // Insert new user if not exists
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare('INSERT INTO login (username, password) VALUES (?, ?)');
            $stmt->bind_param('ss', $username, $hash);
            $stmt->execute();
            $stmt->close();

            // Session created and user redirected to their plants
            $_SESSION['username'] = $username;
            header('Location: my-plants.php');
            exit();
        }
        $check->close();
    }
}

$pageTitle = 'Plant Care Register';
$bodyId    = 'login';
require __DIR__ . '/../includes/header.php';
?>

    <div class="main-body">
        <div class="container">
            <h1>Register</h1>
            <!-- Display error -->
            <?php if ($error): ?><p class="msg"><?= $error?></p><?php endif; ?>

            <form action="register.php" method="POST">
                <label for="username">Username:</label>
                <div class="input-box">
                    <input type="text" id="username" name="username" required value="<?=$_POST['user'] ?? ''?>">
                </div>

                <label for="password">Password:</label>
                <div class="input-box">
                    <input type="password" id="password" name="password" required>
                </div>

                <label for="confirm">Confirm password:</label>
                <div class="input-box">
                    <input type="password" id="confirm" name="confirm" required>
                </div>

                <input type="submit" value="Create" class="submit-btn">
            </form>
            <!-- Redirect to login page if user already has an account -->
            <p>Already registered? <a href="login.php">Log in</a></p>
        </div>
    </div>
</body>
</html>
