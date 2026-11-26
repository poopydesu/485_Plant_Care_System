<?php
require_once __DIR__ . '/../includes/helper.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/care.php';

// Get plant ID from URL, not sensitive info so don't need POST
$id = (int)($_GET['id'] ?? 0);

// Retrieve species information
$plant = $conn->query("
    SELECT *
    FROM species
    WHERE species_id = $id
")->fetch_assoc();

// Return to directory if plant does not exist
if (!$plant) {
    header('Location: plants-directory.php');
    exit();
}

// Get the care information for this specific species
$plantCare = get_plant_care($conn, $id);

// Store the information in separate variables
$care = $plantCare['care'];
$light = $plantCare['light'];
$soil = $plantCare['soil'];
$fert = $plantCare['fert'];
$sample = $plantCare['sample'];
$pests = $plantCare['pests'];

// Set page title and include header from plant name
$pageTitle = $plant['common_name'] . ' Care';
require __DIR__ . '/../includes/header.php';
?>
     <div class="main-body">
        
    <!-- Left side of main body is 25% column -->
    <div class="col-left">

        <!-- Plant image and facts -->
        <img src="https://placehold.co/300x500" alt="Placeholder">

        <h2>Plant Facts</h2>

        <p>Scientific Name: <?= h($plant['scientific_name']) ?></p>
        <p>Growing Season: <?= h($plant['growth_season']) ?></p>
        <p>Growing Location: <?= h($sample['locations'] ?? 'Unknown') ?></p>
        <p>Sample Size: <?= (int)$sample['total'] ?> plants</p>

    </div>

    <!-- Right side of main body is 75% column -->
    <div class="col-right">

        <!-- Plant name -->
        <h1><?= h($plant['common_name']) ?></h1>
        <p class="subtitle"><?= h($plant['scientific_name']) ?></p>

        <p>Based on only the healthiest plants from our data samples.</p>

        <!-- Watering -->
        <div class="expand-box">

            <input type="checkbox" id="box-water" class="toggle-input" checked>

            <label for="box-water" class="box-header">
                Watering <span class="icon">&#9660;</span>
            </label>

            <div class="box-content">

                <p>
                    About <?= $care['water_ml'] ?? 'Unknown' ?> ml of water
                    every <?= $care['water_days'] ?? 'Unknown' ?> days.
                </p>

                <?php if ($care['soil_moisture'] !== null): ?>
                    <p>
                        Soil moisture in the healthy plants averaged about
                        <?= $care['soil_moisture'] ?>%.
                    </p>
                <?php endif; ?>

            </div>
        </div>

        <!-- Light and climate -->
        <div class="expand-box">

            <input type="checkbox" id="box-light" class="toggle-input" checked>

            <label for="box-light" class="box-header">
                Light &amp; climate <span class="icon">&#9660;</span>
            </label>

            <div class="box-content">

                <p>Light: <?= h($light ?? 'Unknown') ?></p>
                <p>Temperature: about <?= $care['temp_c'] ?? 'Unknown' ?> &deg;C</p>
                <p>Humidity: about <?= $care['humidity'] ?? 'Unknown' ?>%</p>

            </div>
        </div>

        <!-- Soil and fertilizer -->
        <div class="expand-box">

            <input type="checkbox" id="box-soil" class="toggle-input" checked>

            <label for="box-soil" class="box-header">
                Soil &amp; fertilizer <span class="icon">&#9660;</span>
            </label>

            <div class="box-content">

                <p>Soil: <?= h($soil ?? 'Unknown') ?></p>

                <?php if ($fert): ?>
                    <p>
                        Fertilizer: <?= h($fert['v']) ?>
                        (about <?= $fert['ml'] ?? 'Unknown' ?> ml)
                    </p>
                <?php else: ?>
                    <p>Fertilizer: none used</p>
                <?php endif; ?>

            </div>
        </div>

        <!-- Pests -->
        <div class="expand-box">

            <input type="checkbox" id="box-pests" class="toggle-input" checked>

            <label for="box-pests" class="box-header">
                Pests to watch for <span class="icon">&#9660;</span>
            </label>

            <div class="box-content">

                <!-- Display pest name, description, and treatment -->
                <?php foreach ($pests as $pest): ?>

                    <p>
                        <strong><?= h($pest['name']) ?></strong><br>
                        <?= h($pest['description']) ?><br>
                        Treatment: <?= h($pest['treatment']) ?>
                    </p>

                <?php endforeach; ?>

                <?php if (!$pests): ?>
                    <p>No pests recorded for this plant.</p>
                <?php endif; ?>

            </div>
        </div>

    </div>
</div>

</body>
</html>