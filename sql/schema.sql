create database PlantCareSystem;

-- Users table
use PlantCareSystem;

create TABLE login (
	username varchar(20) NOT NULL,
    `password` varchar(10) NOT NULL
);

select * from login;

-- WIP: Plants table, may need to decompose
CREATE TABLE IF NOT EXISTS plants (
    id INT PRIMARY KEY AUTO_INCREMENT,
    scientific_name VARCHAR(35) NOT NULL,
    common_name VARCHAR(35) NOT NULL,
    height_cm DECIMAL(5, 2) -- Store up to 5 total digits for height, with 2 digits after decimal
    leaf_count INT,
    new_growth_count INT,
    health_notes VARCHAR(35)
);