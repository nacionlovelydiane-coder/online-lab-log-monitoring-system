<?php
session_start();
include 'config.php';

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['student_id'];

// Get active session
$sql = $conn->prepare("
SELECT s.*, c.computer_name, c.lab_room
FROM session_logs s
JOIN computers c ON s.computer_id = c.id
WHERE s.student_id=? AND s.status='active'
");

if (!$sql) {
    die("SQL Error: " . $conn->error);
}

$sql->bind_param("s", $student_id);
$sql->execute();
$result = $sql->get_result();
$data = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow p-4">
        <h3 class="mb-3">Welcome, <?php echo $student_id; ?> 👋</h3>
        <div class="row">
            <div class="col-md-6">
                <p><strong>Student ID:</strong> <?php echo $student_id; ?></p>
                <p><strong>Computer:</strong> <?php echo $data['computer_name']; ?></p>
                <p><strong>Lab Room:</strong> <?php echo $data['lab_room']; ?></p>
            </div>

            <div class="col-md-6">
                <p><strong>Login Time:</strong> <?php echo $data['login_time']; ?></p>
                <p><strong>Status:</strong> <span class="badge bg-success">Active</span></p>
                <p><strong>Session Duration:</strong>
                    <span id="timer"></span>
                </p>
            </div>
        </div>

        <hr>

        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
</div>

<script>
let loginTime = new Date("<?php echo $data['login_time']; ?>").getTime();

setInterval(function() {
    let now = new Date().getTime();
    let diff = now - loginTime;

    let minutes = Math.floor(diff / (1000 * 60));
    let seconds = Math.floor((diff % (1000 * 60)) / 1000);

    document.getElementById("timer").innerHTML =
        minutes + " minutes " + seconds + " seconds";
}, 1000);
</script>

</body>
</html>