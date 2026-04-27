<?php
include '../config.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] != "Admin") {
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container">
        <div class="nav">
            <a href="../logout.php" class="logout">Logout</a>
        </div>
        <h1>Welcome, Admin <?= $_SESSION['username'] ?></h1>
        <hr style="margin-bottom:20px;">

        <h3>Menu</h3>
        <ul style="list-style:none;padding:0;">
            <li style="margin:10px 0;"><a href="students.php">Manage Students</a></li>
            <li style="margin:10px 0;"><a href="assessors.php">Manage Assessors</a></li>
            <li style="margin:10px 0;"><a href="assign.php">Assign Internship</a></li>
            <li style="margin:10px 0;"><a href="scores.php">View All Scores</a></li>
        </ul>
    </div>
</body>
</html>