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

<div style="width:100%; font-family:Arial, sans-serif;">

    <table width="100%" style="border-collapse:collapse;">
        <tr>
            <td width="18%" style="text-align:center;">
                <img src="images/logo.png" alt="CvSU Logo" style="width:90px; height:auto;">
            </td>

            <td width="64%" style="text-align:center;">
                <div style="font-size:14px;">Republic of the Philippines</div>
                <div style="font-size:26px; font-weight:bold; color:#0b6b2f;">CAVITE STATE UNIVERSITY-CARMONA</div>
                <div style="font-size:14px;">College of Engineering, Architecture and Technology</div>
                <div style="font-size:14px;">Department of Industrial and Information Technology</div>
            </td>

            <td width="18%"></td>
        </tr>
    </table>

    <hr style="margin-top:15px; margin-bottom:15px;">

    <div style="text-align:center; margin-bottom:15px;">
        <h2 style="margin:0; font-weight:bold;">COMPUTER LABORATORY STUDENT LOG</h2>
    </div>

    <table width="100%" style="margin-bottom:15px;">
        <tr>
            <td><b>Date:</b> <?php echo $date; ?></td>
            <td style="text-align:right;"><b>Room:</b> <?php echo $room; ?></td>
        </tr>
    </table>

    <table border="1" width="100%" cellpadding="8" cellspacing="0" style="border-collapse:collapse; font-size:14px;">
        <tr>
            <th>Name</th>
            <th>Computer</th>
            <th>Time In</th>
            <th>Time Out</th>
            <th>Remarks</th>
        </tr>

        <?php 
        $total_students = 0;
        while($row = $result->fetch_assoc()): 
            $total_students++;
        ?>
        <tr>
            <td><?= $row['first_name'] . " " . $row['last_name'] ?></td>
            <td><?= $row['computer_name'] ?></td>
            <td><?= date("h:i A", strtotime($row['login_time'])) ?></td>
            <td><?= $row['logout_time'] ? date("h:i A", strtotime($row['logout_time'])) : '-' ?></td>
            <td></td>
        </tr>
        <?php endwhile; ?>
    </table>

    <br><br>

    <table width="100%">
        <tr>
            <td><b>Total Students:</b> <?= $total_students ?></td>
            <td style="text-align:right;">Checked by: ________________________</td>
        </tr>
    </table>

</div>

</body>
</html>