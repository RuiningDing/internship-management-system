<?php
include '../config.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] != "Admin") {
    header("Location: ../login.php");
    exit();
}

$scores = $conn->query("
    SELECT sc.*, s.student_id, s.student_name, a.assessor_name, c.criteria_name, c.weightage
    FROM assessment_score sc
    JOIN internship i ON sc.internship_id = i.internship_id
    JOIN student s ON i.student_id = s.student_id
    JOIN assessor a ON i.assessor_id = a.assessor_id
    JOIN assessment_criteria c ON sc.criteria_id = c.criteria_id
    ORDER BY s.student_id
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Scores</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container">
        <div class="nav">
            <a href="index.php">← Back</a>
            <a href="../logout.php" class="logout">Logout</a>
        </div>
        <h2>All Internship Scores</h2>
        <table>
            <tr>
                <th>Student ID</th>
                <th>Name</th>
                <th>Assessor</th>
                <th>Criteria</th>
                <th>Weight</th>
                <th>Score</th>
                <th>Comment</th>
                <th>Date</th>
            </tr>
            <?php while($row = $scores->fetch_assoc()): ?>
            <tr>
                <td><?= $row['student_id'] ?></td>
                <td><?= $row['student_name'] ?></td>
                <td><?= $row['assessor_name'] ?></td>
                <td><?= $row['criteria_name'] ?></td>
                <td><?= $row['weightage'] ?>%</td>
                <td><?= $row['score'] ?></td>
                <td><?= $row['comments'] ?></td>
                <td><?= $row['assessed_date'] ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>