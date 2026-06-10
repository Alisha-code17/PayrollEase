<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../Database/db1.php'; 
    
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $allowance1 = ($_POST['allowance1'] === 'Select' || empty($_POST['allowance1'])) ? null : $_POST['allowance1'];
        $allowance1Amount = $_POST['allowance1_Amount'] ?? null;
        $allowance2 = ($_POST['allowance2'] === 'Select' || empty($_POST['allowance2'])) ? null : $_POST['allowance2'];
        $allowance2Amount = $_POST['allowance2_Amount'] ?? null;
        $allowance3 = ($_POST['allowance3'] === 'Select' || empty($_POST['allowance3'])) ? null : $_POST['allowance3'];
        $allowance3Amount = $_POST['allowance3_Amount'] ?? null;
        
        $deduction1 = ($_POST['deduction1'] === 'Select' || empty($_POST['deduction1'])) ? null : $_POST['deduction1'];
        $deduction1Amount = $_POST['deduction1_Amount'] ?? null;
        $deduction2 = ($_POST['deduction2'] === 'Select' || empty($_POST['deduction2'])) ? null : $_POST['deduction2'];
        $deduction2Amount = $_POST['deduction2_Amount'] ?? null;
        $deduction3 = ($_POST['deduction3'] === 'Select' || empty($_POST['deduction3'])) ? null : $_POST['deduction3'];
        $deduction3Amount = $_POST['deduction3_Amount'] ?? null;
            
        $allowanceSelected = !empty($allowance1) || !empty($allowance2) || !empty($allowance3);
        $deductionSelected = !empty($deduction1) || !empty($deduction2) || !empty($deduction3);
        if (!$allowanceSelected || !$deductionSelected) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Please select at least one allowance and one deduction.'
            ]);
            exit;
        }

$sql = "INSERT INTO PayrollProfile 
(Allowance1, Allowance1_Amount, Allowance2, Allowance2_Amount, Allowance3, Allowance3_Amount, 
Deduction1, Deduction1_Amount, Deduction2, Deduction2_Amount, Deduction3, Deduction3_Amount) 
VALUES ('$allowance1', '$allowance1Amount', '$allowance2', '$allowance2Amount', '$allowance3', '$allowance3Amount', 
        '$deduction1', '$deduction1Amount', '$deduction2', '$deduction2Amount', '$deduction3', '$deduction3Amount')";
    if ($conn->query($sql) === TRUE) {
    $last_id = $conn->insert_id;
        echo json_encode([
            'status' => 'success',
            'message' => 'Payroll profile saved successfully!',
            'payrollProfileId' => $last_id
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error saving payroll profile.'
        ]);
    }
}
?>