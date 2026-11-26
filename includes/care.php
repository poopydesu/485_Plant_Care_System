<?php
// PHP scripts for getting care instructions for each plant based on the healthy_plants view in the DB
// Separate from plant-info.php since it will be a lot of query statements
// Function to retrieve care information for a specific plant species
function get_plant_care($conn, $id) {
    // Averages from the healthiest plants in dataset per species
    $care = $conn->query("
        SELECT ROUND(AVG(w.watering_amount)) AS water_ml,
               ROUND(AVG(w.watering_frequency), 1) AS water_days,
               ROUND(AVG(l.location_temperature), 1) AS temp_c,
               ROUND(AVG(e.humidity_pct)) AS humidity,
               ROUND(AVG(e.soil_moisture_pct)) AS soil_moisture
        FROM healthy_plants h
        JOIN watering w ON w.plant_id = h.plant_id
        JOIN location l ON l.location_id = h.location_id
        JOIN environment_reading e ON e.location_id = h.location_id
        WHERE h.species_id = $id
    ")->fetch_assoc();

    // Most common light exposure
    $light = $conn->query("
        SELECT l.sunlight_exposure AS light
        FROM healthy_plants h
        JOIN location l ON l.location_id = h.location_id
        WHERE h.species_id = $id
        GROUP BY l.sunlight_exposure
        ORDER BY COUNT(*) DESC, light
        LIMIT 1
    ")->fetch_assoc()['light'] ?? null;

    // Most common soil type
    $soil = $conn->query("
        SELECT st.name AS soil
        FROM healthy_plants h
        JOIN soil_type st ON st.soil_type_id = h.soil_type_id
        WHERE h.species_id = $id
        GROUP BY st.name
        ORDER BY COUNT(*) DESC, soil
        LIMIT 1
    ")->fetch_assoc()['soil'] ?? null;

    // Most common fertilizer and average amount
    $fert = $conn->query("
        SELECT f.fertilizer_type AS fert,
               ROUND(AVG(fz.fertilizer_amount_ml)) AS ml
        FROM healthy_plants h
        JOIN fertilization fz ON fz.plant_id = h.plant_id
        JOIN fertilizer f ON f.fertilizer_id = fz.fertilizer_id
        WHERE h.species_id = $id
        GROUP BY f.fertilizer_type
        ORDER BY COUNT(*) DESC, fert
        LIMIT 1
    ")->fetch_assoc();

    // Sample size and locations where the plants were grown
    $sample = $conn->query("
        SELECT COUNT(*) AS total,
               GROUP_CONCAT(DISTINCT l.location ORDER BY l.location SEPARATOR ' / ') AS locations
        FROM plant p
        JOIN location l ON l.location_id = p.location_id
        WHERE p.species_id = $id
    ")->fetch_assoc();

    // Pests recorded for this species with descriptions and treatments
    $pests = $conn->query("
        SELECT pe.name, pe.description, pe.treatment
        FROM infestation i
        JOIN plant p ON p.plant_id = i.plant_id
        JOIN pest pe ON pe.pest_id = i.pest_id
        WHERE p.species_id = $id
        GROUP BY pe.pest_id, pe.name, pe.description, pe.treatment
        ORDER BY COUNT(*) DESC, pe.name
    ")->fetch_all(MYSQLI_ASSOC);

    // Return all information to variables
    return [
        'care' => $care,
        'light' => $light,
        'soil' => $soil,
        'fert' => $fert,
        'sample' => $sample,
        'pests' => $pests
    ];
}
?>