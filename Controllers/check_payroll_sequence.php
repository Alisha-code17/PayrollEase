<?php
$conn = new mysqli("localhost", "root", "", "payrollease");

if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

$month = $_GET['month'] ?? '';
$emp_ID_payslip = $_GET['emp_ID_payslip'] ?? '';
$year = $_GET['year'] ?? '';

$response = [
    'monthExists' => false,
    'empExists' => false,
    'fullRecordExists' => false
];

// ✅ Check if month exists
if (!empty($month)) {
    $stmt = $conn->prepare("SELECT 1 FROM payroll WHERE PayrollMonth = ? LIMIT 1");
    $stmt->bind_param("s", $month);
    $stmt->execute();
    $stmt->store_result();
    $response['monthExists'] = $stmt->num_rows > 0;
}

// ✅ Check if employee exists for selected month
if (!empty($month) && !empty($emp_ID_payslip)) {
    $stmt = $conn->prepare("SELECT 1 FROM payroll WHERE PayrollMonth = ? AND Employee_id = ? LIMIT 1");
    $stmt->bind_param("ss", $month, $emp_ID_payslip);
    $stmt->execute();
    $stmt->store_result();
    $response['empExists'] = $stmt->num_rows > 0;
}

// ✅ Check full record with emp + month + year
if (!empty($month) && !empty($emp_ID_payslip) && !empty($year)) {
    $stmt = $conn->prepare("SELECT 1 FROM payroll WHERE PayrollMonth = ? AND Employee_id = ? AND Year = ? LIMIT 1");
    $stmt->bind_param("sss", $month, $emp_ID_payslip, $year);
    $stmt->execute();
    $stmt->store_result();
    $response['fullRecordExists'] = $stmt->num_rows > 0;
}

header('Content-Type: application/json'); // 👈 ensures response is treated as JSON
echo json_encode($response);
?>
