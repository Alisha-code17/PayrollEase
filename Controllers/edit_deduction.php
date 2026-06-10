<?php
include '../Database/db1.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $deductionID = mysqli_real_escape_string($conn, $_POST['editDeductionID']);
    $deductionName = mysqli_real_escape_string($conn, $_POST['editDeductionName']);
    $description = mysqli_real_escape_string($conn, $_POST['editDescription1']);
    $amount = mysqli_real_escape_string($conn, $_POST['editAmount1']);

    // Initialize response
    $response = ['status' => 'error', 'message' => 'Something went wrong'];

    // Validation checks
    if (empty($deductionName)) {
        $response['message'] = 'Deduction name cannot be empty!';
        echo json_encode($response);
        exit();
    }

    if (strlen($deductionName) > 50) {
        $response['message'] = 'Deduction name must be under 50 characters!';
        echo json_encode($response);
        exit();
    }

    if (!preg_match("/^[a-zA-Z\s]+$/", $deductionName)) {
        $response['message'] = 'Only letters and spaces allowed in deduction name!';
        echo json_encode($response);
        exit();
    }

    // Check for existing deduction name (case-sensitive)
    $deductionName = trim($deductionName);
    $checkSql = "SELECT * FROM salarydeductions WHERE Name = '$deductionName' AND SalaryDeductions_id != '$deductionID'";
    $checkResult = $conn->query($checkSql);

    if ($checkResult->num_rows > 0) {
        $response['message'] = 'This Deduction name already exists!';
        echo json_encode($response);
        exit();
    }

    // Description validation (optional field)
    if (strlen($description) > 255) {
        $response['message'] = 'Description must be under 255 characters!';
        echo json_encode($response);
        exit();
    }
    if (!preg_match("/^[a-zA-Z\s]+$/", $description)) {
        $response['message'] = 'Only letters and spaces allowed in deduction name!';
        echo json_encode($response);
        exit();
    }


    // Amount validation
    if (empty($amount)) {
        $response['message'] = 'Amount cannot be empty!';
        echo json_encode($response);
        exit();
    }

    if (!preg_match("/^(?!0\d)\d+(\.\d{1,2})?$/", $amount)) {
        $response['message'] = 'Invalid amount format! Use numbers with up to 2 decimal places.';
        echo json_encode($response);
        exit();
    }

    if ($amount <= 0) {
        $response['message'] = 'Amount must be positive!';
        echo json_encode($response);
        exit();
    }

    // Update the deduction in database
    $query = "UPDATE salarydeductions SET 
                Name = '$deductionName', 
                Description = '$description', 
                Amount = '$amount' 
              WHERE SalaryDeductions_id = '$deductionID'";

    if (mysqli_query($conn, $query)) {
        // Success response
        $response = [
            'status' => 'success',
            'message' => 'Deduction updated successfully!',
            'data' => [
                'SalaryDeductions_id' => $deductionID,
                'Name' => ucfirst($deductionName),
                'Description' => $description,
                'Amount' => number_format($amount, 2)
            ]
        ];
    } else {
        $response['message'] = 'Error updating deduction: ' . mysqli_error($conn);
    }

    // Return response
    echo json_encode($response);
    exit();
}
?>