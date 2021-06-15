<?php

$file_level = "../";

header('Content-Type: application/xhtml+xml; charset=utf-8');

session_start();

if (!isset($_SESSION['email'])) {
    $_SESSION["error"] = 'Please login.';
    header("Location: " . $file_level . "login.php");
    return;
}

require_once $file_level . "includes/pdo.php";
require_once $file_level . "includes/util.php";

$sql = 'SELECT image, name, alt, subtitle, ingredients, method FROM recipes WHERE recipe_id = "' . $_GET["recipe_id"] . '"';
$sth = $dbh->prepare($sql);

if (!empty($sth->execute())) {

    require_once $file_level . "includes/flash.php";

    if ($sth->rowCount() == 0) {
        echo '<p>No data found</p>';
    } else {

        $row = $sth->fetch(PDO::FETCH_ASSOC);
        // Define variables and set to empty values
        $image = $name = $alt = $subtitle = $recipe_id = $ingredients = $method = "";

        // Data validation
        // Get unaltered data back from database, then sanitize it to prevenet HTML injection
        $image = $row["image"];
        $name = sanitize_input($row["name"]);
        $alt = sanitize_input($row["alt"]);
        $subtitle = sanitize_input($row["subtitle"]);
        $ingredients = explode(PHP_EOL, sanitize_input($row["ingredients"]));
        $method = explode(PHP_EOL, sanitize_input($row["method"]));

        // Add the head
        $title = "$name | Your Recipes";
        require_once $file_level . "includes/head.php";
?>

        <main>

            <!-- Hero section -->
            <section class="hero shadow">
                <?php echo ('<img src="data:image/jpeg;base64,' . base64_encode($image) . '" alt="' . $alt . '" />'); ?>
                <div class="container-center">
                    <h1><?php echo $name; ?></h1>
                    <h2><?php echo $subtitle; ?></h2>
                </div>
            </section>

            <div class="container-sidebar border-radius shadow">
                <?php require_once $file_level . "includes/flash.php"; ?>
                <!-- Recipe section -->
                <article class="recipe">
                    <div class="container-col">
                        <h2><?php echo $name; ?></h2>
                        <h3>Ingredients</h3>
                        <?php
                        for ($i = 0; $i < count($ingredients); $i++) {
                            echo ('<li>' . $ingredients[$i] . '</li>');
                        }
                        ?>
                        <h3>Method</h3>
                        <ol class="method">
                            <?php
                            for ($i = 0; $i < count($method); $i++) {
                                echo ('<li>' . $method[$i] . '</li>');
                            }
                            ?>
                        </ol>
                    </div>
                </article>

        <?php
        // Add the recipe sidebar. Requires util to be imported.
        require_once $file_level . "includes/recipe-sidebar.php";
    }
} ?>

            </div>

        </main>

        <?php
        // Add the footer
        require_once $file_level . "includes/footer.php";
        ?>