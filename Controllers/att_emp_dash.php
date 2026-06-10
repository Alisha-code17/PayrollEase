<?php
session_start();
include '../Database/db1.php';

$employee_id = $_SESSION['Employee_id'] ?? null;
if (!$employee_id) {
    echo json_encode(['error' => 'Employee ID not found']);
    exit;
}

// Get previous month name and year
$prevDate = new DateTime('first day of last month');
$previousMonth = $prevDate->format('F');  // e.g., March
$previousYear = $prevDate->format('Y');   // e.g., 2025
$monthNumber = $prevDate->format('n');    // Numeric month for cal_days_in_month

$totalDays = cal_days_in_month(CAL_GREGORIAN, $monthNumber, $previousYear);

$query = "SELECT Present_days, Absent_days FROM attendance WHERE Employee_id = ? AND Month = ? AND Year = ? LIMIT 1";
$stmt = $conn->prepare($query);
$stmt->bind_param("sss", $employee_id, $previousMonth, $previousYear);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode([
        'TotalDays' => $totalDays,
        'PresentDays' => 'Not created yet',
        'AbsentDays' => 'Not created yet',
        'AttendanceRate' => '0'
    ]);
} else {
    $row = $result->fetch_assoc();
    $present = (int)$row['Present_days'];
    $absent = (int)$row['Absent_days'];
    $rate = ($totalDays > 0) ? round(($present / $totalDays) * 100) : 0;

    echo json_encode([
        'TotalDays' => $totalDays,
        'PresentDays' => $present,
        'AbsentDays' => $absent,
        'AttendanceRate' => $rate
    ]);
}
?>
