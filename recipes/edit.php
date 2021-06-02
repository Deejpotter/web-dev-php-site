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
        $sql = 'SELECT image, name, alt, subtitle FROM recipes WHERE recipe_id = "' . $_GET['recipe_id'] . '"';
        $sth = $dbh->prepare($sql);
        $sth->execute();

        $row = $sth->fetch(PDO::FETCH_ASSOC);
        $old_image = base64_encode($row['image']);
        $name = sanitize_input($row['type']);
        $alt = sanitize_input($row['alt']);
        $subtitle = sanitize_input($row['subtitle']);
    } catch (PDOException $e) {

        error_log("Invalid recipe profile: " . $e->getMessage());
        $_SESSION['error'] = "Invalid recipe profile";
        header("Location: edit.php");
        return;
    }
}

if (isset($_POST['edit'])) {

    if (($_POST['type'] !== "") && ($_POST['alt'] !== "") && ($_POST['subtitle'] !== "")) {

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

                $sql = 'UPDATE recipes SET image = :image, name = :type, alt = :alt, subtitle = :subtitle WHERE recipe_id = "' . $_GET['recipe_id'] . '"';
                $sth = $dbh->prepare($sql);
                $sth->execute(
                    array(
                        ':image' => $image,
                        ':type' => $_POST["name"],
                        ':alt' => $_POST["alt"],
                        ':subtitle' => $_POST["subtitle"]
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

                $sql = 'UPDATE recipes SET type = :type, alt = :alt, subtitle = :subtitle WHERE recipe_id = "' . $_GET['recipe_id'] . '"';
                $sth = $dbh->prepare($sql);
                $sth->execute(
                    array(
                        ':type' => $_POST["name"],
                        ':alt' => $_POST["alt"],
                        ':subtitle' => $_POST["subtitle"]
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
$title = "Edit | Recipe thing";
require_once $file_level . "includes/head.php";
?>


<h1>Edit Recipe</h1>

<img src="data:image/jpeg;base64, <?php echo base64_encode($row['image']) ?>" alt="<?php echo $name . ' ' . $alt; ?>" width="100" /><br />

<form method="post" enctype="multipart/form-data">
    <label for="image">Image</label>
    <input type="file" name="image" id="image" /><br />
    <label for="name">Type</label>
    <input type="text" name="name" id="name" value="<?php echo $name; ?>" /><br />
    <label for="alt">alt</label>
    <input type="text" name="alt" id="alt" value="<?php echo $alt; ?>" /><br />
    <label for="subtitle">Date of Birth</label>
    <input type="text" name="subtitle" id="subtitle" value="<?php echo $subtitle; ?>" /><br />
    <input type="submit" onclick="return validateRecipe();" name="edit" value="Edit Recipe" />
    <input type="submit" name="cancel" value="Cancel" />
</form>

<p id="js_validation_message"></p>

<script src="js/validate.js"></script>

<?php
// Add the footer
require_once $file_level . "includes/footer.php";
?>