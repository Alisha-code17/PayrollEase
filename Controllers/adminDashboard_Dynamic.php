<?php
include '../Database/db.php'; // Include your database connection

try {
    // Fetch total earnings for each employee
    $stmt = $conn->prepare("
        SELECT employee.Name, SUM(payroll.NetSalary) AS total_earnings
        FROM payroll
        JOIN employee ON payroll.Employee_ID = employee.Employee_ID
        GROUP BY payroll.Employee_ID
        ORDER BY total_earnings DESC
    ");
    $stmt->execute();
    $earningsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Prepare data for JavaScript
    $employeeNames = [];
    $earnings = [];

    foreach ($earningsData as $row) {
        $employeeNames[] = $row['Name']; // Store names
        $earnings[] = $row['total_earnings']; // Store earnings
    }

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
        document.addEventListener('DOMContentLoaded', function () {
            // PHP Data (Converted to JavaScript)
            const employeeNames = <?php echo json_encode($employeeNames); ?>;
            const earnings = <?php echo json_encode($earnings); ?>;

            // Get Canvas Element
            const ctx = document.getElementById('earningsBarChart').getContext('2d');

            // Create Bar Chart
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: employeeNames, // Employee Names
                    datasets: [{
                        label: 'Total Earnings (Rs)',
                        data: earnings, // Employee Earnings
                        backgroundColor: '#6FBFED',
                        borderColor: '#007bff',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        });
    </script>

<!--line 385 to -->

<div>
                    <div class="row" style="margin-top:50px;margin-bottom:-70px">
                    <?php
                        include '../Database/db.php'; // Include your PDO database connection

                        try {
                            // Query to get total salaries from payroll
                            $stmt = $conn->prepare("SELECT SUM(NetSalary) AS salaries FROM payroll");
                            $stmt->execute();
                            $salaries = $stmt->fetch(PDO::FETCH_ASSOC)['salaries'] ?? 0;

                            // Query to get total allowances
                            $stmt = $conn->prepare("SELECT SUM(Amount) AS allowances FROM allowance");
                            $stmt->execute();
                            $allowances = $stmt->fetch(PDO::FETCH_ASSOC)['allowances'] ?? 0;

                            // Query to get total deductions
                            $stmt = $conn->prepare("SELECT SUM(Amount) AS deductions FROM deduction");
                            $stmt->execute();
                            $deductions = $stmt->fetch(PDO::FETCH_ASSOC)['deductions'] ?? 0;

                        } catch (PDOException $e) {
                            die("Error fetching data: " . $e->getMessage());
                        }
                        ?>
                        <div class="col-md-4" style="background-color:white;margin-bottom: 120px;margin-left:15px;">
    <h5 style="margin-top:30px;">Expenses Breakdown:<br></h5>
    <div>
        <canvas id="pieChart" width="50" height="50"></canvas>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Pass PHP values to JavaScript
        const salaries = <?php echo $salaries; ?>;
        const allowances = <?php echo $allowances; ?>;
        const deductions = <?php echo $deductions; ?>;

        const ctxPie = document.getElementById('pieChart').getContext('2d');
        const pieChart = new Chart(ctxPie, {
            type: 'pie',
            data: {
                labels: ['Salaries', 'Allowances', 'Deductions'],
                datasets: [{
                    data: [salaries, allowances, deductions], // Dynamic Data from PHP
                    backgroundColor: ['#6FBFED', '#3786D5', '#D2D2DC'],
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    });
</script>


<!--Line chart-->
<?php
include '../Database/db.php'; // Include your PDO database connection

try {
    // Query to get total earnings grouped by month from payroll table
    $stmt = $conn->prepare("
        SELECT PayrollMonth AS month_name, SUM(NetSalary) AS total_earnings 
        FROM payroll 
        GROUP BY PayrollMonth 
        ORDER BY FIELD(PayrollMonth, 'January', 'February', 'March', 'April', 'May', 'June', 
                                      'July', 'August', 'September', 'October', 'November', 'December')
    ");
    $stmt->execute();
    $earningsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Initialize arrays for labels (months) and data (earnings)
    $months = [];
    $earnings = [];

    foreach ($earningsData as $row) {
        $months[] = $row['month_name']; // Store month name (January, February, etc.)
        $earnings[] = $row['total_earnings']; // Store total earnings for that month
    }

} catch (PDOException $e) {
    die("Error fetching earnings data: " . $e->getMessage());
}
?>

<div class="col-md-5" style="margin-left:150px; background-color:white; margin-bottom:120px;">
    <h5 style="margin-top:30px;">Earnings Over Time:<br></h5>
    <div>
        <canvas id="lineChart" width="50" height="50"></canvas>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Fetch PHP data and ensure it's in the correct format for JavaScript
        const months = <?php echo json_encode($months); ?>;
        const earnings = <?php echo json_encode($earnings); ?>;

        const ctxLine = document.getElementById('lineChart').getContext('2d');
        const lineChart = new Chart(ctxLine, {
            type: 'line',
            data: {
                labels: months, // Dynamic X-axis labels (Months)
                datasets: [{
                    label: 'Monthly Earnings',
                    data: earnings, // Dynamic Y-axis data (Earnings)
                    borderColor: '#007bff',
                    borderWidth: 2,
                    fill: false
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: true }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>

</div>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top:30px;display:flex;align-items:center;justify-content:center;">
                <!--<div style="margin-top:30px;display:flex;align-items:center;justify-content:center;">-->
                                    <!--bar chart-->
                <?php
include '../Database/db.php'; // Include your database connection

try {
    // Fetch total earnings for each employee
    $stmt = $conn->prepare("
        SELECT employee.Name, SUM(payroll.NetSalary) AS total_earnings
        FROM payroll
        JOIN employee ON payroll.Employee_ID = employee.Employee_ID
        GROUP BY payroll.Employee_ID
        ORDER BY total_earnings DESC
    ");
    $stmt->execute();
    $earningsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Prepare data for JavaScript
    $employeeNames = [];
    $earnings = [];

    foreach ($earningsData as $row) {
        $employeeNames[] = $row['Name']; // Store names
        $earnings[] = $row['total_earnings']; // Store earnings
    }

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>
<div class="col-md-5" style="background-color:white;">
    <h5 style="margin-top:30px;">Total Salaries:<br></h5>
    <div>
    <canvas id="earningsBarChart" width="300" height="300"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
        document.addEventListener('DOMContentLoaded', function () {
            // PHP Data (Converted to JavaScript)
            const employeeNames = <?php echo json_encode($employeeNames); ?>;
            const earnings = <?php echo json_encode($earnings); ?>;

            // Get Canvas Element
            const ctx = document.getElementById('earningsBarChart').getContext('2d');

            // Create Bar Chart
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: employeeNames, // Employee Names
                    datasets: [{
                        label: 'Total Earnings (Rs)',
                        data: earnings, // Employee Earnings
                        backgroundColor: '#6FBFED',
                        borderColor: '#007bff',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        });
    </script>
                </div>
            </div>