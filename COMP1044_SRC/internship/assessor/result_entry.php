<?php
include '../config.php';

// Only Assessor can access this page
if (!isset($_SESSION['role']) || $_SESSION['role'] != "Assessor") {
    header("Location: ../login.php");
    exit();
}

// Check whether internship_id is provided in the URL
if (!isset($_GET['internship_id'])) {
    die("No internship selected.");
}

$internshipid = $_GET['internship_id'];
$userid = $_SESSION['user_id'];

// Find current assessor information
$sqlassessor = "SELECT * FROM assessor WHERE user_id = '$userid'";
$resultassessor = $conn->query($sqlassessor);
$assessor = $resultassessor->fetch_assoc();

if (!$assessor) {
    die("Assessor record not found.");
}

$assessorid = $assessor['assessor_id'];
$assessorname = $assessor['assessor_name'];

// Find student and internship information
$sqlinfo = "
SELECT 
    i.internship_id,
    i.company_name,
    s.student_id,
    s.student_name,
    p.programme_name
FROM internship i
JOIN student s ON i.student_id = s.student_id
JOIN programme p ON s.programme_id = p.programme_id
WHERE i.internship_id = '$internshipid'
AND i.assessor_id = '$assessorid'
";

$resultinfo = $conn->query($sqlinfo);
$student = $resultinfo->fetch_assoc();

if (!$student) {
    die("This student is not assigned to you.");
}

$msg = "";

// When submit button is clicked
if (isset($_POST['submitresult'])) {

    // Get scores from the form
    $task = $_POST['task'];
    $safety = $_POST['safety'];
    $theory = $_POST['theory'];
    $report = $_POST['report'];
    $language = $_POST['language'];
    $learning = $_POST['learning'];
    $project = $_POST['project'];
    $timemg = $_POST['timemg'];

    // Get comment
    $comments = $_POST['comments'];

    // This prevents single quotes in comments from breaking SQL
    $comments = $conn->real_escape_string($comments);

    $date = date("Y-m-d");

    // Delete old scores first, so repeated submit will not create duplicate records
    $deletesql = "
    DELETE FROM assessment_score 
    WHERE internship_id = '$internshipid' 
    AND assessed_by = '$assessorid'
    ";
    $conn->query($deletesql);

    // Insert 8 scores into assessment_score table
    // criteria_id 1 to 8 match the assessment_criteria table

    $sql1 = "INSERT INTO assessment_score 
    (internship_id, criteria_id, score, comments, assessed_by, assessed_date)
    VALUES ('$internshipid', '1', '$task', '$comments', '$assessorid', '$date')";

    $sql2 = "INSERT INTO assessment_score 
    (internship_id, criteria_id, score, comments, assessed_by, assessed_date)
    VALUES ('$internshipid', '2', '$safety', '$comments', '$assessorid', '$date')";

    $sql3 = "INSERT INTO assessment_score 
    (internship_id, criteria_id, score, comments, assessed_by, assessed_date)
    VALUES ('$internshipid', '3', '$theory', '$comments', '$assessorid', '$date')";

    $sql4 = "INSERT INTO assessment_score 
    (internship_id, criteria_id, score, comments, assessed_by, assessed_date)
    VALUES ('$internshipid', '4', '$report', '$comments', '$assessorid', '$date')";

    $sql5 = "INSERT INTO assessment_score 
    (internship_id, criteria_id, score, comments, assessed_by, assessed_date)
    VALUES ('$internshipid', '5', '$language', '$comments', '$assessorid', '$date')";

    $sql6 = "INSERT INTO assessment_score 
    (internship_id, criteria_id, score, comments, assessed_by, assessed_date)
    VALUES ('$internshipid', '6', '$learning', '$comments', '$assessorid', '$date')";

    $sql7 = "INSERT INTO assessment_score 
    (internship_id, criteria_id, score, comments, assessed_by, assessed_date)
    VALUES ('$internshipid', '7', '$project', '$comments', '$assessorid', '$date')";

    $sql8 = "INSERT INTO assessment_score 
    (internship_id, criteria_id, score, comments, assessed_by, assessed_date)
    VALUES ('$internshipid', '8', '$timemg', '$comments', '$assessorid', '$date')";

    $ok = true;

    if (!$conn->query($sql1)) { $ok = false; }
    if (!$conn->query($sql2)) { $ok = false; }
    if (!$conn->query($sql3)) { $ok = false; }
    if (!$conn->query($sql4)) { $ok = false; }
    if (!$conn->query($sql5)) { $ok = false; }
    if (!$conn->query($sql6)) { $ok = false; }
    if (!$conn->query($sql7)) { $ok = false; }
    if (!$conn->query($sql8)) { $ok = false; }

    if ($ok) {
        $msg = "Result submitted successfully.";
    } else {
        $msg = "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="../style.css">
    <meta charset="UTF-8">
    <title>Enter Internship Result</title>
</head>
<body>

<h1>Internship Result Entry</h1>

<a href="students.php">Back to My Assigned Students</a> |
<a href="index.php">Dashboard</a> |
<a href="../logout.php">Logout</a>

<hr>

<?php if ($msg != ""): ?>
    <p style="color: green; font-weight: bold;">
        <?= $msg ?>
    </p>
<?php endif; ?>

<h2>Student Information</h2>

<p><strong>Student ID:</strong> <?= $student['student_id'] ?></p>
<p><strong>Student Name:</strong> <?= $student['student_name'] ?></p>
<p><strong>Programme:</strong> <?= $student['programme_name'] ?></p>
<p><strong>Company Name:</strong> <?= $student['company_name'] ?></p>
<p><strong>Assessor Name:</strong> <?= $assessorname ?></p>

<hr>

<form method="post">

    <label for="task">Undertaking Tasks/Projects:</label>
    <input type="number" id="task" name="task" min="0" max="100" required oninput="calscore()">
    <br><br>

    <label for="safety">Health and Safety Requirements at the Workplace:</label>
    <input type="number" id="safety" name="safety" min="0" max="100" required oninput="calscore()">
    <br><br>

    <label for="theory">Connectivity and Use of Theoretical Knowledge:</label>
    <input type="number" id="theory" name="theory" min="0" max="100" required oninput="calscore()">
    <br><br>

    <label for="report">Presentation of the Report as a Written Document:</label>
    <input type="number" id="report" name="report" min="0" max="100" required oninput="calscore()">
    <br><br>

    <label for="language">Clarity of Language and Illustration:</label>
    <input type="number" id="language" name="language" min="0" max="100" required oninput="calscore()">
    <br><br>

    <label for="learning">Lifelong Learning Activities:</label>
    <input type="number" id="learning" name="learning" min="0" max="100" required oninput="calscore()">
    <br><br>

    <label for="project">Project Management:</label>
    <input type="number" id="project" name="project" min="0" max="100" required oninput="calscore()">
    <br><br>

    <label for="timemg">Time Management:</label>
    <input type="number" id="timemg" name="timemg" min="0" max="100" required oninput="calscore()">
    <br><br>

    <label for="comments">Comments:</label><br>
    <textarea id="comments" name="comments" rows="5" cols="50"></textarea>
    <br><br>

    <label for="finalscore">Final Score:</label>
    <input type="text" id="finalscore" name="finalscore" readonly>
    <br><br>

    <input type="submit" name="submitresult" value="Submit Result">

</form>

<script>
function calscore() {
    let task = parseFloat(document.getElementById('task').value) || 0;
    let safety = parseFloat(document.getElementById('safety').value) || 0;
    let theory = parseFloat(document.getElementById('theory').value) || 0;
    let report = parseFloat(document.getElementById('report').value) || 0;
    let language = parseFloat(document.getElementById('language').value) || 0;
    let learning = parseFloat(document.getElementById('learning').value) || 0;
    let project = parseFloat(document.getElementById('project').value) || 0;
    let timemg = parseFloat(document.getElementById('timemg').value) || 0;

    let finalscore =
        (task * 0.10) +
        (safety * 0.10) +
        (theory * 0.10) +
        (report * 0.15) +
        (language * 0.10) +
        (learning * 0.15) +
        (project * 0.15) +
        (timemg * 0.15);

    document.getElementById('finalscore').value = finalscore.toFixed(2);
}
</script>

</body>
</html>