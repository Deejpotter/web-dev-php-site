-- Init database
DROP DATABASE IF EXISTS recipe_site;
CREATE DATABASE recipe_site;
USE recipe_site;
-- Drop tables if necessary
DROP TABLE IF EXISTS recipes;
DROP TABLE IF EXISTS accounts;
-- Create accounts table
CREATE TABLE accounts (
    account_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR
(255) UNIQUE NOT NULL,
    password VARCHAR
(255) NOT NULL
) ENGINE = InnoDB CHARSET = utf8;
-- Create recipes table
CREATE TABLE recipes
(
    recipe_id INT NOT NULL
    AUTO_INCREMENT PRIMARY KEY,
    account_id INT NOT NULL,
    image LONGBLOB NOT NULL,
    name VARCHAR
    (255) NOT NULL,
    alt VARCHAR
    (255) NOT NULL,
    subtitle VARCHAR
    (255) NOT NULL,
    ingredients VARCHAR
    (1000) NOT NULL,
    method VARCHAR
    (1000) NOT NULL,
    CONSTRAINT fk_recipes_accounts FOREIGN KEY
    (account_id) REFERENCES accounts
    (account_id) 
    ON
    DELETE CASCADE 
    ON
    UPDATE CASCADE ) ENGINE = InnoDB;