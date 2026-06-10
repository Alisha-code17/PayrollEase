<?php
session_start();

// Include MySQLi DB connection
include '../Database/db1.php';

// Check if session variables exist
if (!isset($_SESSION['Employee_id']) || !isset($_SESSION['user_id'])) {
    die("Error: Required session variables are missing.");
}

$employeeId = $_SESSION['user_id'];
$employee_id_emp = $_SESSION['Employee_id'];

// Initialize variables with default values
$imagePath = "uploads/default.jpg";
$fullName = '';
$employee = [];

// First query to get picture and full name
$sql = "SELECT e.Picture, CONCAT(e.FirstName, ' ', e.LastName) AS FullName
        FROM employee e
        JOIN users u ON e.User_id = u.User_id
        WHERE u.User_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $employeeId);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();

if ($result) {
    // Load picture if exists
    if (!empty($result['Picture']) && file_exists('../Controllers/uploads/' . $result['Picture'])) {
        $imagePath = '../Controllers/uploads/' . $result['Picture'];
    }

    // Load full name if exists
    if (!empty($result['FullName'])) {
        $fullName = $result['FullName'];
    }
}
$stmt->close();

// Second query to get all employee details
$query = "
    SELECT 
        e.Employee_id, 
        e.FirstName, 
        e.LastName, 
        e.Address,
        e.Email, 
        e.Phone, 
        e.JoiningDate, 
        e.Status, 
        d.Name AS Department_Name, 
        des.Name AS Designation_Name,
        e.Picture
    FROM employee e
    LEFT JOIN department d ON e.Department_id = d.Department_id
    LEFT JOIN designation des ON e.Designation_id = des.Designation_id
    WHERE e.Employee_id = ?
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $employee_id_emp);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Employee not found.");
}

$employee = $result->fetch_assoc();
$stmt->close();

// Show alert if exists
if (isset($_SESSION['alert'])) {
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
    echo "<script>
        Swal.fire({
            icon: '" . $_SESSION['alert']['icon'] . "',
            title: '" . $_SESSION['alert']['title'] . "',
            text: '" . $_SESSION['alert']['text'] . "',
            showConfirmButton: false,
            timer: 3000
        });
    </script>";
    unset($_SESSION['alert']); // Clear alert after showing
}

$conn->close();
?>
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
	 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
<!--for das card-->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

	<link rel="stylesheet" href="../Resources/css/salary-breakdown.css">
    <link rel="stylesheet" href="../Resources/css/adminDashboard_sidebar.css">
    <link rel="stylesheet" href="../Resources/css/employeeDashboard.css">
</head>

<body style="color:rgba(33,37,41,0.67);margin-left:0px; overflow:auto;"><!--<div>Custom Code</div>-->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="row" id="container">
        <div class="col-md-2" id="sidebar" style="padding-right:0px;padding-left:20px;width:200px;">
            <!-- Sidebar Holder -->
            <nav id="sidebar">
                <div class="sidebar-header" style="height:83px">
                    <div class="row" style="margin-top:12px;margin-bottom:-5px;">
                        <div class="col-md-2" >
                    <img src="../Resources/img/logo-removebg-preview (1).png" style="width:90px;height:80px;margin-top:-20px;margin-left:-32px;">
                            </div>
                        <div class="col-md-4">
                    <h3 style="margin-left:10px;margin-top:4px;"><!--<img src="../Resources/img/logo-removebg-preview (1).png" style="width:50px;height:50px;margin-top:-1px;">-->PayrollEase</h3>
                    <strong>PE</strong>
                            </div>
                        </div>
                </div>
<div class="sidebar-content" id="sidebarContent">
                <ul class="list-unstyled components">
                   
                    <li>
                        <a href="#" onclick="loadPage('dash_emp.php'); return false;" style="cursor:pointer;">
                            <img src="../Resources/img/icons8-dashboard-layout-30 (4).png" style="width:25px;height:27px;margin-top:-1px;">
                            <i class="glyphicon glyphicon-emp"></i> Dashboard
                        </a>
                    </li>   
                    <li>
                        <a href="#empSubmenu" data-toggle="collapse">
                            <img src="../Resources/img/icons8-test-account-30 (1).png" style="width:25px;height:35px;margin-top:-1px;">
                            <i class="glyphicon glyphicon-emp"></i>Profile
                        </a>
                        <ul class="collapse list-unstyled" id="empSubmenu" style="margin-left:20px;">
                            <li><a href="#" onclick="loadPage('viewProfile.php')">View Profile</a></li>
                        </ul>
                    </li>
                   <li>
                       <a href="#depSubmenu" data-toggle="collapse">
                           <img src="../Resources/img/icons8-attendance-30 (3).png" style="width:25px;height:29px;margin-top:-1px;">
                            <i class="glyphicon glyphicon-dep"></i>Attendence
                        </a>
                        <ul class="collapse list-unstyled" id="depSubmenu" style="margin-left:20px;">
                            <li><a href="#"onclick="loadPage('EmpAttendance.php')">View Attendence</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="#levSubmenu" data-toggle="collapse">
                            <img src="../Resources/img/icons8-leave-30 (2).png" style="width:25px;height:27px;margin-top:-1px;">
                            <i class="glyphicon glyphicon-pay"></i>Leave
                        </a>
                        <ul class="collapse list-unstyled" id="levSubmenu" style="margin-left:20px;">
                            <li><a role="presentation" href="javascript:void(0);" style="color:#3786d5;" 
           onclick="loadPage('apply_leave.php')">Apply For Leave</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="#paySubmenu" data-toggle="collapse">
                            <img src="../Resources/img/icons8-money-transfer-30 (4).png" style="width:25px;height:27px;margin-top:-1px;">
                            <i class="glyphicon glyphicon-pay"></i>Payroll
                        </a>
                        <ul class="collapse list-unstyled" id="paySubmenu" style="margin-left:20px;">
                            <li><a href="#"onclick="loadPage('EmpPayrollReport.php')">Payroll Report</a></li>
                        </ul>
                    </li>
                   
                </ul>
                </div>
            </nav>
        <!-- jQuery CDN -->
         </div>
         <!-- Floating Chatbot Button with Custom GIF -->
<button class="chatbot-button" onclick="loadPage('../Views/chatBotEmp.html');">
    <img src="../Resources/img/chatbot_12544440.gif" alt="Chatbot" class="chatbot-gif" />
</button>

<style>
.chatbot-button {
  position: fixed;
  bottom: 20px;
  right: 20px;
  background-color: lightblue; /* Changed to light blue background */
  border: 2px solid blue; /* Blue border */
  border-radius: 50%;
  width: 70px !important;
  height: 70px;
  padding: 5px;
  cursor: pointer !important;
  z-index: 1000;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
  animation: bounce 2s infinite;
  display: flex;
  align-items: center;
  justify-content: center;
  outline: none;
}

.chatbot-button:hover {
  transform: scale(1.1);
}

.chatbot-button:focus {
  outline: none;
  box-shadow: none;
  border-color: blue; /* Keep it blue */
}

.chatbot-gif {
  /*width: 100%;*/
  height: 100%;
  object-fit: contain;
  border-radius: 50%;
}

/* Bouncing Button Animation */
@keyframes bounce {
  0%, 100% {
    transform: translateY(0);
  }
  50% {
    transform: translateY(-10px);
  }
}
</style>

<!--<script>
function openChat() {
  alert("Chat panel opens here.");
}
</script>-->


        <div class="col" id="content" style="padding:px;background-color:#F3F1FA;overflow:hidden;padding-top:35px;">
            <div style="margin-left:20px;">
                <div id="header" style="/*background-color:#e2e6ea;*/background-color:#2374C3;height:83px;position:absolute;top:35px;left:25px;transition:left 0.3s, width 0.3s;/*z-index:1000;*/margin-top:-35px;width:100%;margin-left:-10px;">
                    <div class="row" style="background-color:#2374C3;height:82px;/*margin-top:20px;*/margin-left:-5px;">
                        <div class="col-md-1"><button class="btn btn-primary d-flex justify-content-center" type="button" id="toggleSidebar" style="background-color:rgba(226,230,234,0);border:none;box-shadow:none;width:40px;height:40px;margin-left:0px;margin-top:20px;/*margin-bottom:-70px;*//*padding-top:5px;*//*padding-left:12px;*/"><img src="../Resources/img/icons8-menu-30 (3).png" style="width:40px;height:35px;"></button></div>
                        <div
                            class="col">
                           <!-- <h4 style="color:white;margin-top:26px;font-family:Roboto, sans-serif;">Admin Control Hub<br></h4>-->
                    </div>
                    <div class="col"><div class="container">
    <div class="row height text-start">
        <div class="col-md-auto" style="padding-top:0px; margin-left:150px; margin-top:20px;">
            <div class="search">
                <div class="form-control" style="border-radius:10px; height:40px; display:flex; align-items:center; padding-left:10px;">
                    <?php 
                    // Method 1: Try with explicit timezone
                    try {
                        $timezone = 'Asia/Manila'; // Change to your timezone
                        $currentDate = new DateTime('now', new DateTimeZone($timezone));
                        echo $currentDate->format('l, F j, Y');
                    } catch (Exception $e) {
                        // Method 2: Fallback to server time
                        //$currentDate = new DateTime();
                        //echo $currentDate->format('l, F j, Y') . " (Server Time)";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div id="errorMessage" style="color: red; display: none; margin-top: 10px;">
        No word found.
    </div>
</div>

                        <div class="dropdown d-flex align-self-end swing animated" style="width:50px;height:50px;right:50px;top:10px;margin-top:10px;position:absolute;z-index:1;"><button class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false" type="button" style="background-color:rgba(0,123,255,0);border-radius:50%;height:40px;width:40px;border:1px solid white;"><img src="<?php echo $imagePath; ?>"  style="margin-left:-17px;margin-bottom:0px;margin-top:-9px;height:40px;width:40px;border-radius:50%;"></button>
                            <div
                            class="dropdown-menu dropdown-menu-right" role="menu" style="height:210px;min-width:230px;/*max-height:none;*/position:absolute !important;z-index:1050 !important;font-family:Roboto, sans-serif;border:1px solid #3786d5;margin-left:0px;margin-right:40px;margin-top:13px;overflow:visible;/*transform:translateY(-10px);*//*transition:all 0.3s ease;*//*max-height:0;*//*transform:scaleY(0);*//*transition:transform 0.3s ease, opacity 0.3s ease;*//*opacity:0;*/"><a class="dropdown-item disabled d-flex justify-content-center align-items-center" role="presentation" href="#" style="background-color:#6FBFED;min-height:120px;">
                                <img src="<?php echo $imagePath; ?>" style="height:60px;width: 60px;border-radius:50%;">
                                <?php if (!empty($fullName)): ?>
                                    <h5 style="color:rgb(255,255,255);margin-left:10px;"><?php echo htmlspecialchars($fullName); ?><br></h5>
                                    <?php endif; ?></a>
                                <a
                                    class="dropdown-item" role="presentation" href="#" onclick="loadPage('viewProfileAdmin.php')" style="font-size:18px;"><img src="../Resources/img/icons8-account-20.png" style="margin-right:10px;">Profile<br></a>
                                    <a class="dropdown-item" role="presentation" href="#" style="font-size:18px;" data-toggle="modal" data-target="#logoutModal"><img src="../Resources/img/icons8-login-20.png" style="margin-right:10px;">Logout</a></div>
                    </div>
                </div>
            </div>
        </div>
		<div id="dashboard-content" class="container" style="margin-right:0px;margin-left:0px;padding-left:0px;padding-right:0px;">
        <div class="row" style="margin:9px;margin-left:-16px;">
            <div class="col" style="width:828px; padding-top:50px;">
                <h4 style="margin-top:9px;/*color:rgb(0,123,255);*/">Dashboard</h4>
            </div>
        </div>
        <h5></h5>
        <div class="row">
            <div class="col">
                <div class="card" style="width:500px;height:382px;">
                    <div class="card" style="width: 500px; margin-bottom: 20px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); border-radius: 8px; border: 1px solid #e0e0e0;">
    <div class="card-body" style="padding: 20px;height: 382px;">
        <div class="row align-items-center mb-3">
            <div class="col-auto" style="width: 63px; height: 63px;">
                <img src="<?php echo $imagePath; ?>"
                     style="border-radius: 50%; width: 54px; height: 54px; object-fit: cover;">
            </div>
            <div class="col">
                <h5 class="mb-1"><?php echo htmlspecialchars($employee['FirstName'] . ' ' . $employee['LastName']); ?></h5>
                <p class="text-muted mb-0" style="font-size: 14px;">
                    <?php echo htmlspecialchars($employee['Department_Name'] . ' - ' . $employee['Designation_Name']); ?>
                </p>
                <p class="text-muted mb-0" style="font-size: 14px;">Employee ID: 
    <?php echo htmlspecialchars($_SESSION['Employee_id']); ?>
</p>

            </div>
        </div>

        <div class="row mb-2">
            <div class="col">
                <div style="padding-top: 7px;">
                    <h6 class="mb-1" style="font-size: 14px; color: #555;">Phone Number</h6>
                    <p style="font-size: 13px; color: #333; margin-bottom: 0;"><?php echo htmlspecialchars($employee['Phone']); ?></p>
                </div>
            </div>
        </div>

        <div class="row mb-2">
            <div class="col">
                <div style="padding-top: 9px;">
                    <h6 class="mb-1" style="font-size: 14px; color: #555;">Email Address</h6>
                    <p style="margin-bottom: 0; font-size: 13px; color: #333;"><?php echo htmlspecialchars($employee['Email']); ?></p>
                </div>
            </div>
        </div>

        <div class="row mb-2">
            <div class="col">
                <div style="padding-top: 9px;">
                    <h6 class="mb-1" style="font-size: 14px; color: #555;">Address</h6>
                    <p style="font-size: 13px; color: #333; margin-bottom: 0;">
                        <?php echo htmlspecialchars($employee['Address'] ?? 'Not Available'); ?>
                    </p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <div style="padding-top: 9px;">
                    <h6 class="mb-1" style="font-size: 14px; color: #555;">Joined on</h6>
                    <p style="font-size: 13px; color: #333; margin-bottom: 0;">
                        <?php
                            if (!empty($employee['JoiningDate'])) {
                                echo date("d-m-Y", strtotime($employee['JoiningDate']));
                            } else {
                                echo "Not Available";
                            }
                        ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>              
  </div>
            </div>
            <div class="col" style="width: 500px;">
    <div class="card" style="width: 500px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); border-radius: 8px; border: 1px solid #e0e0e0; height:382px;">
        <div class="card-body" style="padding: 10px;padding-top: 20px;padding-left: 30px;">
            <div style="">
                <h5>Payroll Details</h5>
            </div>
        </div>
        <div>
            <div class="row">
            <div id="payrollChartContainer" style="position: relative; height: 300px;">
    <canvas id="leaveAreaChart" height="330" width="495" style="display: block;box-sizing: border-box;height: 300px;width: 450px;padding-left: 50px;padding-bottom: 20px;"></canvas>

    <!-- 👇 This is the message that will appear if all data is zero -->
    <div id="noDataMessage"
        style="display: none; position: absolute; top: 50%; left: 50%;
               transform: translate(-50%, -50%); text-align: center;
               font-weight: bold; color: #999; font-size: 16px;">
        <i class="fas fa-exclamation-circle" style="font-size: 20px;"></i><br>
        No payroll data available.
    </div>
</div>

            </div>
        </div>
    </div>
</div>
        </div>
         <div class="row" style="padding-top:42px;padding-bottom:10px;">
            <div class="col">
            <div class="card" style="width: 500px; height: 362px;">
  <div class="card-body p-5">
    <h5 class="card-title mb-4 text-primary"><i class="fas fa-calendar-check me-2"></i> Attendance Summary</h5>

    <div class="row mb-4">
      <div class="col-md-6">
        <div class="d-flex align-items-center">
         <div style=" padding-bottom: 23px;  padding-right: 7px;"> <i class="fas fa-list-ol fa-lg me-3 text-secondary"></i> </div>
          <div>
            <p class="mb-1 fw-semibold text-muted">Total Days</p>
            <h6 id="totalDays" class="fw-bold text-dark mb-0">--</h6>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="d-flex align-items-center">
        <div style=" padding-bottom: 23px;  padding-right: 7px;">  <i class="fas fa-check-circle fa-lg me-3 text-success"></i></div>
          <div>
            <p class="mb-1 fw-semibold text-muted">Present Days</p>
            <h6 id="presentDays" class="fw-bold text-success mb-0">--</h6>
          </div>
        </div>
      </div>
    </div>

    <div class="mb-4">
      <div class="d-flex align-items-center">
       <div style="padding-bottom: 23px; padding-right: 7px;"> <i class="fas fa-times-circle fa-lg me-3 text-danger"></i></div>
        <div>
          <p class="mb-1 fw-semibold text-muted">Absent Days</p>
          <h6 id="absentDays" class="fw-bold text-danger mb-0">--</h6>
        </div>
      </div>
    </div>

    <div>
      <p class="mb-2 fw-semibold text-muted">Attendance Rate</p>
      <div class="progress rounded-pill" style="height: 20px;">
      <div id="attendanceProgress" class="progress-bar bg-success rounded-pill" role="progressbar"
        style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
    <span class="visually-hidden" id="attendanceText">0% Complete</span>
    </div>

      </div>
      <small id="attendancePercentage" class="text-muted mt-1">0%</small>
    </div>
  </div>
</div>

            </div>
            <div class="col">
            <div class="card" style="width: 500px;">
				<div class="card-body" style="width: 500px; height: 362px; padding: 25px; padding-top: 40px; border-radius:15px;">
					<h5 class="card-title" style="margin-bottom: 15px;">Salary Breakdown</h5>
					<ul class="list-group">
						<li class="list-group-item">
							<i class="fas fa-money-bill-wave"></i>
							<strong>Basic Salary:</strong> Rs.<span id="basicSalary">Loading...</span>
						</li>
						<li class="list-group-item">
							<i class="fas fa-gift"></i>
							<strong>Bonus:</strong> Rs.<span id="bonus">Loading...</span>
						</li>
						<li class="list-group-item">
							<i class="fas fa-percent"></i>
							<strong>Total Allowence:</strong> Rs.<span id="totalAllowance">Loading...</span>
						</li>
						<li class="list-group-item">
							<i class="fas fa-minus-circle"></i>
							<strong>Other Deductions:</strong> Rs.<span id="deductions">Loading...</span>
						</li>
						<li class="list-group-item">
							<strong>Total Salary:</strong> Rs.<span id="netSalary">Loading...</span>
						</li>
					</ul>
				</div>
			</div>

</div>
            </div>
    </div>
       
    </div>
	<!--leave-manag-admin-->
	<!--<div id="leave-management-admin" style="display:none;">-->
    <div id="dynamic-content" style="margin-top:50px; ">
        <!-- Dynamically loaded content will go here -->
    </div>
	</div>
    </div>
    </div>
    <div id="circle"></div>
    <div class="modal fade" id="logoutModal" role="dialog" tabindex="-1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color:#3786D5;">
                    <h4 class="modal-title" style="color:white;">Logout Confirmation</h4><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button></div>
                <div class="modal-body">
                    <p>Are you sure you want to log out?</p>
                </div>
                <div class="modal-footer"><a href="../Views/login.html" class="btn btn-primary">Yes</a><button class="btn btn-primary" type="button"  data-dismiss="modal">No</button></div>
            </div>
        </div>
    </div>
	<!--add leave model_code-->
    <div class="modal fade" id="leaveModal" tabindex="-1" aria-labelledby="leaveModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="border-radius:20px;">
        <div class="modal-content" style="border-radius:20px;">
            <form id="addLeaveForm" method="POST">
                <div class="modal-header">
                    <h3 class="modal-title" id="editmodalLabel" style="color:rgb(0,115,230);">Add Leave</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <input type="hidden" id="leave_id" name="leave_id">
                </div>
                <div class="modal-body" style="padding:19px; border-radius:20px;">
                    <div style="width:400px;">
                        <p style="color: red; font-size: small;">
                            1. Sick Leaves: Maximum 10 allowed for each employee.<br>
                            2. Casual + Other Leaves (combined): Maximum 5 allowed.<br>
                            3. Total Leaves allowed per employee = 15 (10 Sick + 5 Casual/Other).<br>
                            4. Leaves for past months cannot be applied.<br>
                            5. Leave days cannot exceed the remaining days of the current month.<br>
                            </p>

                        <label>Leave Type:</label>
                        <div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" id="option1" name="options" value="1" required>
                                <label class="form-check-label" for="option1">Sick</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" id="option2" name="options" value="2">
                                <label class="form-check-label" for="option2">Casual</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" id="option3" name="options" value="3">
                                <label class="form-check-label" for="option3">Other</label>
                            </div>
                        </div>
                    </div>

                    <div style="margin-top:8px;">
                        <label style="margin-bottom:5px;" for="month">Month</label>
                        <input class="form-control" style="width:50%;" type="month" id="month" name="month" min="<?= date('Y-m'); ?>" required>
                    </div>

                    <div style="margin-top:8px;">
                        <label style="margin-bottom:5px;" for="total_days">Total days</label>
                        <input class="form-control" style="width:50%;" type="number" id="total_days" name="total_days" min="0" step="1" required>
                    </div>

                    <div style="margin-top:8px;">
                        <label style="margin-bottom:5px;">Description:</label>
                        <textarea class="form-control" id="description" name="description"></textarea>
                    </div>

                    <div class="container d-flex justify-content-center align-items-center" style="margin-top:8px;">
                        <button class="btn btn-primary" type="submit" style="transition: all 0.3s ease;" 
                            onmouseover="this.style.color='#3786D5'; this.style.backgroundColor='white';" 
                            onmouseout="this.style.color='white'; this.style.backgroundColor='#007bff';">
                            Submit
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

                         <!--edit leave modal-->
							
        <div class="modal fade" id="editmodal" tabindex="-1" role="dialog" aria-labelledby="editmodalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="border-radius:20px;">
        <div class="modal-content" style="border-radius:20px;">
			<form id="edit-leave-form">
                <div class="modal-header">
                    <h3 class="modal-title" id="editmodalLabel" style="color:rgb(0,115,230);">Edit Leave</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <!-- Hidden input for leave_id -->
                    <input type="hidden" id="edit-leave-id" name="leave_id">    
                </div>
                <div class="modal-body" style="padding:19px" "border-radius:20px;">  
                    <p style="color: red; font-size: small;">
                                1. Sick Leaves: Maximum 10 allowed for each employee.<br>
                                2. Casual + Other Leaves (combined): Maximum 5 allowed.<br>
                                3. Total Leaves allowed per employee = 15 (10 Sick + 5 Casual/Other).<br>
                                4. Leaves for past months cannot be applied.<br>
                                5. Leave days cannot exceed the remaining days of the current month.<br>
                                6. Once a leave request is <b>Approved</b> or <b>Rejected</b> by Admin, the employee cannot edit it further.<br>

                            </p>
 
						   <div style="width:400px;"><label>Leave Type:</label>
							   <div>
                               <div class="form-check">
                                    <input class="form-check-input" type="radio" id="sick" name="options" value="1">
                                    <label class="form-check-label" for="sick">Sick</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="casual" name="options" value="2">
                                    <label class="form-check-label" for="casual">Casual</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="other" name="options" value="3">
                                    <label class="form-check-label" for="other">Other</label>
                                </div>

							   </div>
						   </div>
                                <div style="margin-top:8px;">
                                     <label style="margin-bottom:5px;" for="month">Month</label>
                                     <input style="width:50%;" class="form-control" type="text" id="edit-month" name="month" required>
                                    <small id="month-error" style="color: red; display: none;">Please enter a valid month name.</small>
                                 </div>
								<div style="margin-top:8px;">
									<label style="margin-bottom:5px;" for="endDate">Total days</label>
									<input style="width:50%;" class="form-control" type="number" id="edit-total-days" name="total_days" required="">
								</div>
								<div style="margin-top:8px;">
									<label style="margin-bottom:5px;">Description:</label>
									<textarea id="edit_description" class="form-control" name="description"></textarea>
								</div>
								<div class="container d-flex justify-content-center align-items-center" style="margin-top:8px;">
									<button 
        							class="btn btn-primary" 
        							type="submit" 
        							style="transition: all 0.3s ease;" 
        							onmouseover="this.style.color='#3786D5'; this.style.backgroundColor='white';" 
        							onmouseout="this.style.color='white'; this.style.backgroundColor='#007bff';">
        							Submit
    								</button>
								</div>
												   
				 </div>    
			</form>

        </div>                 
	</div>
	</div>
		
		<!--delete modal--->
        <div class="modal fade" id="deletemodal" tabindex="-1" role="dialog" aria-labelledby="deletemodalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content" style="border-radius: 20px;">
      
      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title" id="deletemodalLabel" style="color:rgb(0,115,230);">Delete Leave</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <!-- Modal Body -->
      <div class="modal-body" style="font-family: Roboto;">
        <p>Are you sure you want to delete this Leave?</p>
      </div>

      <!-- Modal Footer -->
      <div class="modal-footer" style="display: flex; justify-content: center;">
        <form id="delete_form">
        <input type="hidden" id="deleteLeaveId" name="deleteLeaveId">

          <button class="btn btn-light" type="button" data-dismiss="modal" style="color:rgb(0,115,230);">Close</button>
          <button class="btn btn-primary delete-confirm-btn" type="button" style="background-color:rgb(0,115,230);">Delete</button>
        </form>
      </div>

    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Change Password Modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" role="dialog" aria-labelledby="changePasswordLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
         <div class="modal-content" style="border-radius: 20px;">
         <div class="modal-header">
 <h3 class="modal-title" id="changePasswordLabel" style="color:rgb(0,115,230); font-family: Merriweather, sans;">Change Password</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <div class="modal-body">
        <form action="change_password.php" method="POST" id="changePasswordForm">
        <div class="mb-3">
        <input type="hidden" name="user_id" value="<?php echo $employeeId; ?>">
            <div class="form-group">
        <label for="currentPassword" class="form-label" style="font-family: Roboto;">Current Password</label>
        <input type="password" class="form-control" name="current_password" autocomplete="new-password" placeholder="Enter Current Password"required>
        </div>
        <div class="mb-3">
        <label for="newPassword" class="form-label" style="font-family: Roboto;">New Password</label>    
        <input type="password" class="form-control" name="new_password" id="new_password" placeholder="Enter New Password" required>
        <small id="newPaswordError" style="color: red; display: none;"></small>
            </div>
            <div class="mb-3">
            <label for="confirmNewPassword" class="form-label" style="font-family: Roboto;">Confirm New Password</label> 
            <input type="password" class="form-control" name="confirm_password" id="confirm_password" placeholder="Enter Confirm New Password" required>   
            <small id="confirmNewPasswordError" style="color: red; display: none;"></small>
            </div>
            </form>
        </div>
        <div class="modal-footer" style="display: flex;justify-content: center;">
            <button class="btn btn-light" type="button" style="color:rgb(0,115,230);" data-dismiss="modal">Cancel</button>           
            <button class="btn btn-primary" type="submit" id="changePasswordBtn" form="changePasswordForm" style="background-color:rgb(0,115,230);">Update</button> 
        </div>
        </div>
    </div>
</div>
<!--<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../Resources/js/changePassword.js"></script>
<script src="../Resources/js/EmpAttendence.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="../Resources/bootstrap/js/bootstrap.min.js"></script>
    <script src="../Resources/js/dashboard.js"></script>
    <script src="../Resources/js/emp_dash_cards.js"></script>
    <script src="../Resources/js/add_leave.js"></script>
    <!-- script to display submit model to edit_leave.php,edit leave preefilled input,delete leave too-->
    <script src="../Resources/js/edit_delete_leave_emp.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="../Resources/js/checkboxHandler.js" ></script>
     <script src="../Resources/js/script_emp.js"></script>
	 <script src="../Resources/js/payslip_dynamic.js"></script>
     

<script>
function loadPage(url, push = true) {
	console.log("🔄 Loading page:", url);
	const dynamicContainer = document.getElementById('dynamic-content');
    document.getElementById('dashboard-content').style.display = 'none';

	if (push) {
		history.pushState({ url }, null, "?page=" + encodeURIComponent(url));
	}

	$.ajax({
		url: url,
		method: 'GET',
		success: function(response) {
			dynamicContainer.innerHTML = response;
			 // ✅ Run inline scripts manually
			 const scripts = dynamicContainer.querySelectorAll("script");
            scripts.forEach((script) => {
                const newScript = document.createElement("script");
                if (script.src) {
                    newScript.src = script.src;
                    document.head.appendChild(newScript);
                } else {
                    newScript.textContent = script.textContent;
                    document.body.appendChild(newScript);
                }
            });

            // ✅ Check if the page is `EmpPayrollReport.php` and the chart exists
if (url.includes("EmpPayrollReport.php")) {

// ✅ Initialize Search Feature (Only for Payroll Report)
if (document.getElementById('search')) {
    console.log("✅ Initializing Payroll Report Search...");
    initialize_payroll_report_search();
}
}

function initialize_payroll_report_search() {
console.log("🔍 Payroll Report Search Initialized");

function fetchPayrollReportData() {
    var searchValue = $("#search").val(); // Get search input value

    $.ajax({
        url: "empPayrollReport_search.php", // Your server-side handler
        type: "POST",
        data: {
            search: searchValue // Ensure this matches the server-side POST variable
        },
        success: function(response) {
            $("tbody").html(response); // Update table body with the results
        },
        error: function(xhr, status, error) {
            console.error("Search Error:", error);
        }
    });
}

// Trigger search with delay on keyup
$("#search").on("keyup", function() {
    clearTimeout($(this).data('timer'));
    $(this).data('timer', setTimeout(fetchPayrollReportData, 300));
});

// Also trigger search on button click
$("#searchForm button").on("click", fetchPayrollReportData);
}

if (window.location.href.includes("chatBotEmp.html")) {
    console.log("💬 chatBotEmp.html detected, loading chatBotEmp.js...");

    const script = document.createElement("script");
    script.src = "../Resources/js/chatBotEmp.js"; // ✅ Update path if it's in another folder
    script.onload = () => console.log("✅ chatBotEmp.js loaded successfully.");
    script.onerror = () => console.error("❌ Failed to load chatBotEmp.js.");
    document.body.appendChild(script);
}

			// ✅ Handle das_emp-specific logic
			
            // ✅ For employee dashboard specific init
            if (url.includes("dash_emp.php")) {
                setTimeout(() => {
                    console.log("✅ Initializing Employee Dashboard Components...");
                    if (typeof Chart === 'undefined') {
                        loadScript('https://cdn.jsdelivr.net/npm/chart.js', initializeDashboardComponents);
                    } else {
                        initializeDashboardComponents();
                    }
                }, 100);
            }
		},
		error: function() {
			document.getElementById('dynamic-content').innerHTML = '<p>Error loading the page. Please try again later.</p>';
		}
	});
}
	// Helper function to load scripts dynamically
function loadScript(src, callback) {
    const script = document.createElement('script');
    script.src = src;
    script.onload = callback;
    document.head.appendChild(script);
}

// Dashboard initialization function
function initializeDashboardComponents() {
    try {
        if (typeof initializeEmployeeDashboard === 'function') {
            initializeEmployeeDashboard();
        } else {
            console.warn("⚠️ initializeEmployeeDashboard() function not found");
            
            // Fallback: Try to load the dashboard script
            loadScript('js/employee-dashboard.js', function() {
                if (typeof initializeEmployeeDashboard === 'function') {
                    initializeEmployeeDashboard();
                } else {
                    console.error("❌ Failed to load dashboard functions");
                }
            });
        }
    } catch (e) {
        console.error("❌ Error initializing dashboard:", e);
    }
}

// 🔙 Handle browser navigation
window.onpopstate = function(event) {
	const url = event.state?.url || new URLSearchParams(window.location.search).get("page");
	if (url) loadPage(url, false);
};

// 🚀 Load on refresh
window.addEventListener("DOMContentLoaded", () => {
	const initialPage = new URLSearchParams(window.location.search).get("page");
	if (initialPage) loadPage(initialPage, false);
});
</script>
</body>

</html>
 