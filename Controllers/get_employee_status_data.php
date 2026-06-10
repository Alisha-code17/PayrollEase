<?php
header('Content-Type: application/json');

// Database connection
$conn = new mysqli("localhost", "root", "", "payrollease");

// Check connection
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed']);
    exit();
}

$active = 0;
$inactive = 0;
$totalEmployees = 0;
$newEmployees = 0;

// Count Active and Inactive employees
$sql = "SELECT Status, COUNT(*) AS count FROM employee GROUP BY Status";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $totalEmployees += (int)$row['count'];
        $status = strtolower($row['Status']);
        if ($status === "active") {
            $active = (int)$row["count"];
        } elseif ($status === "inactive") {
            $inactive = (int)$row["count"];
        }
    }
}

// Count new employees this month
$currentMonth = date('m');
$currentYear = date('Y');
$sqlNew = "SELECT COUNT(*) AS count FROM employee WHERE MONTH(JoiningDate) = $currentMonth AND YEAR(JoiningDate) = $currentYear";
$resultNew = $conn->query($sqlNew);
if ($resultNew && $rowNew = $resultNew->fetch_assoc()) {
    $newEmployees = (int)$rowNew['count'];
}

echo json_encode([
    'active' => $active,
    'inactive' => $inactive,
    'total' => $totalEmployees,
    'new' => $newEmployees
]);
?>
