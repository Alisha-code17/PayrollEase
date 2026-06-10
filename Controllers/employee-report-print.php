<?php
require('../Resources/fpdf186/fpdf.php'); // Include FPDF
?>
<?php
// Database connection
include '../Database/db1.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['employees']) && !empty($_POST['employees'])) {
        $selectedEmployees = $_POST['employees']; // Array of selected employee IDs
        
        // Convert array to comma-separated string for SQL query
        $employeeIds = implode("','", $selectedEmployees);

        // Fetch employee details from the database
        $sql = "SELECT 
                    e.Employee_id, 
                    e.FirstName, 
                    e.LastName, 
                    e.Email, 
                    e.Phone, 
                    e.JoiningDate, 
                    e.Status, 
                    d.Name AS Department_Name, 
                    des.Name AS Designation_Name
                FROM employee e
                LEFT JOIN department d ON e.Department_id = d.Department_id
                LEFT JOIN designation des ON e.Designation_id = des.Designation_id
                WHERE e.Employee_id IN ('$employeeIds')";

        $result = $conn->query($sql);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Record Report</title>
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
            padding: 30px;
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
        }
        .info-table th, .info-table td {
            padding: 10px;
            border: 1px solid #ddd;
            font-size: 14px;
            text-align: left;
        }
        .info-table th {
            background-color: #1a73e8;
            color: #fff;
        }
        .footer {
            text-align: center;
            font-size: 14px;
            color: #6c757d;
            margin-top: 20px;
        }
        @media print {
            .btn-primary-custom { display: none; }
            .report-container { box-shadow: none; border: none; }
        }
    </style>
</head>
<body>
    <div class="report-container">
        <div class="header">
            <h1>Employee Record Report</h1>
            <p>As of Date: March 2025</p>
        </div>
        <div class="section">
            <h3>Employee List</h3>
            <table class="info-table">
                <tr>
                    <th>Emp ID</th>
                    <th>Name</th>
                    <th>Department</th>
                    <th>Designation</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Joining Date</th>
                    <th>Status</th>
                </tr>
                <?php if (!empty($result) && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['Employee_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['FirstName'] . " " . $row['LastName']); ?></td>
                            <td><?php echo htmlspecialchars($row['Department_Name']); ?></td>
                            <td><?php echo htmlspecialchars($row['Designation_Name']); ?></td>
                            <td><?php echo htmlspecialchars($row['Email']); ?></td>
                            <td><?php echo htmlspecialchars($row['Phone']); ?></td>
                            <td><?php echo htmlspecialchars($row['JoiningDate']); ?></td>
                            <td><?php echo htmlspecialchars($row['Status']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" style="text-align:center; color:red;">No employee selected!</td>
                    </tr>
                <?php endif; ?>
            </table>
        </div>
        <div class="footer">
            <p>Generated on: <?php echo date('d F Y'); ?> | Time: <?php echo date('h:i A'); ?></p>
            <p>Confidential Employee Report</p>
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
