<?php

session_start();
session_destroy();
header("Location: " . $file_level . "index.php");
