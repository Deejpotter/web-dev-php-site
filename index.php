<?php

header('Content-Type: application/xhtml+xml; charset=utf-8');

session_start();

if (!isset($_SESSION['email'])) {
    $_SESSION["error"] = 'Please login.';
    header("Location: login.php");
    return;
}

require_once "includes/pdo.php";
require_once "includes/util.php";

?>

<?php
// Add the head
$file_level = "";
$title = "Home | Recipe thing";
require_once "includes/head.php";
?>

<body>

    <h1>Recipe thing</h1>

    <?php

    require_once "includes/flash.php";

    $sql = 'SELECT pet_id, image, type, breed, dob FROM pets WHERE user_id = "' . $_SESSION['user_id'] . '"';
    $sth = $dbh->prepare($sql);

    if (!empty($sth->execute())) {

        if ($sth->rowCount() == 0) {
            echo '<p>No data found</p>';
        } else {

            // Define variables and set to empty values
            $image = $type = $breed = $dob = $pet_id = "";

            echo ('<table>');
            echo ('<thead>');
            echo ('<tr><th>Image</th><th>Type</th><th>Breed</th><th>Date of Birth</th><th>Edit</th><th>Delete</th></tr>');
            echo ('</thead>');
            echo ('<tbody>');

            while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {

                // Data validation
                // Get unaltered data back from database, then sanitize it to prevenet HTML injection
                // $image = sanitize_input($row["image"]);
                $type = sanitize_input($row["type"]);
                $breed = sanitize_input($row["breed"]);
                $dob = sanitize_input($row["dob"]);
                $pet_id = $row["image"];

                echo ('<tr>');
                echo ('<td><img src="data:image/jpeg;base64,' . base64_encode($row["image"]) . '" alt="" width="100" /></td>');
                echo ('<td>' . $type . '</td>');
                echo ('<td>' . $breed . '</td>');
                echo ('<td>' . $dob . '</td>');
                echo ('<td><a href="edit.php?pet_id=' . $row['pet_id'] . '">Edit</a></td>');
                echo ('<td><a href="delete.php?pet_id=' . $row['pet_id'] . '">Delete</a></td>');
                echo ('</tr>');
            }

            echo ('</tbody>');
            echo ('</table>');
        }

        echo '<p><a href="add.php">Add Pet</a></p>';
        echo '<p><a href="logout.php">Logout</a></p>';
    }

    ?>

</body>

</html>