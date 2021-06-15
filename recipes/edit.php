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

if (isset($_SESSION['email'])) {

    try {

        // Get unaltered data back from database, then sanitise it to prevent HTML injection
        $sql = 'SELECT image, name, alt, subtitle, ingredients, method FROM recipes WHERE recipe_id = "' . $_GET['recipe_id'] . '"';
        $sth = $dbh->prepare($sql);
        $sth->execute();

        $row = $sth->fetch(PDO::FETCH_ASSOC);
        $old_image = base64_encode($row['image']);
        $name = sanitize_input($row["name"]);
        $alt = sanitize_input($row["alt"]);
        $subtitle = sanitize_input($row["subtitle"]);
        $ingredients = sanitize_input($row["ingredients"]);
        $method = sanitize_input($row["method"]);
    } catch (PDOException $e) {

        error_log("Invalid recipe profile: " . $e->getMessage());
        $_SESSION['error'] = "Invalid recipe profile";
        header("Location: edit.php");
        return;
    }
}

if (isset($_POST['edit'])) {

    if (($_POST["image"] !== "") && ($_POST["name"] !== "") && ($_POST["alt"] !== "") && ($_POST["subtitle"] !== "") && ($_POST["ingredients"] !== "") && ($_POST["method"] !== "")) {

        // Update with a new image
        if (isset($_FILES['image']['tmp_name']) && !empty($_FILES['image']['tmp_name'])) {

            $image = file_get_contents($_FILES['image']['tmp_name']);
            $allowableTypes = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF);
            $detectedType = exif_imagetype($_FILES['image']['tmp_name']);

            if (!in_array($detectedType, $allowableTypes)) {
                $_SESSION['error'] = "Not a valid image type";
                header("Location: " . $file_level . "index.php");
                return;
            }

            try {

                $sql = 'UPDATE recipes SET image = :image, name = :name, alt = :alt, subtitle = :subtitle, ingredients = :ingredients, method = :method WHERE recipe_id = "' . $_GET['recipe_id'] . '"';
                $sth = $dbh->prepare($sql);
                $sth->execute(
                    array(
                        ':image' => $image,
                        ':name' => $_POST["name"],
                        ':alt' => $_POST["alt"],
                        ':subtitle' => $_POST["subtitle"],
                        ':ingredients' => $_POST["ingredients"],
                        ':method' => $_POST["method"]
                    )
                );

                $_SESSION['success'] = 'Recipe updated!';
                header('Location: ' . $file_level . 'index.php');
                return;
            } catch (PDOException $e) {

                error_log('Recipe Update Failed: ' . $e->getMessage());
                $_SESSION['error'] = 'The recipe could not be updated';
                header('Location: ' . $file_level . 'index.php');
                return;
            }
        } else {

            // Update keeping existing image
            try {

                $sql = 'UPDATE recipes SET name = :name, alt = :alt, subtitle = :subtitle, ingredients = :ingredients, method = :method WHERE recipe_id = "' . $_GET['recipe_id'] . '"';
                $sth = $dbh->prepare($sql);
                $sth->execute(
                    array(
                        ':name' => $_POST["name"],
                        ':alt' => $_POST["alt"],
                        ':subtitle' => $_POST["subtitle"],
                        ':ingredients' => $_POST["ingredients"],
                        ':method' => $_POST["method"]
                    )
                );

                $_SESSION['success'] = 'Recipe updated!';
                header('Location: ' . $file_level . 'index.php');
                return;
            } catch (PDOException $e) {

                error_log('Recipe Update Failed: ' . $e->getMessage());
                $_SESSION['error'] = 'The recipe could not be updated';
                header('Location: ' . $file_level . 'index.php');
                return;
            }
        }
    }
} elseif (isset($_POST['cancel'])) {

    header('Location: ' . $file_level . 'index.php');
    return;
}

?>


<?php
// Add the head
$title = "Edit | Your Recipes";
require_once $file_level . "includes/head.php";
?>


<main>

    <!-- Hero section -->
    <section class="hero shadow">
        <img src="<?php echo $file_level; ?>images/main-hero.jpg" alt="A bench with a tablecloth." />
        <div class="container-center">
            <h1>Edit a recipe</h1>
            <h2>Fill in the form below to edit your recipe.</h2>
        </div>
    </section>

    <!-- About section -->
    <div class="container-sidebar light shadow border-radius">
        <article class="about-article">
            <div class="container-col">
                <img src="data:image/jpeg;base64, <?php echo base64_encode($row['image']) ?>" alt="<?php echo $name . ' ' . $alt; ?>" width="100" /><br />

                <form method="post" enctype="multipart/form-data">
                    <label for="image">Image</label>
                    <input type="file" name="image" id="image" /><br />
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name" value="<?php echo $name; ?>" /><br />
                    <label for=" alt">Alt Text</label>
                    <input type="text" name="alt" id="alt" value="<?php echo $alt; ?>" /><br />
                    <label for="subtitle">Subtitle</label>
                    <input type="text" name="subtitle" id="subtitle" value="<?php echo $subtitle; ?>" /><br />
                    <label for="ingredients">Ingredients</label>
                    <textarea name="ingredients" id="ingredients" rows="5" cols="50"><?php echo $ingredients; ?></textarea><br />
                    <label for="method">Method</label>
                    <textarea name="method" id="method" rows="5" cols="50"><?php echo $method; ?></textarea><br />
                    <input type="submit" onclick="return validateRecipe();" name="edit" value="Submit" />
                    <input type="submit" name="cancel" value="Cancel" />
                </form>

                <p id="js_validation_message"></p>

                <script src="<?php echo $file_level; ?>js/validate.js"></script>
            </div>
        </article>
        <aside class="sidebar">
            <p>Fill in the every part of the form before pressing the edit button.</p>
            <p>When entering the ingredients and method, please enter each ingredient or step on a new line so it
                displays properly.</p>
        </aside>
    </div>

</main>

<?php
// Add the footer
require_once $file_level . "includes/footer.php";
?>