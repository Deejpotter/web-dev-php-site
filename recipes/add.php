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

if (isset($_POST['add'])) {

    if (($_POST["image"] !== "") && ($_POST["name"] !== "") && ($_POST["alt"] !== "") && ($_POST["subtitle"] !== "") && ($_POST["ingredients"] !== "") && ($_POST["method"] !== "")) {

        try {

            $image = file_get_contents($_FILES['image']['tmp_name']);
            $allowableTypes = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF);
            $detectedType = exif_imagetype($_FILES['image']['tmp_name']);

            if (!in_array($detectedType, $allowableTypes)) {
                $_SESSION['error'] = 'Not a valid image type';
                header("Location: " . $file_level . "index.php");
                return;
            }

            $sql = 'SELECT account_id FROM accounts WHERE email = "' . $_SESSION['email'] . '"';
            $sth = $dbh->prepare($sql);
            $sth->execute();

            $row = $sth->fetch(PDO::FETCH_ASSOC);
            $account_id = $row["account_id"];

            $sql = 'INSERT INTO recipes (account_id, image, name, alt, subtitle, ingredients, method) VALUES (:account_id, :image, :type, :alt, :subtitle, :ingredients, :method)';
            $sth = $dbh->prepare($sql);
            $sth->execute(
                array(
                    ':account_id' => $account_id,
                    ':image' => $image,
                    ':type' => $_POST["name"],
                    ':alt' => $_POST["alt"],
                    ':subtitle' => $_POST["subtitle"],
                    ':ingredients' => $_POST["ingredients"],
                    ':method' => $_POST["method"],
                )
            );

            $_SESSION['success'] = 'Recipe added!';
            header("Location: " . $file_level . "recipes.php");
            return;
        } catch (PDOException $e) {

            error_log("Recipe Creation Failed: " . $e->getMessage());
            $_SESSION['error'] = 'The recipe could not be added';
            header("Location: " . $file_level . "recipes.php");
            return;
        }
    }
} elseif (isset($_POST['cancel'])) {
    header('Location: ' . $file_level . 'recipes.php');
    return;
}

?>

<?php
// Add the head
$title = "Add | Your Recipes";
require_once $file_level . "includes/head.php";
?>

<main>
    <?php require_once $file_level . "includes/flash.php"; ?>

    <!-- Hero section -->
    <section class="hero shadow">
        <img src="<?php echo $file_level; ?>images/main-hero.jpg" alt="A bench with a tablecloth." />
        <div class="container-center">
            <h1>Add a recipe</h1>
        </div>
    </section>

    <!-- About section -->
    <div class="container-sidebar light shadow border-radius">
        <article class="about-article">
            <h2>Fill in the form below to create a new recipe.</h2>
            <div class="container-col">
                <form enctype="multipart/form-data" method="post">
                    <label for="image">Image</label>
                    <input type="file" name="image" id="image" /><br />
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name" /><br />
                    <label for="alt">Alt Text</label>
                    <input type="text" name="alt" id="alt" /><br />
                    <label for="subtitle">Subtitle</label>
                    <input type="text" name="subtitle" id="subtitle" /><br />
                    <label for="ingredients">Ingredients</label>
                    <textarea name="ingredients" id="ingredients"></textarea><br />
                    <label for="method">Method</label>
                    <textarea name="method" id="method"></textarea><br />
                    <input type="submit" onclick="return validateRecipe();" name="add" value="Submit" />
                    <input type="submit" name="cancel" value="Cancel" />
                </form>

                <p id="js_validation_message"></p>

                <script src="<?php echo $file_level; ?>js/validate.js"></script>
            </div>
        </article>
        <aside class="sidebar">
            <p>Fill in the every part of the form before pressing the add button.</p>
            <p>When entering the ingredients and method, please enter each ingredient or step on a new line so it
                displays properly.</p>
        </aside>
    </div>

</main>

<?php
// Add the footer
require_once $file_level . "includes/footer.php";
?>