<?php
session_start();
include 'config.php';

$student_id = $_SESSION['student_id'];

// Get active session
$get_session = $conn->prepare("SELECT computer_id FROM session_logs 
                               WHERE student_id=? AND status='active'");
$get_session->bind_param("s", $student_id);
$get_session->execute();
$result = $get_session->get_result();
$session = $result->fetch_assoc();
$computer_id = $session['computer_id'];

// Update session log
$update_sql = "UPDATE session_logs 
               SET logout_time = NOW(),
                   duration = TIMESTAMPDIFF(MINUTE, login_time, NOW()),
                   status = 'completed'
               WHERE student_id = ? AND status = 'active'";

$stmt = $conn->prepare($update_sql);
$stmt->bind_param("s", $student_id);
$stmt->execute();

// Make computer available again
$free_computer = $conn->prepare("UPDATE computers SET status='available' WHERE id=?");
$free_computer->bind_param("i", $computer_id);
$free_computer->execute();

session_destroy();
header("Location: login.php");
exit();
?>