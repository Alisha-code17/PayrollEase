<?php
require('../Resources/fpdf186/fpdf.php'); // Include FPDF

// Database connection
include '../Database/db1.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['payroll_records']) && !empty($_POST['payroll_records'])) {
        $selectedMonths = $_POST['payroll_records']; // Array of selected payroll months
        
        // Convert array to comma-separated string for SQL query
        $months = implode("','", $selectedMonths);

        // Start session to get employee ID
        session_start();
        $employee_id = $_SESSION['Employee_id'];

        // First get employee name
        $employee_sql = "SELECT CONCAT(FirstName, ' ', COALESCE(LastName, '')) AS FullName 
                         FROM employee 
                         WHERE Employee_id = '$employee_id'";
        $employee_result = $conn->query($employee_sql);
        $employee_name = "Employee";
        if ($employee_result->num_rows > 0) {
            $employee_row = $employee_result->fetch_assoc();
            $employee_name = $employee_row['FullName'];
        }

        // Fetch payroll details from the database
        $sql = "SELECT 
                    PayrollMonth,
                    YEAR(DateCreated) AS Year,
                    PresentDays,
                    AbsentDays,
                    BasicSalary,
                    GrossSalary,
                    NetSalary
                FROM payroll
                WHERE Employee_id = '$employee_id' 
                AND PayrollMonth IN ('$months')";

        $result = $conn->query($sql);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payroll Report</title>
    <link rel="icon" href="../Resources/img/favicon.ico" type="image/x-icon">

    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f7f7f7;
        }
        .report-container {
            max-width: 1000px;
            margin: 30px auto;
            padding: 100px;
            background-color: #fff;
            border-radius: 10px;
            border: 1px solid #e0e0e0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
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
        .employee-name {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
        }
        .section h3 {
            font-size: 20px;
            color: #1a73e8;
            border-bottom: 2px solid #1a73e8;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .info-table th, .info-table td {
            padding: 10px;
            border: 1px solid #ddd;
            font-size: 14px;
            text-align: center;
        }
        .info-table th {
            background-color: #1a73e8;
            color: #fff;
        }
        .info-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .summary {
            margin-top: 30px;
            padding: 15px;
        
            border-radius: 5px;
            text-align: right;
        }
        .summary-item {
            
            margin-bottom: 10px;
        }
        .summary-label {
            font-weight: bold;
            margin-right: 5px;
        }
        .footer {
            text-align: center;
            font-size: 14px;
            color: #6c757d;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        @media print {
            body {
                background-color: white;
            }
            .report-container {
                box-shadow: none;
                border: none;
                padding: 0;
                margin: 0;
                width: 100%;
                margin-top: 20px;
            }
            .no-print {
                display: none !important;
            }
        }

        
    </style>
</head>
<body>
    <div class="report-container">
        <div class="header">
            <div class="employee-name"></div>
            <!--<h1><?php echo htmlspecialchars($employee_name); ?> Payroll Report</h1>-->
            <h1>Payroll Report</h1>
           <!-- <p>Generation Date: <?//php echo date('d F Y'); ?></p>-->
        </div>
        
        <div class="section">
            <h3>Payroll Details</h3>
            <table class="info-table">
                <tr>
                    <th>Month</th>
                    <th>Year</th>
                    <th>Present Days</th>
                    <th>Absent Days</th>
                    <th>Basic Salary</th>
                    <th>Gross Salary</th>
                    <th>Net Salary</th>
                </tr>
                <?php if (!empty($result) && $result->num_rows > 0): ?>
                    <?php 
                    $totalBasic = 0;
                    $totalGross = 0;
                    $totalNet = 0;
                    while ($row = $result->fetch_assoc()): 
                        $totalBasic += $row['BasicSalary'];
                        $totalGross += $row['GrossSalary'];
                        $totalNet += $row['NetSalary'];
                    ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['PayrollMonth']); ?></td>
                            <td><?php echo htmlspecialchars($row['Year']); ?></td>
                            <td><?php echo htmlspecialchars($row['PresentDays']); ?></td>
                            <td><?php echo htmlspecialchars($row['AbsentDays']); ?></td>
                            <td>Rs. <?php echo number_format($row['BasicSalary'], 2); ?></td>
                            <td>Rs. <?php echo number_format($row['GrossSalary'], 2); ?></td>
                            <td>Rs. <?php echo number_format($row['NetSalary'], 2); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" style="text-align:center; color:red;">No payroll records selected!</td>
                    </tr>
                <?php endif; ?>
            </table>
            
            <?php if (!empty($result) && $result->num_rows > 0): ?>
            <div class="summary">
                <div class="summary-item">
                    <span class="summary-label">Total Basic Salary:</span>
                    <span>Rs. <?php echo number_format($totalBasic, 2); ?></span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Total Gross Salary:</span>
                    <span>Rs. <?php echo number_format($totalGross, 2); ?></span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Total Net Salary:</span>
                    <span>Rs. <?php echo number_format($totalNet, 2); ?></span>
                </div>
            </div>
            <?php endif; ?>
        </div>
        
        <div class="footer">
        <?php
date_default_timezone_set('Asia/Karachi');
?>

        <p>Generated on: <?php echo date('d F Y'); ?> | Time: <?php echo date('h:i A'); ?></p>
            <p>Official Payroll Report document</p>
        </div>
    </div>
    
    <script>
    window.onload = function() {
        window.print();
        // Optional: close the window after printing
        setTimeout(function() {
            window.close();
        }, 1000);
    };
    </script>
</body>
</html>