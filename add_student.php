<?php
include 'config.php';

// Sample student from registrar masterlist
$student_id = "2024001";
$first_name = "Juan";
$last_name = "DelaCruz";
$course = "BSIT";
$year = 3;

// Default password format: studentID + lastName
$raw_password = $student_id . $last_name;

// HASH PASSWORD (IMPORTANT FOR SECURITY)
$hashed_password = password_hash($raw_password, PASSWORD_DEFAULT);

$sql = "INSERT INTO students 
(student_id, first_name, last_name, course, year_level, password)
VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssis", $student_id, $first_name, $last_name, $course, $year, $hashed_password);
$stmt->execute();

echo "Student inserted successfully!";
?>