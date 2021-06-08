<?php

$file_level = "";

header('Content-Type: application/xhtml+xml; charset=utf-8');

session_start();

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
            <h1>About Page</h1>
            <h2>Find out about cooking and stuff.</h2>
        </div>
    </section>

    <div class="container-sidebar border-radius shadow">

        <!-- Recipe section -->
        <article class="about-article">
            <div class="container-col">
                <h2>All about this website</h2>
                <p>We have lots of simple recipes. There are so many recipes on this site and they are all really
                    simple. There are actually only three recipes but I made it look like there are more by showing
                    the same one multiple times. They are all simple though.
                </p>
                <br />
                <h2>All about cooking</h2>
                <p>Cooking can be really fun but it can also be tricky. These recipes are all very simple.</p>
                <br />
                <p>Cooking is all about timing and controlling the temperature of the food. Thicker food usually has
                    to be cooked longer on a lower temperature. Tough joints of meat can take hours to cook but
                    spinach is done in seconds.
                </p>
            </div>
        </article>

        <?php
        // Add the recipe sidebar. Requires util to be imported.
        require_once $file_level . "includes/recipe-sidebar.php";
        ?>

    </div>

</main>

<?php
// Add the footer
require_once $file_level . "includes/footer.php";
?>