<?php
include '../config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != "Admin") {
    header("Location: ../login.php");
    exit();
}


$scores = $conn->query("
    SELECT 
        sc.score_id,
        s.student_id,
        s.student_name,
        a.assessor_name,
        c.criteria_name,
        c.weightage,
        sc.score,
        sc.comments,
        sc.assessed_date
    FROM assessment_score sc
    JOIN internship i ON sc.internship_id = i.internship_id
    JOIN student s ON i.student_id = s.student_id
    JOIN assessor a ON i.assessor_id = a.assessor_id
    JOIN assessment_criteria c ON sc.criteria_id = c.criteria_id
    ORDER BY s.student_id, c.criteria_id
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View All Scores</title>
    <style>
        table { border-collapse: collapse; width: 100%; margin-top:10px; }
        td, th { border:1px solid #333; padding:8px; }
        th { background:#f2f2f2; }
    </style>
</head>
<body>
    <h2>View All Internship Scores</h2>
    <a href="index.php">Back to Dashboard</a> | <a href="../logout.php">Logout</a>
    <hr>

    <h3>Score Records</h3>
    <table>
        <tr>
            <th>Student ID</th>
            <th>Student Name</th>
            <th>Assessor</th>
            <th>Criteria</th>
            <th>Weight</th>
            <th>Score</th>
            <th>Comments</th>
            <th>Date</th>
        </tr>
        <?php while ($row = $scores->fetch_assoc()): ?>
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
</body>
</html>