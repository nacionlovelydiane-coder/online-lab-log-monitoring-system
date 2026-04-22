<?php
include 'config.php';

$date = $_GET['date'];
$room = $_GET['room'];

// Base query
$sql = "
SELECT s.student_id,
       st.first_name,
       st.last_name,
       c.computer_name,
       c.lab_room,
       s.login_time,
       s.logout_time
FROM session_logs s
JOIN students st ON s.student_id = st.student_id
JOIN computers c ON s.computer_id = c.id
WHERE DATE(s.login_time) = '$date'
AND s.status='completed'
";

// Filter by room
if ($room != "ALL") {
    $sql .= " AND c.lab_room='$room'";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Lab Report</title>
</head>

<body onload="window.print()">

<h2 style="text-align:center;">COMPUTER LABORATORY STUDENT LOG</h2>

<p><b>Date:</b> <?php echo $date; ?></p>
<p><b>Room:</b> <?php echo $room; ?></p>

<table border="1" width="100%" cellpadding="8">
<tr>
    <th>Name</th>
    <th>Computer</th>
    <th>Time In</th>
    <th>Time Out</th>
</tr>

<?php while($row = $result->fetch_assoc()): ?>
<tr>
    <td><?= $row['first_name'] . " " . $row['last_name'] ?></td>
    <td><?= $row['computer_name'] ?></td>
    <td><?= date("h:i A", strtotime($row['login_time'])) ?></td>
    <td><?= $row['logout_time'] ? date("h:i A", strtotime($row['logout_time'])) : '-' ?></td>
</tr>
<?php endwhile; ?>

</table>

<br><br>

<p>Checked by: __________________________</p>

</body>
</html>