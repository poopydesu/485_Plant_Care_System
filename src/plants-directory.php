<?php 
require_once __DIR__ . '/../includes/helper.php'; 
require_once __DIR__ . '/../config/database.php'; 

// Get all plant species from DB 
$stmt = $conn->prepare("SELECT species_id, scientific_name, common_name FROM species ORDER BY common_name"); 
$stmt->execute(); 
$plants = $stmt->get_result()->fetch_all(MYSQLI_ASSOC); 
$stmt->close(); 

$pageTitle = 'Plant Directory'; 
require __DIR__ . '/../includes/header.php'; 
?> 

<div class="no-flex-body"> 
    <h1>Click on a plant to learn more about it!</h1> 
    
    <!-- List all available plants in a list --> 
    <ul id="plantList" aria-label="Plants"> 
        <!-- Error handling for no plants in list --> 
        <?php if (empty($plants)): ?> 
            <li>No plants available.</li> 
        <?php else: ?> 
            <?php foreach ($plants as $plant): ?> 
                <li> 
                    <!-- Link to each plant based on ID --> 
                    <a href="plant-info.php?id=<?= (int)$plant['species_id'] ?>"> 
                        <div class="plant-text"> 
                            <div class="name"><?= htmlspecialchars($plant['common_name']) ?></div> 
                            <div class="meta"><?= htmlspecialchars($plant['scientific_name']) ?></div> 
                        </div> 
                    </a> 
                </li> 
            <?php endforeach; ?>
        <?php endif; ?> 
    </ul> 
</div>
</body>
</html>
