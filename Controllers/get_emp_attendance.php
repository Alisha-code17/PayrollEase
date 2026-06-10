<?php
include '../Database/db1.php';
session_start();

$employeeID = isset($_SESSION['Employee_id']) ? intval($_SESSION['Employee_id']) : 0;
$year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');

$response = [
    'summary' => ['present' => 0, 'absent' => 0, 'overtime' => 0],
    'attendance' => []
];

$summaryQuery = "SELECT 
                    SUM(Present_days) AS PresentDays,
                    SUM(Absent_days) AS AbsentDays,
                    SUM(Overtime_hours) AS OvertimeHours
                FROM attendance 
                WHERE Employee_id = $employeeID AND Year = $year";
$summaryResult = mysqli_query($conn, $summaryQuery);
if ($summaryRow = mysqli_fetch_assoc($summaryResult)) {
    $response['summary'] = [
        'present' => $summaryRow['PresentDays'] ?? 0,
        'absent' => $summaryRow['AbsentDays'] ?? 0,
        'overtime' => $summaryRow['OvertimeHours'] ?? 0
    ];
}

$attendanceQuery = "SELECT 
                        Month,
                        SUM(Present_days) AS PresentDays,
                        SUM(Absent_days) AS AbsentDays,
                        SUM(Overtime_hours) AS OvertimeHours
                    FROM attendance 
                    WHERE Employee_id = $employeeID AND Year = $year
                    GROUP BY Month";
$attendanceResult = mysqli_query($conn, $attendanceQuery);

while ($row = mysqli_fetch_assoc($attendanceResult)) {
    $response['attendance'][] = $row;
}
echo json_encode($response);
?>