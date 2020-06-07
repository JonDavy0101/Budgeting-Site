<?php

DEFINE('DB_USERNAME', 'add_username');
DEFINE('DB_PASSWORD', 'add_password');
DEFINE('DB_HOST', 'localhost');
DEFINE('DB_DATABASE', 'add_database');

$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

if (mysqli_connect_error()) {
	// die('Connect Error ('.mysqli_connect_errno().') '.mysqli_connect_error());
	die('Submission Unsuccessful');
}

?>
