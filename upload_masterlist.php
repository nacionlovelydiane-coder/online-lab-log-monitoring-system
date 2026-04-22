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

<h2>Upload Student Masterlist</h2>

<form method="POST" enctype="multipart/form-data">
    <input type="file" name="file" accept=".csv" required>
    <br><br>
    <button type="submit">Upload</button>
</form>