<?php

$file_level = "";

header('Content-Type: application/xhtml+xml; charset=utf-8');

session_start();

if (!isset($_SESSION['email'])) {
    $_SESSION["error"] = 'Please login.';
    header("Location: " . $file_level . "login.php");
    return;
}

require_once $file_level . "includes/pdo.php";
require_once $file_level . "includes/util.php";

?>

<?php
// Add the head
$file_level = "";
$title = "Home | Recipe thing";
require_once $file_level . "includes/head.php";
?>


<main>

    <!-- Hero section -->
    <section class="hero shadow">
        <img src="<?php echo $file_level; ?>images\main-hero.jpg" alt="A guinea pig." />
        <div class="container-center">
            <h1>All Recipes</h1>
            <h2>This is a list of all the recipes.</h2>
            <a href="<?php echo $file_level; ?>recipes/add.php" class="link-button shadow">Add a new recipe here</a>
        </div>
    </section>

    <?php
    // Add the recipe grid. Requires util to be imported.
    require_once $file_level . "includes/recipe-grid.php";
    ?>

</main>

<?php
// Add the footer
require_once $file_level . "includes/footer.php";
?>