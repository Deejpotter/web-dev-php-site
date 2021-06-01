<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?php echo $title ?></title>
    <link rel="stylesheet" href="<?php echo $file_level; ?>css/styles.css" />
</head>

<body>

    <header>

        <!-- Navbar -->
        <div class="navbar shadow">
            <div class="container">
                <div class="brand">
                    <a href="<?php echo $file_level ?>index.php">Simple Recipes</a>
                </div>
                <nav>
                    <label for="show-menu">Show Menu</label>
                    <input type="checkbox" id="show-menu" role="button" />
                    <ul id="menu">
                        <?php if (isset($_SESSION['email'])) {
                            echo ('<li><a href="' . $file_level . 'index.php">Home</a></li>');
                            echo ('<li><a href="' . $file_level . 'recipes.php">Recipes</a></li>');
                            echo ('<li><a href="' . $file_level . 'about.php">About</a></li>');
                            echo ('<li><a href="' . $file_level . 'contact.php">Contact</a></li>');
                            echo ('<li><a href="' . $file_level . 'logout.php">Logout</a></li>');
                        } else {
                            echo ('<li><a href="' . $file_level . 'index.php">Home</a></li>');
                            echo ('<li><a href="' . $file_level . 'about.php">About</a></li>');
                            echo ('<li><a href="' . $file_level . 'contact.php">Contact</a></li>');
                            echo ('<li><a href="' . $file_level . 'login.php">Login</a></li>');
                        } ?>
                    </ul>
                </nav>
            </div>
        </div>
    </header>