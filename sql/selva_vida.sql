-- Generic
cd
sudo
ls

-- Lampp
/opt/lampp/lampp start
sudo /opt/lampp/lampp stop
sudo /opt/lampp/lampp status
cd /opt/lampp/htdocs


-- Copy csv files
cd /opt/lampp/htdocs/DanielPotterSelvaVida/'Data Files'
sudo cp invoices.csv /opt/lampp/var/mysql/selva_vida/invoices.csv
sudo cp accounts.csv /opt/lampp/var/mysql/selva_vida/accounts.csv
sudo cp customers.csv /opt/lampp/var/mysql/selva_vida/customers.csv
sudo cp products.csv /opt/lampp/var/mysql/selva_vida/products.csv
sudo cp invoice_items.csv /opt/lampp/var/mysql/selva_vida/invoice_items.csv

-- Database
sudo /opt/lampp/bin/mysql
sudo /opt/lampp/bin/mysql -u daniel -p
sudo /opt/lampp/bin/mysql -u daniel -p databasename
GjPVoNzn6exzvLLb

-- Backup
cd /opt/lampp/htdocs/DanielPotterSelvaVida
sudo /opt/lampp/bin/mysqldump --add-drop-table -h localhost -u daniel -p selva_vida > selva_vida.bak.sql
ls
sudo /opt/lampp/bin/mysqladmin -u daniel -p create selva_vida_new
sudo /opt/lampp/bin/mysql -u daniel -p selva_vida_new < selva_vida.bak.sql

******************************************************************

-- Init database
DROP DATABASE selva_vida;
CREATE DATABASE selva_vida DEFAULT CHARACTER SET utf8;
USE selva_vida;
SELECT database();

-- Drop tables if necessary
DROP TABLE IF EXISTS invoice_items;
DROP TABLE IF EXISTS invoices;
DROP TABLE IF EXISTS products;
DROP TABLE IF EXISTS customers;
DROP TABLE IF EXISTS accounts;

-- Create accounts table
CREATE TABLE accounts (
    account_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR (255) NOT NULL
) ENGINE = InnoDB;

-- Create customers table
CREATE TABLE customers (
    customer_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    account_id INT NOT NULL UNIQUE,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    phone VARCHAR (255) NOT NULL,
    address VARCHAR (255) NOT NULL,
    city VARCHAR (255) NOT NULL,
    state VARCHAR (255) NOT NULL,
    country VARCHAR (255) NOT NULL,
    postcode VARCHAR (255) NOT NULL,
    CONSTRAINT fk_account_id
    FOREIGN KEY (account_id) REFERENCES accounts(account_id)
    ON DELETE CASCADE
    ON UPDATE RESTRICT
) ENGINE = InnoDB;

-- Create invoices table
CREATE TABLE invoices (
    invoice_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    date DATE NOT NULL,
    CONSTRAINT fk_customer_id
    FOREIGN KEY (customer_id) REFERENCES customers(customer_id)
    ON DELETE CASCADE
    ON UPDATE RESTRICT
) ENGINE = InnoDB;

-- Create products table
CREATE TABLE products (
    product_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    scientific_name VARCHAR(255) NOT NULL,
    origin VARCHAR(255) NOT NULL,
    price DOUBLE NOT NULL
) ENGINE = InnoDB;

-- Create invoice_items table
CREATE TABLE invoice_items (
    invoice_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    CONSTRAINT pk_invoice_items PRIMARY KEY (invoice_id, product_id),
    CONSTRAINT fk_invoice_id
    FOREIGN KEY (invoice_id) REFERENCES invoices(invoice_id)
    ON DELETE CASCADE
    ON UPDATE RESTRICT,
    CONSTRAINT fk_product_id
    FOREIGN KEY (product_id) REFERENCES products(product_id)
    ON DELETE CASCADE
    ON UPDATE RESTRICT
) ENGINE = InnoDB;

-- Set the sql mode
SET sql_mode = '';

-- Load data into empty database tables
LOAD DATA INFILE 'accounts.csv' INTO TABLE accounts
FIELDS TERMINATED BY ','
OPTIONALLY ENCLOSED BY '"'
LINES TERMINATED BY '\r\n'
IGNORE 1 LINES;

LOAD DATA INFILE 'customers.csv' INTO TABLE customers
FIELDS TERMINATED BY ','
OPTIONALLY ENCLOSED BY '"'
LINES TERMINATED BY '\r\n'
IGNORE 1 LINES;

LOAD DATA INFILE 'invoices.csv' INTO TABLE invoices
FIELDS TERMINATED BY ','
OPTIONALLY ENCLOSED BY '"'
LINES TERMINATED BY '\r\n'
IGNORE 1 LINES;

LOAD DATA INFILE 'products.csv' INTO TABLE products
FIELDS TERMINATED BY ','
OPTIONALLY ENCLOSED BY '"'
LINES TERMINATED BY '\r\n'
IGNORE 1 LINES;

LOAD DATA INFILE 'invoice_items.csv' INTO TABLE invoice_items
FIELDS TERMINATED BY ','
OPTIONALLY ENCLOSED BY '"'
LINES TERMINATED BY '\r\n'
IGNORE 1 LINES;

-- Test the database
desc accounts;
desc invoices;
desc customers;
desc products;
desc invoice_items;

-- 5 Find a customer with at least 3 invoices
-- 486 gwinderdh@google.es Garrek Winder
SELECT customers.customer_id, customers.email,
    customers.first_name, customers.last_name,
    COUNT(customers.email) AS 'Number of Invoices'
FROM customers
    INNER JOIN invoices ON customers.customer_id = invoices.customer_id
GROUP BY customers.email
ORDER BY COUNT(customers.email) DESC
LIMIT 0, 25;

-- 5.1) Display a customer’s list of invoices. For each record, display the customers first name, last name, invoice id, and date. Ensure the chosen customer has at least three invoices.
SELECT customers.first_name, customers.last_name, invoices.invoice_id, invoices.date
FROM invoices INNER JOIN customers ON customers.customer_id = invoices.customer_id
WHERE customers.customer_id LIKE '486';

-- 5.2) For the same customer (used in query 1), calculate the number of days between their last and second last invoice. 
SELECT DATEDIFF(
(
SELECT date
FROM invoices
WHERE customer_id LIKE '486'
ORDER BY date DESC
LIMIT 0, 1
),
(
SELECT date
FROM invoices
WHERE customer_id LIKE '486'
ORDER BY date DESC
LIMIT 1, 1
)
) AS 'Days between orders';

-- 5.3) For the same customer (used in query 1), display all the items corresponding to one of their invoices. Include the customers first and last name, the scientific name, origin, price, quantity, and date for each invoice item, and. Ensure the chosen invoice has at least three items. Order the results by scientific name.
SELECT customers.first_name, customers.last_name, products.scientific_name,
    products.origin, products.price, invoice_items.quantity, invoices.date
FROM invoices
    INNER JOIN customers ON customers.customer_id = invoices.customer_id
    INNER JOIN invoice_items ON invoices.invoice_id = invoice_items.invoice_id
    INNER JOIN products ON invoice_items.product_id = products.product_id
WHERE invoices.invoice_id LIKE '486'
ORDER BY products.scientific_name;

-- 5.4) For the same invoice (used in query 3), calculate the cost of the items (unit price * quantity) on the invoice. Ensure that the quantity of at least one invoice item is more than one. Include the scientific name, origin, price, and quantity.
SELECT products.product_id, products.scientific_name, products.origin, products.price, invoice_items.quantity, 
    SUM(price * quantity) AS 'Total'
FROM invoices
    INNER JOIN invoice_items ON invoices.invoice_id = invoice_items.invoice_id
    INNER JOIN products ON invoice_items.product_id = products.product_id
WHERE invoices.invoice_id LIKE '486'
GROUP BY product_id;


-- 5.5) For the same invoice (used in query 3), calculate the total cost of the invoice. Ensure that the quantity of at least one invoice item is more than one.
SELECT invoices.invoice_id, SUM(price * quantity) AS 'Invoice Total'
FROM invoices
    INNER JOIN invoice_items ON invoices.invoice_id = invoice_items.invoice_id
    INNER JOIN products ON invoice_items.product_id = products.product_id
WHERE invoices.invoice_id LIKE '486';

-- 5.6) Display the scientific name, origin, unit price, quantity ordered, unit price * quantity, and the invoice date for all items ordered in the year 2018. Order the results by date.
SELECT products.product_id, products.scientific_name, products.origin, products.price, invoice_items.quantity,
    SUM(price * quantity) AS 'Total', invoices.date
FROM invoices
    INNER JOIN invoice_items ON invoices.invoice_id = invoice_items.invoice_id
    INNER JOIN products ON invoice_items.product_id = products.product_id
WHERE invoices.date LIKE '%2018%'
GROUP BY product_id
ORDER BY date;

-- 5.7) Find the sum of all invoices for the year 2018.
SELECT SUM(price * quantity) AS 'Total for 2018'
FROM invoices
    INNER JOIN invoice_items ON invoices.invoice_id = invoice_items.invoice_id
    INNER JOIN products ON invoice_items.product_id = products.product_id
WHERE invoices.date LIKE '%2018%';

-- 5.8) Display the top ten most frequently sold products. Display the scientific name, origin, price, and the number of times they were sold.
SELECT products.product_id, products.scientific_name, products.origin, products.price,
COUNT(products.scientific_name) AS 'Number of times sold'
FROM invoices
    INNER JOIN invoice_items ON invoices.invoice_id = invoice_items.invoice_id
    INNER JOIN products ON invoice_items.product_id = products.product_id
GROUP BY products.scientific_name 
ORDER BY COUNT(products.scientific_name) DESC
LIMIT 0, 10;

-- 5.9) Display the top ten products with the greatest quantity ordered in one order. Display the scientific name, origin, price, quantity sold, and date.
SELECT products.product_id, products.scientific_name, products.origin, products.price,
invoice_items.quantity, invoices.date
FROM invoices
    INNER JOIN invoice_items ON invoices.invoice_id = invoice_items.invoice_id
    INNER JOIN products ON invoice_items.product_id = products.product_id
ORDER BY invoice_items.quantity DESC
LIMIT 0, 10;

-- 5.10) Display the top ten products with the greatest total quantity sold. Display the scientific name, origin, price, and total quantity sold.
SELECT products.product_id, products.scientific_name, products.origin, products.price,
SUM(invoice_items.quantity)
FROM invoices
    INNER JOIN invoice_items ON invoices.invoice_id = invoice_items.invoice_id
    INNER JOIN products ON invoice_items.product_id = products.product_id
GROUP BY products.scientific_name
ORDER BY SUM(invoice_items.quantity) DESC
LIMIT 0, 10;

-- 6 CRUD
INSERT INTO accounts (username, password)
VALUES ('Daniel', 'Password');

SELECT *
FROM accounts
WHERE username LIKE 'Daniel';

INSERT INTO customers (account_id, first_name, last_name, email, phone, address, city, state, country, postcode)
VALUES ((SELECT account_id FROM accounts WHERE username = 'Daniel'),'Daniel', 'Potter', 'deejpotter@gmail.com', 
'032164978', '123 Sasnsignity Road', 'Somethintown', 'Somewhere', 'Someplace', '3200');

SELECT *
FROM customers
WHERE first_name LIKE 'Daniel';

UPDATE customers
SET address = '56 Newtown Street', city = 'Newsville', state = 'Newthing', country = 'Newstralia', postcode = '0000'
WHERE first_name LIKE 'Daniel';

SELECT *
FROM customers
WHERE first_name LIKE 'Daniel';

DELETE FROM customers
WHERE first_name LIKE 'Daniel';

-- 7 Optimization
CHECK TABLE customers;

CHECK TABLE invoices;

CHECK TABLE accounts;

CHECK TABLE invoice_items;

CHECK TABLE products;

OPTIMIZE TABLE customers;

OPTIMIZE TABLE invoices;

OPTIMIZE TABLE accounts;

OPTIMIZE TABLE invoice_items;

OPTIMIZE TABLE products;

