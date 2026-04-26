<?php
include '../config.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] != "Admin") {
    header("Location: ../login.php");
    exit();
}

$msg = "";

if(isset($_POST['add_assessor'])){
    $username = $_POST['username'];
    $password = $_POST['password'];
    $a_name = $_POST['assessor_name'];
    $dept = $_POST['department'];

    $sqlUser = "INSERT INTO users (username, password, role) VALUES (?, ?, 'Assessor')";
    $stmtU = $conn->prepare($sqlUser);
    $stmtU->bind_param("ss", $username, $password);

    if($stmtU->execute()){
        $userId = $conn->insert_id;
        $sqlAss = "INSERT INTO assessor (assessor_name, user_id, department) VALUES (?, ?, ?)";
        $stmtA = $conn->prepare($sqlAss);
        $stmtA->bind_param("sis", $a_name, $userId, $dept);
        $stmtA->execute();
        $msg = "Assessor created successfully!";
    }else{
        $msg = "Username already exists.";
    }
}

if(isset($_GET['delete'])){
    $aid = $_GET['delete'];
    $res = $conn->query("SELECT user_id FROM assessor WHERE assessor_id = '$aid'");
    $row = $res->fetch_assoc();
    $uid = $row['user_id'];
    $conn->query("DELETE FROM assessor WHERE assessor_id = '$aid'");
    $conn->query("DELETE FROM users WHERE user_id = '$uid'");
    header("Location: assessors.php");
    exit();
}

$assessors = $conn->query("SELECT a.*, u.username FROM assessor a JOIN users u ON a.user_id = u.user_id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Assessors</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container">
        <div class="nav">
            <a href="index.php">← Back</a>
            <a href="../logout.php" class="logout">Logout</a>
        </div>
        <h2>Manage Assessors</h2>
        <?php if($msg): ?>
            <p class="success"><?= $msg ?></p>
        <?php endif; ?>

        <h3>Add New Assessor</h3>
        <form method="post">
            <label>Username</label><input type="text" name="username" required>
            <label>Password</label><input type="password" name="password" required>
            <label>Assessor Name</label><input type="text" name="assessor_name" required>
            <label>Department</label><input type="text" name="department">
            <button name="add_assessor">Create Assessor</button>
        </form>

        <hr style="margin:25px 0;">
        <h3>Assessor List</h3>
        <table>
            <tr><th>ID</th><th>Name</th><th>Username</th><th>Department</th><th>Action</th></tr>
            <?php while($d = $assessors->fetch_assoc()): ?>
            <tr>
                <td><?= $d['assessor_id'] ?></td>
                <td><?= $d['assessor_name'] ?></td>
                <td><?= $d['username'] ?></td>
                <td><?= $d['department'] ?></td>
                <td><a href="assessors.php?delete=<?= $d['assessor_id'] ?>" onclick="return confirm('Delete?')" style="color:red;">Delete</a></td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>