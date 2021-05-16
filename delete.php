<?php

header('Content-Type: application/xhtml+xml; charset=utf-8');

session_start();

if (!isset($_SESSION['email'])) {

    require_once "includes/pdo.php";
    require_once "includes/util.php";

    if (isset($_SESSION['delete']) && isset($_POST['pet_id'])) {

        try {

            $sql = 'DELETE FROM pets WHERE pet_id = :pet_id';
            $sth = $dbh->prepare($sql);
            $sth->execute(array(':pet_id' => $_POST['pet_id']));

            $_SESSION['success'] = 'Pet deleted';
            header('Location: index.php');
            return;
        } catch (PDOException $e) {

            error_log("Invalid pet profile: ");
            $_SESSION['error'] = "Delete pet failure"  . $e->getMessage();
            header("Location: edit.php");
            return;
        }
    } elseif (isset($_POST['cancel'])) {
        header('Location: index.php');
        return;
    }

    // Make sure that pet_id is present
    if (!isset($_GET['pet_id'])) {

        $_SESSION['error'] = 'Pet Missing';
        header('Location: index.php');
        return;
    }

    try {

        if (isset($_GET['pet_id'])) {

            // Get unaltered data back from database, then sanitise it to prevent HTML injection
            $sth = $dbh->prepare('SELECT pet_id, image, type, breed, dob FROM pets WHERE pet_id = :pet_id');
            $sth->execute(array(':pet_id' => $_GET['pet_id']));
            $row = $sth->fetch(PDO::FETCH_ASSOC);

            if ($row === false) {
                $_SESSION['error'] = 'Bad value for pet_id';
                header('Location: index.php');
                return;
            }

            $pet_id = sanitize_input($row['pet_id']);
            $type = sanitize_input($row['type']);
            $breed = sanitize_input($row['breed']);
            $dob = sanitize_input($row['dob']);
        }
    } catch (PDOException $e) {

        error_log("Invalid pet profile: ");
        $_SESSION['error'] = "Delete pet failure"  . $e->getMessage();
        header("Location: edit.php");
        return;
    }
}

?>

<?php
// Add the head
$file_level = "";
$title = "Delete | Recipe thing";
require_once "includes/head.php";
?>

<body>

    <h1>Delete Pet</h1>

    <img src="data:image/jpeg;base64,<?php echo base64_encode($row[" image"]) ?>" alt="<?php echo $type . ' ' . $breed; ?>" width="100" />
    <p>Type: <?php echo $type ?></p>
    <p>Breed: <?php echo $breed ?></p>
    <p>Date of Birth: <?php echo $dob ?></p>

    <form method="post">
        <input type="hidden" name="pet_id" value="<?php echo $pet_id; ?>" />
        <input type="submit" name="delete" value="Delete">
        <input type="submit" name="cancel" value="Cancel">
    </form>

</body>

</html>