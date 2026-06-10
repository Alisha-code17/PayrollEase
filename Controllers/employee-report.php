<?php
include '../Database/db1.php';
// Count Active and Inactive employees
$sql = "SELECT Status, COUNT(*) AS count FROM employee GROUP BY Status";
$result = $conn->query($sql);

$active = 0;
$deactivated = 0;
$totalEmployees = 0;
$newEmployees = 0;

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $status = $row['Status'];
        if ($status === "Active") {
            $active += (int)$row["count"];
        } elseif ($status === "Deactivated") {
            $deactivated += (int)$row["count"];
        }
    }
}

// Calculate total employees after summing up both statuses
$totalEmployees = $active + $deactivated;

// Get new employees (joined in current month)
$currentMonth = date('m');
$currentYear = date('Y');
$sqlNew = "SELECT COUNT(*) AS count FROM employee WHERE MONTH(JoiningDate) = $currentMonth AND YEAR(JoiningDate) = $currentYear";
$resultNew = $conn->query($sqlNew);
if ($resultNew && $rowNew = $resultNew->fetch_assoc()) {
    $newEmployees = (int)$rowNew['count'];
}
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
    
</head>
<style>
	/* Print-specific styles */
    @media print {
    body {
        background-color: #fff;
        color: #000;
        margin: 0;
        font-size: 12pt;
    }
    .btn-primary-custom {
        display: none; /* Hide the print button */
    }
    .employee-report-container {
        border: none; /* Remove unnecessary borders */
        box-shadow: none; /* Remove shadows */
    }
    h1, h4 {
        color: black; /* Ensure text is visible */
    }
}
</style>

<body style="font-family:'Merriweather Sans', sans-serif;">
<div style="display: flex; justify-content: center;">

    <div class="container" style="padding-right:0px;width:1207px; padding-right:0px;margin-right:0px;margin-left:0px;">
		 <div class="row" style="margin-top:37px;width:1207px;height:65px;">
            <div class="col" style="margin-top:22px;">
                <h3>Employee Report</h3>
            </div>
			<div class="dropdown" style="margin-left:462px;margin-top:22px;width:200px">
					<button class="btn btn-primary dropdown-toggle" data-toggle="dropdown"  data-bs-display="static" aria-expanded="false" type="button" style="background-color:white;color:#1a73e8;">
						<img src="../Resources/img/rec.png" style="width:20px;height:20px;border-radius:50%;">Export&nbsp;
					</button>
					<div class="dropdown-menu my-custom-dropdown" role="menu">
						<button type="button" id="exportButton" class="dropdown-item" style="padding-left:10px;padding-right:10px;" onclick="employeeReportPrint()">
							Export as PDF
						</button>
					</div>
			</div>
        </div>
        <div class="row" style="padding-top:0px;padding-bottom:0px; height:35px;">
            <div class="col" style="margin-top:1px;">
                <p style="font-size:19px;">Report/Employee Report</p>
            </div>
        </div>
		<div class="d-flex justify-content-center align-content-center" style="width:1160px;margin-right:30px;margin-left:-20px;margin-top:1px;">
            <div class="row" style="width:1160px;margin-top:19px;">
                <div class="col">
                    <div class="card" style="width:350px;padding-top:0px;padding-bottom:0px;margin-bottom:30px;border:3px solid #ADB2D4;border-radius:5px;box-shadow:5px 5px 15px rgba(0, 0, 0, 0.2);">
                        <div class="card-body d-flex justify-content-center align-content-center">
                            <div class="row" style="width:229px;">
                                <div class="col" style="padding-right:0px;padding-left:15px;width:1px;">
                                    <div style="width:50px;"><img src="../Resources/img/employee (9).png"></div>
                                </div>
                                <div class="col" style="width:273px;">
                                    <div style="width:134px;">
                                        <p style="width:163px;">Total &nbsp;Employees<br></p>
                                        <h4><?php echo $totalEmployees; ?></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card" style="border:3px solid #dba9cf;border-radius:5px;box-shadow:5px 5px 15px rgba(0, 0, 0, 0.2);">
                        <div class="card-body d-flex justify-content-center align-content-center">
                            <div class="row" style="width:229px;">
                                <div class="col" style="padding-right:0px;padding-left:15px;width:1px;"><img src="../Resources/img/employee (7).png">
                                    <div style="width:50px;"></div>
                                </div>
                                <div class="col" style="width:273px;">
                                    <div style="width:134px;">
                                        <p style="width:163px;">Deactive Employees<br></p>
                                        <h4><?php echo $deactivated; ?></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card" style="width:350px;border-radius:5px;border:3px solid #f5dd42;box-shadow:box-shadow: 5px 5px 15px rgba(0, 0, 0, 0.2);box-shadow:5px 5px 15px rgba(0, 0, 0, 0.2);">
                        <div class="card-body d-flex justify-content-center align-content-center">
                            <div class="row" style="width:229px;">
                                <div class="col" style="padding-right:0px;padding-left:15px;width:1px;">
                                    <div style="width:50px;"></div><img src="../Resources/img/employee (8).png"></div>
                                <div class="col" style="width:273px;">
                                    <div style="width:134px;">
                                        <p style="width:163px;">Active Employees<br></p>
                                        <h4><?php echo $active; ?></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card" style="margin-top:30px;border-radius:5px;border:3px solid #eb7171;box-shadow:box-shadow: 5px 5px 15px rgba(0, 0, 0, 0.2);box-shadow:5px 5px 15px rgba(0, 0, 0, 0.2);">
                        <div class="card-body d-flex justify-content-center align-content-center">
                            <div class="row" style="width:229px;">
                                <div class="col" style="padding-right:0px;padding-left:15px;width:1px;">
                                    <div style="width:50px;"></div><img src="../Resources/img/employee (6).png"></div>
                                <div class="col" style="width:273px;">
                                    <div style="width:134px;">
                                        <p style="width:163px;">New Employees<br></p>
                                        <h4><?php echo $newEmployees;?></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
			<div class="col" style="padding-left:29px;">
    
    <canvas id="employeeLollipopChart"></canvas>
</div>

    <!--<script>
        document.addEventListener("DOMContentLoaded", function () {
            const ctx = document.getElementById("employeeLollipopChart").getContext("2d");

            const employeeStatus = ["Active Employees", "Inactive Employees"];
            const employeeCount = [40, 10]; // Employee counts
            const colors = ["rgba(54, 162, 235, 0.7)", "rgba(255, 99, 132, 0.7)"];

            // Data points for the lollipop markers
            const dataPoints = employeeCount.map((value, index) => ({
                x: employeeStatus[index], // Use the status as the x value
                y: value
            }));

            // Create the chart
            new Chart(ctx, {
                type: "scatter",
                data: {
                    datasets: [{
                        label: "", // Removed the label for the lollipop markers
                        data: dataPoints,
                        pointBackgroundColor: colors,
                        pointBorderColor: colors,
                        pointRadius: 8, 
                        pointHoverRadius: 10,
                        showLine: false, // Set to false to not connect the points
                    }, {
                        type: "bar",
                        data: employeeCount.map((value, index) => ({ x: employeeStatus[index], y: value })),
                        backgroundColor: colors.map(color => color.replace("0.7", "0.3")),
                        borderColor: colors,
                        borderWidth: 2,
                        barThickness: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            type: "category", // Change to category scale
                            labels: employeeStatus // Set the labels directly
                        },
                        y: {
                            beginAtZero: true,
                            max: 50 // Set max limit to control height
                        }
                    },
                    plugins: {
                        legend: {
                            display: false // Hide the legend
                        }
                    }
                }
            });
        });
    </script>-->
            </div>
        </div>
       
     <div class="container mt-4">
     <div style="display: flex; justify-content: space-between; align-items: center;padding-left:0px;">
        <p style="padding-right:20px;padding-top:12px;">Search by name and id</p>
		<form id="searchForm">
        <div class="input-group" style="width: 250px;">
            <input type="text" class="form-control" name="search" id="search" placeholder="Search...">
            <button class="btn btn-primary" type="button">
                <i class="fas fa-search"></i>
            </button>
        </div>
		</form>
		<p style="padding-left:100px;padding-top:13px;padding-left:200px;" >Search by joining date</p>
        <div style="flex-grow: 1;"></div>
        <div class="input-group" style="width: 250px;">
            <input type="date" id="joiningDate" class="form-control">
        </div>
    </div>
</div>

    </div>
    </div>
    </div>
    </div>
	<div style="display: flex; justify-content: center;">

    <div style="border-radius:7px;border:1px solid #007bff;width:1160px;background-color:white;">
        <div class="table" style="/*border:1px solid #ccc;*/border-radius:1px;box-sizing:border-box;/*border-color:#dfe5e8;*//*border-style:inset;*/">
            <form method="POST" id="exportForm" action="employee-report-print.php">
			<table class="table">
                <thead>
                    <tr style="background-color:#3786D5;color:white;">
                        <th style="width:35px;padding:10px;"><input type="checkbox" id="mainCheckbox" style="width:21px;height:15px;"></th>
                        <th style="width:127px;padding:10px;/*color:#1a73e8;*/">Em ID<br></th>
                        <th style="width:127px;padding:10px;/*color:#1a73e8;*/">Name<br></th>
                        <th style="width:127px;/*color:#1a73e8;*/">Department<br></th>
                        <th style="padding-top:10px;width:127px;/*color:#1a73e8;*/">Designation<br></th>
                        <th style="width:127px;padding:10px;/*color:#1a73e8;*/"><strong>Email</strong><br></th>
                        <th style="width:127px;/*color:#1a73e8;*/">Phone<br></th>
                        <th style="padding:10px;width:127px;/*color:#1a73e8;*/">Joining Date<br></th>
                        <th style="width:127px;padding:10px;/*color:#1a73e8;*/">Status</th>
                    </tr>
                </thead>
				
                <tbody>
            <?php
            // Database connection
            $conn = new mysqli("localhost", "root", "", "payrollease");
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // SQL Query
            $sql = "SELECT 
                        e.Employee_id, 
                        e.FirstName, 
                        e.LastName, 
						e.Picture,
                        e.Email, 
                        e.Phone, 
                        e.JoiningDate, 
                        e.Status, 
                        d.Name AS Department_Name, 
                        des.Name AS Designation_Name
                    FROM employee e
                    LEFT JOIN department d ON e.Department_id = d.Department_id
                    LEFT JOIN designation des ON e.Designation_id = des.Designation_id";

            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
    echo "<td>
            <input type='checkbox' class='subCheckbox' name='employees[]' value='" . $row['Employee_id'] . "' style='width:15px;height:15px;'>
          </td>";
    echo "<td>" . $row['Employee_id'] . "</td>";
    echo "<td>
            <div class='row' style='height:60px;width:200px;'>
                <div class='col' style='padding-right:0px;'>
                    <div style='width:50px;'>
                        <img src='../Controllers/uploads/" . $row['Picture'] . "' style='width:50px;height:50px;border-radius:50%;'>
                    </div>
                </div>
                <div class='col d-flex align-items-center align-content-center' style='margin-top:4px;'>
                    <div>
                        <p style='margin-bottom:1px;color:#212529;'>" . $row['FirstName'] . " " . $row['LastName'] . "</p>
                    </div>
                </div>
            </div>
          </td>";
    echo "<td>" . $row['Department_Name'] . "</td>";
    echo "<td>" . $row['Designation_Name'] . "</td>";
    echo "<td>" . $row['Email'] . "</td>";
    echo "<td>" . $row['Phone'] . "<br></td>";
    echo "<td>" . date("d-m-Y", strtotime($row['JoiningDate'])) . "<br></td>";
    echo "<td>" . $row['Status'] . "<br></td>";
    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='9'>No employees found.</td></tr>";
            }
            $conn->close();
            ?>
        </tbody>
            </table>
			</form>
        </div>
    </div>
    </div>


    <script src="../Resources/js/jquery.min.js"></script>
    <script src="../Resources/bootstrap/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.15/js/dataTables.bootstrap.min.js"></script>
</body>

</html>