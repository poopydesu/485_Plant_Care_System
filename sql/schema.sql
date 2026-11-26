CREATE DATABASE IF NOT EXISTS `PlantCareSystem`;
USE `PlantCareSystem`;

CREATE TABLE IF NOT EXISTS `login` (
	`username` VARCHAR(20) PRIMARY KEY,
    `password` VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS `species` (
    `species_id` INT AUTO_INCREMENT PRIMARY KEY,
    `scientific_name` VARCHAR(50) NOT NULL UNIQUE,
    `common_name` VARCHAR(50) NOT NULL,
    `growth_season` VARCHAR(30)
);

CREATE TABLE IF NOT EXISTS `soil_type` (
    `soil_type_id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(30) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS `location` (
    `location_id` INT AUTO_INCREMENT PRIMARY KEY,
    `location` VARCHAR(20) NOT NULL,    -- indoor / outdoor
    `sunlight_exposure` VARCHAR(50),
    `location_temperature` DECIMAL(4,1) -- in degrees C
);

CREATE TABLE IF NOT EXISTS `fertilizer` (
    `fertilizer_id` INT AUTO_INCREMENT PRIMARY KEY,
    `fertilizer_type` VARCHAR(30) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS `pest` (
    `pest_id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(50) NOT NULL UNIQUE,
    `description` VARCHAR(255),
    `treatment` VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS `plant` (
    `plant_id` INT AUTO_INCREMENT PRIMARY KEY,
    `species_id` INT NOT NULL,
    `soil_type_id` INT,
    `location_id` INT,
    FOREIGN KEY (`species_id`) REFERENCES `species`(`species_id`),
    FOREIGN KEY (`soil_type_id`) REFERENCES `soil_type`(`soil_type_id`),
    FOREIGN KEY (`location_id`) REFERENCES `location`(`location_id`)
);

CREATE TABLE IF NOT EXISTS `watering` (
    `watering_id` INT AUTO_INCREMENT PRIMARY KEY,
    `plant_id` INT NOT NULL,
    `date` DATE,
    `watering_amount` INT,         -- ml of water per watering
    `watering_frequency` INT,   -- days between waterings
    FOREIGN KEY (`plant_id`) REFERENCES `plant`(`plant_id`)
);

CREATE TABLE IF NOT EXISTS `plant_observation` (
    `observation_id` INT AUTO_INCREMENT PRIMARY KEY,
    `plant_id` INT NOT NULL,
    `date` DATE,
    `height_cm` DECIMAL(5,2),
    `leaf_count` INT,
    `new_growth_count` INT,
    `health_notes` VARCHAR(100),
    `health_score` INT NOT NULL,    -- 1 (poor) to 5 (thriving)
    FOREIGN KEY (`plant_id`) REFERENCES `plant`(`plant_id`)
);

CREATE TABLE IF NOT EXISTS `environment_reading` (
    `environment_id` INT AUTO_INCREMENT PRIMARY KEY,
    `location_id` INT NOT NULL,
    `date` DATE,
    `humidity_pct` DECIMAL(4,1),
    `soil_moisture_pct` DECIMAL(4,1),
    FOREIGN KEY (`location_id`) REFERENCES `location`(`location_id`)
);

CREATE TABLE IF NOT EXISTS `fertilization` (
    `fertilization_id` INT AUTO_INCREMENT PRIMARY KEY,
    `plant_id` INT NOT NULL,
    `fertilizer_id` INT NOT NULL,
    `date` DATE,
    `fertilizer_amount_ml` DECIMAL(6,1),
    FOREIGN KEY (`plant_id`) REFERENCES `plant`(`plant_id`),
    FOREIGN KEY (`fertilizer_id`) REFERENCES `fertilizer`(`fertilizer_id`)
);

CREATE TABLE IF NOT EXISTS `infestation` (
    `infestation_id` INT AUTO_INCREMENT PRIMARY KEY,
    `plant_id` INT NOT NULL,
    `pest_id` INT NOT NULL,
    `date` DATE,
    `pest_severity` VARCHAR(10),    -- Low / moderate / high
    FOREIGN KEY (`plant_id`) REFERENCES `plant`(`plant_id`),
    FOREIGN KEY (`pest_id`) REFERENCES `pest`(`pest_id`)
);

CREATE TABLE IF NOT EXISTS `user_plants` (
    `user_plant_id` INT AUTO_INCREMENT PRIMARY KEY,
    `owner` VARCHAR(20)  NOT NULL,
    `species_id` INT,   -- NULL for other / not listed plants
    `custom_name` VARCHAR(50) NOT NULL, -- the user's custom name for the plant
    `image` VARCHAR(255),
    `last_watered` DATE,
    FOREIGN KEY (`owner`) REFERENCES `login`(`username`) ON DELETE CASCADE,
    FOREIGN KEY (`species_id`) REFERENCES `species`(`species_id`) ON DELETE SET NULL
);

-- Get the healthiest (score of 4 or more, or best score if no 4's available) samples of the plants from each species
CREATE OR REPLACE VIEW `healthy_plants` AS
SELECT p.plant_id, p.species_id, p.soil_type_id, p.location_id, o.health_score
FROM `plant` p
JOIN `plant_observation` o ON o.plant_id = p.plant_id
WHERE o.health_score >= (
    SELECT LEAST(4, MAX(o2.health_score))
    FROM `plant` p2
    JOIN `plant_observation` o2 ON o2.plant_id = p2.plant_id
    WHERE p2.species_id = p.species_id
);