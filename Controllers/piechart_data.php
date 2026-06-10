<?php
include '../Database/db1.php'; // database connection

// Query to get total salaries from payroll
$query = "SELECT SUM(NetSalary) AS salaries FROM payroll";
$result = $conn->query($query);
$salaries = $result ? ($result->fetch_assoc()['salaries'] ?? 0) : 0;
if ($result) $result->free();

// Query to get total salaryextras
$query = "SELECT SUM(Amount) AS salaryextras FROM salaryextras";
$result = $conn->query($query);
$salaryextras = $result ? ($result->fetch_assoc()['salaryextras'] ?? 0) : 0;
if ($result) $result->free();

// Query to get total salarydeductions
$query = "SELECT SUM(Amount) AS salarydeductions FROM salarydeductions";
$result = $conn->query($query);
$salarydeductions = $result ? ($result->fetch_assoc()['salarydeductions'] ?? 0) : 0;
if ($result) $result->free();

// Close connection (optional)
$conn->close();
?>