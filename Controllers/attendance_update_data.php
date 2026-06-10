<?php
include '../Database/db1.php';

// Get data from POST (unchanged)
$id = $_POST['id']; // Employee ID
$presentdays = $_POST['presentdays'];
$absentdays = $_POST['absentdays'];
$overtimehours = $_POST['overtimehours'];
$month = $_POST['month'];
$year = $_POST['year'];

// First check if record exists for this employee+month+year
$check_sql = "SELECT COUNT(*) FROM attendance 
             WHERE Employee_id = ? 
             AND Month = ? 
             AND Year = ?";
$check_stmt = $conn->prepare($check_sql);
$check_stmt->bind_param("isi", $id, $month, $year);
$check_stmt->execute();
$check_stmt->bind_result($exists);
$check_stmt->fetch();
$check_stmt->close();

if ($exists) {
    // Update existing record
    $update_sql = "UPDATE attendance 
                  SET Present_days = ?,
                      Absent_days = ?,
                      Overtime_hours = ?
                  WHERE Employee_id = ?
                  AND Month = ?
                  AND Year = ?";
    
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("iiisis", $presentdays, $absentdays, $overtimehours, $id, $month, $year);
    
    if ($update_stmt->execute()) {
        echo 'success';
    } else {
        echo 'error: Update failed';
    }
    $update_stmt->close();
} else {
    echo 'error: Record not found';
}

$conn->close();
?>