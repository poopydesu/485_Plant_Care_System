<?php
require_once __DIR__ . '/../includes/helper.php';

// Check if user is logged in
require_login();

// Include database connection
require_once __DIR__ . '/../config/database.php';

// Retrieve plants belonging to logged-in user
$stmt = $conn->prepare("
    SELECT up.user_plant_id, up.custom_name, up.species_id, up.image, s.scientific_name
    FROM user_plants up
    LEFT JOIN species s ON s.species_id = up.species_id
    WHERE up.owner = ?
    ORDER BY up.user_plant_id
");

// Bind user to query
$owner = current_user();
$stmt->bind_param("s", $owner);
$stmt->execute();

// Return all matching plants
$plants = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$stmt->close();

// Get all species for the dropdown in the add/edit popup
$species = $conn->query("SELECT species_id, common_name, scientific_name FROM species ORDER BY common_name")->fetch_all(MYSQLI_ASSOC);

$pageTitle = 'My Plants';
require __DIR__ . '/../includes/header.php';
?>
    <!-- popup for adding and editing plants -->
    <dialog id="plantDialog">
        <form id="plantForm" method="dialog">
            <h2 id="dialogTitle">Add plant</h2>
            <label>Name
                <input id="fName" maxlength="50" required>
            </label>
            <label>Species
                <select id="fSpecies">
                    <option value="">Other / not listed</option>
                    <?php foreach ($species as $s): ?>
                        <option value="<?= (int)$s['species_id'] ?>"><?= htmlspecialchars($s['common_name']) ?> (<?= htmlspecialchars($s['scientific_name']) ?>)</option>
                    <?php endforeach; ?>
                </select>
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
        <h1>Your plants</h1>
        <div class="tools">
            <button id="addBtn" title="Add plant" aria-label="Add plant">+</button>
            <button id="removeBtn" title="Remove selected plant" aria-label="Remove selected plant" disabled>&minus;</button>
            <button id="editBtn" title="Edit selected plant" aria-label="Edit selected plant" disabled>&#9998;</button>
        </div>

        <ul id="plantList" role="listbox" aria-label="Plants"></ul>
    </div>

    <!-- Give the plants to plants.js -->
    <script>window.PLANTS = <?= json_encode($plants, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;</script>
    <script src="plants.js"></script>
</body>
</html>
