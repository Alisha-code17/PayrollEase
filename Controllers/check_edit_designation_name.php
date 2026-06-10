<?php
include '../Database/db1.php';

if (isset($_POST['designationName']) && isset($_POST['id'])) {
    $designationName = trim($_POST['designationName']);
    $id = intval($_POST['id']);
    $designationName = strtolower($designationName);  

    $checkQuery = "SELECT * FROM Designation WHERE Name = ? AND Designation_id != ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("si", $designationName, $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(["exists" => true]);
    } else {
        echo json_encode(["exists" => false]);
    }
}
?>