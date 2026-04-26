<?php
include '../config.php';

// Restrict access to Admin only
if (!isset($_SESSION['role']) || $_SESSION['role'] != "Admin") {
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
</head>
<body>
    <h1>Welcome, Admin: <?= $_SESSION['username'] ?></h1>
    <a href="../logout.php">Logout</a>
    <hr>

    <h3>Menu</h3>
    <ul>
        <li><a href="students.php">Manage Students</a></li>
        <li><a href="assessors.php">Manage Assessors</a></li>
        <li><a href="assign.php">Assign Internship</a></li>
        <li><a href="scores.php">View All Scores</a></li>
    </ul>
</body>
</html>