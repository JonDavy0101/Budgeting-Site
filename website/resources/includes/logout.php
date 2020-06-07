<?php
// Always start this first
session_start();

// Destroying the session clears the $_SESSION variable, thus "logging" the user
// out. This also happens automatically when the browser is closed
session_destroy();
$_SESSION['logged-in'] = false;
header("Location: ../../login.php");
?>