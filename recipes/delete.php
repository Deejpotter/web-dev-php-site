<?php

$file_level = "../";

header('Content-Type: application/xhtml+xml; charset=utf-8');

session_start();

if (isset($_SESSION['email'])) {

    require_once $file_level . "includes/pdo.php";
    require_once $file_level . "includes/util.php";

    if (isset($_POST['delete']) && isset($_POST['recipe_id'])) {

        try {

            $sql = 'DELETE FROM recipes WHERE recipe_id = :recipe_id';
            $sth = $dbh->prepare($sql);
            $sth->execute(array(':recipe_id' => $_POST['recipe_id']));

            $_SESSION['success'] = 'Recipe deleted';
            header('Location: ' . $file_level . 'index.php');
            return;
        } catch (PDOException $e) {

            error_log("Invalid recipe profile: ");
            $_SESSION['error'] = "Delete recipe failure"  . $e->getMessage();
            header("Location: " . $file_level . "recipes/edit.php");
            return;
        }
    } elseif (isset($_POST['cancel'])) {
        header('Location: ' . $file_level . 'index.php');
        return;
    }

    // Make sure that recipe_id is present
    if (!isset($_GET['recipe_id'])) {

        $_SESSION['error'] = 'Recipe Missing';
        header('Location: ' . $file_level . 'index.php');
        return;
    }

    try {

        if (isset($_GET['recipe_id'])) {

            // Get unaltered data back from database, then sanitise it to prevent HTML injection
            $sth = $dbh->prepare('SELECT recipe_id, image, name, alt, subtitle, ingredients, method FROM recipes WHERE recipe_id = :recipe_id');
            $sth->execute(array(':recipe_id' => $_GET['recipe_id']));
            $row = $sth->fetch(PDO::FETCH_ASSOC);

            if ($row === false) {
                $_SESSION['error'] = 'Bad value for recipe_id';
                header('Location: ' . $file_level . 'index.php');
                return;
            }

            $recipe_id = sanitize_input($row['recipe_id']);
            $name = sanitize_input($row['name']);
            $alt = sanitize_input($row['alt']);
            $subtitle = sanitize_input($row['subtitle']);
        }
    } catch (PDOException $e) {

        error_log("Invalid recipe profile: ");
        $_SESSION['error'] = "Delete recipe failure"  . $e->getMessage();
        header("Location: " . $file_level . "recipes/edit.php");
        return;
    }
}

?>

<?php
// Add the head
$title = "Delete | Your Recipes";
require_once $file_level . "includes/head.php";
?>

<main>

    <!-- Hero section -->
    <section class="hero shadow">
        <img src="<?php echo $file_level; ?>images/main-hero.jpg" alt="A guinea pig." />
        <div class="container-center">
            <h1>Delete a recipe</h1>
            <h2>Press the delete button below to delete your recipe.</h2>
        </div>
    </section>

    <!-- About section -->
    <div class="container-sidebar light shadow border-radius">
        <article class="about-article">
            <div class="container-col">
                <img src="data:image/jpeg;base64, <?php echo base64_encode($row['image']) ?>" alt="<?php echo $name . ' ' . $alt; ?>" width="100" /><br />
                <p>Type: <?php echo $name ?></p>
                <p>alt: <?php echo $alt ?></p>
                <p>Date of Birth: <?php echo $subtitle ?></p>

                <form method="post">
                    <input type="hidden" name="recipe_id" value="<?php echo $recipe_id; ?>" />
                    <input type="submit" name="delete" value="Delete" />
                    <input type="submit" name="cancel" value="Cancel" />
                </form>

                <p id="js_validation_message"></p>

                <script src="js/validate.js"></script>
            </div>
        </article>
        <aside class="sidebar">
            <p>Make sure you are looking at the right recipe before pressing the delete button.</p>
            <p>There's no way to get this back if you make a mistake.</p>
        </aside>
    </div>

</main>

<?php
// Add the footer
require_once $file_level . "includes/footer.php";
?>