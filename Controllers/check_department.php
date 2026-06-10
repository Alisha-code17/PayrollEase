<?php
require '../Database/db1.php';

if (isset($_GET['department_id'])) {
    $departmentId = $_GET['department_id'];

    $query = "SELECT COUNT(*) AS employee_count FROM employee WHERE department_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $departmentId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    if ($row['employee_count'] > 0) {
        echo json_encode(["exists" => true]);
    } else {
        echo json_encode(["exists" => false]);
    }
    $stmt->close();
    $conn->close();
}
?>