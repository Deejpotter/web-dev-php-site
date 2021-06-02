<?php

$file_level = "";

header('Content-Type: application/xhtml+xml; charset=utf-8');

session_start();

require_once $file_level . "includes/pdo.php";
require_once $file_level . "includes/util.php";

?>

<?php
// Add the head
$title = "Home | Recipe thing";
require_once $file_level . "includes/head.php";
?>

<main>

    <!-- Hero section -->
    <section class="hero shadow">
        <img src="<?php echo $file_level; ?>images/blank-knife.jpg" alt="An empty table with a knife." />
        <div class="container-center">
            <h1>Simple Recipes</h1>
            <h2>Every recipe here is simple.</h2>
            <a href="<?php echo $file_level; ?>accounts.php" class="link-button shadow">Join Up Here</a>
        </div>
    </section>

    <!-- About section -->
    <section class="about-section">
        <div class="container-col shadow border-radius">
            <div class="about-text">
                <h2>All about this website</h2>
                <p>We have lots of simple recipes. There are so many recipes on this site and they are all really
                    simple.
                    There are actually only three recipes but I made it look like there are more by showing the same
                    one multiple times.
                    They are all simple though.
                </p>
            </div>
            <div>
                <a href="<?php echo $file_level; ?>about.php" class="link-button shadow">Link to about page</a>
            </div>
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