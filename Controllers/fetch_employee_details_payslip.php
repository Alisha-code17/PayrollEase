<?php
include '../Database/db1.php';

$emp_ID_payslip = $_GET['emp_ID_payslip'];

$sql = "SELECT 
            e.Employee_id, 
            e.FirstName,
			e.LastName,
            e.Address, 
			e.Email,
			e.Phone,
			e.JoiningDate,
            d.Name AS Department_Name, 
            des.Name AS Designation_Name
        FROM employee e
        LEFT JOIN department d ON e.Department_id = d.Department_id
        LEFT JOIN designation des ON e.Designation_id = des.Designation_id
        WHERE e.Employee_id = '$emp_ID_payslip'";

$result = $conn->query($sql);

$employee = $result->fetch_assoc();

echo json_encode($employee);
?>
