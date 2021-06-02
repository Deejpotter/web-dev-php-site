<!-- Sidebar -->
<aside class="sidebar">
    <?php if (isset($_SESSION['email'])) {
        $sql = 'SELECT recipe_id, image, name, alt, subtitle, ingredients, method FROM recipes WHERE account_id = "' . $_SESSION['account_id'] . '"';
        $sth = $dbh->prepare($sql);

        if (!empty($sth->execute())) {

            require_once $file_level . "includes/flash.php";

            if ($sth->rowCount() == 0) {
                echo '<p>No data found</p>';
            } else {

                // Define variables and set to empty values
                $image = $name = $alt = $subtitle = $ingredients = $method = $recipe_id = "";

                for ($i = 0; $i < 2; $i++) {
                    if ($row = $sth->fetch(PDO::FETCH_ASSOC)) {

                        // Data validation
                        // Get unaltered data back from database, then sanitize it to prevenet HTML injection
                        // $image = sanitize_input($row["image"]);
                        $name = sanitize_input($row["name"]);
                        $alt = sanitize_input($row["alt"]);
                        $subtitle = sanitize_input($row["subtitle"]);
                        $ingredients = sanitize_input($row["ingredients"]);
                        $method = sanitize_input($row["method"]);

                        echo ('<div class="card shadow">');
                        // recipe.image     recipe.alt
                        echo ('<img src="data:image/jpeg;base64,' . base64_encode($row["image"]) . '" alt="An empty table with a knife." />');
                        echo ('<a href="' . $file_level . 'recipes/edit.php?recipe_id=' . $row['recipe_id'] . '">Edit</a>');
                        echo ('<a href="' . $file_level . 'recipes/delete.php?recipe_id=' . $row['recipe_id'] . '">Delete</a>');
                        echo ('</div>');
                    }
                }
            }
        }
    } ?>
</aside>