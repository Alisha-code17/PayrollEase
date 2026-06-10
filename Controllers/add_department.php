<?php
include '../Database/db1.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $departmentName = trim($_POST['departmentName']);

    if (strlen($departmentName) > 50) {
        echo json_encode(['status' => 'error', 'message' => 'Department name should not exceed 50 characters!']);
        exit;
    }
    if (!preg_match("/^[a-zA-Z\s]+$/", $departmentName)) {
        echo json_encode(['status' => 'error', 'message' => 'Only letters and spaces allowed!']);
        exit;
    }

    $checkQuery = "SELECT * FROM Department WHERE Name = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("s", $departmentName);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Department already exists!']);
    } else {
        $insertQuery = "INSERT INTO Department (Name) VALUES (?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("s", $departmentName);

        if ($stmt->execute()) {
            $newDepartmentID = $conn->insert_id;
            echo json_encode([
                'status' => 'success',
                'message' => 'Department added successfully!',
                'data' => [
                    'id' => $newDepartmentID,
                    'departmentId' => $newDepartmentID, 
                    'departmentName' => ucfirst($departmentName),
                    'totalEmployees' => 0 
                ]
            ]);            
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error adding department!']);
        }
    }
    $stmt->close();
    $conn->close();
}
?>