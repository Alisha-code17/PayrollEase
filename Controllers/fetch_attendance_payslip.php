<?php
include '../Database/db1.php';

$emp_ID_payslip = $_GET['emp_ID_payslip'];
$month = $_GET['month'];
$year = $_GET['year'];

$sql = "SELECT 
            PresentDays, 
            AbsentDays 
        FROM payroll 
        WHERE Employee_id = '$emp_ID_payslip' 
        AND PayrollMonth = '$month' 
        AND Year = '$year'";

$result = $conn->query($sql);

// Calculate total days in the given month/year
$monthNumber = date('n', strtotime($month)); // Converts "March" -> 3
$totalDays = cal_days_in_month(CAL_GREGORIAN, $monthNumber, $year);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    echo json_encode([
        "Total_Days" => $totalDays,
        "Present_Days" => $row['PresentDays'],
        "Absent_Days" => $row['AbsentDays']
    ]);
} else {
    echo json_encode([
        "Total_Days" => $totalDays,
        "Present_Days" => 0,
        "Absent_Days" => 0
    ]);
}

$conn->close();
?>
