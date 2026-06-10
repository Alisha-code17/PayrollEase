<?php
header('Content-Type: application/json');
error_reporting(0);
ini_set('display_errors', 0);
include '../Database/db1.php'; // Must create a MySQLi $conn

$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['Leave_id'])) {
        $leaveId = (int) $_POST['Leave_id'];

        $stmt = $conn->prepare("DELETE FROM `leave` WHERE Leave_id = ?");
        $stmt->bind_param("i", $leaveId);

        if ($stmt->execute()) {
            $response = [
                'status' => 'success',
                'message' => 'Leave deleted successfully'
            ];
        } else {
            $response = [
                'status' => 'error',
                'message' => 'Error deleting leave'
            ];
        }

        $stmt->close();
        $conn->close();
    } else {
        $response = [
            'status' => 'error',
            'message' => 'Leave ID not received'
        ];
    }
} else {
    $response = [
        'status' => 'error',
        'message' => 'Invalid request method'
    ];
}
echo json_encode($response);
?>