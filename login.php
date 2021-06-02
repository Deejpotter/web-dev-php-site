<?php

$file_level = "";

header('Content-Type: application/xhtml+xml; charset=utf-8');

session_start();

require_once $file_level . "includes/pdo.php";

if (isset($_SESSION['email'])) {
    $_SESSION["error"] = 'Please logout first.';
    header("Location: " . $file_level . "index.php");
    return;
}

if (isset($_POST['email']) && isset($_POST['password'])) {

    $sql = "SELECT account_id, email, password FROM accounts WHERE email = :email";
    $sth = $dbh->prepare($sql);
    $sth->bindParam(':email', $_POST['email']);
    $sth->execute();

    $result = $sth->fetch(PDO::FETCH_ASSOC);

    if (!empty($result['email'])) {

        if (password_verify($_POST['password'], $result['password'])) {

            $_SESSION['email'] = $_POST['email'];
            $_SESSION['account_id'] = $result['account_id'];
            $_SESSION['success'] = "You are now logged in!";
            header("Location: " . $file_level . "index.php");
            return;
        } else {

            $_SESSION['error'] = 'Invalid password.';
            header("Location: " . $file_level . "login.php");
            return;
        }
    } else {
        $_SESSION['error'] = "Email Not Found.";
        header("Location: " . $file_level . "login.php");
        return;
    }
}

$dbh = null;

?>

<?php
// Add the head
$title = "Login | Recipe thing";
require_once $file_level . "includes/head.php";
?>

<main>
    <section class="container-center" id="login">

        <h1>Login</h1>

        <?php
        // Flash message
        require_once $file_level . "includes/flash.php";
        ?>

        <form method="post">
            <label for="email">Email</label>
            <input type="text" name="email" id="email" /><br />
            <label for="password">Password</label>
            <input type="password" name="password" id="password" /><br />
            <input type="submit" onclick="return validateLogin();" value="Login" />
        </form>

        <p id="js_validation_message"></p>

        <script src="<?php echo $file_level ?>js/validate.js"></script>

    </section>
</main>

<?php
// Add the footer
require_once $file_level . "includes/footer.php";
?>