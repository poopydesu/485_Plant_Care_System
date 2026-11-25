CREATE DATABASE IF NOT EXISTS `PlantCareSystem`;
USE `PlantCareSystem`;

CREATE TABLE `login` (
	`username` VARCHAR(20) PRIMARY KEY,
    `password` VARCHAR(255) NOT NULL
);

CREATE TABLE `plants` (
    `plant_id` INT AUTO_INCREMENT PRIMARY KEY,
    `owner` VARCHAR(50) NOT NULL,
    `scientific_name` VARCHAR(50) NOT NULL,
    `common_name` VARCHAR(50) NOT NULL,
    `image` VARCHAR(255) NULL
);