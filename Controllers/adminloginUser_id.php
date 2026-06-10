<?php

include '../Database/db1.php';

$user_id = $_SESSION['user_id'];

// Fetch picture and full name of the employee
$sql = "SELECT e.Picture, CONCAT(e.FirstName, ' ', e.LastName) AS FullName
        FROM employee e
        JOIN users u ON e.User_id = u.User_id
        WHERE u.User_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$picture = 'default.jpg'; // fallback picture
$fullName = ''; // no fallback name

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    // Load picture if exists
    if (!empty($row['Picture']) && file_exists('../Controllers/uploads/' . $row['Picture'])) {
        $picture = $row['Picture'];
    }

    // Load full name if exists
    if (!empty($row['FullName'])) {
        $fullName = $row['FullName'];
    }
}

$imagePath = '../Controllers/uploads/' . $picture;
?>
