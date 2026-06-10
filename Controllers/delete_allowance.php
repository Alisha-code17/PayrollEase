<?php
include '../Database/db1.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $allowanceID = mysqli_real_escape_string($conn, $_POST['allowanceID']);
    
    // First check if it's safe to delete (additional server-side validation)
    $checkQuery = "SELECT Name FROM salaryextras WHERE SalaryExtras_id = '$allowanceID'";
    $checkResult = mysqli_query($conn, $checkQuery);
    
    if (mysqli_num_rows($checkResult) === 0) {
        echo json_encode(['status' => 'error', 'message' => 'Allowance not found']);
        exit();
    }
    
    $allowanceData = mysqli_fetch_assoc($checkResult);
    $allowanceName = $allowanceData['Name'];
    
    // Check if allowance is used in payroll profiles
    $usageQuery = "SELECT PayrollProfile_ID FROM payrollprofile 
                  WHERE Allowance1 = '$allowanceName' 
                     OR Allowance2 = '$allowanceName' 
                     OR Allowance3 = '$allowanceName' 
                  LIMIT 1";
    $usageResult = mysqli_query($conn, $usageQuery);
    
    if (mysqli_num_rows($usageResult) > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Cannot delete allowance as it is used in payroll profiles']);
        exit();
    }
    
    // Proceed with deletion
    $deleteQuery = "DELETE FROM salaryextras WHERE SalaryExtras_id = '$allowanceID'";
    
    if (mysqli_query($conn, $deleteQuery)) {
        echo json_encode(['status' => 'success', 'message' => 'Allowance deleted successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error deleting allowance']);
    }
    
    exit();
}
?>