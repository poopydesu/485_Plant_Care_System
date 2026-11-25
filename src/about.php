<?php
require_once __DIR__ . '/../includes/helper.php';
$pageTitle = 'Plant Care About';
require __DIR__ . '/../includes/header.php';
?>

    <div class="no-flex-body">
        <h1>PlantCareSystem</h1>
        <p>
            PlantCareSystem is a group project for our database course. It stores care records for a small
            set of houseplants and turns them into easy-to-read care guides, and it lets registered users keep a
            list of their own plants.
        </p>

        <h2>Where the care numbers come from</h2>
        <p>
            Our dataset has a few sample plants for each species, with their watering, light, temperature,
            humidity, soil, fertilizer, pest and health records. For each species we average the numbers from the
            healthiest sample plants (health score 4 or higher). If no sample plant of a species scored that
            well, we use its best-scoring plants and say so on the page.
        </p>
        <p>
            The sample is small, so treat the guidance as a starting point based on our data, not as expert
            horticultural advice.
        </p>

        <h2>How it is built</h2>
        <p>
            A PHP and MySQL back end (tables designed from our ERD), HTML and CSS for the pages, and a little
            JavaScript for the My Plants screen.
        </p>
    </div>
</body>
</html>
