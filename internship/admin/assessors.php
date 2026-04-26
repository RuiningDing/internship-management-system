<?php
include '../config.php';

// Only Admin can access
if (!isset($_SESSION['role']) || $_SESSION['role'] !== "Admin") {
    header("Location: ../login.php");
    exit();
}

$msg = "";

// Add new Assessor (create user account first)
if(isset($_POST['add_assessor'])){
    $username   = $_POST['username'];
    $password   = $_POST['password'];
    $a_name     = $_POST['assessor_name'];
    $dept       = $_POST['department'];

    // Insert into users table (role = Assessor)
    $sqlUser = "INSERT INTO users (username, password, role) VALUES (?, ?, 'Assessor')";
    $stmtU = $conn->prepare($sqlUser);
    $stmtU->bind_param("ss", $username, $password);

    if($stmtU->execute()){
        $userId = $conn->insert_id;
        // Insert into assessor table
        $sqlAss = "INSERT INTO assessor (assessor_name, user_id, department) VALUES (?, ?, ?)";
        $stmtA = $conn->prepare($sqlAss);
        $stmtA->bind_param("sis", $a_name, $userId, $dept);
        $stmtA->execute();
        $msg = "Assessor account created successfully.";
    }else{
        $msg = "Username already exists.";
    }
}

// Delete assessor
if(isset($_GET['delete'])){
    $aid = $_GET['delete'];
    // Get user_id first
    $res = $conn->query("SELECT user_id FROM assessor WHERE assessor_id = '$aid'");
    $row = $res->fetch_assoc();
    $uid = $row['user_id'];

    $conn->query("DELETE FROM assessor WHERE assessor_id = '$aid'");
    $conn->query("DELETE FROM users WHERE user_id = '$uid'");

    header("Location: assessors.php");
    exit();
}

// Fetch all assessors with user info
$sql = "
SELECT a.assessor_id, a.assessor_name, a.department, u.username
FROM assessor a
JOIN users u ON a.user_id = u.user_id
";
$assessors = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Assessors</title>
</head>
<body>
    <h2>Manage Assessors</h2>
    <a href="index.php">Back to Dashboard</a> |
    <a href="../logout.php">Logout</a>
    <hr>

    <?php if($msg != ""): ?>
        <p style="color:green"><?= $msg ?></p>
    <?php endif; ?>

    <h3>Add New Assessor</h3>
    <form method="post">
        Username: <input type="text" name="username" required><br><br>
        Password: <input type="password" name="password" required><br><br>
        Assessor Name: <input type="text" name="assessor_name" required><br><br>
        Department: <input type="text" name="department"><br><br>
        <button type="submit" name="add_assessor">Create Assessor</button>
    </form>

    <hr>
    <h3>Assessor List</h3>
    <table border="1" cellpadding="6">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Username</th>
            <th>Department</th>
            <th>Action</th>
        </tr>
        <?php while($data = $assessors->fetch_assoc()): ?>
        <tr>
            <td><?= $data['assessor_id'] ?></td>
            <td><?= $data['assessor_name'] ?></td>
            <td><?= $data['username'] ?></td>
            <td><?= $data['department'] ?></td>
            <td>
                <a href="assessors.php?delete=<?= $data['assessor_id'] ?>"
                   onclick="return confirm('Delete this assessor?')">
                   Delete
                </a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

</body>
</html>