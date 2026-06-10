<?php
include '../Database/db1.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $deductionID = mysqli_real_escape_string($conn, $_POST['deductionID']);
    
    // First check if it's safe to delete (additional server-side validation)
    $checkQuery = "SELECT Name FROM salarydeductions WHERE SalaryDeductions_id = '$deductionID'";
    $checkResult = mysqli_query($conn, $checkQuery);
    
    if (mysqli_num_rows($checkResult) === 0) {
        echo json_encode(['status' => 'error', 'message' => 'Deduction not found']);
        exit();
    }
    
    $deductionData = mysqli_fetch_assoc($checkResult);
    $deductionName = $deductionData['Name'];
    
    // Check if deduction is used in payroll profiles
    $usageQuery = "SELECT PayrollProfile_ID FROM payrollprofile 
                  WHERE Deduction1 = '$deductionName' 
                     OR Deduction2 = '$deductionName' 
                     OR Deduction3 = '$deductionName' 
                  LIMIT 1";
    $usageResult = mysqli_query($conn, $usageQuery);
    
    if (mysqli_num_rows($usageResult) > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Cannot delete deduction as it is used in payroll profiles']);
        exit();
    }
    
    // Proceed with deletion
    $deleteQuery = "DELETE FROM salarydeductions WHERE SalaryDeductions_id = '$deductionID'";
    
    if (mysqli_query($conn, $deleteQuery)) {
        echo json_encode(['status' => 'success', 'message' => 'Deduction deleted successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error deleting deduction']);
    }
    
    exit();
}
?>