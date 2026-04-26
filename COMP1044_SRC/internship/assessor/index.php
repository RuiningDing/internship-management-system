<?php
include '../config.php';

// Only Assessor can access this page
if (!isset($_SESSION['role']) || $_SESSION['role'] != "Assessor") {
    header("Location: ../login.php");
    exit();
}

$userid = $_SESSION['user_id'];

$sqlassessor = "SELECT * FROM assessor WHERE user_id = '$userid'";
$resultassessor = $conn->query($sqlassessor);
$assessor = $resultassessor->fetch_assoc();

if ($assessor) {
    $assessorname = $assessor['assessor_name'];
} else {
    $assessorname = "Assessor";
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Assessor Dashboard</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<h1>Assessor Dashboard</h1>

<p>Welcome, <?= $assessorname ?>.</p>

<ul>
    <li><a href="students.php">My Assigned Students</a></li>
    <li><a href="result.php">View Results</a></li>
</ul>

<a href="../logout.php">Logout</a>

</body>
</html>