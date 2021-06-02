<?php

$file_level = "";

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
$title = "Home | Recipe thing";
require_once $file_level . "includes/head.php";
?>

<!-- Hero section -->
<section class="hero shadow">
    <img src="<?php echo $file_level; ?>images/blank-knife.jpg" alt="An empty table with a knife." />
    <div class="container-center">
        <h1>Simple Recipes</h1>
        <h2>Every recipe here is simple.</h2>
    </div>
</section>

<?php


$sql = 'SELECT recipe_id, image, name, alt, subtitle, ingredients, method FROM recipes WHERE account_id = "' . $_SESSION['account_id'] . '"';
$sth = $dbh->prepare($sql);

if (!empty($sth->execute())) {

    echo ('<div class="container-center">');
    require_once $file_level . "includes/flash.php";

    if ($sth->rowCount() == 0) {
        echo '<p>No data found</p>';
    } else {

        // Define variables and set to empty values
        $image = $name = $alt = $subtitle = $ingredients = $method = $recipe_id = "";

        echo ('<table>');
        echo ('<thead>');
        echo ('<tr><th>Image</th><th>Type</th><th>alt</th><th>Date of Birth</th><th>Edit</th><th>Delete</th></tr>');
        echo ('</thead>');
        echo ('<tbody>');

        while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {

            // Data validation
            // Get unaltered data back from database, then sanitize it to prevenet HTML injection
            // $image = sanitize_input($row["image"]);
            $name = sanitize_input($row["name"]);
            $alt = sanitize_input($row["alt"]);
            $subtitle = sanitize_input($row["subtitle"]);
            $recipe_id = $row["image"];

            echo ('<tr>');
            echo ('<td><img src="data:image/jpeg;base64,' . base64_encode($row["image"]) . '" alt="" width="100" /></td>');
            echo ('<td>' . $name . '</td>');
            echo ('<td>' . $alt . '</td>');
            echo ('<td>' . $subtitle . '</td>');
            echo ('<td><a href="' . $file_level . 'recipes/edit.php?recipe_id=' . $row['recipe_id'] . '">Edit</a></td>');
            echo ('<td><a href="' . $file_level . 'recipes/delete.php?recipe_id=' . $row['recipe_id'] . '">Delete</a></td>');
            echo ('</tr>');
        }

        echo ('</tbody>');
        echo ('</table>');
    }

    echo '<p><a href="' . $file_level . 'recipes/add.php">Add Recipe</a></p>';
    echo '<p><a href="' . $file_level . 'logout.php">Logout</a></p>';
    echo ('</div>');
}

?>

<?php
// Add the footer
require_once $file_level . "includes/footer.php";
?>