<?php
// Database Connection
include '../Database/db1.php';
// Query to get distinct Years from payroll table
$sql = "SELECT DISTINCT Year FROM payroll ORDER BY Year DESC";
$result = $conn->query($sql);

$years = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $years[] = $row;
    }
}

echo json_encode($years);
?>
