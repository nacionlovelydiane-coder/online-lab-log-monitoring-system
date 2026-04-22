<?php
session_start();
include 'config.php';

// Only admin can access
if ($_SESSION['admin_role'] != 'admin') {
    echo "Access Denied!";
    exit();
}

// CREATE ACCOUNT
if (isset($_POST['create'])) {

    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $stmt = $conn->prepare("INSERT INTO admins (username, password, role) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $password, $role);
    $stmt->execute();

    echo "Account created successfully!";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Accounts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-4">

    <nav class="navbar navbar-dark bg-dark rounded px-3 mb-4">
        <span class="navbar-brand mb-0 h1">Admin Panel</span>
        <div>
            <a href="index.php" class="btn btn-light btn-sm">Home</a>
            <a href="admin_dashboard.php" class="btn btn-light btn-sm">Dashboard</a>
            <a href="upload_masterlist.php" class="btn btn-light btn-sm">Upload</a>
            <a href="reports_page.php" class="btn btn-light btn-sm">Reports</a>
            <a href="admin_logout.php" class="btn btn-danger btn-sm">Logout</a>
        </div>
    </nav>

    <div class="row g-4">

        <div class="col-md-5">
            <div class="card shadow p-4">
                <h3 class="mb-3">Create Account</h3>
                <p class="text-muted">Create an admin or instructor account.</p>

                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-select">
                            <option value="admin">Admin</option>
                            <option value="instructor">Instructor</option>
                        </select>
                    </div>

                    <button type="submit" name="create" class="btn btn-primary w-100">
                        Create Account
                    </button>
                </form>
            </div>
        </div>

        <div class="col-md-7">
            <div class="card shadow p-4">
                <h3 class="mb-3">Existing Accounts</h3>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Username</th>
                                <th>Role</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $result = $conn->query("SELECT username, role FROM admins");

                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                                        <td>{$row['username']}</td>
                                        <td>{$row['role']}</td>
                                      </tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

    </div>

</div>

</body>
</html>