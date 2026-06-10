<?php
require '../Database/db1.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['department_id'])) {
    $departmentId = $_POST['department_id'];
    if (empty($departmentId)) {
        echo json_encode(["status" => "error", "message" => "Department ID missing"]);
        exit();
    }
    // Check if emp exist in this dept
    $checkQuery = "SELECT COUNT(*) AS employee_count FROM employee WHERE department_id = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("i", $departmentId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $employeeCount = $row['employee_count'];

    if ($employeeCount > 0) {
        echo json_encode(["status" => "error", "message" => "Cannot delete! Employees exist in this department."]);
    } else {
        $deleteQuery = "DELETE FROM department WHERE Department_id = ?";
        $stmt = $conn->prepare($deleteQuery);
        $stmt->bind_param("i", $departmentId);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error deleting department."]);
        }
    }
    $stmt->close();
    $conn->close();
    exit();
}
?>