<?php

header('Content-Type: application/xhtml+xml; charset=utf-8');

session_start();

require_once "pdo.php";

if (isset($_SESSION['email'])) {
    $_SESSION["error"] = 'Please logout first.';
    header("Location: index.php");
    return;
}

if (isset($_POST['email']) && isset($_POST['password'])) {

    $sql = "SELECT user_id, email, password FROM accounts WHERE email = :email";
    $sth = $dbh->prepare($sql);
    $sth->bindParam(':email', $_POST['email']);
    $sth->execute();

    $result = $sth->fetch(PDO::FETCH_ASSOC);

    if (!empty($result['email'])) {

        if (password_verify($_POST['password'], $result['password'])) {

            $_SESSION['email'] = $_POST['email'];
            $_SESSION['user_id'] = $result['user_id'];
            $_SESSION['success'] = "You are now logged in!";
            header("Location: index.php");
            return;
        } else {

            $_SESSION['error'] = 'Invalid password.';
            header("Location: login.php");
            return;
        }
    } else {
        $_SESSION['error'] = "Email Not Found.";
        header("Location: login.php");
        return;
    }
}

$dbh = null;

?>

<?php
// Add the head
$file_level = "";
$title = "Accounts | Recipe thing";
require_once "head.php";
?>

<body>

    <h1>Login</h1>

    <nav>
        <a href="index.php">Index</a>
        <a href="accounts.php">Accounts</a>
    </nav>

    <section id="login">

        <hr />

        <h2>Login</h2>

        <!-- Flash message -->
        <?php require "flash.php"; ?>

        <form method="post">
            <label for="email">Email</label>
            <input type="text" name="email" id="email" /><br />
            <label for="password">Password</label>
            <input type="password" name="password" id="password" /><br />
            <input type="submit" onclick="return validateLogin();" value="Login" />
        </form>

        <p id="js_validation_message"></p>

    </section>

</body>

</html>