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
$file_level = "";
$title = "Accounts | Recipe thing";
require_once $file_level . "includes/head.php";
?>


<h1>User Accounts</h1>

<nav>
    <a href="index.php"></a>
    <a href="login.php"></a>
</nav>

<section id="create">

    <hr />

    <h2>Create New Account</h2>

    <?php
    // Flash message
    require_once $file_level . "includes/flash.php";
    ?>

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

</section>

<script src="js/validate.js"></script>
<?php
// Add the footer
require_once $file_level . "includes/footer.php";
?>