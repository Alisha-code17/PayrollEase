<?php
include '../Database/db1.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $allowanceName = mysqli_real_escape_string($conn, $_POST['allowanceName']);
    
    // Check if allowance exists in any of the Allowance columns in payrollprofile
    $query = "SELECT PayrollProfile_ID FROM payrollprofile 
              WHERE Allowance1 = '$allowanceName' 
                 OR Allowance2 = '$allowanceName' 
                 OR Allowance3 = '$allowanceName' 
              LIMIT 1";
    
    $result = mysqli_query($conn, $query);
    
    echo json_encode([
        'isUsed' => mysqli_num_rows($result) > 0
    ]);
    exit();
}
?>