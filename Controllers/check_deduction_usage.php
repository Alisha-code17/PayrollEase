<?php
include '../Database/db1.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $deductionName = mysqli_real_escape_string($conn, $_POST['deductionName']);
    
    // Check if deduction exists in any of the Deduction columns in payrollprofile
    $query = "SELECT PayrollProfile_ID FROM payrollprofile 
              WHERE Deduction1 = '$deductionName' 
                 OR Deduction2 = '$deductionName' 
                 OR Deduction3 = '$deductionName' 
              LIMIT 1";
    
    $result = mysqli_query($conn, $query);
    
    echo json_encode([
        'isUsed' => mysqli_num_rows($result) > 0
    ]);
    exit();
}
?>