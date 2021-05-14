<?php

header('Content-Type: application/xhtml+xml; charset=utf-8');

session_start();

require_once "pdo.php";

// Return true if email is valid
function checkemail($email)
{
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return true;
    } else {
        return false;
    }
}


// Check to see if we have some POST data, if we do process it
if (isset($_POST['email']) && isset($_POST['password']) && isset($_POST['retype'])) {

    unset($_SESSION['email']);

    $checkemail = checkemail($_POST['email']);

    if ($checkemail === false) {
        $_SESSION["error"] = "Invalid email.";
        header("Location: accounts.php");
        return;
    } else if (strlen($_POST['password']) < 5) {
        $_SESSION["error"] = "Invalid password. Password must be at least 5 characters";
        header("Location: accounts.php");
        return;
    } else {

        if (isset($_POST['password'])) {
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        }
    }
}