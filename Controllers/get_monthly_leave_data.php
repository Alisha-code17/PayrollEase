<?php
// get_monthly_leave_data.php

$conn = new mysqli("localhost", "root", "", "payrollease");

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed"]);
    exit();
}

// Define month name to number mapping
$monthMap = [
    "January" => 1, "February" => 2, "March" => 3,
    "April" => 4, "May" => 5, "June" => 6,
    "July" => 7, "August" => 8, "September" => 9,
    "October" => 10, "November" => 11, "December" => 12
];

// Get current year
$year = date("Y");

// Query to get leave data with status 'Approved'
$sql = "SELECT Month, COUNT(*) AS leave_count
        FROM `leave`
        WHERE Status = 'Approved' AND Year = ?
        GROUP BY Month";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $year);
$stmt->execute();
$result = $stmt->get_result();

// Initialize leave count array for all 12 months
$monthlyLeaveData = array_fill(0, 12, 0);

while ($row = $result->fetch_assoc()) {
    $monthName = $row["Month"];
    if (isset($monthMap[$monthName])) {
        $monthIndex = $monthMap[$monthName] - 1; // Convert to 0-based index
        $monthlyLeaveData[$monthIndex] = (int)$row["leave_count"];
    }
}

echo json_encode($monthlyLeaveData);

$stmt->close();
$conn->close();
?>
