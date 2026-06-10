<?php
include '../Database/db1.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['employee_id'])) {
    $employee_id = $_POST['employee_id'];
    
    if (empty($employee_id)) {
        echo "<script>alert('Error: Employee ID missing.'); window.location.href='manageEmp.php';</script>";
        exit();
    }
    $query = "UPDATE employee SET Status='Deactivated' WHERE Employee_id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $employee_id);

if ($stmt->execute()) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => "Error deactivating employee."]);
}
    $stmt->close();
    $conn->close();
}
?>