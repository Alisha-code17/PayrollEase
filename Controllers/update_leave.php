<?php
include '../Database/db1.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $leave_id = $_POST['leave_id'];
    $leave_type = $_POST['options']; // radio name
    $month = $_POST['month'];
    $total_days = $_POST['total_days'];
    $description = $_POST['description'];

    // Convert full month name to numerical format (01 to 12)
    $monthNum = date('m', strtotime($month));
    $year = date('Y'); // Optional: adjust this as needed

    // Update query
    $query = "UPDATE leave_table SET 
        TypeName = ?, 
        Month = ?, 
        Year = ?, 
        Totaldays = ?, 
        Description = ? 
        WHERE Leave_id = ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssisi", $leave_type, $month, $year, $total_days, $description, $leave_id);

    if ($stmt->execute()) {
        echo json_encode([
            "status" => "success",
            "message" => "Leave updated successfully!",
            "data" => [
                "leave_id" => $leave_id,
                "leave_type" => $leave_type,
                "month" => $month,
                "total_days" => $total_days,
                "description" => $description,
                "year" => $year
            ]
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Failed to update leave."
        ]);
    }

    $stmt->close();
    $conn->close();
}
?>
