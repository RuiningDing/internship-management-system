<?php
session_start();


$host = "localhost";
$db_user = "root";
$db_pass = "";
$dbname = "internship_system";

$conn = new mysqli($host, $db_user, $db_pass, $dbname);

if ($conn->connect_error) {
    die("database connection failed: " . $conn->connect_error);
}
?>