<?php
session_start();

// Redirect to login page if the user is not authenticated
if (!isset($_SESSION['username']) || !isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

include 'adminloginUser_id.php';
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../Resources/css/adminDashboard_sidebar.css">
    <link rel="stylesheet" href="../Resources/css/adminDashboard.css">

    
</head>

<body style="color:rgba(33,37,41,0.67);margin-left:0px; overflow:auto;"><!--<div>Custom Code</div>-->


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
                    <!--<li class="active">
                        <a href="#dashSubmenu" data-toggle="collapse" aria-expanded="false">
                            <i class="glyphicon glyphicon-dash"></i>Dashboard
                        </a>
                        <ul class="collapse list-unstyled" id="dashSubmenu">
                            <li><a href="#">Analytics</a></li>
                            <li><a href="#">Invoice</a></li>
                        </ul>
                    </li>
                    <li class="active">
                        <a href="#empSubmenu" data-toggle="collapse" aria-expanded="false">
                            <i class="glyphicon glyphicon-emp"></i>Employee
                        </a>
                        <ul class="collapse list-unstyled" id="empSubmenu">
                            <li><a href="#">Employee List</a></li>
                            <li><a href="#">Manage Employee</a></li>
                            <li><a href="#">Attendance</a></li>
                        </ul>
                    </li>-->
                    <!--<li class="active">
                        <a href="#depSubmenu" data-toggle="collapse" aria-expanded="false">
                            <i class="glyphicon glyphicon-dep"></i>Department
                        </a>
                        <ul class="collapse list-unstyled" id="depSubmenu">
                            <li><a href="#">Designation</a></li>
                            <li><a href="#">Manage Department</a></li>
                        </ul>
                    </li>-->
                      <li>
                        <!--<a href="#dashSubmenu" data-toggle="collapse">-->
                        <a href="#" onclick="loadPage('dashboard.php'); $('#dashSubmenu').collapse('toggle'); return false;" style="cursor:pointer;">
                           
                            <img src="../Resources/img/icons8-dashboard-layout-30 (4).png" style="width:25px;height:27px;margin-top:-1px;">
                            <!--<i><a href="#" onclick="loadPage('dashboard.php')">Dashboard</a></i>-->
                            <i class="glyphicon glyphicon-emp"></i>Dashboard
                        </a>
                       <!--<ul class="collapse list-unstyled" id="dashSubmenu" style="margin-left:20px;">
                            <li><a href="#" onclick="loadPage('dashboard.php')">Overview</a></li>
                           <!-- <li><a href="#">Invoice</a></li>
                        </ul>-->
                    </li>
                    <li>
                        <a href="#empSubmenu" data-toggle="collapse">
                            <img src="../Resources/img/icons8-employees-30 (3).png" style="width:25px;height:35px;margin-top:-1px;">
                            <i class="glyphicon glyphicon-emp"></i>Employee
                        </a>
                        <ul class="collapse list-unstyled" id="empSubmenu" style="margin-left:20px;">
                            <li><a href="#" onclick="loadPage('AddEmp.php')">Add Employee</a></li>
                            <li><a href="#" onclick="loadPage('manageEmp.php')">Manage Employee</a></li>
                        </ul>
                    </li>
                   <li>
                        <!--<a href="#">
                            <i class="glyphicon glyphicon-briefcase"></i>
                            About
                        </a>-->
                       <a href="#depSubmenu" data-toggle="collapse">
                           <img src="../Resources/img/icons8-department-30 (6).png" style="width:25px;height:29px;margin-top:-1px;">
                            <i class="glyphicon glyphicon-dep"></i>Department
                        </a>
                        <ul class="collapse list-unstyled" id="depSubmenu" style="margin-left:20px;">
                            <li><a href="#" onclick="loadPage('department.php')">Manage Department</a></li>
                            <li><a href="#" onclick="loadPage('designation.php')">Manage Designation</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="#attSubmenu" data-toggle="collapse">
                            <img src="../Resources/img/icons8-attendance-30 (3).png" style="width:25px;height:27px;margin-top:-1px;">
                            <i class="glyphicon glyphicon-att"></i>Attendance
                        </a>
                        <ul class="collapse list-unstyled" id="attSubmenu" style="margin-left:20px;">
                            <li><a href="#" onclick="loadPage('attendance.php')">Employee Attendance</a></li>
                            <!--<li><a href="#">Set Leave Policies</a></li>-->
                        </ul>
                    </li>
                    <li>
                        <a href="#levSubmenu" data-toggle="collapse">
                            <img src="../Resources/img/icons8-leave-30 (2).png" style="width:25px;height:27px;margin-top:-1px;">
                            <i class="glyphicon glyphicon-pay"></i>Leave
                        </a>
                        <ul class="collapse list-unstyled" id="levSubmenu" style="margin-left:20px;" >
						
                            <li><a class="dropdown-item" role="presentation" href="javascript:void(0);" onclick="loadPage('admin_leave_page.php')">Status Leaves</a></li>
                        </ul>
                    </li>
					<li>
                        <a href="#ReportSubmenu" data-toggle="collapse">
                            <img src="../Resources/img/icons8-report-30.png" style="width:25px;height:27px;margin-top:-1px;">
                            <i class="glyphicon glyphicon-pay"></i>Report
                        </a>
                        <ul class="collapse list-unstyled" id="ReportSubmenu" style="margin-left:20px;">
                            <li><a href="#" onclick="loadPage('employee-report.php')">Employee Report</a></li>
                            <li id="attendence-report"><a href="#" onclick="loadPage('Attendence_report.php')">Attendance Report</a></li>
							<li><a href="#" onclick="loadPage('leave_report.php')">Leave Report</a></li>
							<!--<li><a href="#" onclick="loadPage('employee-report.php')">Payroll Report</a></li>-->
                        </ul>
                    </li>
                    <li>
                        <a href="#adjSubmenu" data-toggle="collapse">
                            <img src="../Resources/img/icons8-exposure-30.png" style="width:25px;height:27px;margin-top:-1px;">
                            <i class="glyphicon glyphicon-pay"></i>Adjustments
                        </a>
                        <ul class="collapse list-unstyled" id="adjSubmenu" style="margin-left:20px;">
                            <li><a href="#" onclick="loadPage('../Views/allowance.html')">Allowances</a></li>
                            <li><a href="#" onclick="loadPage('allowances_list.php')">Allowances List</a></li>
                            <li><a href="#" onclick="loadPage('../Views/deduction.html')">Deductions</a></li>
                            <li><a href="#" onclick="loadPage('deductions_list.php')">Deductions List</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="#paySubmenu" data-toggle="collapse">
                            <img src="../Resources/img/icons8-money-transfer-30 (4).png" style="width:25px;height:25px;margin-top:-1px;">
                            <i class="glyphicon glyphicon-pay"></i>Payroll Generation
                        </a>
                        <ul class="collapse list-unstyled" id="paySubmenu" style="margin-left:20px;">
                            <li><a href="#" onclick="loadPage('payrollprofile.php')">Payroll Profile</a></li>
                            <li><a href="#" onclick="loadPage('payrollprofile_list.php')">Payroll Profile List</a></li>
                            <li><a href="#" onclick="loadPage('../Views/payroll.html')">Payroll Computation</a></li>
                            <li><a role="presentation" href="javascript:void(0);" style="color:#3786d5;" 
           onclick="loadPage('payslip.php')">Payslip Log</a></li>
                        </ul>
                    </li>
                    <!--<li>
                        <a href="#chatbot" data-toggle="collapse">
                            <img src="../Resources/img/icons8-chatbot-30.png" style="width:25px;height:25px;margin-top:-1px;">
                            <i class="glyphicon glyphicon-pay"></i>ChatBot
                        </a>
                        <ul class="collapse list-unstyled" id="chatbot" style="margin-left:20px;">
                            <li><a href="#" onclick="loadPage('../Views/chatBot.html')">PE-Bot</a></li>
                            
                        </ul>
                    </li>-->
                    
                    <!--<li class="active">
                        <a href="#pgSubmenu" data-toggle="collapse" aria-expanded="false">
                            <i class="glyphicon glyphicon-emp"></i>Pages
                        </a>
                        <ul class="collapse list-unstyled" id="pgSubmenu">
                            <li><a href="#">Profile</a></li>
                            <li><a href="#">Manage Employee</a></li>
                            <li><a href="#">Attendance</a></li>
                        </ul>
                    </li>
                   <!-- <li>
                        <a href="#">
                            <i class="glyphicon glyphicon-link"></i>
                            Portfolio
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="glyphicon glyphicon-paperclip"></i>
                            FAQ
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="glyphicon glyphicon-send"></i>
                            Contact
                        </a>
                    </li>-->
                </ul>
                </div>
            </nav>

            <!-- Page Content Holder -->
            
        





        <!-- jQuery CDN -->
         </div>
        <div class="col" id="content" style="background-color:#F3F1FA;overflow:hidden;padding-top:35px;">
            <div style="margin-left:20px;">
                <div id="header" style="/*background-color:#e2e6ea;*/background-color:#2374C3;height:83px;position:absolute;top:35px;left:25px;transition:left 0.3s, width 0.3s;/*z-index:1000;*/margin-top:-35px;width:100%;margin-left:-10px;">
                    <div class="row" style="background-color:#2374C3;height:82px;/*margin-top:20px;*/margin-left:-5px;">
                        <div class="col-md-1"><button class="btn btn-primary d-flex justify-content-center" type="button" id="toggleSidebar" style="background-color:rgba(226,230,234,0);border:none;box-shadow:none;width:40px;height:40px;margin-left:0px;margin-top:20px;/*margin-bottom:-70px;*//*padding-top:5px;*//*padding-left:12px;*/"><img src="../Resources/img/icons8-menu-30 (3).png" style="width:40px;height:35px;"></button></div>
                        <div
                            class="col">
                            <h3 style="color:#fff;margin-top:26px;font-family:Roboto, sans-serif;">Admin Control Hub<br></h3>
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

                        <div class="dropdown d-flex align-self-end swing animated" style="width:50px;height:50px;right:50px;top:10px;margin-top:10px;position:absolute;z-index:1;"><button class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false" type="button" style="background-color:rgba(0,123,255,0);border-radius:50%;height:40px;width:40px;border:1px solid white;"><img src="<?php echo $imagePath; ?>" style="margin-left:-17px;margin-bottom:0px;margin-top:-9px;height:40px;width:40px;border-radius:50%;"></button>
                            <div
                                class="dropdown-menu dropdown-menu-right" role="menu" style="height:210px;min-width:230px;/*max-height:none;*/position:absolute !important;z-index:1050 !important;font-family:Roboto, sans-serif;border:1px solid #3786d5;margin-left:0px;margin-right:40px;margin-top:13px;overflow:visible;/*transform:translateY(-10px);*//*transition:all 0.3s ease;*//*max-height:0;*//*transform:scaleY(0);*//*transition:transform 0.3s ease, opacity 0.3s ease;*//*opacity:0;*/"><a class="dropdown-item disabled d-flex justify-content-center align-items-center" role="presentation" href="#" style="background-color:#6FBFED;min-height:120px;">
                                    <img src="<?php echo $imagePath; ?>" style="height:60px;weidth: 60px;border-radius:50%;">
                                    <?php if (!empty($fullName)): ?>
                                        <h5 style="color:rgb(255,255,255);margin-left:10px;"><?php echo htmlspecialchars($fullName); ?><br></h5>
                                        <?php endif; ?></a>
                                <a id="frame"
                                    class="dropdown-item" role="presentation" href="#" onclick="loadPage('viewProfileAdmin.php')" style="font-size:18px;"><img src="../Resources/img/icons8-account-20.png" style="margin-right:10px;">Profile<br></a><a class="dropdown-item" role="presentation" href="#" style="font-size:18px;" data-toggle="modal" data-target="#logoutModal"><img src="../Resources/img/icons8-login-20.png" style="margin-right:10px;">Logout</a></div>
<!-- onclick="loadPage('/landing/adprofile/index.html')"-->
                                </div>
                </div>

                <!-- Floating Chatbot Button with Custom GIF -->
<button class="chatbot-button" onclick="loadPage('../Views/chatBot.html');">
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


            </div>  
        </div>
        <div id="dashboard-content" style="display: block;font-family:Roboto, sans-serif;height: 100%;">
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
        <canvas id="pieChart" width="50" height="50"></canvas>
        </div>
        </div>
        <script>
    function drawPieChart() {
        const salaries = <?php echo $salaries; ?>;
        const salaryextras = <?php echo $salaryextras; ?>;
        const salarydeductions = <?php echo $salarydeductions; ?>;

        const ctxPie = document.getElementById('pieChart').getContext('2d');
        return new Chart(ctxPie, {
            type: 'doughnut',
            data: {
                labels: ['Salaries', 'Extras', 'Deductions'],
                datasets: [{
                    data: [salaries, salaryextras, salarydeductions],
                    backgroundColor: ['#1D4ED8', '#10B981', '#F59E0B'],
                    hoverOffset: 8,
                    borderColor: '#fff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                cutout: '60%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#333',
                            font: { size: 14, weight: 'bold' }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                return `${label}: Rs ${value.toLocaleString()}`;
                            }
                        }
                    }
                }
            }
        });
    }

    // ✅ Directly call the function on page load
    document.addEventListener('DOMContentLoaded', drawPieChart);
</script>




<!--Line chart-->
<?php include 'linechart_data.php'; ?>

<div class="col-md-5" style="margin-left:150px; background-color:white; margin-bottom:120px;">
    <h5 style="margin-top:30px;">Earnings Over Time:<br></h5>
    <div>
        <canvas id="lineChart" width="50" height="50"></canvas>
    

<script>
    function drawEarningsLineChart() {
        // Fetch PHP data and ensure it's in the correct format for JavaScript
        const months = <?php echo json_encode($months); ?>;
        const earnings = <?php echo json_encode($earnings); ?>;

        const ctxLine = document.getElementById('lineChart').getContext('2d');
        new Chart(ctxLine, {
            type: 'line',
            data: {
                labels: months,
                datasets: [{
                    label: 'Monthly Earnings',
                    data: earnings,
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
    }

    // ✅ Call the chart drawing function on page load
    document.addEventListener('DOMContentLoaded', drawEarningsLineChart);
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
    <canvas id="earningsBarChart" width="300" height="300"></canvas>
    </div>
</div>


<script>
    function drawEarningsBarChart() {
        // PHP Data (Converted to JavaScript)
        const employeeNames = <?php echo json_encode($employeeNames); ?>;
        const earnings = <?php echo json_encode($earnings); ?>;

        // Get Canvas Element
        const ctx = document.getElementById('earningsBarChart').getContext('2d');

        // Create Line Chart (can be changed to 'bar' if needed)
        new Chart(ctx, {
            type: 'line', // 🔁 Use 'bar' here if you want a bar chart
            data: {
                labels: employeeNames,
                datasets: [{
                    label: 'Total Salaries (Rs)',
                    data: earnings,
                    borderColor: '#007bff',
                    backgroundColor: 'rgba(111, 191, 237, 0.2)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    }

    // ✅ Automatically draw chart on page load
    document.addEventListener('DOMContentLoaded', drawEarningsBarChart);
</script>

                </div>
            </div>
        </div>
    </div>
    
	<!--leave-manag-emp-->
	<!--<div id="leave-management-emp" style="display:none;">
    <div id="dynamic-content" style="margin-top:20px; background-color:#f8f9fa;">
         Dynamically loaded content will go here 
    </div>
	</div>-->

    <!--profile-->
	<div id="profile" style="display:none;">
    <div id="dynamic-content" style="margin-top:60px; background-color:#F3F1FA;">
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
    
    <!--<iframe id="content-iframe" src="/landing/adprofile/index.html" style="
    width: 100%; 
    height: calc(100vh - 1
    20px); /* Adjust height dynamically */
    border: none;
    margin: 0; 
    padding: 0; 
    display: block;
"></iframe>
    <!-- Script to Handle Button Clicks -->
    <!--<script>
        // Get iframe reference
        const iframe = document.getElementById('content-iframe');
        document.getElementById('dashboard-content').style.display = 'none';
        // Add event listeners to load different pages
        document.getElementById('frame').addEventListener('click', function () {
            iframe.src = '/landing/adprofile/index.html'; // Load the profile page dynamically
        });

        
    </script>-->
    <!-- Modal in Dashboard -->
<!--<div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="profileModalLabel">Profile Modal</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
         Modal content -->
       <!-- This is the modal for the profile page.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" data-dismiss="modal">Save changes</button>
      </div>
    </div>
  </div>
</div>-->
    


<!-- Add Designation Modal -->
<div class="modal fade" id="addDesignationModal" tabindex="-1" role="dialog" aria-labelledby="addDesignationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 20px;">
            <div class="modal-header">
                <h3 class="modal-title" id="addDesignationModalLabel" style="color:rgb(0,115,230); font-family: Merriweather, sans;">Add Designation</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>       
            <div class="modal-body">
                <form action="add_designation.php" method="POST" id="addDesignationForm">
                    <div class="mb-3">
                        <label for="designationName" class="form-label"> <strong>Designation Name </strong></label> 
                        <input type="text" class="form-control" id="designationNameInput" name="designationName" placeholder="Enter Designation Name" required>                     
                        <small id="designationNameError" style="color: red; display: none;">⚠ Only letters and spaces are allowed!</small>
                    </div>
                    <div class="mb-3">
                        <label for="salary" class="form-label"> <strong>Salary </strong></label>                        
                        <input type="number" class="form-control" id="salaryInput" name="salary" placeholder="Enter Salary" required>
                        <small id="salaryError" style="color: red; display: none;"></small>
                    </div>                
                </form>
            </div>
            <div class="modal-footer" style="display: flex;justify-content: center;">
            <button class="btn btn-light" type="button" style="color:rgb(0,115,230);" data-dismiss="modal">Close</button>
            <button class="btn btn-primary" type="submit" id="addDesignationSubmitBtn" form="addDesignationForm" style="background-color:rgb(0,115,230);">Submit</button> 
        </div>
        </div>
    </div>
</div>     

<!-- Edit Designation Modal -->
<div class="modal fade" id="editDesignationModal" tabindex="-1" role="dialog" aria-labelledby="editDesignationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 20px;">
            <div class="modal-header">
                <h3 class="modal-title" id="editDesignationModalLabel" style="color:rgb(0,115,230); font-family: Merriweather, sans;">Edit Designation</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="edit_designation.php" method="POST" id="editDesignationForm">
                    <div class="mb-3">
                        <label for="editDesignationID" class="form-label"> <strong>Designation ID </strong></label>
                        <input type="text" class="form-control" id="editDesignationID" name="editDesignationID" placeholder="Enter Designation ID" readonly required>
                    </div>
                    <div class="mb-3">
                        <label for="editDesignationName" class="form-label"> <strong>Designation Name </strong></label>
                        <input type="text" class="form-control" id="editDesignationName" name="editDesignationName" placeholder="Enter Designation Name" required>
                        <div id="edit-name-error" class="text-danger" style="display: none; font-size: 14px;"></div>
                    </div>
                    <div class="mb-3">
                        <label for="editSalary" class="form-label"> <strong>Salary </strong></label>
                        <input type="number" class="form-control" id="editSalary" name="editSalary" placeholder="Enter Salary" required>
                        <div id="edit-salary-error" class="text-danger" style="display: none; font-size: 14px;"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer" style="display: flex;justify-content: center;">
                <button class="btn btn-light" type="button" style="color:rgb(0,115,230);" data-dismiss="modal">Close</button>
                <button class="btn btn-primary" type="submit" form="editDesignationForm" id="edit-designation-submit-btn" style="background-color:rgb(0,115,230);">Submit</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Designation Modal -->
<div class="modal fade" id="deleteDesignationModal" tabindex="-1" role="dialog" aria-labelledby="deleteDesignationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 20px;">
            <div class="modal-header">
                <h4 class="modal-title" id="deleteDesignationModalLabel" style="color:rgb(0,115,230);">Delete Designation</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="font-family: Roboto;">
                <p>Are you sure you want to delete this Designation?</p>
                <p id="errorMessage" class="text-danger" style="display: none;">Cannot delete this Department, as it has employee(s) assigned to it.</p>
            </div>
            <div class="modal-footer" style="display: flex;justify-content: center;">
                <form action="delete_designation.php" method="POST" id="deleteDesignationForm">
                    <input type="hidden" name="designationID" id="designationID">
                    <button class="btn btn-light" type="button" style="color:rgb(0,115,230);" data-dismiss="modal">Close</button>
                    <button class="btn btn-primary" type="submit" id="confirmDeleteBtn" style="background-color:rgb(0,115,230);">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Add Department Modal -->
<div class="modal fade" id="addDepartmentModal" tabindex="-1" role="dialog" aria-labelledby="addDepartmentLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 20px;">
            <div class="modal-header">
                <h3 class="modal-title" id="addDepartmentLabel" style="color:rgb(0,115,230);">Add Department</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container" style="padding: 10px;">  

                    <form action="add_department.php" method="POST" id="add-department-form" autocomplete="off">
                        <div class="mb-3">
                            <label for="add-department"> <strong>Department Name </strong></label>
                            <input class="form-control" type="text" id="add-department" name="departmentName" 
                                placeholder="Enter Department Name" style="height:32px;" required />
                            <small id="error-msg" style="color: red; display: none;">Only letters and spaces are allowed!</small>
                        </div>
                        <div class="modal-footer" style="display: flex;justify-content: center;">
                            <button class="btn btn-light" type="button" style="color:rgb(0,115,230);" data-dismiss="modal">Close</button>
                            <button class="btn btn-primary" type="submit" id="submit-btn" style="background-color:rgb(0,115,230);">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!--Edit Department Model -->
<div class="modal fade" id="editDepartmentModal" tabindex="-1" role="dialog" aria-labelledby="editDepartmentLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius:20px;">
            <div class="modal-header">
                <h3 class="modal-title" id="editDepartmentLabel" style="color:rgb(0,115,230);">Edit Department</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container" style="padding: 10px;">
                    <form action="edit_department.php" method="POST" id="edit-department-form">
                        <div class="mb-3">
                            <label for="editDepartmentID"> <strong>Department ID </strong></label>
                            <input class="form-control" type="text" id="editDepartmentID" name="id" style="height:32px;" readonly />
                        </div>
                        <div class="mb-3">
                            <label for="edit-department"> <strong>Department Name</strong></label>
                            <input class="form-control" type="text" id="edit-department" name="departmentName" style="height:32px;" required />
                            <small id="edit-error-msg" style="color: red; display: none;">Invalid department name</small>
                        </div>    
                        
                        <div class="modal-footer" style="display: flex;justify-content: center;">
                            <button class="btn btn-light" type="button" style="color:rgb(0,115,230);" data-dismiss="modal">Close</button>
                            <button class="btn btn-primary" type="submit" id="edit-submit-btn" style="background-color:rgb(0,115,230);">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Department Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 20px;">
            <div class="modal-header">
                <h4 class="modal-title" id="deleteModalLabel" style="color:rgb(0,115,230);">Delete Department</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
            <div class="modal-body" style="font-family: Roboto;">
                <p>Are you sure you want to delete the <strong id="deptName"></strong>?</p>
                <p id="deptErrorMessage" class="text-danger" style="display: none;"></p>
            </div>
            <div class="modal-footer" style="display: flex;justify-content: center;">
                <form action="delete_department.php" method="POST" id="deleteDepartmentForm">
                    <input type="hidden" id="department_id" name="department_id">
                    <button class="btn btn-light" type="button" style="color:rgb(0,115,230);" data-dismiss="modal">Close</button>
                    <button class="btn btn-primary" type="submit" id="confirmDeleteDeptBtn" style="background-color:rgb(0,115,230);">Delete</button>         
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Employee Modal -->
<div class="modal fade" id="editEmpModal" tabindex="-1" role="dialog" aria-labelledby="editEmpModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 20px;">
            <div class="modal-header">
                <h3 class="modal-title" id="editEmpModalLabel" style="color:rgb(0,115,230); font-family: Merriweather, sans;">Edit Employee</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="edit_emp.php" method="POST" id="editEmpForm" enctype="multipart/form-data">
                    <input type="hidden" id="editEmpID" name="employee_id">
                   <div class="form-group text-center">
                      <label>Current Picture</label><br>
                      <img id="editEmpPicture" src="" alt="Employee Picture" class="rounded-circle" width="100" height="100">
                    </div>
                   <div class="form-group">
                       <label for="editPicture">Change Picture</label>
                       <input type="file" class="form-control" id="editPicture" name="picture" accept="image/*">
                    </div>
                    <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="editFirstName"><strong>First Name </strong></label>
                        <input type="text" class="form-control" id="editFirstName" name="first_name" required>
                        <small id="editFirstName-error-msg" style="color: red; display: none;">Only letters and spaces allowed!</small>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="editLastName"><strong>Last Name </strong></label>
                        <input type="text" class="form-control" id="editLastName" name="last_name">
                        <small id="editLastName-error-msg" style="color: red; display: none;">Only letters and spaces allowed!</small>
                    </div>
                    </div>
                    <!-- Department Dropdown -->
                    <div class="form-row">
                 <div class="form-group col-md-6">
                      <label for="editDept"><strong>Department </strong></label>
                         <select class="form-control" id="editDept" name="department" required>
                          <option value="" disabled selected>Select Department</option>
                          <?php include 'EmpDepIDs.php'; ?>
                         </select>
                    </div>
                        <!-- Designation Dropdown -->
                    <div class="form-group col-md-6">
                        <label for="editDesignation"><strong>Designation</strong></label>
                        <select class="form-control" id="editDesignation" name="designation" required>
                            <option value="" disabled selected>Select Designation</option>
                            <?php include 'EmpDesgIDs.php' ?>
                        </select>
                    </div>  
                    </div>      
                    <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="editEmail"><strong>Email</strong></label>
                        <input class="form-control" type="email" id="editEmail" name="email" autocomplete="email" onkeyup="checkEmail()" required>
                        <small id="editEmail-error-msg" style="color: red; display: none;">Invalid Email Format</small>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="editPhone"><strong>Phone</strong></label>
                        <input class="form-control" type="text" id="editPhone" name="phone" autocomplete="tel" title="Enter 11-digit phone number">
                        <small id="editPhone-error-msg" style="color:red;"></small>
                    </div>
                    </div>
                    <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="editAddress"><strong>Address</strong></label>
                        <input type="text" class="form-control" id="editAddress" name="address" required>
                        <small id="editAddress-error-msg" style="color:red;"></small>
                    </div>
                    <!--<div class="form-row">-->
                    <div class="form-group col-md-6">
                        <label for="editJoiningDate"><strong>Joining Date</strong></label>
                        <input type="date" class="form-control" id="editJoiningDate" name="joining_date" required>
                        <small id="editJoiningDate-error-msg" style="color:red;"></small>
                    </div>
                    <!--<div class="form-group col-md-6">
                        <label for="editStatus"><strong>Status</strong></label>
                        <select class="form-control" id="editStatus" name="status" required>
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div>-->
                    </div>
                    <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="editusername"><strong>Username</strong></label>
                        <input class="form-control" type="text" id="username" name="username" autocomplete="username" required onkeyup="checkUsername()">
                        <small id="username-error-msg" style="color: red;"></small>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="edituser_role"><strong>User Role</strong></label>
                        <select class="form-control" id="user_role" name="user_role" autocomplete="user_role" required>
                       <option value="" disabled selected>Select User Role</option>
                       <option value="Admin">Admin</option>
                       <option value="Employee">Employee</option>
                      </select>
                    </div>
                    </div>
                    <div class="modal-footer" style="display: flex;justify-content: center;">
                        <button class="btn btn-light" type="button" style="color:rgb(0,115,230);" data-dismiss="modal">Close</button>
                        <button class="btn btn-primary" type="submit" style="background-color:rgb(0,115,230);">Submit</button> 
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Employee Modal -->
<div class="modal fade" id="deleteEmpModal" tabindex="-1" role="dialog" aria-labelledby="deleteEmpModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 20px;">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteEmpModalLabel" style="color:rgb(0,115,230);"> Deactivate Employee</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="font-family: Roboto;">
                Are you sure you want to Deactivate this employee?
            </div>
            <div class="modal-footer" style="display: flex;justify-content:center;">
                <form action="delete_emp.php" method="POST" id="deleteEmpForm">
                    <input type="hidden" name="employee_id" id="deleteEmpID">
                    <button class="btn btn-light" type="button" style="color:rgb(0,115,230);" data-dismiss="modal">Close</button>
                    <button class="btn btn-primary" type="submit" style="background-color:rgb(0,115,230);">Deactivate</button> 
                </form>
            </div>
        </div>
    </div>
</div>

<script src="../Resources/js/addEmp.js"></script>
<script src="../Resources/js/editEmp.js"></script>
<script src="../Resources/js/deactivateEmp.js"></script>
<script src="../Resources/js/payrollProfile.js"></script>
<script src="../Resources/js/addDesignation.js"></script>
<script src="../Resources/js/editDesignation.js"></script>
<script src="../Resources/js/deleteDesignation.js"></script>
<script src="../Resources/js/addDepartment.js"></script>
<script src="../Resources/js/editDepartment.js"></script>
<script src="../Resources/js/deleteDepartment.js"></script>


<!-- update Attendance Modal -->
   <div class="modal fade" id="updateModal" role="dialog" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="border-radius:20px;">
            <div class="modal-content" style="border-radius:20px;">
                <div class="modal-header">
                    <h3 class="modal-title" style="color:rgb(0,115,230);">Edit Employee Attendance</h3><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button></div>
                <div class="modal-body">
                    <div class="container">
                        <div>
                            <form id="updateForm" method="post">
                                <div class="form-group">
                                    <div id="formdiv" style="box-shadow:none;">
                                            <div style="margin-top:8px;">
                                            <label style="margin-bottom:5px;" for="id"><strong>Employee ID:</strong></label>
                                            <input class="form-control" type="text" id="id" name="id" disabled 
                                                style="color:rgb(73,80,87); background-color:rgb(255,255,255);">
                                        </div>
                                        <div style="margin-top:8px;">
                                            <label style="margin-bottom:5px;" for="employee"><strong>Employee Name:</strong></label>
                                            <input class="form-control" type="text" id="employee" name="employee" disabled 
                                                style="color:rgb(73,80,87); background-color:rgb(255,255,255);">
                                        </div>
                                        <div style="margin-top:8px;">
                                            <label style="margin-bottom:5px;" for="presentdays"><strong>Present Days:</strong></label>
                                            <input class="form-control" type="text" id="presentdays" name="presentdays" 
                                                style="color:rgb(73,80,87); background-color:rgb(255,255,255);">
                                                <small id="presentDaysError1" style="color:red;"></small>
                                        </div>
                                        <div style="margin-top:8px;">
                                            <label style="margin-bottom:5px;" for="absentdays"><strong>Absent Days:</strong></label>
                                            <input class="form-control" type="text" id="absentdays" name="absentdays" 
                                                style="color:rgb(73,80,87); background-color:rgb(255,255,255);">
                                                <small id="absentDaysError1" style="color:red;"></small>
                                                <small id="totalDaysError" style="color:red;"></small>
                                        </div>
                                        <div style="margin-top:8px;">
                                            <label style="margin-bottom:5px;" for="overtimehours"><strong>Overtime Hours:</strong></label>
                                            <input class="form-control" type="text" id="overtimehours" name="overtimehours" 
                                                style="color:rgb(73,80,87); background-color:rgb(255,255,255);">
                                                <small id="overtimeError1" style="color:red;"></small>
                                        </div>
                                        <div style="margin-top:8px;">
                                            <label style="margin-bottom:5px;" for="month"><strong>Month:</strong></label>
                                            <select class="form-control" id="month" name="month" 
                                                    style="color:rgb(73,80,87); background-color:rgb(255,255,255);">
                                                <option value="january" >January</option>
                                                <option value="february">February</option>
                                                <option value="march">March</option>
                                                <option value="april">April</option>
                                                <option value="may">May</option>
                                                <option value="june">June</option>
                                                <option value="july">July</option>
                                                <option value="august">August</option>
                                                <option value="september">September</option>
                                                <option value="october">October</option>
                                                <option value="november">November</option>
                                                <option value="december">December</option>
                                            </select>
                                        </div>
                                        <div style="margin-top:8px;">
                                            <label style="margin-bottom:5px;" for="year"><strong>Year:</strong></label>
                                            <select class="form-control" id="year" name="year" 
                                                    style="color:rgb(73,80,87); background-color:rgb(255,255,255);">
                                                    <!--<option value="" selected disabled>Select Year</option>-->
                                                    <script>
                                                        // Populate the Year dropdown dynamically (e.g., from 2020 to current year + 5)
                                                        const yearSelect = document.getElementById("year");
                                                        const currentYear = new Date().getFullYear();
                                                        for (let y = 2020; y <= currentYear; y++) {
                                                            const option = document.createElement("option");
                                                            option.value = y;
                                                            option.textContent = y;
                                                            yearSelect.appendChild(option);
                                                        }
                                                    </script>
                                                </select>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="display: flex;justify-content: center;"><button class="btn btn-light" type="button" style="color:rgb(0,115,230);" data-dismiss="modal">Close</button><button class="btn btn-primary" type="button" id="updateBtn" style="background-color:rgb(0,115,230);">Update</button></div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Mark Attendance Modal -->
<div class="modal fade" id="markAttendanceModal" role="dialog" tabindex="-1" aria-labelledby="markAttendanceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="border-radius:20px;">
        <div class="modal-content" style="border-radius:20px;">
            <div class="modal-header">
                <h3 class="modal-title" style="color:rgb(0,115,230);">Mark Employee Attendance</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
            <div class="inform"></div>
                <div class="container">
                <p style="color: red; font-size: small;">
                1. Only Employee IDs appear in the dropdown whose attendance of Current Month is not marked.<br>
                2. The Present and Absent days sum can't exceed the total days of the Month i.e 30.<br>
                3. The Overtime hours can be null.<br>
                </p>

                    <div>
                        <form id="markAttendanceForm" method="post">
                            <div class="form-group">
                                <div id="formdiv" style="box-shadow:none;">
                                    <!-- Employee ID Dropdown -->
                                    <div style="margin-top:8px;">
                                        <label><strong>Employee ID<span style="color: red;">&nbsp;&nbsp;*</span></strong></label>
                                        <select class="form-control" name="empid" id="empid" required>    
                                            <option value="" selected disabled>Select Employee</option>
                                        </select>
                                    </div>
                                    <!-- Employee Name Field -->
                                    <div style="margin-top:8px;">
                                        <label style="margin-bottom:5px;" for="empname"><strong>Employee Name:</strong></label>
                                        <input class="form-control" type="text" id="empname" name="empname" disabled
                                            style="color:rgb(73,80,87); background-color:rgb(255,255,255);">
                                    </div>
                                    <!-- Present Days Field -->
                                    <div style="margin-top:8px;">
                                        <label style="margin-bottom:5px;" for="presentdays1"><strong>Total Present Days:</strong></label>
                                        <input class="form-control" type="text" id="presentdays1" name="presentdays1" 
                                            style="color:rgb(73,80,87); background-color:rgb(255,255,255);">
                                        <small id="presentDaysError" style="color:red;"></small>
                                    </div>
                                    <!-- Absent Days Field -->
                                    <div style="margin-top:8px;">
                                        <label style="margin-bottom:5px;" for="absentdays1"><strong>Total Absent Days:</strong></label>
                                        <input class="form-control" type="text" id="absentdays1" name="absentdays1" required
                                            style="color:rgb(73,80,87); background-color:rgb(255,255,255);">
                                        <small id="absentDaysError" style="color:red;"></small>
                                        <small id="daysError" style="color:red;"></small>
                                    </div>
                                    <!-- Overtime Hours Field -->
                                    <div style="margin-top:8px;">
                                        <label style="margin-bottom:5px;" for="overtimehours1"><strong>Total Overtime Hours:</strong></label>
                                        <input class="form-control" type="text" id="overtimehours1" name="overtimehours1" required
                                            style="color:rgb(73,80,87); background-color:rgb(255,255,255);">
                                        <small id="overtimeError" style="color:red;"></small>
                                    </div>
                                    <div class="row" style="margin-top:8px;">
                                        <!-- Month Dropdown -->
                                        <div class="col-md-6">
                                            <label style="margin-bottom:5px;" for="month1"><strong>Month:</strong></label>
                                            <select class="form-control" id="month1" name="month1" required
                                                    style="color:rgb(73,80,87); background-color:rgb(255,255,255);">
                                            </select>
                                        </div>
                                        <!-- Year Dropdown -->
                                        <div class="col-md-6">
                                            <label style="margin-bottom:5px;" for="year1"><strong>Year:</strong></label>
                                            <select class="form-control" id="year1" name="year1" required
                                                    style="color:rgb(73,80,87); background-color:rgb(255,255,255);">
                                                </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="display: flex;justify-content: center;">
                <button class="btn btn-light" type="button" style="color:rgb(0,115,230);" data-dismiss="modal">Cancel</button>
                <button class="btn btn-primary" type="button" id="markBtn" style="background-color:rgb(0,115,230);">Mark</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Allowance Modal -->
<div class="modal fade" id="editAllowanceModal" tabindex="-1" role="dialog" aria-labelledby="editAllowanceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 20px;">
            <!-- Modal Header -->
            <div class="modal-header">
                <h3 class="modal-title" id="editAllowanceModalLabel" style="color:rgb(0,115,230); font-family: Merriweather, sans;">Edit Allowance</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <!-- Modal Body -->
            <div class="modal-body">
                <form action="edit_allowance.php" method="POST" id="editAllowanceForm">
                    <!-- Allowance ID -->
                    <div class="mb-3">
                        <label for="editAllowanceID" class="form-label"><strong>Allowance ID</strong></label>
                        <input type="text" class="form-control" id="editAllowanceID" name="editAllowanceID" placeholder="Allowance ID" readonly required>
                    </div>
                    <!-- Allowance Name -->
                    <div class="mb-3">
                        <label for="editAllowanceName" class="form-label"><strong>Allowance Type</strong></label>
                        <input type="text" class="form-control" id="editAllowanceName" name="editAllowanceName" placeholder="Enter Allowance Type" required>
                        <div id="edit-name-error" class="text-danger" style="display: none; font-size: 14px;"></div>
                    </div>
                    <!-- Description -->
                    <div class="mb-3">
                        <label for="editDescription" class="form-label"><strong>Description</strong></label>
                        <textarea class="form-control" id="editDescription" name="editDescription" placeholder="Enter Description" rows="3"></textarea>
                    </div>
                    <!-- Amount -->
                    <div class="mb-3">
                        <label for="editAmount" class="form-label"><strong>Amount</strong></label>
                        <input type="number" class="form-control" id="editAmount" name="editAmount" placeholder="Enter Amount" step="0.01" required>
                        <div id="edit-amount-error" class="text-danger" style="display: none; font-size: 14px;"></div>
                    </div>
                </form>
            </div>
            <!-- Modal Footer -->
            <div class="modal-footer" style="display: flex;justify-content: center;">
                <button class="btn btn-light" type="button" style="color:rgb(0,115,230);" data-dismiss="modal">Close</button>
                <button class="btn btn-primary" type="submit" form="editAllowanceForm" id="edit-allowance-submit-btn" style="background-color:rgb(0,115,230);">Submit</button>
            </div>
        </div>
    </div>
</div>


<!-- Delete Allowance Modal -->
<div class="modal fade" id="deleteAllowanceModal" tabindex="-1" role="dialog" aria-labelledby="deleteAllowanceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 20px;">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title" id="deleteAllowanceModalLabel" style="color:rgb(0,115,230);">Delete Allowance</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <!-- Modal Body -->
            <div class="modal-body" style="font-family: Roboto;">
                <p>Are you sure you want to delete this Allowance?</p>
                <p id="allowanceErrorMessage" class="text-danger" style="display: none;">
                    Cannot delete this Allowance as it is being used in one or more payroll profiles.
                </p>
            </div>
            <!-- Modal Footer -->
            <div class="modal-footer" style="display: flex;justify-content: center;">
                <form action="delete_allowance.php" method="POST" id="deleteAllowanceForm">
                    <input type="hidden" name="allowanceID" id="allowanceID">
                    <input type="hidden" name="allowanceName" id="allowanceName">
                    <button class="btn btn-light" type="button" style="color:rgb(0,115,230);" data-dismiss="modal">Close</button>
                    <button class="btn btn-primary" type="submit" id="confirmDeleteAllowanceBtn" style="background-color:rgb(0,115,230);">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Deduction Modal -->
<div class="modal fade" id="editDeductionModal" tabindex="-1" role="dialog" aria-labelledby="editDeductionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 20px;">
            <!-- Modal Header -->
            <div class="modal-header">
                <h3 class="modal-title" id="editDeductionModalLabel" style="color:rgb(0,115,230); font-family: Merriweather, sans;">Edit Deduction</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <!-- Modal Body -->
            <div class="modal-body">
                <form action="edit_deduction.php" method="POST" id="editDeductionForm">
                    <!-- Deduction ID -->
                    <div class="mb-3">
                        <label for="editDeductionID" class="form-label"><strong>Deduction ID</strong></label>
                        <input type="text" class="form-control" id="editDeductionID" name="editDeductionID" placeholder="Deduction ID" readonly required>
                    </div>
                    <!-- Deduction Name -->
                    <div class="mb-3">
                        <label for="editDeductionName" class="form-label"><strong>Deduction Type</strong></label>
                        <input type="text" class="form-control" id="editDeductionName" name="editDeductionName" placeholder="Enter Deduction Type" required>
                        <div id="edit-name-error" class="text-danger" style="display: none; font-size: 14px;"></div>
                    </div>
                    <!-- Description -->
                    <div class="mb-3">
                        <label for="editDescription1" class="form-label"><strong>Description</strong></label>
                        <textarea class="form-control" id="editDescription1" name="editDescription1" placeholder="Enter Description" rows="3"></textarea>
                    </div>
                    <!-- Amount -->
                    <div class="mb-3">
                        <label for="editAmount1" class="form-label"><strong>Amount</strong></label>
                        <input type="number" class="form-control" id="editAmount1" name="editAmount1" placeholder="Enter Amount" step="0.01" required>
                        <div id="edit-amount-error" class="text-danger" style="display: none; font-size: 14px;"></div>
                    </div>
                </form>
            </div>
            <!-- Modal Footer -->
            <div class="modal-footer" style="display: flex;justify-content: center;">
                <button class="btn btn-light" type="button" style="color:rgb(0,115,230);" data-dismiss="modal">Close</button>
                <button class="btn btn-primary" type="submit" form="editDeductionForm" id="edit-deduction-submit-btn" style="background-color:rgb(0,115,230);">Submit</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Deduction Modal -->
<div class="modal fade" id="deleteDeductionModal" tabindex="-1" role="dialog" aria-labelledby="deleteDeductionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 20px;">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title" id="deleteDeductionModalLabel" style="color:rgb(0,115,230);">Delete Deduction</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <!-- Modal Body -->
            <div class="modal-body" style="font-family: Roboto;">
                <p>Are you sure you want to delete this Deduction?</p>
                <p id="deductionErrorMessage" class="text-danger" style="display: none;">
                    Cannot delete this Deduction as it is being used in one or more payroll profiles.
                </p>
            </div>
            <!-- Modal Footer -->
            <div class="modal-footer" style="display: flex;justify-content: center;">
                <form action="delete_deduction.php" method="POST" id="deleteDeductionForm">
                    <input type="hidden" name="deductionID" id="deductionID">
                    <input type="hidden" name="deductionName" id="deductionName">
                    <button class="btn btn-light" type="button" style="color:rgb(0,115,230);" data-dismiss="modal">Close</button>
                    <button class="btn btn-primary" type="submit" id="confirmDeleteDeductionBtn" style="background-color:rgb(0,115,230);">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>


<!--Separate dashboard content-->
<script>
    function drawPieChartSep() {
        const salaries = <?php echo $salaries; ?>;
        const salaryextras = <?php echo $salaryextras; ?>;
        const salarydeductions = <?php echo $salarydeductions; ?>;

        const ctxPie = document.getElementById('pieChartSep').getContext('2d');
        return new Chart(ctxPie, {
            type: 'doughnut',
            data: {
                labels: ['Salaries', 'Extras', 'Deductions'],
                datasets: [{
                    data: [salaries, salaryextras, salarydeductions],
                    backgroundColor: ['#1D4ED8', '#10B981', '#F59E0B'],
                    hoverOffset: 8,
                    borderColor: '#fff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                cutout: '60%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#333',
                            font: { size: 14, weight: 'bold' }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                return `${label}: Rs ${value.toLocaleString()}`;
                            }
                        }
                    }
                }
            }
        });
    }

    // ✅ Directly call the function on page load
    document.addEventListener('DOMContentLoaded', drawPieChartSep);

    function drawEarningsLineChartSep() {
        // Fetch PHP data and ensure it's in the correct format for JavaScript
        const months = <?php echo json_encode($months); ?>;
        const earnings = <?php echo json_encode($earnings); ?>;

        const ctxLine = document.getElementById('lineChartSep').getContext('2d');
        new Chart(ctxLine, {
            type: 'line',
            data: {
                labels: months,
                datasets: [{
                    label: 'Monthly Earnings',
                    data: earnings,
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
    }

    // ✅ Call the chart drawing function on page load
    document.addEventListener('DOMContentLoaded', drawEarningsLineChartSep);

    function drawEarningsBarChartSep() {
        // PHP Data (Converted to JavaScript)
        const employeeNames = <?php echo json_encode($employeeNames); ?>;
        const earnings = <?php echo json_encode($earnings); ?>;

        // Get Canvas Element
        const ctx = document.getElementById('earningsBarChartSep').getContext('2d');

        // Create Line Chart (can be changed to 'bar' if needed)
        new Chart(ctx, {
            type: 'line', // 🔁 Use 'bar' here if you want a bar chart
            data: {
                labels: employeeNames,
                datasets: [{
                    label: 'Total Salaries (Rs)',
                    data: earnings,
                    borderColor: '#007bff',
                    backgroundColor: 'rgba(111, 191, 237, 0.2)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    }

    // ✅ Automatically draw chart on page load
    document.addEventListener('DOMContentLoaded', drawEarningsBarChartSep);
</script>

    <div class="col-md-9">
                <p id="currentDate"></p>
            </div>
    <script>
        function displayCurrentDate() {
          // Get the current date
          const now = new Date();
    
          // Format the date
          const options = { year: 'numeric', month: 'long', day: 'numeric' };
          const formattedDate = now.toLocaleDateString(undefined, options);
    
          // Display the date in the HTML element
          document.getElementById('currentDate').innerText = formattedDate;
        }
    
        // Call the function
        displayCurrentDate();
      </script>
    <script src="../Resources/js/jquery.min.js"></script>
    <script src="../Resources/bootstrap/js/bootstrap.min.js"></script>
    <script src="../Resources/js/dashboard.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script>
    // Function to open the printable payslip page and trigger print
    function printPayslip() {
        var printWindow = window.open('http://localhost/newPayrollEase/PayrollEase/Controllers/payslip_printed.html', '_blank'); // Open payslip_printed.html in the same folder
        printWindow.onload = function() {
            printWindow.print(); // Trigger print dialog once the page is fully loaded
        };
    }
</script>


<!-- Make sure SweetAlert is included in your HTML head -->


<!--<script>
$(document).ready(function () {
    $(document).on("submit", "#payrollForm", function (e) {
        e.preventDefault(); // Prevent default form submission

        var formData = $(this).serialize(); // Serialize form data

        $.post("../Controllers/save_payroll.php", formData, function (response) {
            alert(response); // Show success/error message

            // Reload only the payroll form (not the entire page)
            $("#payrollForm").load("../Views/payroll.html #payrollForm > *");
        }).fail(function () {
            alert("❌ Something went wrong! Check console.");
        });
    });
});
</script>-->

	<!--script for load leave-management-emp-->
	<!--<script>
	function loadPage(url) {
    // Hide the dashboard content
    document.getElementById('dashboard-content').style.display = 'none';

    // Show the leave-management-emp section
    document.getElementById('leave-management-emp').style.display = 'block';

    // Load content dynamically into dynamic-content
    $.ajax({
        url: url,
        method: 'GET',
        success: function(response) {
            document.getElementById('dynamic-content').innerHTML = response;
        },
        error: function() {
            document.getElementById('dynamic-content').innerHTML = '<p>Error loading the page. Please try again later.</p>';
        }
    });
}

	</script>-->
	<script>
    // function updateStatus(leaveId, status) {
    //     $.post('../Controllers/update_status.php', { leave_id: leaveId, status: status }, function(response) {
    //         $('#status-' + leaveId).text(status); // Update the status in the UI
            
    //         // Update Status Image Dynamically
    //         var imgPath = '';
    //         if (status == 'Pending') {
    //             imgPath = '../Resources/img/icons8-select-16.png';
    //         } else if (status == 'Approved') {
    //             imgPath = '../Resources/img/icons8-select-32 (2).png';
    //         } else if (status == 'Rejected') {
    //             imgPath = '../Resources/img/icons8-select-32 (1).png';
    //         }

    //         $('#status-img-' + leaveId).attr('src', imgPath);
            
    //         // Show SweetAlert on success
    //         Swal.fire({
    //             title: 'Success!',
    //             text: 'Leave status updated to ' + status,
    //             icon: 'success',
    //             confirmButtonText: 'OK'
    //         });
            
    //     }).fail(function() {
    //         // Show SweetAlert on error
    //         Swal.fire({
    //             title: 'Error!',
    //             text: 'Error updating status. Please try again later.',
    //             icon: 'error',
    //             confirmButtonText: 'OK'
    //         });
    //     });
    // }
</script>
			

<!--script for printform-->
<script>
function employeeReportPrint() {
    let form = document.getElementById("exportForm"); // Ensure the correct ID is used
    if (form) {
        form.submit(); // Submit the form if it exists
    } else {
        console.error("Form with ID 'exportForm' not found!"); // Debugging message
    }
}

</script>
<script>
function AttendanceReportPrint() {
    let form = document.getElementById("exportForm2"); // Ensure the correct ID is used
    if (form) {
        form.submit(); // Submit the form if it exists
    } else {
        console.error("Form with ID 'exportForm' not found!"); // Debugging message
    }
}

</script>
<script>
function leave_report_print() {
    let form = document.getElementById("exportForm3"); // Ensure the correct ID is used
    if (form) {
        form.submit(); // Submit the form if it exists
    } else {
        console.error("Form with ID 'exportForm' not found!"); // Debugging message
    }
}

</script>

<script>
    function setAction(action) {
        // Set the action parameter based on the button clicked
        var form = document.getElementById('payslipForm');
        if (action === 'email') {
            form.action = 'sendmail.php'; // Update with the actual email handler script
        }
        // Submit the form
        form.submit();
    }
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
 <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!--script for select all checkbox on dashboard--> 
 <script src="../Resources/js/checkboxHandler.js" ></script>
 <!---script to load charts-->
 <script src="../Resources/js/Reports.js"></script>
 <!-- script for paysliplog-->
  <script src="../Resources/js/allowance.js"></script>
 <script src="../Resources/js/update_leave_status.js"></script>

 <script src="../Resources/js/allowance.js"></script>
 <script src="../Resources/js/deduction.js"></script>
 <script src="../Resources/js/editAllowance_list.js"></script>
 <script src="../Resources/js/deleteAllowance_list.js"></script>
 <script src="../Resources/js/editDeduction_list.js"></script>
 <script src="../Resources/js/deleteDeduction_list.js"></script>
 <script src="../Resources/js/markAttendance.js"></script>
 <script src="../Resources/js/updateAttendance.js"></script>
 <script src="../Resources/js/payrollPage.js"></script>
 <script>
function loadPage(url, push = true) {
    console.log("🔄 Loading page:", url);

    // ✅ Push URL to browser history
    if (push) {
        history.pushState({ url }, null, "?page=" + encodeURIComponent(url));
    }

    // Hide dashboard and show profile section
    document.getElementById('dashboard-content').style.display = 'none';
    document.getElementById('profile').style.display = 'block';

    // ✅ Clear previous content before loading new content
    document.getElementById('dynamic-content').innerHTML = '<p>Loading...</p>';

        // Load content dynamically
    $.ajax({
        url: url,
        method: 'GET',
        success: function(response) {
            document.getElementById('dynamic-content').innerHTML = response;
            console.log("✅ Page loaded successfully:", url);

            // ✅ Ensure charts run only after content is updated
            setTimeout(() => {
				// 🟢 Initialize Payslip Page Logic
                if (url.includes("payslip.php")) {
                    if (typeof initPayslipPage === "function") {
                        console.log("✅ Initializing Payslip Page...");
                        initPayslipPage();
                    } else {
                        console.warn("⚠️ initPayslipPage() function not found.");
                    }
                }
                // ✅ Check if the page is `employee-report.php` and the chart exists
if (url.includes("employee-report.php")) {
    // ✅ Initialize Employee Lollipop Chart
    if (document.getElementById('employeeLollipopChart')) {
        console.log("✅ Drawing Employee Lollipop Chart...");
        drawEmployeeLollipopChart();
    }

    // ✅ Initialize Search Feature (Only for Employee)
    if (document.getElementById('search')) {
        console.log("✅ Initializing Employee Report Search...");
        initialize_emp_Search();
    }
}

// ✅ Check if the page is `attendance_report.php` and the chart exists
if (url.includes("Attendence_report.php")) {
    let attendanceChartCanvas = document.getElementById("lineChart");

    if (attendanceChartCanvas) {
        console.log("✅ Drawing Attendance Chart...");
        drawAttendanceLineChart();
    } else {
        console.warn("⚠️ Attendance Chart canvas not found, retrying in 1s...");
        setTimeout(() => {
            let retryCanvas = document.getElementById("lineChart");
            if (retryCanvas) {
                console.log("✅ Retrying Attendance Chart...");
                drawAttendanceLineChart();
            } else {
                console.error("❌ Element with ID 'lineChart' is still missing.");
            }
        }, 500);
    }

    // ✅ Initialize Search Feature (Only for Attendance)
    if (document.getElementById('search')) {
        console.log("✅ Initializing Attendance Report Search...");
        initialize_att_Search();
    }
}

// ✅ Check if the page is `leave_report.php` and the chart exists
if (url.includes("leave_report.php")) {
    let leaveChartCanvas = document.getElementById("leave_barChart");

    if (leaveChartCanvas) {
        console.log("✅ Drawing Leave Report Chart...");
        drawLeaveReportChart();
    } else {
        console.warn("⚠️ Leave Report Chart canvas not found, retrying in 1s...");
        setTimeout(() => {
            let retryCanvas = document.getElementById("leave_barChart");
            if (retryCanvas) {
                console.log("✅ Retrying Leave Report Chart...");
                drawLeaveReportChart();
            } else {
                console.error("❌ Element with ID 'barChart' is still missing.");
            }
        }, 300);
    }

    // ✅ Initialize Search Feature (Only for Leave)
    if (document.getElementById('search')) {
        console.log("✅ Initializing Leave Report Search...");
        initialize_leave_Search();
    }
}

// ✅ Check if the page is `attendance.php` and the chart exists
if (url.includes("attendance.php")) {
    // ✅ Initialize Search Feature (Only for Attendance)
    if (document.getElementById('searchAttendance')) {
        console.log("✅ Initializing Attendance  Search...");
        initialize_attendance_search();
    }
}
function initialize_attendance_search() {
    console.log("🔍 Attendance Search Initialized");
    
    function fetchAttendanceData() {
        var searchValue = $("#searchAttendance").val(); // Get search input value

        $.ajax({
            url: "empSearch_attendance.php", // Updated to match your new search file
            type: "POST",
            data: { 
                attsearch: searchValue // Changed parameter name to match your form
            },
            success: function(response) {
                $("tbody").html(response); // Update table body with results
            },
            error: function(xhr, status, error) {
                console.error("Search Error:", error);
            }
        });
    }

    // Search on keyup with slight delay to prevent excessive requests
    $("#searchAttendance").on("keyup", function() {
        clearTimeout($(this).data('timer'));
        $(this).data('timer', setTimeout(fetchAttendanceData, 300));
    });

    // Also trigger search when search button is clicked
    $("#searchForm button").on("click", fetchAttendanceData);
}

// manageEmp.php page search Bar 
if (url.includes("manageEmp.php")) {
    if (document.getElementById('manageEmp_search')) {
        console.log("✅ Initializing ManageEmp Search...");
        initialize_manageEmp_search();
    }
}
function initialize_manageEmp_search() {
    console.log("🔍 ManageEmp Search Initialized");

    function fetchManageEmpData() {
        var searchValue = $("#manageEmp_search").val();

        $.ajax({
            url: "manageEmp_search.php",
            type: "GET",
            data: {
                search: searchValue
            },
            success: function(response) {
                $("tbody").html(response); // Replace table body content
            },
            error: function(xhr, status, error) {
                console.error("Search Error:", error);
            }
        });
    }
    // Trigger search on keyup with delay
    $("#manageEmp_search").on("keyup", function() {
        clearTimeout($(this).data('timer'));
        $(this).data('timer', setTimeout(fetchManageEmpData, 300));
    });
    // Also trigger search when search button is clicked
    $("#searchForm button").on("click", fetchManageEmpData);
}


                // ✅ Dynamically load charts based on the current page
if (url.includes("dashboard.php")) {
    // General function to safely destroy existing chart instances
    const destroyChartIfExists = (canvasId) => {
        const canvas = document.getElementById(canvasId);
        if (canvas) {
            const chartInstance = Chart.getChart(canvas);
            if (chartInstance) {
                chartInstance.destroy();
                console.log(`♻️ Destroyed chart: ${canvasId}`);
                canvas.width = canvas.width; // Clear canvas
            }
        }
    };

    // Generic chart loader with retries and logging
    const attemptChartCreation = (canvasId, drawFunction, retryCount = 0) => {
        destroyChartIfExists(canvasId);

        const canvas = document.getElementById(canvasId);

        if (!canvas) {
            if (retryCount < 3) {
                console.warn(`⚠️ ${canvasId} not found. Retrying in ${300 * (retryCount + 1)}ms...`);
                return setTimeout(() => attemptChartCreation(canvasId, drawFunction, retryCount + 1), 300 * (retryCount + 1));
            }
            return console.error(`❌ ${canvasId} element not found after multiple attempts`);
        }

        try {
            console.log(`✅ Drawing chart: ${canvasId}`);
            window[`${canvasId}Instance`] = drawFunction();

            setTimeout(() => {
                const chart = Chart.getChart(canvas);
                if (!chart) {
                    console.error(`❌ Chart instance not found for ${canvasId}`);
                    if (retryCount < 3) {
                        console.warn(`⚠️ Retrying chart creation: ${canvasId} (attempt ${retryCount + 1})`);
                        return attemptChartCreation(canvasId, drawFunction, retryCount + 1);
                    }
                } else {
                    console.log(`✔️ Chart rendered: ${canvasId}`);
                }
            }, 200);
        } catch (error) {
            console.error(`⚠️ Error drawing ${canvasId}: ${error.message}`);
            if (retryCount < 3) {
                setTimeout(() => attemptChartCreation(canvasId, drawFunction, retryCount + 1), 500 * (retryCount + 1));
            }
        }
    };

    // ⏳ Start chart drawing after slight delay
    setTimeout(() => {
        attemptChartCreation("pieChartSep", drawPieChartSep);
        attemptChartCreation("lineChartSep", drawEarningsLineChartSep);
        attemptChartCreation("earningsBarChartSep", drawEarningsBarChartSep);
    }, 50);
}


        // Check if the page is `adminDashboard.php`
        /*if (url.includes("adminDashboard.php")) {
            if (document.getElementById('pieChart')) {
                console.log("✅ Drawing adminDashboard Pie Chart...");
                drawPieChart(); // Load Pie Chart for adminDashboard
            }
        }

        // ✅ You can add more conditions for other pages if needed
        else if (url.includes("dashboard.php")) {
            if (document.getElementById('pieChart')) {
                console.log("✅ Drawing Pie Chart for other page...");
                drawPieChart(); // Load Pie Chart for other page
            }
        }*/
  
                // ✅ Check if the page is `payroll.html` and the emp id drop down exists 
                if (url.includes("payroll.html")) {
                    
    console.log("💬 payroll.html detected, loading payroll.js...");

    const script = document.createElement("script");
    script.src = "../Resources/js/payroll.js"; // ✅ Update path if it's in another folder
    script.onload = () => console.log("✅ payroll.js loaded successfully.");
    script.onerror = () => console.error("❌ Failed to load payroll.js.");
    document.body.appendChild(script);

                    // Set current month and year immediately
                    setCurrentMonthAndYear();
                    let empidDropdown = document.getElementById("empID");

                    if (empidDropdown) {
                        console.log("✅ Employee ids dropdown found...");
                        populateEmployeeDropdown();
                    } else {
                        console.warn("⚠️ Employee id dropdown not found, retrying in 1s...");
                        setTimeout(() => {
                            let retryEmpidDropdown = document.getElementById("empID");
                            if (retryEmpidDropdown) {
                                console.log("✅ Retrying Employee ids dropdown...");
                                populateEmployeeDropdown();
                            } else {
                                console.error("❌ Employee with ID selected is still missing.");
                            }
                        }, 300);
                    }
                    let payrollprofileDropdown = document.getElementById("payrollProfileID");

                    if (payrollprofileDropdown) {
                        console.log("✅ Payroll Profile dropdown found...");
                        populatePayrollProfiles();
                    } else {
                        console.warn("⚠️ Payroll Profile id dropdown not found, retrying in 1s...");
                        setTimeout(() => {
                            let retryPayrollprofileDropdown = document.getElementById("payrollProfileID");
                            if (retryPayrollprofileDropdown) {
                                console.log("✅ Retrying Payroll Profile ids dropdown...");
                                populatePayrollProfiles();
                            } else {
                                console.error("❌ Payroll Profile with ID selected is still missing.");
                            }
                        }, 300);
                    }
                    // Add retry logic for month/year fields in case they're not immediately available
                    if (!document.getElementById('payrollMonth') || !document.getElementById('payrollYear')) {
                        console.warn("⚠️ Month/Year inputs not found, retrying...");
                        setTimeout(setCurrentMonthAndYear, 300);
                    }
                }

             

                //QR code script
                function loadQRCode() {
    const empID = $('#emp_ID_payslip').val();
    const month = $('#month_Select').val();
    const year = $('#selectedYearHidden').val();

    if (empID && month && year) {
        $.ajax({
            url: 'generate_qr.php', 
            method: 'GET',
            data: {
                emp_ID_payslip: empID,
                payrollMonth: month,
                selectedYearHidden: year
            },
            xhrFields: {
                responseType: 'blob'
            },
            success: function (blob) {
                if (blob.type.startsWith('image')) {
                    const url = URL.createObjectURL(blob);
                    $('#qrImage').attr('src', url).show();
                    $('#qrStatus').text('');
                } else {
                    $('#qrImage').hide();
                    $('#qrStatus').text('Received non-image response.');
                }
            },
            error: function (xhr) {
                $('#qrImage').hide();
                const errorMsg = xhr.responseJSON?.error || 'Unexpected error occurred';
                $('#qrStatus').text('Failed to generate QR Code: ' + errorMsg);
            }
        });
    } else {
        $('#qrImage').hide();
        $('#qrStatus').text('Please select all fields to generate QR Code.');
    }
}
// Reload QR code when any value changes
$('#month_Select, #emp_ID_payslip').change(loadQRCode);
$('#year_Dropdown').on('click', '.dropdown-item', function () {
    const year = $(this).text();
    $('#selectedYearHidden').val(year);
    $('#year_Button').text(year);
    loadQRCode();
});




                if (window.location.href.includes("chatBot.html")) {
    console.log("💬 chatBot.html detected, loading chatBot.js...");

    const script = document.createElement("script");
    script.src = "../Resources/js/chatBot.js"; // ✅ Update path if it's in another folder
    script.onload = () => console.log("✅ chatBot.js loaded successfully.");
    script.onerror = () => console.error("❌ Failed to load chatBot.js.");
    document.body.appendChild(script);
}

                    
            }, 500); // ✅ Ensure content is updated before running chart scripts

            
        },
        error: function() {
            document.getElementById('dynamic-content').innerHTML = '<p>Error loading the page. Please try again later.</p>';
        }
    });
}
// 🔙 Handle browser back/forward button clicks
window.onpopstate = function(event) {
    const url = event.state?.url || new URLSearchParams(window.location.search).get('page');
    if (url) {
        loadPage(url, false); // Don't push again to avoid loop
    }
};
// ✅ Load from URL on initial page load (refresh or direct link)
window.addEventListener("DOMContentLoaded", () => {
        const initialPage = new URLSearchParams(window.location.search).get("page");
        if (initialPage) {
            loadPage(initialPage, false);
        }
    });
</script>
</body>
</html>