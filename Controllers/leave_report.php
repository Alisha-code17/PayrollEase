
<?php
include '../Database/db1.php';
$currentYear = date("Y");

// Get the previous month name (e.g., if now is May, use April)
$previousMonth = date("F", strtotime("first day of -1 month"));

$total = $approved = $pending = $rejected = 0;

// Get leave status counts for the previous month
$query = $conn->prepare("SELECT Status, COUNT(*) as count FROM `leave` WHERE Year = ? AND Month = ? GROUP BY Status");
$query->bind_param("ss", $currentYear, $previousMonth);
$query->execute();
$result = $query->get_result();

while ($row = $result->fetch_assoc()) {
    $count = (int)$row['count'];
    $status = $row['Status'];

    $total += $count;

    if ($status === 'Approved') $approved += $count;
    if ($status === 'Pending') $pending += $count;
    if ($status === 'Rejected') $rejected += $count;
}
?>



<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>leave-report</title>
    <link rel="stylesheet" href="..Resources/bootstrap/css/bootstrap.min.css">
   <!-- <link rel="stylesheet" href="..Resources/css/leave_styles.css">-->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

    <div class="container" style="padding-right:0px;width:1120px;margin-right:0px;margin-left:0px;">
	<div class="row" style="margin-top:37px;width:1207px;height:65px;">
            <div class="col" style="margin-top:22px;">
                <h3>Leave Report</h3>
            </div>
			<div class="dropdown" style="margin-left:462px;margin-top:22px;width:200px">
					<button class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false" type="button" style="background-color:white;color:#1a73e8;">
						<img src="../Resources/img/rec.png" style="width:20px;height:20px;border-radius:50%;">Export&nbsp;
					</button>
					<div class="dropdown-menu  my-custom-dropdown" role="menu">
						<button type="button" id="exportButton" class="dropdown-item" style="padding-left:10px;padding-right:10px;" onclick="leave_report_print()">
							Export as PDF
						</button>
					</div>
			</div>
            <!--<div class="col" style="width:619px;">
                <div class="dropdown" style="margin-left:462px;margin-top:22px;width:115px;"><button class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false" type="button" style="background-color:white;color:#1a73e8;" ><img src="../Resources/img/rec.png" style="width:20px;height:20px;border-radius:50%;">Export&nbsp;</button>
                    <div class="dropdown-menu" role="menu"><a class="dropdown-item" role="presentation" href="#" style="padding-left:10px;padding-right:4px;width:121px;" onclick="leave_report_print()">Export as PDF</a></div>
                </div>
            </div>-->
        </div>
        <div class="row" style="padding-top:0px; height:35px">
            <div class="col" style="margin-top:1px;">
                <p style="font-size:19px;">Report/Leave Report</p>
            </div>
			
        </div>
       <!-- <div class="col" style="margin-top:10px;">
                    <h5>Employee List</h5>
        </div>-->
	<div>
            <div>
                <div class="d-flex justify-content-center align-content-center" style="width:1160px;margin-right:30px;margin-left:-20px;margin-top:1px;">
                    <div class="row" style="width:1160px;margin-top:19px;height:274px;">
                        <div class="col">
                            <div class="card" style="width:350px;padding-top:0px;padding-bottom:0px;margin-bottom:30px;border:3px solid #dba9cf;border-radius:5px;box-shadow:5px 5px 15px rgba(0, 0, 0, 0.2);">
                                <div class="card-body d-flex justify-content-center align-content-center">
                                    <div class="row" style="width:229px;">
                                        <div class="col" style="padding-right:0px;padding-left:15px;width:1px;">
                                            <div style="width:50px;"></div><img src="../Resources/img/leave (8).png"></div>
                                        <div class="col" style="width:273px;">
                                            <div style="width:134px;">
                                                <p style="width:163px;font-size:17px;margin-bottom:3px;">Total Leaves</p>
                                                 <p style="width:100px;font-size: 15px;color:#c0c0c0f7;">(Last &nbsp;Month)<br></p>
                                               <h4><?php echo $total; ?></h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card" style="border:3px solid #9B7EBD;border-radius:5px;box-shadow:5px 5px 15px rgba(0, 0, 0, 0.2);">
                                <div class="card-body d-flex justify-content-center align-content-center">
                                    <div class="row" style="width:229px;">
                                        <div class="col" style="padding-right:0px;padding-left:15px;width:1px;">
                                            <div style="width:50px;"></div><img src="../Resources/img/leave (13).png"></div>
                                        <div class="col" style="width:273px;">
                                            <div style="width:134px;">
                                                <p style="width:163px;font-size:17px;margin-bottom:3px;">Pending Leaves</p>
                                                 <p style="width:100px;font-size: 15px;color:#c0c0c0f7;">(Last &nbsp;Month)<br></p>
                                               <h4><?php echo $pending; ?></h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card" style="width:350px;border-radius:5px;border:3px solid #7FC7D9;box-shadow:box-shadow: 5px 5px 15px rgba(0, 0, 0, 0.2);box-shadow:5px 5px 15px rgba(0, 0, 0, 0.2);">
                                <div class="card-body d-flex justify-content-center align-content-center">
                                    <div class="row" style="width:229px;">
                                        <div class="col" style="padding-right:0px;padding-left:15px;width:1px;">
                                            <div style="width:50px;"></div><img src="../Resources/img/leave (9).png"></div>
                                        <div class="col" style="width:273px;">
                                            <div style="width:134px;">
                                                <p style="width:163px;font-size:17px;margin-bottom:3px;">Approved Leaves</p>
                                                 <p style="width:100px;font-size: 15px;color:#c0c0c0f7;">(Last &nbsp;Month)<br></p>
                                                <h4><?php echo $approved; ?></h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card" style="margin-top:30px;border-radius:5px;border:3px solid #eb7171;box-shadow:box-shadow: 5px 5px 15px rgba(0, 0, 0, 0.2);box-shadow:5px 5px 15px rgba(0, 0, 0, 0.2);">
                                <div class="card-body d-flex justify-content-center align-content-center">
                                    <div class="row" style="width:229px;">
                                        <div class="col" style="padding-right:0px;padding-left:15px;width:1px;"><img src="../Resources/img/leave (10).png">
                                            <div style="width:50px;"></div>
                                        </div>
                                        <div class="col" style="width:273px;">
                                            <div style="width:134px;">
                                                <p style="width:163px;font-size:17px;margin-bottom:3px;">Rejected Leaves</p>
                                                 <p style="width:100px;font-size: 15px;color:#c0c0c0f7;">(Last &nbsp;Month)<br></p>
                                                <h4><?php echo $rejected; ?></h4>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
						<div id="leaveReport" class="col" style="padding-left:29px; width: 400px; height: 280px;">
    <canvas id="leave_barChart" width="400" height="300" style="display: block !important; width: 100% !important; height: 100% !important;"></canvas>
</div>
                        <!--<div class="col" style="padding-left:29px;"><canvas id="barChart"></canvas>-->


<!--<script>
    document.addEventListener("DOMContentLoaded", function () {
        const months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
        const attendanceData = [20, 18, 22, 25, 21, 19, 23, 24, 26, 28, 22, 20]; // Days Present per Month

        // Light colors for each bar
        const barColors = [
            "rgba(255, 99, 132, 0.4)",  // Light Red
            "rgba(54, 162, 235, 0.4)",  // Light Blue
            "rgba(75, 192, 192, 0.4)",  // Light Green
            "rgba(255, 206, 86, 0.4)",  // Light Yellow
            "rgba(153, 102, 255, 0.4)", // Light Purple
            "rgba(255, 159, 64, 0.4)",  // Light Orange
            "rgba(255, 99, 255, 0.4)",  // Light Pink
            "rgba(102, 255, 204, 0.4)", // Light Cyan
            "rgba(255, 204, 153, 0.4)", // Light Peach
            "rgba(204, 255, 102, 0.4)", // Light Lime
            "rgba(102, 178, 255, 0.4)", // Light Sky Blue
            "rgba(192, 192, 192, 0.4)"  // Light Gray
        ];

        new Chart(document.getElementById("barChart").getContext("2d"), {
            type: "bar",
            data: {
                labels: months,
                datasets: [{
                    label: "Total Present Days",
                    data: attendanceData,
                    backgroundColor: barColors,
                    borderColor: "rgba(0, 0, 0, 0.1)",
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>
</div>-->
                    </div>
                </div>
            </div>
        </div>
        <div class="container mt-4">
    <div style="display: flex; justify-content: space-between; align-items: center;padding-left:0px;padding-top:40px;">
        <p style="padding-right:20px;padding-top:12px;">Search by name and id</p>
        <form id="searchForm">
		<div class="input-group" style="width: 250px;">
            <input type="text" class="form-control" name= "search" id="search" placeholder="Search...">
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
     <div style="display: flex; justify-content: center;">
   
    <div style="border-radius:7px;border:1px solid #007bff;width:1160px;background-color:white;">
        <div class="table">
            <form method="POST" id="exportForm3" action="leave_report_print.php">
			<table class="table leave-table">
            <thead>
  <tr>
    <th style="background-color:#3786D5;color:white;width:35px;padding:10px;">
      <input type="checkbox" id="mainCheckbox" style="width:21px;height:15px;">
    </th>
    <th style="background-color:#3786D5;color:white;width:127px;padding:10px;">Em ID</th>
    <th style="background-color:#3786D5;color:white;width:127px;padding:10px;">Name</th>
    <th style="background-color:#3786D5;color:white;width:127px;">Department</th>
    <th style="background-color:#3786D5;color:white;padding-top:10px;width:127px;">Designation</th>
    <th style="background-color:#3786D5;color:white;width:127px;">Leave Type</th>
    <th style="background-color:#3786D5;color:white;width:127px;padding:10px;padding-left:10px;">Month</th>
    <th style="background-color:#3786D5;color:white;width:127px;padding-left:5px;">Year</th>
    <th style="background-color:#3786D5;color:white;padding:10px;width:127px;">Total days</th>
    <th style="background-color:#3786D5;color:white;width:127px;padding:10px;">Status</th>
  </tr>
</thead>

				
                <tbody>
    <?php

    // SQL Query (Using LEFT JOIN to get all employees even if they have no leave record)
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
	LEFT JOIN leavetype lt ON l.LeaveType_id = lt.LeaveType_id"; // Removed extra semicolon

// Execute query
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
                            <p style='margin-bottom:1px;color:#212529;'>" . $row['Employee_Name'] . "</p>
                        </div>
                    </div>
                </div>
              </td>";
         echo "<td>" . $row['Department_Name'] . "</td>";
		 echo "<td>" . $row['Designation_Name'] . "</td>";
     if (!isset($row['TypeName'])) {
    echo "<td style='color: red;'>Column Not Found</td>"; 
} else {
    echo "<td>" . $row['TypeName'] . "</td>";
}
        echo "<td>" . $row['Month'] . "</td>";
        echo "<td>" . $row['Year'] . "</td>";
        echo "<td>" . $row['Totaldays'] . "</td>";
        echo "<td>" . $row['Status'] . "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='9'>No leave records found.</td></tr>";
}
$conn->close();
?>
</tbody>
</form>

                    <!--<tr>
                        <td><input type="checkbox" style="width:15px;height:15px;"></td>
                        <td>EMP_01</td>
                        <td>
                            <div class="row" style="height:60px;width:154px;">
                                <div class="col" style="padding-right:0px;">
                                    <div style="width:50px;"><img src="../Resources/img/em1.jpg" style="width:50px;height:50px;border-radius:50%;"></div>
                                </div>
                                <div class="col d-flex align-items-center align-content-center" style="margin-top:4px;">
                                    <div>
                                        <p style="margin-bottom:1px;">Ali hassan</p>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>Fiance</td>
                        <td>Manager</td>
                        <td style="padding-left:43px;">2021</td>
                        <td style="padding-left:50px;">January</td>
                        <td class="d-flex justify-content-center">29</td>
                        <td style="padding-left:12px;">Approved</td>
                    </tr>
                    
                   -->
                
            </table>
        </div>
    </div>
	</div>
    <script src="../Resources/js/jquery.min.js"></script>
    <script src="../Resources/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>