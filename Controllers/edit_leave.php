<?php
ob_start();
ini_set('display_errors', 0);
error_reporting(E_ALL);
session_start();
header('Content-Type: application/json');

include '../Database/db1.php';


if (!isset($_SESSION['Employee_id'])) {
    die(json_encode(['status' => 'error', 'message' => 'Unauthorized access']));
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die(json_encode(['status' => 'error', 'message' => 'Invalid request method']));
}

$leaveId = filter_input(INPUT_POST, 'leave_id', FILTER_VALIDATE_INT);
$leaveTypeId = filter_input(INPUT_POST, 'options', FILTER_VALIDATE_INT);
$totalDays = filter_input(INPUT_POST, 'total_days', FILTER_VALIDATE_FLOAT);
$monthInput = trim($_POST['month'] ?? '');
$description = trim($_POST['description'] ?? '');

if (!$leaveId || !$leaveTypeId || !$totalDays || !$monthInput) {
    die(json_encode(['status' => 'error', 'message' => 'Missing required fields']));
}

$employeeId = $_SESSION['Employee_id'];
$applyMonth = substr($monthInput, 5, 2);
$monthName = date("F", strtotime($monthInput));
$currentYear = date("Y");
$currentMonth = date("m");

// 🔒 Prevent editing for past months
$applyYearFromInput = substr($monthInput, 0, 4);
if ($applyYearFromInput < $currentYear || ($applyYearFromInput == $currentYear && $applyMonth < $currentMonth)) {
    die(json_encode(['status' => 'error', 'message' => 'You cannot update leave for past months']));
}

// 🔍 Get existing Year from DB
$getYearSql = "SELECT Year, Status FROM `leave` WHERE Leave_id = ? AND Employee_id = ?";
$getYearStmt = $conn->prepare($getYearSql);
$getYearStmt->bind_param("ii", $leaveId, $employeeId);
$getYearStmt->execute();
$yearResult = $getYearStmt->get_result();
$currentLeave = $yearResult->fetch_assoc();
$getYearStmt->close();

if (!$currentLeave) {
    die(json_encode(['status' => 'error', 'message' => 'Leave record not found']));
}

if (in_array($currentLeave['Status'], ['Approved', 'Rejected'])) {
    die(json_encode(['status' => 'error', 'message' => 'Cannot edit this leave because it has been ' . $currentLeave['Status']]));
}

$applyYear = $currentLeave['Year']; // Preserve original year

// 🔒 Check leave limits
if ($leaveTypeId == 1) {
    $maxLeaves = 10;
    $query = "SELECT SUM(Totaldays) AS total_leaves FROM `leave` 
              WHERE Employee_id = ? AND LeaveType_id = 1 AND Year = ? AND Month = ? AND Leave_id != ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("issi", $employeeId, $applyYear, $monthName, $leaveId);
} elseif (in_array($leaveTypeId, [2, 3])) {
    $maxLeaves = 5;
    $query = "SELECT SUM(Totaldays) AS total_leaves FROM `leave` 
              WHERE Employee_id = ? AND LeaveType_id IN (2, 3) AND Year = ? AND Month = ? AND Leave_id != ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("issi", $employeeId, $applyYear, $monthName, $leaveId);
} else {
    die(json_encode(['status' => 'error', 'message' => 'Invalid leave type']));
}

$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();

$takenLeaves = $row ? intval($row['total_leaves']) : 0;
$remainingLeaves = $maxLeaves - $takenLeaves;

if ($totalDays > $remainingLeaves) {
    die(json_encode([
        'status' => 'error',
        'message' => "You requested $totalDays leave(s), but only $remainingLeaves out of $maxLeaves are available for $monthName."
    ]));
}

// ✅ Update leave record
$sql = "UPDATE `leave` SET LeaveType_id=?, Month=?, Totaldays=?, Year=?, Description=? WHERE Leave_id=? AND Employee_id=?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die(json_encode(['status' => 'error', 'message' => 'Database error']));
}

$stmt->bind_param("ssissii", $leaveTypeId, $monthName, $totalDays, $applyYear, $description, $leaveId, $employeeId);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode([
            'status' => 'success',
            'data' => [
                'leave_id' => $leaveId,
                'leave_type' => $leaveTypeId,
                'month' => $monthName,
                'total_days' => $totalDays,
                'description' => $description,
                'message' => "Leave updated successfully. Remaining: " . ($remainingLeaves - $totalDays)
            ]
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No changes made']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Update failed']);
}

$stmt->close();
$conn->close();
ob_end_flush();
?>
