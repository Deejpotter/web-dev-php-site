<?php

// Flash message
if (isset($_SESSION['error'])) {
    echo ('<p style="color: red">' . htmlentities($_SESSION['error']) . "</p\n");
    unset($_SESSION['error']);
}

// Flash message
if (isset($_SESSION['success'])) {
    echo ('<p style="color: green">' . htmlentities($_SESSION['success']) . "</p\n");
    unset($_SESSION['success']);
}