<?php
include '../Database/db1.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $allowanceID = mysqli_real_escape_string($conn, $_POST['editAllowanceID']);
    $allowanceName = mysqli_real_escape_string($conn, $_POST['editAllowanceName']);
    $description = mysqli_real_escape_string($conn, $_POST['editDescription']);
    $amount = mysqli_real_escape_string($conn, $_POST['editAmount']);

    // Initialize response
    $response = ['status' => 'error', 'message' => 'Something went wrong'];

    // Validation checks
    if (empty($allowanceName)) {
        $response['message'] = 'Allowance name cannot be empty!';
        echo json_encode($response);
        exit();
    }

    if (strlen($allowanceName) > 50) {
        $response['message'] = 'Allowance name must be under 50 characters!';
        echo json_encode($response);
        exit();
    }

    if (!preg_match("/^[a-zA-Z\s]+$/", $allowanceName)) {
        $response['message'] = 'Only letters and spaces allowed in allowance name!';
        echo json_encode($response);
        exit();
    }

    // Check for existing allowance name (case-sensitive)
    $allowanceName = trim($allowanceName);
    $checkSql = "SELECT * FROM salaryextras WHERE Name = '$allowanceName' AND SalaryExtras_id != '$allowanceID'";
    $checkResult = $conn->query($checkSql);

    if ($checkResult->num_rows > 0) {
        $response['message'] = 'This Allowance name already exists!';
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
        $response['message'] = 'Only letters and spaces allowed in allowance name!';
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

    // Update the allowance in database
    $query = "UPDATE salaryextras SET 
                Name = '$allowanceName', 
                Description = '$description', 
                Amount = '$amount' 
              WHERE SalaryExtras_id = '$allowanceID'";

    if (mysqli_query($conn, $query)) {
        // Success response
        $response = [
            'status' => 'success',
            'message' => 'Allowance updated successfully!',
            'data' => [
                'SalaryExtras_id' => $allowanceID,
                'Name' => ucfirst($allowanceName),
                'Description' => $description,
                'Amount' => number_format($amount, 2)
            ]
        ];
    } else {
        $response['message'] = 'Error updating allowance: ' . mysqli_error($conn);
    }

    // Return response
    echo json_encode($response);
    exit();
}
?>