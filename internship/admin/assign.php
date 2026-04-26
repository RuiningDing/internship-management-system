<?php
include '../config.php';

// Only Admin can access
if (!isset($_SESSION['role']) || $_SESSION['role'] != "Admin") {
    header("Location: ../login.php");
    exit();
}

$message = "";

// Assign Internship
if (isset($_POST['assign_internship'])) {
    $student_id = $_POST['student_id'];
    $assessor_id = $_POST['assessor_id'];
    $company = $_POST['company_name'];
    $start = $_POST['start_date'];
    $end = $_POST['end_date'];

    // Check if student already has internship
    $check = $conn->query("SELECT * FROM internship WHERE student_id = '$student_id'");
    if ($check->num_rows > 0) {
        $message = "Error: This student already has an internship assigned!";
    } else {
        $sql = "INSERT INTO internship (student_id, assessor_id, company_name, start_date, end_date)
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sisss", $student_id, $assessor_id, $company, $start, $end);

        if ($stmt->execute()) {
            $message = "Internship assigned successfully!";
        } else {
            $message = "Error: " . $conn->error;
        }
    }
}

// Get all students (not yet assigned)
$students = $conn->query("SELECT * FROM student WHERE student_id NOT IN (SELECT student_id FROM internship)");

// Get all assessors
$assessors = $conn->query("SELECT * FROM assessor");

// Get all assigned internships
$internships = $conn->query("
    SELECT i.*, s.student_name, a.assessor_name
    FROM internship i
    JOIN student s ON i.student_id = s.student_id
    JOIN assessor a ON i.assessor_id = a.assessor_id
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Assign Internship</title>
</head>
<body>
    <h2>Assign Internship</h2>
    <a href="index.php">Back to Dashboard</a> | <a href="../logout.php">Logout</a>
    <hr>

    <?php if ($message != ""): ?>
        <p style="color:green; font-weight:bold;"><?= $message ?></p>
    <?php endif; ?>

    <h3>Assign New Internship</h3>
    <form method="post">
        Student:
        <select name="student_id" required>
            <option value="">-- Select Student --</option>
            <?php while ($s = $students->fetch_assoc()): ?>
                <option value="<?= $s['student_id'] ?>">
                    <?= $s['student_id'] ?> - <?= $s['student_name'] ?>
                </option>
            <?php endwhile; ?>
        </select><br><br>

        Assessor:
        <select name="assessor_id" required>
            <option value="">-- Select Assessor --</option>
            <?php while ($a = $assessors->fetch_assoc()): ?>
                <option value="<?= $a['assessor_id'] ?>">
                    <?= $a['assessor_id'] ?> - <?= $a['assessor_name'] ?>
                </option>
            <?php endwhile; ?>
        </select><br><br>

        Company Name: <input type="text" name="company_name"><br><br>
        Start Date: <input type="date" name="start_date"><br><br>
        End Date: <input type="date" name="end_date"><br><br>

        <button type="submit" name="assign_internship">Assign Internship</button>
    </form>

    <hr>
    <h3>Assigned Internship List</h3>
    <table border="1" cellpadding="8">
        <tr>
            <th>Internship ID</th>
            <th>Student</th>
            <th>Assessor</th>
            <th>Company</th>
            <th>Start Date</th>
            <th>End Date</th>
        </tr>
        <?php while ($row = $internships->fetch_assoc()): ?>
        <tr>
            <td><?= $row['internship_id'] ?></td>
            <td><?= $row['student_name'] ?></td>
            <td><?= $row['assessor_name'] ?></td>
            <td><?= $row['company_name'] ?></td>
            <td><?= $row['start_date'] ?></td>
            <td><?= $row['end_date'] ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>