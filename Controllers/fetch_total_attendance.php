<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

include '../Database/db1.php'; // MySQLi database connection

if (isset($_GET['employee_id'], $_GET['payrollMonth'], $_GET['payrollYear'])) {
    $employeeId = (int)$_GET['employee_id'];
    $payrollMonth = $_GET['payrollMonth'];
    $payrollYear = $_GET['payrollYear'];

    $sql = "SELECT Present_days, Absent_days, Overtime_hours 
            FROM attendance 
            WHERE Employee_id = ? 
              AND Month = ? 
              AND Year = ?
            ORDER BY Attendance_id DESC 
            LIMIT 1";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isi", $employeeId, $payrollMonth, $payrollYear);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $row = $result->fetch_assoc();

    if ($row) {
        echo json_encode($row);
    } else {
        echo json_encode(["error" => "No attendance data found for Employee ID $employeeId in $payrollMonth $payrollYear"]);
    }

    $stmt->close();
} else {
    echo json_encode(["error" => "Missing required parameters (Employee ID, Payroll Month, or Payroll Year)"]);
}

$conn->close(); // Close the connection
?>