<?php
session_start();
header('Content-Type: application/json');

function fail($code, $msg) {
    http_response_code($code);
    echo json_encode(['error' => $msg]);
    exit();
}
function saveImage() {
    if (!isset($_FILES['image']) || $_FILES['image']['error'] === UPLOAD_ERR_NO_FILE) return null;
    $f = $_FILES['image'];
    if ($f['error'] !== UPLOAD_ERR_OK) fail(400, 'Upload failed (file may be too large).');
    if ($f['size'] > 2 * 1024 * 1024) fail(400, 'Image must be under 2 MB.');
    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($f['tmp_name']);
    $ext = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp', 'image/gif' => 'gif'][$mime] ?? null;
    if (!$ext) fail(400, 'Only JPG, PNG, WebP or GIF images are allowed.');

    $dir = __DIR__ . '/uploads';
    if (!is_dir($dir)) mkdir($dir, 0755, true);
    $file = bin2hex(random_bytes(16)) . '.' . $ext;   
    if (!move_uploaded_file($f['tmp_name'], "$dir/$file")) fail(500, 'Could not save image.');
    return "uploads/$file";
}

function deleteImage($path) {
    if ($path && str_starts_with($path, 'uploads/')) @unlink(__DIR__ . '/' . $path);
}

if (!isset($_SESSION['username'])) fail(401, 'Please log in again.');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') fail(405, 'POST only.');

require_once __DIR__ . '/../config/database.php';
$owner  = $_SESSION['username'];
$action = $_POST['action'] ?? '';

    if ($action === 'add' || $action === 'edit') {
        $name = trim($_POST['custom_name'] ?? '');
        if ($name === '' || strlen($name) > 50) fail(400, 'Invalid name.');

        // Species is optional, may be in "other/not listed"
        $species = ($_POST['species_id'] ?? '') === '' ? null : (int)$_POST['species_id'];

        if ($action === 'add') {
            $image = saveImage();
            $stmt = $conn->prepare("INSERT INTO user_plants (owner, species_id, custom_name, image) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("siss", $owner, $species, $name, $image);
            $stmt->execute();
            echo json_encode(['ok' => true]);
        }
        else {
            $id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
            if ($id === false) fail(400, 'Invalid id.');

            // Make sure the plant belongs to this user
            $stmt = $conn->prepare("SELECT image FROM user_plants WHERE user_plant_id = ? AND owner = ?");
            $stmt->bind_param("is", $id, $owner);
            $stmt->execute();
            $row = $stmt->get_result()->fetch_assoc();
            if (!$row) fail(404, 'Plant not found.');

            // Keep old image unless a new one was chosen
            $newImage = saveImage();
            $image = $newImage ?? $row['image'];

            $stmt = $conn->prepare("UPDATE user_plants SET species_id = ?, custom_name = ?, image = ? WHERE user_plant_id = ? AND owner = ?");
            $stmt->bind_param("issis", $species, $name, $image, $id, $owner);
            $stmt->execute();
            if ($newImage) deleteImage($row['image']);
            echo json_encode(['ok' => true]);
        }
    } 
    elseif ($action === 'delete') {
        $id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
        if ($id === false) fail(400, 'Invalid id.');

        $stmt = $conn->prepare("SELECT image FROM user_plants WHERE user_plant_id = ? AND owner = ?");
        $stmt->bind_param("is", $id, $owner);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();

        $stmt = $conn->prepare("DELETE FROM user_plants WHERE user_plant_id = ? AND owner = ?");
        $stmt->bind_param("is", $id, $owner);
        $stmt->execute();
        if ($row) deleteImage($row['image']);
        echo json_encode(['ok' => true]);

    } 
?>
