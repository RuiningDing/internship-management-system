<?php
session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

$host = "127.0.0.1";
$db_user = "root";
$db_pass = "root";
$dbname = "internship_system";
$port = 3306;

$conn = new mysqli($host, $db_user, $db_pass, $dbname, $port);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>