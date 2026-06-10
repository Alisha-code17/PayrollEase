<?php
include '../Database/db1.php';

if (isset($_POST['departmentName']) && isset($_POST['id'])) {
    $departmentName = trim($_POST['departmentName']);
    $id = intval($_POST['id']);
    $departmentName = strtolower($departmentName);  
   
    $checkQuery = "SELECT * FROM Department WHERE Name = ? AND Department_id != ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("si", $departmentName, $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(["exists" => true]);
    } else {
        echo json_encode(["exists" => false]);
    }
}
?>