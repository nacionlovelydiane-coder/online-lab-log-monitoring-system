<?php
session_start();
include 'config.php';

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta http-equiv="refresh" content="5">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-dark mb-4">
    <div class="container">
        <span class="navbar-brand">Admin Panel</span>
        <div>

            <a href="admin_dashboard.php" class="btn btn-light btn-sm">Dashboard</a>

            <?php if ($_SESSION['admin_role'] == 'admin'): ?>
                <a href="upload_masterlist.php" class="btn btn-light btn-sm">Upload</a>
                <a href="manage_admins.php" class="btn btn-warning btn-sm">Accounts</a>
            <?php endif; ?>

            <a href="reports_page.php" class="btn btn-light btn-sm">Reports</a>
            <a href="admin_logout.php" class="btn btn-danger btn-sm">Logout</a>

        </div>
    </div>
</nav>

<div class="container mt-5">
    <h2 class="mb-4">Admin Monitoring Dashboard</h2>

    <div class="card shadow mb-4">
        <div class="card-header bg-primary text-white">
            Currently Logged In Students
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <tr>
                    <th>Student ID</th>
                    <th>Computer</th>
                    <th>Login Time</th>
                </tr>

                <?php
                $sql = "SELECT session_logs.student_id,
               		students.first_name,
               		students.last_name,
               		computers.computer_name,
               		session_logs.login_time
        	FROM session_logs
        	JOIN computers ON session_logs.computer_id = computers.id
        	JOIN students ON session_logs.student_id = students.student_id
        	WHERE session_logs.status = 'active'";

                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['student_id']}</td>
                                <td>{$row['computer_name']}</td>
                                <td>{$row['login_time']}</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>No active sessions</td></tr>";
                }
                ?>
            </table>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-header bg-secondary text-white">
            Computer Status
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <tr>
                    <th>Computer</th>
                    <th>Status</th>
                </tr>

                <?php
                $comp_sql = "SELECT * FROM computers";
                $comp_result = $conn->query($comp_sql);

                while ($comp = $comp_result->fetch_assoc()) {
                    $badge = $comp['status'] == 'available' 
                        ? "<span class='badge bg-success'>Available</span>" 
                        : "<span class='badge bg-danger'>In Use</span>";

                    echo "<tr>
                            <td>{$comp['computer_name']}</td>
                            <td>$badge</td>
                          </tr>";
                }
                ?>
            </table>
        </div>
    </div>

</div>

</body>
</html>