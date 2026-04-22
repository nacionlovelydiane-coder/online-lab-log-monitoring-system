<?php
session_start();
include 'config.php';

// Auto cleanup inactive sessions (15 mins)
$conn->query("
UPDATE session_logs s
JOIN computers c ON s.computer_id = c.id
SET s.status='completed',
    s.logout_time=NOW(),
    s.duration=TIMESTAMPDIFF(MINUTE, s.login_time, NOW()),
    c.status='available'
WHERE s.status='active'
AND TIMESTAMPDIFF(MINUTE, s.last_activity, NOW()) >= 15
");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $student_id = $_POST['student_id'];
    $password = $_POST['password'];
    $computer_id = $_POST['computer_id'];

    $sql = "SELECT * FROM students WHERE student_id = ? AND status='active'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $student = $result->fetch_assoc();

        if (password_verify($password, $student['password'])) {

            // Check active session
            $check = $conn->prepare("SELECT * FROM session_logs WHERE student_id=? AND status='active'");
            $check->bind_param("s", $student_id);
            $check->execute();
            $check_result = $check->get_result();

            if ($check_result->num_rows > 0) {
                $error = "You are already logged in!";
            } else {

                // Check computer availability
                $comp = $conn->prepare("SELECT status FROM computers WHERE id=?");
                $comp->bind_param("i", $computer_id);
                $comp->execute();
                $comp_result = $comp->get_result();
                $computer = $comp_result->fetch_assoc();

                if ($computer['status'] == 'in_use') {
                    $error = "Computer is already in use!";
                } else {

                    $_SESSION['student_id'] = $student_id;

                    $insert = $conn->prepare("INSERT INTO session_logs (student_id, computer_id, login_time, last_activity)
                                              VALUES (?, ?, NOW(), NOW())");
                    $insert->bind_param("si", $student_id, $computer_id);
                    $insert->execute();

                    $update = $conn->prepare("UPDATE computers SET status='in_use' WHERE id=?");
                    $update->bind_param("i", $computer_id);
                    $update->execute();

                    header("Location: dashboard.php");
                    exit();
                }
            }
        } else {
            $error = "Invalid Password!";
        }
    } else {
        $error = "Student not found!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Online Lab Log System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card shadow p-4" style="width: 400px;">
        <h3 class="text-center mb-4">Online Lab Log System</h3>

        <?php if(isset($error)) { ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php } ?>

        <form method="POST">

            <div class="mb-3">
                <label class="form-label">Student ID</label>
                <input type="text" name="student_id" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Select Computer</label>
                <select name="computer_id" class="form-select" required>
                    <?php
                    $computers = $conn->query("SELECT * FROM computers WHERE status='available'");
                    while ($row = $computers->fetch_assoc()) {
                        echo "<option value='{$row['id']}'>{$row['computer_name']} - {$row['lab_room']}</option>";
                    }
                    ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>
    </div>
</div>

</body>
</html>