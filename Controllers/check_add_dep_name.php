<?php
include '../Database/db1.php';

if (isset($_POST['departmentName'])) {
    $departmentName = trim($_POST['departmentName']);
    $departmentName = strtolower($departmentName);
    
    $checkQuery = "SELECT * FROM Department WHERE Name = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("s", $departmentName);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(["exists" => true]);
    } else {
        echo json_encode(["exists" => false]);
    }
}
?>