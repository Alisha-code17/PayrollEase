<?php
// Allow cross-origin requests for development
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include your MySQLi DB connection
include '../Database/db1.php';

// Check if Employee_id exists in session
if (!isset($_SESSION['Employee_id'])) {
    die("Error: Employee ID is missing in the session.");
}

$employee_id = $_SESSION['Employee_id'];

// SQL query to fetch payroll data for the logged-in employee
$query = "SELECT 
    PayrollMonth,
    YEAR(DateCreated) AS Year,
    PresentDays,
    AbsentDays,
    BasicSalary,
    GrossSalary,
    NetSalary
FROM payroll
WHERE Employee_id = ?";

// Prepare and execute the query
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $employee_id);
$stmt->execute();
$result = $stmt->get_result();

// Fetch all payroll records
$payroll_data = [];
while ($row = $result->fetch_assoc()) {
    $payroll_data[] = $row;
}

$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>employee_report</title>
    <link rel="stylesheet" href="../Resources/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Merriweather+Sans">
    <link rel="stylesheet" href="../Resources/css/Data-Table.css">
    <link rel="stylesheet" href="../Resources/css/Data-Table.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.15/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../Resources/css/employee_report.css">
</head>

<body class="employee-report-body">
    <!-- Main Container -->
    <div class="container employee-report-container">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center my-4 header-section">
            <div>
                <h3>Payroll Report</h3>
                <p class="mb-0">Payroll > Payroll Report</p>
            </div>
            <div class="dropdown" id="exportDropdown">
                <button class="btn btn-outline-primary dropdown-toggle export-button" type="button" data-toggle="dropdown">
                    <img src="../Resources/img/rec.png" class="export-icon"> Export
                </button>
                <div class="dropdown-menu dropdown-menu-right">
                    <button class="dropdown-item" onclick="employeePayrollReportPrint()">Export as PDF</button>
                </div>
            </div>
        </div>

        <!-- Search and Table Section -->
        <div class="card border-primary report-card">
            <div class="card-body">
                <!-- Search Bar -->
                <form id="searchForm" class="mb-3 search-form">
                    <div class="input-group search-input-group">
                        <input type="text" class="form-control" name="search" id="search" placeholder="Search by Month or Year">
                        <div class="input-group-append">
                            <button class="btn btn-primary search-button" type="button">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Table -->
                <form method="POST" id="exportForm" action="empPayrollReport_print.php">
                    <div class="table-responsive">
                        <table class="table payroll-table">
                            <thead class="bg-primary text-white table-header">
                                <tr>
                                    <th width="35px"><input type="checkbox" id="mainCheckbox"></th>
                                    <th>Month</th>
                                    <th>Year</th>
                                    <th>Present Days</th>
                                    <th>Absent Days</th>
                                    <th>Basic Salary</th>
                                    <th>Gross Salary</th>
                                    <th>Net Salary</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(count($payroll_data) > 0): ?>
                                    <?php foreach($payroll_data as $row): ?>
                                        <tr>
                                            <td><input type="checkbox" class="subCheckbox" name="payroll_records[]" value="<?= htmlspecialchars($row['PayrollMonth']) ?>"></td>
                                            <td><?= htmlspecialchars($row['PayrollMonth']) ?></td>
                                            <td><?= htmlspecialchars($row['Year']) ?></td>
                                            <td><?= htmlspecialchars($row['PresentDays']) ?></td>
                                            <td><?= htmlspecialchars($row['AbsentDays']) ?></td>
                                            <td>Rs.<?= htmlspecialchars($row['BasicSalary']) ?></td>
                                            <td>Rs.<?= htmlspecialchars($row['GrossSalary']) ?></td>
                                            <td>Rs.<?= htmlspecialchars($row['NetSalary']) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8" class="text-center py-4 no-records">No payroll records found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!--script for printform-->
    <script>
    function employeePayrollReportPrint() {
        let form = document.getElementById("exportForm"); // Ensure the correct ID is used
        if (form) {
            form.submit(); // Submit the form if it exists
        } else {
            console.error("Form with ID 'exportForm' not found!"); // Debugging message
        }
    }
    </script>
    <!-- Required Bootstrap JS for Export button dropdown -->
    <!--<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>  -->

   <!-- <script src="../Resources/js/jquery.min.js"></script>-->
    <!--<script src="../Resources/bootstrap/js/bootstrap.min.js"></script>-->
    <!--<script src="https://kit.fontawesome.com/a076d05399.js"></script>-->
</body>
</html>