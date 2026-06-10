<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PayrollEase</title>
	<link rel="icon" href="../Resources/img/favicon.ico" type="image/x-icon">

    <link rel="stylesheet" href="../Resources/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../Resources/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="../Resources/fonts/simple-line-icons.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Bitter:400,700">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Merriweather+Sans">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,700">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../Resources/css/adminDashboard_sidebar.css">
    <link rel="stylesheet" href="../Resources/css/adminDashboard.css">

    
</head>

<body style="color:rgba(33,37,41,0.67);margin-left:0px; overflow:auto;"><!--<div>Custom Code</div>-->
    <div id="dashboard-content" style="margin-left:20px;display: block;font-family:Roboto, sans-serif;height: 100%;">
        <div style="margin-top:90px;">
            <h6 style="margin-top:30px;font-family:'Merriweather Sans', sans-serif;">Dashboard</h6>
            <!--<h4 style="margin-top:5px;font-family:Roboto, sans-serif;">Home</h4>-->
        </div>
        <div style="margin-top:20px;font-family:Roboto, sans-serif;">
            <div class="row" style="margin-top:50px;">
                <div class="col-md-4">
                    <div class="card" style="border-radius:15px;width:250px;border:1px solid #6fbfed;">
                        <div class="card-body">
                            <h5 class="card-title">Total Departments<br></h5>
                            <div>
                                <div class="row">
                                    <div class="col">
                                    <h6 class="text-muted mb-2" style="padding-top:10px;">
                                        <?php
                                                include '../Database/db1.php'; // Include your MySQLi database connection

                                                // Query to count total departments
                                                $query = "SELECT COUNT(*) AS total_departments FROM department";
                                                $result = $conn->query($query);

                                                if ($result) {
                                                    // Fetch the result
                                                    $row = $result->fetch_assoc();
                                                    
                                                    // Output the total count
                                                    echo $row['total_departments'];
                                                    
                                                    // Free result set
                                                    $result->free();
                                                } else {
                                                    echo "Error: " . $conn->error;
                                                }

                                                // Close connection (optional)
                                                $conn->close();
                                            ?>
                                    </h6>

                                    </div>
                                    <div class="col"><img src="../Resources/img/icons8-department-30 (4).png" style="height:30px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card" style="border-radius:15px;width:250px;border:1px solid #6fbfed;">
                        <div class="card-body">
                            <h5 class="card-title">Employee Count</h5>
                            <div class="row">
                                <div class="col">
                                <h6 class="text-muted mb-2" style="padding-top:10px;">
                                     <?php
                                        include '../Database/db1.php'; // Include your MySQLi database connection

                                        // Query to count employees grouped by status
                                        $query = "SELECT Status, COUNT(*) AS count FROM employee GROUP BY Status";
                                        $result = $conn->query($query);

                                        if ($result) {
                                            // Initialize counts
                                            $activeCount = 0;
                                            $deactivatedCount = 0;

                                            // Fetch results
                                            while ($row = $result->fetch_assoc()) {
                                                if (strtolower($row['Status']) == 'active') {
                                                    $activeCount = $row['count'];
                                                } elseif (strtolower($row['Status']) == 'deactivated') {
                                                    $deactivatedCount = $row['count'];
                                                }
                                            }

                                            // Calculate and output total employees
                                            $totalEmployees = $activeCount + $deactivatedCount;
                                            echo $totalEmployees;
                                            
                                            // Free result set
                                            $result->free();
                                        } else {
                                            echo "Error: " . $conn->error;
                                        }

                                        // Close connection (optional)
                                        $conn->close();
                                    ?>
                                </h6>

                                </div>
                                <div class="col"><img src="../Resources/img/icons8-employees-30 (1).png" ></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card" style="background-image:url(&quot;../Resources/img/dashboard_card_background_249x118.jpg&quot;);padding-bottom:12px;border-radius:15px;width:250px;border:1px solid #6fbfed;overflow:visible;">
                        <div class="card-body">
                            <h5 class="card-title">Active Employees<br></h5>
                            <div class="row">
                                <div class="col">
                                    <h6 class="text-muted mb-2" style="color:black;">
                                    <?php
                                            include '../Database/db1.php'; // Include your MySQLi database connection

                                            // Query to count only active employees
                                            $query = "SELECT COUNT(*) AS total_active_employees FROM employee WHERE Status = 'active'";
                                            $result = $conn->query($query);

                                            if ($result) {
                                                // Fetch the result
                                                $row = $result->fetch_assoc();
                                                
                                                // Output the total count of active employees
                                                echo $row['total_active_employees'];
                                                
                                                // Free result set
                                                $result->free();
                                            } else {
                                                echo "Error: " . $conn->error;
                                            }

                                            // Close connection (optional)
                                            $conn->close();
                                        ?>
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <div class="row" style="margin-top:50px;margin-bottom:-70px">
                <?php include 'piechart_data.php'; ?>
                    <div class="col-md-4" style="background-color:white;margin-bottom: 120px;margin-left:15px;">
<h5 style="margin-top:30px;">Expenses Breakdown:<br></h5>
<div>
    <canvas id="pieChartSep" width="50" height="50"></canvas>
    </div>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Pass PHP values to JavaScript
    const salaries = <?php echo $salaries; ?>;
    const salaryextras = <?php echo $salaryextras; ?>;
    const salarydeductions = <?php echo $salarydeductions; ?>;

    const ctxPie = document.getElementById('pieChartSep').getContext('2d');
    const pieChartSep = new Chart(ctxPie, {
        type: 'pie',
        data: {
            labels: ['Salaries', 'Extras', 'Deductions'],
            datasets: [{
                data: [salaries, salaryextras, salarydeductions], // Dynamic Data from PHP
                backgroundColor: ['#6495ED', '#87CEEB', '#000080'],
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
<?php include 'linechart_data.php'; ?>

<div class="col-md-5" style="margin-left:150px; background-color:white; margin-bottom:120px;">
<h5 style="margin-top:30px;">Earnings Over Time:<br></h5>
<div>
    <canvas id="lineChartSep" width="50" height="50"></canvas>


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
                                <?php include 'barchart_data.php'; ?>
<div class="col-md-5" style="background-color:white;">
<h5 style="margin-top:30px;">Total Salaries:<br></h5>
<div>
<canvas id="earningsBarChartSep" width="300" height="300"></canvas>
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
                        label: 'Total Salaries (Rs)',
                        data: earnings, // Employee Salaries
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
        </div>
    </div>
    
