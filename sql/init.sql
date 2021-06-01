-- Init database
DROP DATABASE IF EXISTS school_test;
CREATE DATABASE school_test;
-- Drop tables if necessary
DROP TABLE IF EXISTS pets;
DROP TABLE IF EXISTS accounts;
-- Create accounts table
CREATE TABLE accounts (
    user_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR
(255) UNIQUE NOT NULL,
    password VARCHAR
(255) NOT NULL
) ENGINE = InnoDB CHARSET = utf8;
-- Create invoices table
CREATE TABLE pets
(
    pet_id INT NOT NULL
    AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    image LONGBLOB NOT NULL,
    type VARCHAR
    (255) NOT NULL,
    breed VARCHAR
    (255) NOT NULL,
    dob VARCHAR
    (255) NOT NULL,
    date DATE NOT NULL,
    CONSTRAINT fk_pets_accounts FOREIGN KEY
    (user_id) REFERENCES accounts
    (user_id) ON
    DELETE CASCADE ON
    UPDATE CASCADE
) ENGINE = InnoDB;