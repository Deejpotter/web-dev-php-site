<?php

// Data source name
$dsn = "mysql:host=localhost; dbname=recipe_site; charset=utf8";
$username = "root";
$password = "";

try {
    // Database handle
    $dbh = new PDO($dsn, $username, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    // echo "Connected successfully";
} catch (PDOException $e) {
    $_SESSION['error'] = 'Connection to database failed.';
    error_log("Connection to database failed: " . $e->getMessage());
}