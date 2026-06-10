<?php
include '../Database/db1.php';
$emp_id = $_POST['emp_ID_payslip'] ?? '';
$month = $_POST['payrollMonth'] ?? '';
$year = $_POST['selectedYearHidden'] ?? '';
$totalDays = cal_days_in_month(CAL_GREGORIAN, date('m', strtotime($month)), $year);

// Employee Info
$emp_result = mysqli_query($conn, "SELECT  
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
    WHERE e.Employee_id = '$emp_id'");
$employee = mysqli_fetch_assoc($emp_result);


// Payroll
$payroll_result = mysqli_query($conn, "SELECT 
    BasicSalary,
    TotalAllowance,
    TotalDeductions,
    Bonus,
    GrossSalary,
    NetSalary
    FROM payroll
    WHERE Employee_id = '$emp_id' AND PayrollMonth = '$month' AND year = '$year'");
$payroll = mysqli_fetch_assoc($payroll_result);



$attendance_result = mysqli_query($conn, "SELECT 
    PresentDays,
    AbsentDays
    FROM payroll
    WHERE Employee_id = '$emp_id' AND PayrollMonth = '$month' AND year = '$year'");
$attendance = mysqli_fetch_assoc($attendance_result);
// Now you can use $attendance['PresentDays'], $attendance['AbsentDays'], and $totalDays in your payslip
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payslip</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f7f7f7;
        }
        .payslip-container {
            max-width: 1000px;
            margin: 30px auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 10px;
            border: 1px solid #e0e0e0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 5px;
        }
        .header h1 {
            font-size: 28px;
            color: #1a73e8;
            margin: 0;
        }
        .header p {
            font-size: 16px;
            color: #6c757d;
        }
        .section {
            margin-bottom: 5px;
        }
        .section h3 {
            font-size: 20px;
            color: #1a73e8;
            border-bottom: 2px solid #1a73e8;
            padding-bottom: 5px;
            margin-bottom: 5px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
        }
        .info-table td {
            padding: 10px;
            border: 1px solid #ddd;
            font-size: 14px;
        }
        .info-table th {
            padding: 12px;
            background-color: #1a73e8;
            color: #fff;
            font-size: 14px;
            text-align: left;
        }
        .info-table .total {
            font-weight: bold;
            background-color: #f0f8ff;
        }
        .totals {
            font-size: 18px;
            font-weight: bold;
            margin-top: 30px;
            text-align: right;
        }
        .totals p {
            margin: 5px 0;
        }
        .footer {
            text-align: center;
            font-size: 14px;
            color: #6c757d;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="payslip-container">
        <!-- Header Section -->
        <div class="header">
            <h1>Employee Payslip</h1>
            <p>For the month of <?= htmlspecialchars($month) ?></p>

        </div>

        <!-- Employee Details -->
        <div class="section">
            <h3>Employee Details</h3>
            <table class="info-table">
                <tr>
                    <td><strong>Employee ID:</strong></td>
                    <td><?= $employee['Employee_id'] ?></td>
                    <td><strong>Name:</strong></td>
                    <td><?= $employee['FirstName'] . ' ' . $employee['LastName'] ?></td>
                </tr>
                <tr>
                    <td><strong>Department:</strong></td>
                    <td><?= $employee['Department_Name'] ?></td>
                    <td><strong>Designation:</strong></td>
                    <td><?= $employee['Designation_Name'] ?></td>
					
                </tr>
				<tr>
                    <td><strong>Email:</strong></td>
                    <td><?= $employee['Email'] ?></td>
                    <td><strong>Phone No:</strong></td>
                    <td><?= $employee['Phone'] ?></td>
					
                </tr>
				<tr>
                    <td><strong>Address:</strong></td>
                    <td><?= $employee['Address'] ?></td>
                    <td><strong>Joining Date:</strong></td>
                    <td><?= $employee['JoiningDate'] ?></td>
					
                </tr>
            </table>
        </div>
		<!--Attendance Record-->
		<div class="section">
            <h3>Attendance details</h3>
            <table class="info-table">
                <tr>
                    <td><strong>Total Days:</strong></td>
					<td><?= htmlspecialchars($totalDays) ?></td>
                    <td><strong>Present days:</strong></td>
					<td><?= !empty($attendance['PresentDays']) ? $attendance['PresentDays'] : '<span style="color:red;">No record found' ?></td>
					<td><strong>Absent Days:</strong></td>
                    <td><?= !empty($attendance['AbsentDays']) ? $attendance['AbsentDays'] : '<span style="color:red;">No record found' ?></td>	
				</tr>
            </table>
        </div>

        <!-- Earnings Section -->
        <div class="section">
            <h3>Earnings</h3>
            <table class="info-table">
                <tr>
                    <th>Description</th>
                    <th>Amount</th>
                </tr>
                <tr>
                    <td>Basic Salary</td>
                    <td>Rs.<?= !empty($payroll['BasicSalary']) ? $payroll['BasicSalary'] : '<span style="color:red;">No record found</span>' ?>
</td>
                </tr>
                <tr>
                    <td>Bonus</td>
                    <td>Rs.<?= !empty($payroll['Bonus']) ? $payroll['Bonus'] : '<span style="color:red;">No record found' ?></td>
                </tr>
                <tr>
                    <td>Total Allowance</td>
                    <td>Rs.<?= !empty($payroll['TotalAllowance']) ? $payroll['TotalAllowance'] : '<span style="color:red;">No record found' ?></td>
                </tr>
                
                <tr class="total">
                    <td><strong>Total Earnings</strong></td>
                    <td><strong>Rs.<?= !empty($payroll['GrossSalary']) ? $payroll['GrossSalary'] : '<span style="color:red;">No record found' ?></strong></td>
                </tr>
            </table>
        </div>

        <!-- Deductions Section -->
        <div class="section">
            <h3>Deductions</h3>
            <table class="info-table">
                <tr>
                    <th>Description</th>
                    <th>Amount</th>
                </tr>
                <tr class="total">
                    <td><strong>Total Deductions</strong></td>
                    <td><strong>Rs.<?= !empty($payroll['TotalDeductions']) ? $payroll['TotalDeductions'] : '<span style="color:red;">No record found' ?></strong></td>
                </tr>
            </table>
        </div>

        <!-- Net Salary Section -->
        <div class="totals">
            <p><strong>Gross Salary:</strong>Rs.<?= !empty($payroll['GrossSalary']) ? $payroll['GrossSalary'] : '<span style="color:red;">No record found' ?></p>
            <p><strong>Total Deductions:</strong>Rs.<?= !empty($payroll['TotalDeductions']) ? $payroll['TotalDeductions'] : '<span style="color:red;">No record found' ?></p>
            <p><strong>Net Salary:</strong>Rs.<?= !empty($payroll['NetSalary']) ? $payroll['NetSalary'] : '<span style="color:red;">No record found' ?></p>
        </div>

        <!-- Footer Section -->
        <div class="footer">
    <p>Issued On: <?= date('d F Y') ?> | Time: <?= date('h:i A') ?></p>
</div>

    </div>
	
<script>
window.onload = function () {
  setTimeout(function () {
    window.print();
  }, 200); // Wait 0.5s to let content load
};
</script>




</body>
</html>
