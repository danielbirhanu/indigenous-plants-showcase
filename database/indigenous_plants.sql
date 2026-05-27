CREATE DATABASE IF NOT EXISTS indigenous_plants_db
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE indigenous_plants_db;

CREATE TABLE IF NOT EXISTS plants (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    scientific_name VARCHAR(150),
    region VARCHAR(100),
    category VARCHAR(100),
    short_description TEXT,
    full_description TEXT,
    uses TEXT,
    cultural_importance TEXT,
    growing_conditions TEXT,
    image VARCHAR(255),
    source_name VARCHAR(150),
    source_url VARCHAR(255),
    featured TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
