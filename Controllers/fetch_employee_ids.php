<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

include '../Database/db1.php'; // MySQLi database connection

// Get current month and year for payroll calculation
$currentMonth = date('m') - 1; // Previous month
$currentYear = date('Y');
$currentMonthName = date("F", mktime(0, 0, 0, $currentMonth, 1)); // Full month name

try {
    // Fetch employee IDs who don't have payroll calculated for current month
    $sql = "SELECT e.Employee_id 
            FROM employee e
            WHERE e.Status = 'Active'
            AND NOT EXISTS (
                SELECT 1 FROM payroll p
                WHERE p.Employee_ID = e.Employee_id
                AND p.PayrollMonth = ?
                AND p.Year = ?
            )";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $currentMonthName, $currentYear);
    $stmt->execute();
    $result = $stmt->get_result();

    $employee_ids = array();
    while ($row = $result->fetch_assoc()) {
        $employee_ids[] = $row;
    }

    // Return the results as JSON
    echo json_encode($employee_ids);
    
    $stmt->close();
} catch (Exception $e) {
    // Handle errors gracefully
    echo json_encode(["error" => "Database error: " . $e->getMessage()]);
    exit;
}

$conn->close();
?>