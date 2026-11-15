create database PlantCareSystem;
USE PlantCareSystem;

CREATE TABLE login (
	username varchar(20) NOT NULL,
    `password` varchar(10) NOT NULL
);

CREATE TABLE plants (
    plant_id INT AUTO_INCREMENT PRIMARY KEY,
    owner VARCHAR(50) NOT NULL,
    scientific_name VARCHAR(50) NOT NULL,
    common_name VARCHAR(50) NOT NULL,
    image VARCHAR(255) NULL
);

ALTER TABLE login MODIFY password VARCHAR(255) NOT NULL;
DELETE FROM login;
SELECT username, LENGTH(password) FROM login;
SELECT username, LENGTH(password), LEFT(password, 7) FROM login;
select * from login;

select username, password from login;
describe login;
drop table plants;
select * from plants;

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