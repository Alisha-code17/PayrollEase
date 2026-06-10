<?php
session_start();
include '../Database/db1.php'; 
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['status' => 'error', 'message' => 'User session not found. Please log in again.']);
        exit;
    }
    $userId = $_SESSION['user_id'];
    $currentPass = $_POST['current_password'];
    $newPass = $_POST['new_password'];
    $confirmPass = $_POST['confirm_password'];

    if (empty($currentPass) || empty($newPass) || empty($confirmPass)) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
        exit;
    }
    if ($newPass !== $confirmPass) {
        echo json_encode(['status' => 'error', 'message' => 'New password and confirm password do not match. Please make sure both fields are the same.']);
        exit;
    }
    if (!preg_match('/^(?=.*[A-Z])(?=.*[0-9]).{5,}$/',$newPass)) {
        echo json_encode([
            "status" => "error",
            "message" => "Password must be at least 5 characters long and contain at least 1 uppercase letter and 1 number."
        ]);
        exit();
    }
    // Fetch current password
    $query = "SELECT Password FROM users WHERE User_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $userId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $dbPassword);
    $found = mysqli_stmt_fetch($stmt); 
    mysqli_stmt_close($stmt);

    if (!$found) {
        echo json_encode(['status' => 'error', 'message' => 'User not found.']);
        exit;
    }
    if ($currentPass !== $dbPassword && !password_verify($currentPass, $dbPassword)) {
        echo json_encode(['status' => 'error', 'message' => 'Current password is incorrect.']);
        exit;
    }

    $newHashedPass = password_hash($newPass, PASSWORD_DEFAULT);
    $updateQuery = "UPDATE users SET Password = ? WHERE User_id = ?";
    $updateStmt = mysqli_prepare($conn, $updateQuery);
    mysqli_stmt_bind_param($updateStmt, "ss", $newHashedPass, $userId);

    if (mysqli_stmt_execute($updateStmt)) {
        echo json_encode(['status' => 'success', 'message' => 'Password updated successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update password.']);
    }

    mysqli_stmt_close($updateStmt);
}
?>