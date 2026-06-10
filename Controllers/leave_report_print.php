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

        // Fetch employee and leave details from the database
        $sql = "SELECT 
                    e.Employee_id, 
                    e.FirstName AS Employee_Name, 
                    e.Picture AS Picture, 
                    d.Name AS Department_Name, 
                    des.Name AS Designation_Name, 
                    COALESCE(l.Year, '-') AS Year, 
                    COALESCE(l.Month, '-') AS Month, 
                    COALESCE(l.Totaldays, '0') AS Totaldays, 
                    COALESCE(l.Status, 'No Leave') AS Status,
                    COALESCE(lt.TypeName, 'No Leave') AS TypeName
                FROM employee e
                JOIN department d ON e.Department_id = d.Department_id
                JOIN designation des ON e.Designation_id = des.Designation_id
                LEFT JOIN `leave` l ON e.Employee_id = l.Employee_id
                LEFT JOIN leavetype lt ON l.LeaveType_id = lt.LeaveType_id
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
    <title>Employee Leave Report</title>
    <link rel ="stylesheet" href="../Resources/css/report_print_styles.css">
</head>
<body>
    <div class="report-container">
        <div class="header">
            <h1>Employee Leave Report</h1>
            <p>As of Date: <?php echo date('F Y'); ?></p>
        </div>
        <div class="section">
            <h3>Employee Leave Details</h3>
            <table class="info-table">
                <tr>
                    <th>Emp ID</th>
                    <th>Name</th>
                    <th>Department</th>
                    <th>Designation</th>
                    <th>Leave Type</th>
                    <th>Month</th>
                    <th>Year</th>
                    <th>Total Days</th>
                    <th>Status</th>
                </tr>
                <?php if (!empty($result) && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['Employee_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['Employee_Name']); ?></td>
                            <td><?php echo htmlspecialchars($row['Department_Name']); ?></td>
                            <td><?php echo htmlspecialchars($row['Designation_Name']); ?></td>
                            <td><?php echo htmlspecialchars($row['TypeName']); ?></td>
                            <td><?php echo htmlspecialchars($row['Month']); ?></td>
                            <td><?php echo htmlspecialchars($row['Year']); ?></td>
                            <td><?php echo htmlspecialchars($row['Totaldays']); ?></td>
                            <td><?php echo htmlspecialchars($row['Status']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" style="text-align:center; color:red;">No employee selected or no leave records found!</td>
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
