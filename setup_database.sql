-- Use the single database
USE pairs_game;

CREATE TABLE IF NOT EXISTS registered_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) UNIQUE NOT NULL,
    skin VARCHAR(255) NOT NULL,
    eyes VARCHAR(255) NOT NULL,
    mouth VARCHAR(255) NOT NULL
);

-- Create the All table for overall scores
CREATE TABLE IF NOT EXISTS `All` (
    username VARCHAR(255) PRIMARY KEY,
    score INT
);

-- Create tables for levels 1 to 10
CREATE TABLE IF NOT EXISTS `1` (
    username VARCHAR(255) PRIMARY KEY,
    score INT
);

CREATE TABLE IF NOT EXISTS `2` (
    username VARCHAR(255) PRIMARY KEY,
    score INT
);

CREATE TABLE IF NOT EXISTS `3` (
    username VARCHAR(255) PRIMARY KEY,
    score INT
);

CREATE TABLE IF NOT EXISTS `4` (
    username VARCHAR(255) PRIMARY KEY,
    score INT
);

CREATE TABLE IF NOT EXISTS `5` (
    username VARCHAR(255) PRIMARY KEY,
    score INT
);

CREATE TABLE IF NOT EXISTS `6` (
    username VARCHAR(255) PRIMARY KEY,
    score INT
);

CREATE TABLE IF NOT EXISTS `7` (
    username VARCHAR(255) PRIMARY KEY,
    score INT
);

CREATE TABLE IF NOT EXISTS `8` (
    username VARCHAR(255) PRIMARY KEY,
    score INT
);

CREATE TABLE IF NOT EXISTS `9` (
    username VARCHAR(255) PRIMARY KEY,
    score INT
);

CREATE TABLE IF NOT EXISTS `10` (
    username VARCHAR(255) PRIMARY KEY,
    score INT
);