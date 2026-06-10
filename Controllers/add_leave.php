<?php
session_start();
header('Content-Type: application/json');
include '../Database/db1.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $leaveTypeId = isset($_POST['options']) ? intval($_POST['options']) : null;
    $totalDays = isset($_POST['total_days']) ? intval($_POST['total_days']) : null;
    $monthInput = $_POST['month'] ?? null;
    $description = htmlspecialchars($_POST['description'] ?? '');

    if ($leaveTypeId === null || $totalDays === null || empty($monthInput)) {
        echo json_encode([
            'icon' => 'warning',
            'title' => 'Missing Data',
            'text' => 'Please fill all required fields before saving!'
        ]);
        exit;
    }

    if (!isset($_SESSION['Employee_id'])) {
        echo json_encode([
            'icon' => 'error',
            'title' => 'Unauthorized',
            'text' => 'Error: Employee not logged in.'
        ]);
        exit;
    }

    $employeeId = $_SESSION['Employee_id'];
    $applyYear = substr($monthInput, 0, 4);
    $applyMonth = substr($monthInput, 5, 2);
    $monthName = date("F", strtotime($monthInput));
    $currentYear = date("Y");
    $currentMonth = date("m");

    if ($applyYear < $currentYear || ($applyYear == $currentYear && $applyMonth < $currentMonth)) {
        echo json_encode([
            'icon' => 'error',
            'title' => 'Invalid Date',
            'text' => 'You cannot apply for leave in past months!'
        ]);
        exit;
    }

    if ($leaveTypeId == 1) {
        $maxLeaves = 10;
        $query = "SELECT SUM(Totaldays) AS total_leaves FROM `leave` 
                  WHERE Employee_id = ? AND LeaveType_id = 1 AND Year = ? AND Month = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iss", $employeeId, $applyYear, $monthName);
    } elseif (in_array($leaveTypeId, [2, 3])) {
        $maxLeaves = 5;
        $query = "SELECT SUM(Totaldays) AS total_leaves FROM `leave` 
                  WHERE Employee_id = ? AND LeaveType_id IN (2, 3) AND Year = ? AND Month = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iss", $employeeId, $applyYear, $monthName);
    } else {
        echo json_encode([
            'icon' => 'error',
            'title' => 'Invalid Leave Type',
            'text' => 'Unknown leave type selected.'
        ]);
        exit;
    }

    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result ? $result->fetch_assoc() : null;
    $stmt->close();

    $takenLeaves = $row ? intval($row['total_leaves']) : 0;
    $remainingLeaves = $maxLeaves - $takenLeaves;

    if ($totalDays > $remainingLeaves) {
        echo json_encode([
            'icon' => 'error',
            'title' => 'Leave Limit Exceeded',
            'text' => "You requested $totalDays leave(s), but only $remainingLeaves out of $maxLeaves are available for $monthName."
        ]);
        exit;
    }

    $status = "Pending";
    $insertQuery = "INSERT INTO `leave` (Employee_id, LeaveType_id, Month, Totaldays, Year, Description, Status) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("iisisss", $employeeId, $leaveTypeId, $monthName, $totalDays, $applyYear, $description, $status);


    if ($stmt->execute()) {
        echo json_encode([
            'icon' => 'success',
            'title' => 'Success!',
            'text' => "Leave request submitted successfully! You have " . ($remainingLeaves - $totalDays) . " leave(s) left."
        ]);
    } else {
        echo json_encode([
            'icon' => 'error',
            'title' => 'Insert Failed',
            'text' => 'Error submitting leave request. Please try again!'
        ]);
    }

    $stmt->close();
    $conn->close();
    exit;
}

echo json_encode([
    'icon' => 'error',
    'title' => 'Invalid Request',
    'text' => 'Request must be POST.'
]);
exit;
?>
