<?php

// Data source name
$dsn = "mysql:host=localhost;dbname=crud4chisholm;charset=utf8";
$username = "root";
$password = "";

try {
    // Database handle
    $dbh = new PDO($dsn, $username, $password);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}