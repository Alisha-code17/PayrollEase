<?php
// update_status.php

// Database connection
include '../Database/db1.php';

// Check if request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $leaveId = $_POST['leave_id']; // Leave request ID
    $status = $_POST['status'];    // New status

    // Ensure leave_id and status are not empty
    if (!empty($leaveId) && !empty($status)) {
        // Update the status in the `leave` table
        $sql = "UPDATE `leave` SET Status = ? WHERE Leave_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $status, $leaveId);

        if ($stmt->execute() && $stmt->affected_rows > 0) {
            echo "Status updated successfully!";
        } else {
            echo "Error updating status or no changes made.";
        }

        $stmt->close();
    } else {
        echo "Invalid request. Please provide leave_id and status.";
    }
}

$conn->close();
?>


