<?php
include '../Database/db1.php'; // MySQLi database connection

// Get the current month and year (unchanged)
$currentMonth = date('m') - 1; // Numeric format: 03 for March
$currentYear = date('Y');  // 2025

// Convert current month number to name format (March -> "March")
$currentMonthName = date("F", mktime(0, 0, 0, $currentMonth, 1));

// Fetch employees whose attendance for the current month is NOT marked
$sql = "SELECT e.Employee_id, 
               CONCAT(COALESCE(e.FirstName, ''), ' ', COALESCE(e.LastName, '')) AS EmployeeName
        FROM employee e
        WHERE e.Status = 'Active' 
        AND NOT EXISTS (
            SELECT 1 FROM attendance a
            WHERE a.Employee_id = e.Employee_id 
            AND LOWER(a.Month) = LOWER(?) 
            AND a.Year = ?
        )";

$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $currentMonthName, $currentYear);
$stmt->execute();
$result = $stmt->get_result();

$employees = array();
while ($row = $result->fetch_assoc()) {
    $employees[] = $row;
}

echo json_encode($employees);

$stmt->close();
$conn->close();
?>