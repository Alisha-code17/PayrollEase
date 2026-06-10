<?php
// fetch_payroll_profile_details.php

// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Database connection
include '../Database/db1.php'; // MySQLi database connection

// Set content type header once at the top
header('Content-Type: application/json');

// Check if payrollProfileID is set
if (isset($_GET['payrollProfileID'])) {
    try {
        // Use the correct key name from the GET array
        $payrollprofile_id = (int)$_GET['payrollProfileID'];
        
        // Prepare SQL query
        $sql = "SELECT Allowance1, Allowance1_Amount, Allowance2, Allowance2_Amount, Allowance3, Allowance3_Amount, 
                       Deduction1, Deduction1_Amount, Deduction2, Deduction2_Amount, Deduction3, Deduction3_Amount 
                FROM payrollprofile
                WHERE PayrollProfile_ID = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $payrollprofile_id);
        $stmt->execute();
        $result = $stmt->get_result();

        // Fetch results
        $row = $result->fetch_assoc();

        if ($row) {
            // Return JSON data
            echo json_encode($row);
        } else {
            // No profile found
            echo json_encode(["error" => "No payroll profile found"]);
        }
        
        $stmt->close();
    } catch (Exception $e) {
        // Handle database errors
        echo json_encode(["error" => "Database error: " . $e->getMessage()]);
    }
} else {
    // Missing parameter
    echo json_encode(["error" => "Missing payrollProfileID parameter"]);
}

// Close connection
$conn->close();
?>