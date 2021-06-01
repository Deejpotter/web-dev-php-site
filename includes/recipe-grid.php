<?php if (isset($_SESSION['email'])) {
    $sql = 'SELECT pet_id, image, type, breed, dob FROM pets WHERE user_id = "' . $_SESSION['user_id'] . '"';
    $sth = $dbh->prepare($sql);

    if (!empty($sth->execute())) {

        echo ('<section>');
        require_once $file_level . "includes/flash.php";

        if ($sth->rowCount() == 0) {
            echo '<p>No data found</p>';
        } else {

            // Define variables and set to empty values
            $image = $type = $breed = $dob = $pet_id = "";

            echo ('<div class="grid">');

            while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {

                // Data validation
                // Get unaltered data back from database, then sanitize it to prevenet HTML injection
                // $image = sanitize_input($row["image"]);
                $type = sanitize_input($row["type"]);
                $breed = sanitize_input($row["breed"]);
                $dob = sanitize_input($row["dob"]);

                echo ('<div class="card shadow">');
                echo ('<img src="data:image/jpeg;base64,' . base64_encode($row["image"]) . '" alt="An empty table with a knife." />');
                echo ('<a href="' . $file_level . 'recipes/edit.php?pet_id=' . $row['pet_id'] . '">Edit</a>');
                echo ('<a href="' . $file_level . 'recipes/delete.php?pet_id=' . $row['pet_id'] . '">Delete</a>');
                echo ('</div>');
            }
            echo ('</div>');
            echo ('</section>');
        }
    }
}