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

    $kw = "";

    if (isset($_GET['kw'])) {
        $kw = $_GET['kw'];
        $kw = $conn->real_escape_string($kw);
    }

// Find results assessed by this assessor
    $sql = "
    SELECT 
        i.internship_id,
        s.student_id,
        s.student_name,
        p.programme_name,
        i.company_name,
        SUM(sc.score * ac.weightage / 100) AS finalscore,
        MAX(sc.comments) AS comments,
        MAX(sc.assessed_date) AS assesseddate
    FROM assessment_score sc
    JOIN assessment_criteria ac ON sc.criteria_id = ac.criteria_id
    JOIN internship i ON sc.internship_id = i.internship_id
    JOIN student s ON i.student_id = s.student_id
    JOIN programme p ON s.programme_id = p.programme_id
    WHERE sc.assessed_by = '$assessorid'
    AND (
        s.student_id LIKE '%$kw%'
        OR s.student_name LIKE '%$kw%'
    )
    GROUP BY 
        i.internship_id,
        s.student_id,
        s.student_name,
        p.programme_name,
        i.company_name
    ";

    $result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>View Results</title>
</head>
<body>

    <h1>View Results</h1>

    <p><strong>Assessor:</strong> <?= $assessorname ?></p>

    <a href="index.php">Dashboard</a> |
    <a href="students.php">My Assigned Students</a> |
    <a href="../logout.php">Logout</a>

    <hr>

    <form method="get">
        <label>Search by Student ID or Name:</label>
        <input type="text" name="kw" value="<?= $kw ?>">
        <input type="submit" value="Search">
        <a href="result.php">Reset</a>
    </form>

    <br>

    <table border="1" cellpadding="8">
        <tr>
            <th>Internship ID</th>
            <th>Student ID</th>
            <th>Student Name</th>
            <th>Programme</th>
            <th>Company</th>
            <th>Final Score</th>
            <th>Comments</th>
            <th>Assessed Date</th>
        </tr>

        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['internship_id'] ?></td>
                    <td><?= $row['student_id'] ?></td>
                    <td><?= $row['student_name'] ?></td>
                    <td><?= $row['programme_name'] ?></td>
                    <td><?= $row['company_name'] ?></td>
                    <td><?= number_format($row['finalscore'], 2) ?></td>
                    <td><?= $row['comments'] ?></td>
                    <td><?= $row['assesseddate'] ?></td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="8">No results found.</td>
        </tr>
    <?php endif; ?>

</table>

</body>
</html>