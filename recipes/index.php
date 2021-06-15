<?php

$file_level = "../";

header('Content-Type: application/xhtml+xml; charset=utf-8');

session_start();

if (!isset($_SESSION['email'])) {
    $_SESSION["error"] = 'Please login.';
    header("Location: " . $file_level . "login.php");
    return;
}

?>

<?php
// Add the head
$title = "Lost | Your Recipes";
require_once $file_level . "includes/head.php";
?>

<main>
    <h1>Looks like you're lost</h1>
    <a href="<?php echo $file_level; ?>index.php" class="link-button shadow">Try this instead</a>
</main>

<?php
// Add the footer
require_once $file_level . "includes/footer.php";
?>