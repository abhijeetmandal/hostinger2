<?php
// Database configuration
$dbHost     = "localhost";
$dbUsername = "u280902347_crush";
$dbPassword = "GF8J2gZ8cdDj";
$dbName     = "u280902347_crush";

//Create connection and select DB
$db = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

if ($db->connect_error) {
    die("Unable to connect database: " . $db->connect_error);
}
?>