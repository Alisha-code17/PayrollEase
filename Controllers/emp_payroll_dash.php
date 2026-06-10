<?php
session_start();
$conn = new mysqli("localhost", "root", "", "payrollease");

if ($conn->connect_error) {
    die(json_encode(['error' => 'Database connection failed']));
}

$employee_id = $_SESSION['Employee_id'] ?? null;

if (!$employee_id) {
    echo json_encode(['error' => 'Employee ID not found']);
    exit;
}

// Get previous month and corresponding year
$previousMonthDate = new DateTime('first day of last month');
$previousMonth = $previousMonthDate->format('F');  // e.g., March
$previousYear = $previousMonthDate->format('Y');   // e.g., 2025

$query = "SELECT BasicSalary, Bonus, TotalDeductions, NetSalary, TotalAllowance
          FROM payroll 
          WHERE Employee_id = ? AND PayrollMonth = ? AND Year = ? 
          LIMIT 1";

$stmt = $conn->prepare($query);
$stmt->bind_param("sss", $employee_id, $previousMonth, $previousYear);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['error' => 'Payroll not created yet']);
} else {
    echo json_encode($result->fetch_assoc());
}
?>
