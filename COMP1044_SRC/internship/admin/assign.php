<?php
include '../config.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] != "Admin") {
    header("Location: ../login.php");
    exit();
}

$message = "";

if (isset($_POST['assign_internship'])) {
    $sid = $_POST['student_id'];
    $aid = $_POST['assessor_id'];
    $com = $_POST['company_name'];
    $start = $_POST['start_date'];
    $end = $_POST['end_date'];

    $check = $conn->query("SELECT * FROM internship WHERE student_id = '$sid'");
    if ($check->num_rows > 0) {
        $message = "Student already assigned!";
    } else {
        $sql = "INSERT INTO internship (student_id, assessor_id, company_name, start_date, end_date) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sisss", $sid, $aid, $com, $start, $end);
        $stmt->execute();
        $message = "Internship assigned successfully!";
    }
}

$students = $conn->query("SELECT * FROM student WHERE student_id NOT IN (SELECT student_id FROM internship)");
$assessors = $conn->query("SELECT * FROM assessor");
$internships = $conn->query("SELECT i.*, s.student_name, a.assessor_name FROM internship i JOIN student s ON i.student_id = s.student_id JOIN assessor a ON i.assessor_id = a.assessor_id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Assign Internship</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container">
        <div class="nav">
            <a href="index.php">← Back</a>
            <a href="../logout.php" class="logout">Logout</a>
        </div>
        <h2>Assign Internship</h2>
        <?php if($message): ?>
            <p class="success"><?= $message ?></p>
        <?php endif; ?>

        <form method="post">
            <label>Student</label>
            <select name="student_id" required>
                <option value="">-- Select --</option>
                <?php while($s = $students->fetch_assoc()): ?>
                    <option value="<?= $s['student_id'] ?>"><?= $s['student_id'] ?> <?= $s['student_name'] ?></option>
                <?php endwhile; ?>
            </select>

            <label>Assessor</label>
            <select name="assessor_id" required>
                <option value="">-- Select --</option>
                <?php while($a = $assessors->fetch_assoc()): ?>
                    <option value="<?= $a['assessor_id'] ?>"><?= $a['assessor_name'] ?></option>
                <?php endwhile; ?>
            </select>

            <label>Company</label><input type="text" name="company_name">
            <label>Start Date</label><input type="date" name="start_date">
            <label>End Date</label><input type="date" name="end_date">
            <button name="assign_internship">Assign</button>
        </form>

        <hr style="margin:25px 0;">
        <h3>Assigned List</h3>
        <table>
            <tr><th>ID</th><th>Student</th><th>Assessor</th><th>Company</th><th>Start</th><th>End</th></tr>
            <?php while($row = $internships->fetch_assoc()): ?>
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
    </div>
</body>
</html>