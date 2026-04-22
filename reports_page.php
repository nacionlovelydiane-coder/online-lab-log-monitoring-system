<?php
session_start();
include 'config.php';

if (!isset($_SESSION['admin'])) {
    echo "Access Denied!";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Generate Reports</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-4">

    <nav class="navbar navbar-dark bg-dark rounded px-3 mb-4">
        <span class="navbar-brand mb-0 h1">Admin Panel</span>
        <div>
            <a href="index.php" class="btn btn-light btn-sm">Home</a>
            <a href="admin_dashboard.php" class="btn btn-light btn-sm">Dashboard</a>

            <?php if (isset($_SESSION['admin_role']) && $_SESSION['admin_role'] == 'admin'): ?>
                <a href="upload_masterlist.php" class="btn btn-light btn-sm">Upload</a>
                <a href="manage_admins.php" class="btn btn-warning btn-sm">Accounts</a>
            <?php endif; ?>

            <a href="reports_page.php" class="btn btn-light btn-sm">Reports</a>
            <a href="admin_logout.php" class="btn btn-danger btn-sm">Logout</a>
        </div>
    </nav>

    <div class="card shadow p-4">
        <h3 class="mb-3">Generate Laboratory Report</h3>
        <p class="text-muted">
            Select the report date and laboratory room to generate a printable student log sheet.
        </p>

        <form method="GET" action="report.php">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Date</label>
                    <input type="date" name="date" class="form-control" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Laboratory Room</label>
                    <select name="room" class="form-select">
                        <option value="ALL">All Rooms</option>
                        <option value="CCL 1">CCL 1</option>
                        <option value="CCL 2">CCL 2</option>
                    </select>
                </div>

                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        Generate Report
                    </button>
                </div>
            </div>
        </form>

        <hr>

        <div class="bg-light border rounded p-3 mt-2">
            <h6 class="mb-2">Included in the printable report:</h6>
            <ul class="mb-0">
                <li>Student name</li>
                <li>Assigned computer</li>
                <li>Time in</li>
                <li>Time out</li>
                <li>Remarks column</li>
            </ul>
        </div>
    </div>

</div>

</body>
</html>