<?php
include '../Database/db1.php';
$year = date('Y'); // Current year
$months = [
    "January", "February", "March", "April", "May", "June",
    "July", "August", "September", "October", "November", "December"
];

// Map month names to numbers for cal_days_in_month
$monthMap = [
    "January" => 1, "February" => 2, "March" => 3, "April" => 4,
    "May" => 5, "June" => 6, "July" => 7, "August" => 8,
    "September" => 9, "October" => 10, "November" => 11, "December" => 12
];

$data = [];

foreach ($months as $month) {
    $monthNum = $monthMap[$month];

    // Get total days in the month
    $totalDaysInMonth = cal_days_in_month(CAL_GREGORIAN, $monthNum, $year);

    // Get total present days
    $sql = "SELECT SUM(Present_Days) AS total_present FROM attendance WHERE Month = '$month' AND Year = '$year'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $total_present = $row['total_present'] ? (int)$row['total_present'] : 0;

    // Get employee count (distinct employees)
    $emp_sql = "SELECT COUNT(DISTINCT Employee_id) AS emp_count FROM attendance WHERE Month = '$month' AND Year = '$year'";
    $emp_result = $conn->query($emp_sql);
    $emp_row = $emp_result->fetch_assoc();
    $employee_count = $emp_row['emp_count'] ? (int)$emp_row['emp_count'] : 0;

    // Calculate total possible present days
    $total_possible_days = $totalDaysInMonth * $employee_count;

    // Calculate ratio (avoid division by zero)
    $present_ratio = $total_possible_days > 0 ? round(($total_present / $total_possible_days) * 100, 2) : 0;

    $data[] = $present_ratio;
}

echo json_encode($data);
?>
