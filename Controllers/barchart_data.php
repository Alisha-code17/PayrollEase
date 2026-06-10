<?php
include '../Database/db1.php'; // database connection

// Query to fetch total salaries for each employee with concatenated names
$query = "
    SELECT CONCAT(employee.FirstName, ' ', COALESCE(employee.LastName, '')) AS FullName, 
           SUM(payroll.NetSalary) AS total_earnings
    FROM payroll
    JOIN employee ON payroll.Employee_ID = employee.Employee_ID
    GROUP BY payroll.Employee_ID
    ORDER BY total_earnings DESC
";

$result = $conn->query($query);

if ($result) {
    // Initialize arrays for data
    $employeeNames = [];
    $earnings = [];
    
    // Fetch and process data
    while ($row = $result->fetch_assoc()) {
        $employeeNames[] = $row['FullName'];
        $earnings[] = $row['total_earnings'];
    }
    
    // Free result set
    $result->free();
} else {
    die("Error: " . $conn->error);
}

// Close connection 
$conn->close();
?>