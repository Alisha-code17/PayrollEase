<?php
include '../Database/db1.php';

$emp_ID_payslip = $_GET['emp_ID_payslip'];
$month = $_GET['month'];
$year = $_GET['year'];

$sql = "SELECT 
        p.BasicSalary,
        p.TotalAllowance,
        p.TotalDeductions,
        p.Bonus,
        p.GrossSalary,
        p.NetSalary
    FROM payroll p
    WHERE p.Employee_id = '$emp_ID_payslip' 
    AND p.PayrollMonth = '$month' 
    AND p.Year = '$year'";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    echo json_encode([
        "BasicSalary" => $row['BasicSalary'],
        "TotalAllowance" => $row['TotalAllowance'],
        "TotalDeductions" => $row['TotalDeductions'],
        "Bonus" => $row['Bonus'],
        "GrossSalary" => $row['GrossSalary'],
        "NetSalary" => $row['NetSalary']
    ]);
} else {
    echo json_encode([
        "BasicSalary" => 0,
        "TotalAllowance" => 0,
        "TotalDeductions" => 0,
        "Bonus" => 0,
        "GrossSalary" => 0,
        "NetSalary" => 0
    ]);
}

$conn->close();
?>