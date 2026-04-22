<?php
session_start();
include 'config.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check admin login
if (!isset($_SESSION['admin'])) {
    echo "Access Denied!";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $file = $_FILES['file']['tmp_name'];

    if (($handle = fopen($file, "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

    	    if (count($data) < 4){
        		continue;
    	    }

    	    $student_id = str_replace('-', '', trim($data[0]));
    	    $first_name = trim($data[1]);
    	    $last_name = trim($data[2]);
    	    $course = trim($data[3]);

    	    // remove spaces in surnames like Dela Cruz -> delacruz
    	    $clean_lastname = strtolower(str_replace(' ','',$last_name));

    	    // password = student_id + surname
    	    $password = password_hash(
            	$student_id . $clean_lastname,
        	PASSWORD_DEFAULT
    	    );

    $stmt = $conn->prepare("
        INSERT INTO students
        (student_id, first_name, last_name, course, status, password)
        VALUES (?, ?, ?, ?, 'active', ?)
    ");

    $stmt->bind_param(
        "sssss",
        $student_id,
        $first_name,
        $last_name,
        $course,
        $password
    );

    $stmt->execute();
}

        fclose($handle);
        echo "<p style='color:green;'>Upload successful!</p>";
    } else {
        echo "Error reading file.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Upload Student Masterlist</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-4">

    <nav class="navbar navbar-dark bg-dark rounded px-3 mb-4">
        <span class="navbar-brand mb-0 h1">Admin Panel</span>
        <div>
            <a href="index.php" class="btn btn-light btn-sm">Home</a>
            <a href="admin_dashboard.php" class="btn btn-light btn-sm">Dashboard</a>
            <a href="reports_page.php" class="btn btn-light btn-sm">Reports</a>
            <a href="admin_logout.php" class="btn btn-danger btn-sm">Logout</a>
        </div>
    </nav>

    <div class="card shadow p-4">
        <h3 class="mb-3">Upload Student Masterlist</h3>
        <p class="text-muted">
            Upload a CSV file containing student ID, first name, last name, and course.
        </p>

        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Choose CSV File</label>
                <input type="file" name="file" accept=".csv" class="form-control" required>
            </div>

            <button type="submit" name="upload" class="btn btn-success">Upload CSV</button>
        </form>

        <hr>

        <h5>Required CSV Format</h5>
        <div class="bg-light border rounded p-3">
            <code>
                2021-001,Juan,Dela Cruz,BSIT<br>
                2021-002,Ana,Santos,BSIT
            </code>
        </div>

        <p class="mt-3 mb-0 text-muted">
            Generated student credentials:<br>
            Username = Student ID number<br>
            Password = Student ID number + surname
        </p>
    </div>

</div>

</body>
</html>