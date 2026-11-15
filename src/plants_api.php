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

require 'db.php';
$owner  = $_SESSION['username'];
$action = $_POST['action'] ?? '';

    if ($action === 'add' || $action === 'edit') {
        $sci    = trim($_POST['scientific_name'] ?? '');
        $common = trim($_POST['common_name'] ?? '');
        if ($common === '' || strlen($common) > 50 || strlen($sci) > 50) fail(400, 'Invalid name.');

        if ($action === 'add') {
            $image = saveImage();
            $stmt = $conn->prepare("INSERT INTO plants (owner, scientific_name, common_name, image) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $owner, $sci, $common, $image);
            $stmt->execute();
            echo json_encode(['ok' => true, 'id' => $conn->insert_id, 'image' => $image]);
        }
    } 
    elseif ($action === 'delete') {
        $id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
        if ($id === false) fail(400, 'Invalid id.');

        $stmt = $conn->prepare("SELECT image FROM plants WHERE plant_id = ? AND owner = ?");
        $stmt->bind_param("is", $id, $owner);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();

        $stmt = $conn->prepare("DELETE FROM plants WHERE plant_id = ? AND owner = ?");
        $stmt->bind_param("is", $id, $owner);
        $stmt->execute();
        if ($row) deleteImage($row['image']);
        echo json_encode(['ok' => true]);

    } 
?>