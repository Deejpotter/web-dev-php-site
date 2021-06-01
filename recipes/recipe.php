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

?>

<?php
// Add the head
$title = "recipe.name | Recipe thing";
require_once $file_level . "includes/head.php";
?>

<main>

    <?php if (isset($_SESSION['email'])) {
        $sql = 'SELECT pet_id, image, type, breed, dob FROM pets WHERE user_id = "' . $_SESSION['user_id'] . '"';
        $sth = $dbh->prepare($sql);

        if (!empty($sth->execute())) {

            require_once $file_level . "includes/flash.php";

            if ($sth->rowCount() == 0) {
                echo '<p>No data found</p>';
            } else {

                // Define variables and set to empty values
                $image = $type = $breed = $dob = $pet_id = "";

                for ($i = 0; $i < 2; $i++) {
                    if ($row = $sth->fetch(PDO::FETCH_ASSOC)) {

                        // Data validation
                        // Get unaltered data back from database, then sanitize it to prevenet HTML injection
                        // $image = sanitize_input($row["image"]);
                        $type = sanitize_input($row["type"]);
                        $breed = sanitize_input($row["breed"]);
                        $dob = sanitize_input($row["dob"]);

                        echo ('<div class="card shadow">');
                        echo ('<img src="data:image/jpeg;base64,' . base64_encode($row["image"]) . '" alt="An empty table with a knife." />');
                        echo ('<a href="' . $file_level . 'recipes/edit.php?pet_id=' . $row['pet_id'] . '">Edit</a>');
                        echo ('<a href="' . $file_level . 'recipes/delete.php?pet_id=' . $row['pet_id'] . '">Delete</a>');
                        echo ('</div>');
                    }
                }
            }
        }
    } ?>

    <!-- Hero section -->
    <section class="hero shadow">
        <!-- recipe.image -->
        <img src="../images/blank-knife.jpg" alt="An empty table with a knife." />
        <div class="container-center">
            <!-- recipe.name -->
            <h1>2 Minute Noodles</h1>
            <!-- recipe.subtitle -->
            <h2>Probably the easiest thing to cook.</h2>
        </div>
    </section>

    <div class="container-sidebar border-radius shadow">

        <!-- Recipe section -->
        <article class="recipe">
            <div class="container-col">
                <!-- recipe.name -->
                <h2>2 Minute Noodles</h2>
                <h3>Ingredients</h3>
                <ul class="ingredients">
                    <!-- recipe.ingredients -->
                    <!-- explode recipe.ingredients -->
                    <!-- FOR ingredients IN recipe.ingredients -->
                    <!-- echo <li> -->
                    <li>1 pack of 2 minute noodles</li>
                    <li>1 cup of water</li>
                </ul>
                <h3>Method</h3>
                <ol class="method">
                    <!-- recipe.method -->
                    <li>Empty the packet of noodles and the seasoning into a saucepan.</li>
                    <li>Pour the water into the saucepan as well.</li>
                    <li>Bring the water to the boil and cook until there is a small ammount of water left.</li>
                    <li>Lower the temperature then cook the noodles for another minute before moving the noodles to
                        a serving bowl.</li>
                    <li>Eat the noodles.</li>
                </ol>
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