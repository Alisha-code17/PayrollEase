<?php
include '../Database/db1.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $designationID = mysqli_real_escape_string($conn, $_POST['editDesignationID']);
    $designationName = mysqli_real_escape_string($conn, $_POST['editDesignationName']);
    $salary = mysqli_real_escape_string($conn, $_POST['editSalary']);
    $response = ['status' => 'error', 'message' => 'Something went wrong'];

    if (empty($designationName)) {
        $response['message'] = 'Designation name cannot be empty!';
        echo json_encode($response);
        exit();
    }
    if (strlen($designationName) > 50) {
        $response['message'] = 'Designation name must be under 50 characters!';
        echo json_encode($response);
        exit();
    }
    if (!preg_match("/^[a-zA-Z\s]+$/", $designationName)) {
        $response['message'] = 'Only letters and spaces allowed!';
        echo json_encode($response);
        exit();
    }

    $designationName = trim($designationName);
    $checkSql = "SELECT * FROM Designation WHERE Name = '$designationName' AND Designation_id != '$designationID'";
    $checkResult = $conn->query($checkSql);

    if ($checkResult->num_rows > 0) {
        $response['message'] = 'This Designation name already exists!';
        echo json_encode($response);
        exit();
    }
    if (empty($salary)) {
        $response['message'] = 'Salary cannot be empty!';
        echo json_encode($response);
        exit();
    }
    if (!preg_match("/^(?!0\d)\d+(\.\d{1,2})?$/", $salary)) {
        $response['message'] = 'Invalid salary format!';
        echo json_encode($response);
        exit();
    }
    if ($salary <= 0) {
        $response['message'] = 'Salary must be positive!';
        echo json_encode($response);
        exit();
    }

    $query = "UPDATE Designation SET Name = '$designationName', Salary = '$salary' WHERE Designation_id = '$designationID'";
    if (mysqli_query($conn, $query)) {
        $response = [
            'status' => 'success',
            'message' => 'Designation updated successfully!',
            'data' => [
                'id' => $designationID,
                'designationName' => ucfirst($designationName),
                'salary' => $salary
            ]
        ];
    } else {
        $response['message'] = 'Error updating designation!';
    }
    echo json_encode($response);
    exit();
}
?>