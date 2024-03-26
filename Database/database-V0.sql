CREATE DATABASE IF NOT EXISTS paradise_db;
USE paradise_db;

CREATE TABLE IF NOT EXISTS zone (
    Id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    description TEXT
);

CREATE TABLE IF NOT EXISTS building (
    Id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL ,
    description TEXT,
    zone_id INT,
    FOREIGN KEY (zone_id) REFERENCES zone(Id)
);

CREATE TABLE IF NOT EXISTS enclos (
    Id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL ,
    description TEXT,
    zone_id INT,
    FOREIGN KEY (zone_id) REFERENCES zone(Id)
);

CREATE TABLE IF NOT EXISTS animal (
    Id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL ,
    specie VARCHAR(255),
    birthday DATE,
    description TEXT,
    enclos_id INT,
    building_id INT,
    FOREIGN KEY (enclos_id) REFERENCES enclos(Id),
    FOREIGN KEY (building_id) REFERENCES building(Id)
);

CREATE TABLE IF NOT EXISTS image (
    Id INT PRIMARY KEY AUTO_INCREMENT,
    animal_id INT,
    url VARCHAR(255),
    alt VARCHAR(255),
    FOREIGN KEY (animal_id) REFERENCES animal(Id)
);

CREATE TABLE IF NOT EXISTS parent_animal (
    animal_id INT,
    parent_id INT,
    PRIMARY KEY (animal_id, parent_id),
    FOREIGN KEY (animal_id) REFERENCES animal(Id),
    FOREIGN KEY (parent_id) REFERENCES animal(Id)
);