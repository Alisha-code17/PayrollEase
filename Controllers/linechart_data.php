<?php
include '../Database/db1.php'; // database connection

// Query to get total earnings grouped by month from payroll table
$query = "
    SELECT PayrollMonth AS month_name, SUM(NetSalary) AS total_earnings 
    FROM payroll 
    GROUP BY PayrollMonth 
    ORDER BY FIELD(PayrollMonth, 'January', 'February', 'March', 'April', 'May', 'June', 
                              'July', 'August', 'September', 'October', 'November', 'December')
";

$result = $conn->query($query);

if ($result) {
    // Initialize arrays for labels (months) and data (earnings)
    $months = [];
    $earnings = [];

    while ($row = $result->fetch_assoc()) {
        $months[] = $row['month_name']; // Store month name (January, February, etc.)
        $earnings[] = $row['total_earnings']; // Store total earnings for that month
    }
    
    // Free result set
    $result->free();
} else {
    die("Error fetching earnings data: " . $conn->error);
}

// Close connection 
$conn->close();
?>