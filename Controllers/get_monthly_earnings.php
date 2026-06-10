<?php
session_start();
include '../Database/db1.php';
// Get the current year
$currentYear = date("Y");

// Define full month names in order
$monthOrder = [
    'January', 'February', 'March', 'April', 'May', 'June',
    'July', 'August', 'September', 'October', 'November', 'December'
];

// Initialize array with 0 values
$data = array_fill_keys($monthOrder, 0);

$employee_id = $_SESSION['Employee_id'] ?? null;

if (!$employee_id) {
    die("Error: Employee ID not found in session.");
}

$query = "
    SELECT PayrollMonth AS month, SUM(NetSalary) AS total_salary
    FROM payroll
    WHERE Year = ? AND Employee_id = ?
    GROUP BY PayrollMonth
";

$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $currentYear, $employee_id);

$stmt->execute();
$result = $stmt->get_result();

// Store results in correct month slots
while ($row = $result->fetch_assoc()) {
    $month = $row['month'];
    if (isset($data[$month])) {
        $data[$month] = (float)$row['total_salary'];
    }
}

// Output data in correct order
echo json_encode(array_values($data));
?>
