<?php

header('Content-Type: application/xhtml+xml; charset=utf-8');

session_start();

require_once "pdo.php";
require_once "pdo.php";

if (!isset($_SESSION['email'])) {
    $_SESSION["error"] = 'Please login.';
    header("Location: login.php");
    return;
}

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="styles.css">
    <title>Recipe thing</title>
</head>

<body>

</body>

</html>