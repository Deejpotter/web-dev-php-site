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
        $sql = 'SELECT image, type, breed, dob FROM pets WHERE pet_id = "' . $_GET['pet_id'] . '"';
        $sth = $dbh->prepare($sql);
        $sth->execute();

        $row = $sth->fetch(PDO::FETCH_ASSOC);
        $old_image = base64_encode($row['image']);
        $type = sanitize_input($row['type']);
        $breed = sanitize_input($row['breed']);
        $dob = sanitize_input($row['dob']);
    } catch (PDOException $e) {

        error_log("Invalid pet profile: " . $e->getMessage());
        $_SESSION['error'] = "Invalid pet profile";
        header("Location: edit.php");
        return;
    }
}

if (isset($_POST['edit'])) {

    if (($_POST['type'] !== "") && ($_POST['breed'] !== "") && ($_POST['dob'] !== "")) {

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

                $sql = 'UPDATE pets SET image = :image, type = :type, breed = :breed, dob = :dob WHERE pet_id = "' . $_GET['pet_id'] . '"';
                $sth = $dbh->prepare($sql);
                $sth->execute(
                    array(
                        ':image' => $image,
                        ':type' => $_POST["type"],
                        ':breed' => $_POST["breed"],
                        ':dob' => $_POST["dob"]
                    )
                );

                $_SESSION['success'] = 'Pet updated!';
                header('Location: ' . $file_level . 'index.php');
                return;
            } catch (PDOException $e) {

                error_log('Pet Update Failed: ' . $e->getMessage());
                $_SESSION['error'] = 'The pet could not be updated';
                header('Location: ' . $file_level . 'index.php');
                return;
            }
        } else {

            // Update keeping existing image
            try {

                $sql = 'UPDATE pets SET type = :type, breed = :breed, dob = :dob WHERE pet_id = "' . $_GET['pet_id'] . '"';
                $sth = $dbh->prepare($sql);
                $sth->execute(
                    array(
                        ':type' => $_POST["type"],
                        ':breed' => $_POST["breed"],
                        ':dob' => $_POST["dob"]
                    )
                );

                $_SESSION['success'] = 'Pet updated!';
                header('Location: ' . $file_level . 'index.php');
                return;
            } catch (PDOException $e) {

                error_log('Pet Update Failed: ' . $e->getMessage());
                $_SESSION['error'] = 'The pet could not be updated';
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


<h1>Edit Pet</h1>

<img src="data:image/jpeg;base64, <?php echo base64_encode($row['image']) ?>" alt="<?php echo $type . ' ' . $breed; ?>"
    width="100" /><br />

<form method="post" enctype="multipart/form-data">
    <label for="image">Image</label>
    <input type="file" name="image" id="image" /><br />
    <label for="type">Type</label>
    <input type="text" name="type" id="type" value="<?php echo $type; ?>" /><br />
    <label for="breed">Breed</label>
    <input type="text" name="breed" id="breed" value="<?php echo $breed; ?>" /><br />
    <label for="dob">Date of Birth</label>
    <input type="text" name="dob" id="dob" value="<?php echo $dob; ?>" /><br />
    <input type="submit" onclick="return validatePet();" name="edit" value="Edit Pet" />
    <input type="submit" name="cancel" value="Cancel" />
</form>

<p id="js_validation_message"></p>

<script src="js/validate.js"></script>

<?php
// Add the footer
require_once $file_level . "includes/footer.php";
?>