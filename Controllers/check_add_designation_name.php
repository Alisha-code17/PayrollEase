<?php
include '../Database/db1.php';

if (isset($_POST['designationName'])) {
    $designationName = trim($_POST['designationName']);
    $designationName = strtolower($designationName);
    
    $checkQuery = "SELECT * FROM designation WHERE LOWER(Name) = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("s", $designationName);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(["exists" => true]);
    } else {
        echo json_encode(["exists" => false]);
    }
}
?>