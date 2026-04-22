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
    <title>Manage Admins</title>
</head>
<body>

<h2>Create Admin / Instructor Account</h2>

<form method="POST">
    Username:<br>
    <input type="text" name="username" required><br><br>

    Password:<br>
    <input type="password" name="password" required><br><br>

    Role:<br>
    <select name="role">
        <option value="admin">Admin</option>
        <option value="instructor">Instructor</option>
    </select><br><br>

    <button type="submit" name="create">Create Account</button>
</form>

<hr>

<h3>Existing Accounts</h3>

<table border="1">
<tr>
    <th>Username</th>
    <th>Role</th>
</tr>

<?php
$result = $conn->query("SELECT * FROM admins");

while ($row = $result->fetch_assoc()) {
    echo "<tr>
            <td>{$row['username']}</td>
            <td>{$row['role']}</td>
          </tr>";
}
?>

</table>

</body>
</html>