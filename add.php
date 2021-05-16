<?php

header('Content-Type: application/xhtml+xml; charset=utf-8');

session_start();

if (!isset($_SESSION['email'])) {
    $_SESSION["error"] = 'Please login.';
    header("Location: login.php");
    return;
}

require_once "pdo.php";

if (isset($_POST['add'])) {

    if (($_POST["image"] !== "") && ($_POST["type"] !== "") && ($_POST["breed"] !== "") && ($_POST["dob"] !== "")) {

        try {

            $image = file_get_contents($_FILES['image']['tmp_name']);
            $allowableTypes = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF);
            $detectedType = exif_imagetype($_FILES['image']['tmp_name']);

            if (!in_array($detectedType, $allowableTypes)) {
                $_SESSION['error'] = 'Not a valid image type';
                header("Location: index.php");
                return;
            }

            $sql = 'SELECT user_id FROM accounts WHERE email = "' . $_SESSION['email'] . '"';
            $sth = $dbh->prepare($sql);
            $sth->execute();

            $row = $sth->fetch(PDO::FETCH_ASSOC);
            $user_id = $row["user_id"];

            $sql = 'INSERT INTO pets (user_id, image, type, breed, dob) VALUES (:user_id, :image, :type, :breed, :dob)';
            $sth = $dbh->prepare($sql);
            $sth->execute(
                array(
                    ':user_id' => $user_id,
                    ':image' => $image,
                    ':type' => $_POST["type"],
                    ':breed' => $_POST["breed"],
                    ':dob' => $_POST["dob"]
                )
            );

            $_SESSION['success'] = 'Pet added!';
            header("Location: index.php");
            return;
        } catch (PDOException $e) {

            error_log("Pet Creation Failed: " . $e->getMessage());
            $_SESSION['error'] = 'The pet could not be added';
            header("Location: index.php");
            return;
        }
    }
} elseif (isset($_POST['cancel'])) {
    header('Location: index.php');
    return;
}

?>

<?php
// Add the head
$file_level = "";
$title = "Add | Recipe thing";
require_once "head.php";
?>

<body>

    <h1>Add recipe</h1>

    <form method="post">
        <label for="image">Image</label>
        <input type="file" name="image" id="image" /><br />
        <label for="type">Type</label>
        <input type="text" name="type" id="type" /><br />
        <label for="breed">Breed</label>
        <input type="text" name="breed" id="breed" /><br />
        <label for="dob">Date of Birth</label>
        <input type="text" name="dob" id="dob" /><br />
        <input type="submit" onclick="return validateAccount();" name="submit" value="Submit" />
        <input type="submit" name="cancel" value="Cancel" />
    </form>

    <p id="js_validation_message"></p>

    <script src="add.js"></script>

</body>

</html>