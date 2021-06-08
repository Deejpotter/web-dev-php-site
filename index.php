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
        <img src="<?php echo $file_level; ?>images/main-hero.jpg" alt="A guinea pig." />
        <div class="container-center">
            <h1>Simple Recipes</h1>
            <h2>Create and view your recipes in one place.</h2>
            <?php
            if (!isset($_SESSION['email'])) {
                echo ('<a href= "' . $file_level . 'accounts.php" class="link-button shadow">Join Up Here</a>');
            } else {
                echo ('<a href= "' . $file_level . 'recipes/add.php" class="link-button shadow">Add a new recipe</a>');
            }
            ?>

        </div>
    </section>

    <!-- About section -->
    <section class="about-section">
        <div class="container-col shadow border-radius">
            <div class="about-text">
                <h2>All about this website</h2>
                <p>Create your own recipes and store them all in one place. You can create a new recipe, edit or delete
                    a current recipe, and keep all of your recipes stored here for the future. Maybe you can see
                    everyone else's recipes as well if I get that far. For now, go and <a href="<?php echo $file_level; ?>recipes/add.php">create some recipes</a>.
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