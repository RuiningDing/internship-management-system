<?php
    include '../config.php';

    // Only Assessor can access this page
    if (!isset($_SESSION['role']) || $_SESSION['role'] != "Assessor") {
        header("Location: ../login.php");
        exit();
    }

    $userid = $_SESSION['user_id'];

    // Find current assessor
    $sqlassessor = "SELECT * FROM assessor WHERE user_id = '$userid'";
    $resultassessor = $conn->query($sqlassessor);
    $assessor = $resultassessor->fetch_assoc();

    if (!$assessor) {
        die("Assessor record not found.");
    }

    $assessorid = $assessor['assessor_id'];
    $assessorname = $assessor['assessor_name'];

    // Find assigned students
    $sqlstudent = "
        SELECT 
        i.internship_id,
        i.company_name,
        i.start_date,
        i.end_date,
        i.status,
        s.student_id,
        s.student_name,
        p.programme_name
    FROM internship i
    JOIN student s ON i.student_id = s.student_id
    JOIN programme p ON s.programme_id = p.programme_id
    WHERE i.assessor_id = '$assessorid'
    ";

    $resultstudent = $conn->query($sqlstudent);
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="../style.css">
    <meta charset="UTF-8">
    <title>My Assigned Students</title>
</head>
<body>

    <h1>My Assigned Students</h1>

    <p><strong>Assessor:</strong> <?= $assessorname ?></p>

    <a href="index.php">Back to Dashboard</a> |
    <a href="../logout.php">Logout</a>

    <hr>

    <table border="1" cellpadding="8">
        <tr>
            <th>Internship ID</th>
            <th>Student ID</th>
            <th>Student Name</th>
            <th>Programme</th>
            <th>Company</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Status</th>
            <th>Action</th>
        </tr>

    <?php if ($resultstudent->num_rows > 0): ?>
        <?php while ($row = $resultstudent->fetch_assoc()): ?>
            <tr>
                <td><?= $row['internship_id'] ?></td>
                <td><?= $row['student_id'] ?></td>
                <td><?= $row['student_name'] ?></td>
                <td><?= $row['programme_name'] ?></td>
                <td><?= $row['company_name'] ?></td>
                <td><?= $row['start_date'] ?></td>
                <td><?= $row['end_date'] ?></td>
                <td><?= $row['status'] ?></td>
                <td>
                    <a href="result_entry.php?internship_id=<?= $row['internship_id'] ?>">
                        Assess
                    </a>
                </td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="9">No students assigned yet.</td>
        </tr>
    <?php endif; ?>
</table>

</body>
</html>