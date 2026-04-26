<?php
include '../config.php';

// Admin only access
if (!isset($_SESSION['role']) || $_SESSION['role'] != "Admin") {
    header("Location: ../login.php");
    exit();
}

$message = "";

// Add Student
if (isset($_POST['add_student'])) {
    $student_id = $_POST['student_id'];
    $student_name = $_POST['student_name'];
    $programme_id = $_POST['programme_id'];
    $contact = $_POST['contact'];

    $sql = "INSERT INTO student (student_id, student_name, programme_id, contact) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssis", $student_id, $student_name, $programme_id, $contact);

    if ($stmt->execute()) {
        $message = "Student added successfully!";
    } else {
        $message = "Error: Student ID may already exist.";
    }
}

// Delete Student
if (isset($_GET['delete'])) {
    $sid = $_GET['delete'];
    $conn->query("DELETE FROM student WHERE student_id = '$sid'");
    header("Location: students.php");
    exit();
}

// Get Students & Programmes
$students = $conn->query("SELECT s.*, p.programme_name FROM student s LEFT JOIN programme p ON s.programme_id = p.programme_id");
$programmes = $conn->query("SELECT * FROM programme");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Students</title>
</head>
<body>
    <h2>Manage Students</h2>
    <a href="index.php">Back to Dashboard</a> | <a href="../logout.php">Logout</a>
    <hr>

    <?php if ($message != ""): ?>
        <p style="color:green;"><?= $message ?></p>
    <?php endif; ?>

    <h3>Add New Student</h3>
    <form method="post">
        Student ID: <input type="text" name="student_id" required><br><br>
        Student Name: <input type="text" name="student_name" required><br><br>
        Programme:
        <select name="programme_id" required>
            <?php while ($p = $programmes->fetch_assoc()): ?>
                <option value="<?= $p['programme_id'] ?>"><?= $p['programme_name'] ?></option>
            <?php endwhile; ?>
        </select><br><br>
        Contact: <input type="text" name="contact"><br><br>
        <button type="submit" name="add_student">Add Student</button>
    </form>

    <hr>
    <h3>Student List</h3>
    <table border="1" cellpadding="8">
        <tr>
            <th>Student ID</th>
            <th>Name</th>
            <th>Programme</th>
            <th>Contact</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $students->fetch_assoc()): ?>
        <tr>
            <td><?= $row['student_id'] ?></td>
            <td><?= $row['student_name'] ?></td>
            <td><?= $row['programme_name'] ?></td>
            <td><?= $row['contact'] ?></td>
            <td>
                <a href="students.php?delete=<?= $row['student_id'] ?>" onclick="return confirm('Delete this student?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>