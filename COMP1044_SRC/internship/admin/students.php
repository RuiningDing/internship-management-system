<?php
include '../config.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] != "Admin") {
    header("Location: ../login.php");
    exit();
}

$message = "";
$msgtype = "";

if (isset($_POST['add_student'])) {
    $student_id = $_POST['student_id'];
    $student_name = $_POST['student_name'];
    $programme_id = $_POST['programme_id'];
    $contact = $_POST['contact'];

    $sql = "INSERT INTO student (student_id, student_name, programme_id, contact) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssis", $student_id, $student_name, $programme_id, $contact);

    if ($stmt->execute()) $message = "Student added successfully!";
    else $message = "Error: ID already exists.";
}

if (isset($_GET['delete'])) {
    $sid = $_GET['delete'];

    $checksql = "SELECT * FROM internship WHERE student_id = '$sid'";
    $checkresult = $conn->query($checksql);

    if ($checkresult->num_rows > 0) {
        $message = "Cannot delete this student because this student already has internship record.";
        $msgtype = "error";
    } else {
        $deletesql = "DELETE FROM student WHERE student_id = '$sid'";

        if ($conn->query($deletesql)) {
            $message = "Student deleted successfully.";
            $msgtype = "success";
        } else {
            $message = "Error: " . $conn->error;
            $msgtype = "error";
        }
    }
}

$students = $conn->query("SELECT s.*, p.programme_name FROM student s LEFT JOIN programme p ON s.programme_id = p.programme_id");
$programmes = $conn->query("SELECT * FROM programme");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Students</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container">
        <div class="nav">
            <a href="index.php">← Back</a>
            <a href="../logout.php" class="logout">Logout</a>
        </div>
        <h2>Manage Students</h2>
        <?php if($message): ?>
          
            <p class="<?php echo $msgtype; ?>"><?php echo $message; ?></p>

        <?php endif; ?>

        <h3>Add New Student</h3>
        <form method="post">
            <label>Student ID</label>
            <input type="text" name="student_id" required>

            <label>Student Name</label>
            <input type="text" name="student_name" required>

            <label>Programme</label>
            <select name="programme_id" required>
                <?php while($p = $programmes->fetch_assoc()): ?>
                    <option value="<?= $p['programme_id'] ?>"><?= $p['programme_name'] ?></option>
                <?php endwhile; ?>
            </select>

            <label>Contact</label>
            <input type="text" name="contact">
            <button name="add_student">Add Student</button>
        </form>

        <hr style="margin:25px 0;">
        <h3>Student List</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Programme</th>
                <th>Contact</th>
                <th>Action</th>
            </tr>
            <?php while($row = $students->fetch_assoc()): ?>
            <tr>
                <td><?= $row['student_id'] ?></td>
                <td><?= $row['student_name'] ?></td>
                <td><?= $row['programme_name'] ?></td>
                <td><?= $row['contact'] ?></td>
                <td><a href="students.php?delete=<?= $row['student_id'] ?>" onclick="return confirm('Delete?')" style="color:red;">Delete</a></td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>