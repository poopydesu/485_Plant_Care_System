<?php
require_once __DIR__ . '/../includes/helper.php';
require_once __DIR__ . '/../config/database.php';
// Need to add plant id
require __DIR__ . '/../includes/header.php';
?>
     <div class="main-body">
        <!-- Left side of main body is 25% column-->
        <div class="col-left">
            <!-- Plant image and facts -->
            <img src="https://placehold.co/300x500" alt="Placeholder">
            <h2>Plant Facts</h2>
            <p>Scientific Name</p>
            <p>Growing Season</p>
            <p>Growing Location</p>
            <p>Sample Size</p>

            <!-- Need to draw from DB here for facts, maybe add a way for users to add to their plants from here? -->
        </div>
        
        <!-- Right side of main body is 75% column-->
        <div class="col-right">
            <!-- Plant name-->
            <h1>Plant Name</h1>
            <p class="subtitle">Scientific Name</p>

            <p>Based on only the healthiest plants from our data samples.</p>

            <!-- Expanding boxes-->
            <div class="expand-box">
                <input type="checkbox" id="box-water" class="toggle-input" checked>
                <label for="box-water" class="box-header">Watering <span class="icon">&#9660;</span></label>
                <div class="box-content">
                    <p>
                        About ? ml of water every ? days.
                    </p>
                    <?php if ($care['soil_moisture_pct'] !== null): ?>
                        <p>Soil moisture in the healthy plants averaged about <?= h(round($care['soil_moisture_pct'])) ?>%.</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="expand-box">
                <input type="checkbox" id="box-light" class="toggle-input" checked>
                <label for="box-light" class="box-header">Light &amp; climate <span class="icon">&#9660;</span></label>
                <div class="box-content">
                    <p>Light</p>
                    <p>Temperature</p>
                    <p>Humidity</p>
                </div>
            </div>

            <div class="expand-box">
                <input type="checkbox" id="box-soil" class="toggle-input" checked>
                <label for="box-soil" class="box-header">Soil &amp; fertilizer <span class="icon">&#9660;</span></label>
                <div class="box-content">
                    <p>Soil</p>
                    <p>Fertilizer</p>
                </div>
            </div>

            <div class="expand-box">
                <input type="checkbox" id="box-pests" class="toggle-input" checked>
                <label for="box-pests" class="box-header">Pests to watch for <span class="icon">&#9660;</span></label>
                <div class="box-content">
                   <!-- List pest name, description, and treatment -->
                </div>
            </div>

        </div>
     </div>
</body>
</html>
