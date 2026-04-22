<?php
include 'config.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Generate Reports</title>
</head>
<body>

<h2>Generate Lab Report</h2>

<form method="GET" action="report.php">
    
    Date:
    <input type="date" name="date" required>

    Lab Room:
    <select name="room">
        <option value="ALL">All</option>
        <option value="CCL 1">CCL 1</option>
        <option value="CCL 2">CCL 2</option>
    </select>

    <br><br>

    <button type="submit">Generate Report</button>

</form>

</body>
</html>