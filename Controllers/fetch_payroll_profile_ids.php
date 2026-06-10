<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include '../Database/db1.php'; 
header('Content-Type: application/json');

try {
    $sql = "SELECT PayrollProfile_ID FROM payrollprofile";
    $result = $conn->query($sql);

    if ($result) {
        $payroll_profile_ids = array();
        while ($row = $result->fetch_assoc()) {
            $payroll_profile_ids[] = $row;
        }
        
        echo json_encode($payroll_profile_ids);
        $result->free();
    } else {
        throw new Exception("Query failed");
    }
} catch (Exception $e) {
    echo json_encode(["error" => "Database error: " . $e->getMessage()]);
    exit;
}

$conn->close();
?>