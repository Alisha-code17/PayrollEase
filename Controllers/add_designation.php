<?php
include '../Database/db1.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $designationName = mysqli_real_escape_string($conn, $_POST['designationName']);
    $salary = mysqli_real_escape_string($conn, $_POST['salary']);
    if (empty($designationName) || empty($salary)) {
        die('All fields are required.');
    }

    $query = "INSERT INTO designation (Name, Salary) VALUES ('$designationName', '$salary')";
    if (mysqli_query($conn, $query)) {
        $last_id = mysqli_insert_id($conn); 
        echo json_encode([
            'status' => 'success',
            'data' => [
                'id' => $last_id,
               'designationName' => ucfirst($designationName), 
                'salary' => $salary
            ]
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to add designation.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
}
?>