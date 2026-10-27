use cs485_labs;

-- Users table
CREATE TABLE IF NOT EXISTS plant_users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(25) NOT NULL,
    password VARCHAR (25) NOT NULL
);

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