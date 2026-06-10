<?php
// Database Connection
include '../Database/db1.php';
// Query to get Employee IDs
$sql = "SELECT DISTINCT Employee_id FROM payroll";
$result = $conn->query($sql);

$employee_ids_payslip = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $employee_ids_payslip[] = $row;
    }
}

echo json_encode($employee_ids_payslip);
?>
