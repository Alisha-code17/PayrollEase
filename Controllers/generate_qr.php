<?php
require_once __DIR__ . '/../vendor/autoload.php';
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

$conn = new mysqli("localhost", "root", "", "payrollease");
if ($conn->connect_error) {
    http_response_code(500);
    exit;
}

$employeeID = $_GET['emp_ID_payslip'] ?? null;
$month = $_GET['payrollMonth'] ?? null;
$year = $_GET['selectedYearHidden'] ?? null;
if (!$employeeID || !$month || !$year) {
    http_response_code(400);
    exit;
}

$stmt = $conn->prepare("
    SELECT 
        e.Employee_id, 
        CONCAT(e.FirstName, ' ', e.LastName) AS name,
        p.BasicSalary,
        p.TotalAllowance,
        p.TotalDeductions,
        p.GrossSalary, 
        p.NetSalary
    FROM employee e
    JOIN payroll p ON e.Employee_id = p.Employee_id
    WHERE e.Employee_id = ? AND p.PayrollMonth = ? AND p.Year = ?
");

$stmt->bind_param("sss", $employeeID, $month, $year);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
        $qrText = "PayrollEase\n"
        . "PAYSLIP - $month $year\n\n"
        . "Name: {$row['name']}\n"
        . "Employee ID: {$row['Employee_id']}\n\n"
        . "Basic Salary: {$row['BasicSalary']}\n"
        . "Total Allowances: {$row['TotalAllowance']}\n"
        . "Total Deductions: {$row['TotalDeductions']}\n"
        . "Gross Salary: {$row['GrossSalary']}\n"
        . "NET PAY: {$row['NetSalary']}";        
    $qrCode = new QrCode($qrText);
    $writer = new PngWriter();
    $qrImage = $writer->write($qrCode);

    header('Content-Type: image/png');
    echo $qrImage->getString();
} else {
    http_response_code(404);
    exit;
}
$stmt->close();
$conn->close();
?>