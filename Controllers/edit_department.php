<?php
include('../Database/db1.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id']) && isset($_POST['departmentName'])) {
    $id = intval($_POST['id']);
    $departmentName = trim(mysqli_real_escape_string($conn, $_POST['departmentName']));
    $response = ["status" => "error", "message" => "Something went wrong"];
    
    if (empty($departmentName)) {
        $response["message"] = "Department name cannot be empty!";
        echo json_encode($response);
        exit();
    }
    if (strlen($departmentName) > 50) {
        $response["message"] = "Department name must be under 50 characters!";
        echo json_encode($response);
        exit();
    }
    if (!preg_match("/^[a-zA-Z\s]+$/", $departmentName)) {
        $response["message"] = "Only letters and spaces are allowed!";
        echo json_encode($response);
        exit();
    }
    $departmentName = trim($_POST['departmentName']);
    $checkSql = "SELECT * FROM Department WHERE Name = '$departmentName' AND Department_id != $id";
    $checkResult = $conn->query($checkSql);

    if ($checkResult->num_rows > 0) {
        $response["message"] = "This department name already exists!";
        echo json_encode($response);
        exit();
    } else {
        $sql = "UPDATE Department SET Name='$departmentName' WHERE Department_id = $id";
        if ($conn->query($sql) === TRUE) {
            $response = [
                "status" => "success",
                "message" => "Department updated successfully!",
                "data" => [
                    "id" => $id,
                    "departmentName" => ucfirst($departmentName)
                ]
            ];
        } else {
            $response["message"] = "Failed to update department!";
        }
    }
    echo json_encode($response);
    exit();
}
?>