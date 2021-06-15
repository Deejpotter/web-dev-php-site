<?php

$file_level = "";

header('Content-Type: application/xhtml+xml; charset=utf-8');

session_start();

if (isset($_SESSION['email'])) {
    $_SESSION["error"] = 'Please logout first.';
    header("Location: " . $file_level . "index.php");
    return;
}

require_once $file_level . "includes/pdo.php";

// Return true if email is valid
function checkemail($email)
{
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return true;
    } else {
        return false;
    }
}


// Check to see if we have some POST data, if we do process it
if (isset($_POST['email']) && isset($_POST['password']) && isset($_POST['retype'])) {

    unset($_SESSION['email']);

    $checkemail = checkemail($_POST['email']);

    if ($checkemail === false) {
        $_SESSION["error"] = "Invalid email.";
        header("Location: " . $file_level . "accounts.php");
        return;
    } else if (strlen($_POST['password']) < 5) {
        $_SESSION["error"] = "Invalid password. Password must be at least 5 characters";
        header("Location: " . $file_level . "accounts.php");
        return;
    } else {

        if ($_POST['password'] === $_POST['retype']) {

            try {
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $sql = "INSERT INTO accounts (email, password) VALUES (:email, :password)";
                $sth = $dbh->prepare($sql);
                $sth->bindParam(":email", $_POST['email']);
                $sth->bindParam(":password", $password);

                $sth->execute();
                $_SESSION["success"] = "New account created successfully";
                header("Location: " . $file_level . "login.php");
                return;
            } catch (PDOException $e) {
                error_log("Account Creation Failed: " . $e->getMessage());
                $_SESSION["error"] = "Account creation failed";
                header("Location: " . $file_level . "accounts.php");
                return;
            }
        } else {
            $_SESSION["error"] = "Passwords Do Not Match.";
            header("Location: " . $file_level . "accounts.php");
            return;
        }
    }
}

$dbh = null;

?>


<?php
// Add the head
$title = "Accounts | Your Recipes";
require_once $file_level . "includes/head.php";
?>

<main>

    <!-- Hero section -->
    <section class="hero shadow">
        <img src="<?php echo $file_level; ?>images/main-hero.jpg" alt="A bench with a tablecloth." />
        <div class="container-center">
            <h1>Create an account</h1>
        </div>
    </section>

    <section class="container-center" id="create">
        <h2>Create a new account</h2>
        <div class="container-sidebar light shadow border-radius">
            <article class="about-article">
                <div class="container-col">
                    <?php
                    // Flash message
                    require_once $file_level . "includes/flash.php";
                    ?>

                    <h2>Account creation</h2>

                    <form method="post">
                        <label for="email">Email</label>
                        <input type="text" name="email" id="email" /><br />
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" /><br />
                        <label for="retype">Re-type Password</label>
                        <input type="password" name="retype" id="retype" /><br />
                        <input type="submit" onclick="return validateAccount();" name="submit" value="Submit" />
                    </form>

                    <p id="js_validation_message"></p>

                    <script src="<?php echo $file_level; ?>js/validate.js"></script>
                </div>
            </article>
            <aside class="sidebar">
                <p>Please enter your new account details in the form.</p>
                <p>If you already have an account, log in <a href="<?php echo $file_level; ?>login.php">here</a>
                </p>
            </aside>
        </div>
    </section>
</main>

<script src="js/validate.js"></script>
<?php
// Add the footer
require_once $file_level . "includes/footer.php";
?>