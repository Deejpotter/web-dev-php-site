<?php

$file_level = "";

header('Content-Type: application/xhtml+xml; charset=utf-8');

session_start();

require_once $file_level . "includes/pdo.php";
require_once $file_level . "includes/util.php";

?>

<?php
// Add the head
$file_level = "";
$title = "Home | Recipe thing";
require_once $file_level . "includes/head.php";
?>

<main>

    <!-- Hero section -->
    <section class="hero shadow">
        <img src="<?php echo $file_level ?>images/blank-tablecloth.jpg" alt="An empty table with a tablecloth." />
        <div class="container-center">
            <h1>Contact Me</h1>
            <h2>Find some contact information.</h2>
        </div>
    </section>

    <div class="container-sidebar border-radius shadow">

        <!-- Recipe section -->
        <article class="contact-article">
            <div class="container-col">
                <h2>How to contact me</h2>
                <p>Email me using this address to find out all about cooking and stuff.</p>
                <br />
                <p>Use this phone number if you have some urgent recipe things to learn.</p>
            </div>
        </article>

        <!-- Sidebar -->
        <aside class="sidebar">
            <h2>Contact details</h2>
            <p>Email: email@address.com</p>
            <br />
            <p>Phone: 0432 123 456</p>
        </aside>

    </div>

</main>

<?php
// Add the footer
require_once $file_level . "includes/footer.php";
?>