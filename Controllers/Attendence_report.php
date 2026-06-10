<?php
// Database connection
include '../Database/db1.php';
// Get last month and current year
$now = new DateTime();
$now->modify('first day of last month');
$lastMonth = $now->format('F'); // e.g., "April"
$lastMonthNumber = $now->format('n'); // 1-12
$currentYear = $now->format('Y');

// Calculate total days in the last month
$totalDaysInMonth = cal_days_in_month(CAL_GREGORIAN, $lastMonthNumber, $currentYear);

// Fetch present, absent from attendance
$stmt = $conn->prepare("SELECT 
    SUM(Present_days) AS total_present, 
    SUM(Absent_days) AS total_absent 
    FROM attendance 
    WHERE Month = ? AND Year = ?");
$stmt->bind_param("si", $lastMonth, $currentYear);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

$total_present = $data['total_present'] ?? 0;
$total_absent = $data['total_absent'] ?? 0;

// Get total employees recorded in attendance
$emp_stmt = $conn->prepare("SELECT COUNT(DISTINCT Employee_id) AS employee_count FROM attendance WHERE Month = ? AND Year = ?");
$emp_stmt->bind_param("si", $lastMonth, $currentYear);
$emp_stmt->execute();
$emp_result = $emp_stmt->get_result();
$emp_data = $emp_result->fetch_assoc();
$employee_count = $emp_data['employee_count'] ?? 0;

// Calculate total possible days
$total_possible_days = $totalDaysInMonth * $employee_count;

// Optional: Get leave days from leave table (if exists)
$leave_stmt = $conn->prepare("SELECT SUM(Totaldays) AS total_leaves FROM `leave` WHERE Month = ? AND Year = ?");
$leave_stmt->bind_param("si", $lastMonth, $currentYear);
$leave_stmt->execute();
$leave_result = $leave_stmt->get_result();
$leave_data = $leave_result->fetch_assoc();
$total_leaves = $leave_data['total_leaves'] ?? 0;

// Compute ratios (avoid division by zero)
$present_ratio = $total_possible_days > 0 ? round(($total_present / $total_possible_days) * 100, 2) : 0;
$absent_ratio = $total_possible_days > 0 ? round(($total_absent / $total_possible_days) * 100, 2) : 0;
$leave_ratio = $total_possible_days > 0 ? round(($total_leaves / $total_possible_days) * 100, 2) : 0;
?>


<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance-report</title>
    <link rel="stylesheet" href="../Resources/bootstrap/css/bootstrap.min.css">
</head>
<!-- <style>
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
</style> -->
<body style="font-family:'Merriweather Sans', sans-serif;">
<div style="display: flex; justify-content: center;">

    <div class="container" style="padding-right:0px;width:1207px;margin-right:0px;margin-left:0px;">
	<div class="row" style="margin-top:37px;width:1207px;height:65px;">
            <div class="col" style="margin-top:22px;">
                <h3>Attendance Report</h3>
            </div>
			
            <!--<div class="col" style="width:619px;">
                <div class="dropdown" style="margin-left:462px;margin-top:22px;width:115px;"><button class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false" type="button" style="background-color:white;color:#1a73e8;"><img src="../Resources/img/rec.png" style="width:20px;height:20px;border-radius:50%;margin-right:0px;">Export&nbsp;</button>
                    <div class="dropdown-menu" role="menu"><a class="dropdown-item" role="presentation" href="#" style="padding-left:10px;padding-right:4px;width:121px;" onclick="AttendanceReportPrint()">Export as PDF</a></div>
                </div>
            </div>-->
			<div class="dropdown" style="margin-left:462px;margin-top:22px;width:200px">
					<button class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false" type="button" style="background-color:white;color:#1a73e8;">
						<img src="../Resources/img/rec.png" style="width:20px;height:20px;border-radius:50%;">Export&nbsp;
					</button>
					<div class="dropdown-menu  my-custom-dropdown" role="menu">
						<button type="button" id="exportButton" class="dropdown-item" style="padding-left:10px;padding-right:10px;" onclick="AttendanceReportPrint()">
							Export as PDF
						</button>
					</div>
			</div>
     </div>
        <div class="row" style="padding-top:0px;height:35px;">
            <div class="col" style="margin-top:1px;">
                <p style="font-size:19px;">Report/Attendance Report</p>
            </div>
        </div>
		<div>
            <div class="d-flex justify-content-center align-content-center" style="width:1160px;margin-right:30px;margin-left:-20px;margin-top:1px;">
                <div class="row" style="width:1160px;margin-top:19px;">
                    <div class="col">
                        <div class="card" style="width:350px;padding-top:0px;padding-bottom:0px;margin-bottom:30px;border:3px solid #dba9cf;border-radius:5px;box-shadow:5px 5px 15px rgba(0, 0, 0, 0.2);">
                            <div class="card-body d-flex justify-content-center align-content-center">
                                <div class="row" style="width:229px;">
                                    <div class="col" style="padding-right:0px;padding-left:15px;width:1px;">
                                        <div style="width:50px;"></div><img src="../Resources/img/schedule.png"></div>
                                    <div class="col" style="width:273px;">
                                        <div style="width:134px;">
                                            <p style="width:163px;margin-bottom:3px;">Total &nbsp;Days<br></p>
                                            <p style="width:100px;font-size: 15px;color:#c0c0c0f7;">(Last &nbsp;Month)<br></p>
                                            <h4><?php echo $totalDaysInMonth; ?></h4>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card" style="border:3px solid #ADB2D4;border-radius:5px;box-shadow:5px 5px 15px rgba(0, 0, 0, 0.2);">
                            <div class="card-body d-flex justify-content-center align-content-center">
                                <div class="row" style="width:229px;">
                                    <div class="col" style="padding-right:0px;padding-left:15px;width:1px;">
                                        <div style="width:50px;"></div><img src="../Resources/img/schedule-2.png"></div>
                                    <div class="col" style="width:273px;">
                                        <div style="width:134px;">
                                            <p style="width:163px;margin-bottom:3px;">Present Ratio</p>
                                            <p style="width:100px;font-size: 15px;color:#c0c0c0f7;">(Last &nbsp;Month)<br></p>
                                            <h4><?php echo $present_ratio . '%'; ?></h4>

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
                                        <div style="width:50px;"></div><img src="../Resources/img/schedule-1.png"></div>
                                    <div class="col" style="width:273px;">
                                        <div style="width:134px;">
                                            <p style="width:163px;margin-bottom:3px;">Absent Ratio<br></p>
                                            <p style="width:100px;font-size: 15px;color:#c0c0c0f7;">(Last &nbsp;Month)<br></p>
                                            <h4><?php echo $absent_ratio . '%'; ?></h4>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card" style="margin-top:30px;border-radius:5px;border:3px solid #eb7171;box-shadow:box-shadow: 5px 5px 15px rgba(0, 0, 0, 0.2);box-shadow:5px 5px 15px rgba(0, 0, 0, 0.2);">
                            <div class="card-body d-flex justify-content-center align-content-center">
                                <div class="row" style="width:229px;">
                                    <div class="col" style="padding-right:0px;padding-left:15px;width:1px;"><img src="../Resources/img/schedule-3.png">
                                        <div style="width:50px;"></div>
                                    </div>
                                    <div class="col" style="width:273px;">
                                        <div style="width:134px;">
                                            <p style="width:163px;margin-bottom:3px;">Leave Ratio<br></p>
                                            <p style="width:100px;font-size: 15px;color:#c0c0c0f7;">(Last &nbsp;Month)<br></p>
                                            <h4><?php echo $leave_ratio . '%'; ?></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="attendanceReport" class="col" style="padding-left:29px; width: 400px; height: 300px;">
    <canvas id="Attendance_lineChart" width="400" height="300" style="display: block !important; width: 100% !important; height: 100% !important;"></canvas>
</div>
                </div>
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

    <div style="border-radius:7px;border:1px solid #007bff;width:1160px;">
        <div class="table-responsive d-flex justify-content-center align-items-center" style="border-radius:1px;box-sizing:border-box; background-color:white;">
         <form method="POST" id="exportForm2" action="attendance-report-print.php" style="width:1160px;">
		<table class="table">
                <thead>
                    <tr style="background-color:#3786D5;color:white;">
                        <th style="width:35px;padding:10px;"><input type="checkbox" id="mainCheckbox" style="width:21px;height:15px;"></th>
                        <th style="width:127px;padding:10px;/*color:#1a73e8;*/">Em ID<br></th>
                        <th style="width:127px;padding:10px;/*color:#1a73e8;*/">Name<br></th>
                        <th style="width:127px;/*color:#1a73e8;*/">Department<br></th>
                        <th style="padding-top:10px;width:127px;/*color:#1a73e8;*/">Designation<br></th>
                        <th style="width:127px;padding:10px;/*color:#1a73e8;*/padding-left:10px;">Month</th>
                        <th style="width:127px;/*color:#1a73e8;*/padding-left:10px;">Year</th>
                        <th style="padding:10px;width:127px;/*color:#1a73e8;*/">Present days</th>
                        <th style="width:127px;padding:10px;/*color:#1a73e8;*/">Absent days</th>
                    </tr>
                </thead>
		<tbody> 
			<?php
                // SQL Query to fetch attendance data
                $sql = "SELECT 
                            e.Employee_id, 
                            e.FirstName AS Employee_Name, 
                            e.Picture AS Picture, 
                            d.Name AS Department_Name, 
                            des.Name AS Designation_Name, 
                            a.Present_Days, 
                            a.Absent_Days, 
                            a.Month, 
                            a.Year
                        FROM employee e
                        JOIN department d ON e.Department_id = d.Department_id
                        JOIN designation des ON e.Designation_id = des.Designation_id
                        JOIN attendance a ON e.Employee_id = a.Employee_id";

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
                        echo "<td>" . $row['Month'] . "</td>";
                        echo "<td>" . $row['Year'] . "</td>";
                        echo "<td>" . $row['Present_Days'] . "</td>";
                        echo "<td>" . $row['Absent_Days'] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='9'>No attendance records found.</td></tr>";
                }
                $conn->close();
             ?>
        </tbody>
        </table>
        </form>       
                   
        </div>
    </div>
	</div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="../Resources/js/jquery.min.js"></script>
    <script src="..Resources/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>