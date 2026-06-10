<?php
session_start();
include '../Database/db1.php'; // MySQLi database connection

if (!isset($_SESSION['user_id'])) {
    echo "User not logged in!";
    exit();
}

$employeeId = $_SESSION['user_id'];
$imagePath = "uploads/default.jpg";
$employee_id_emp = $_SESSION['Employee_id'];

// Fetch employee data
$query = "SELECT * FROM employee WHERE User_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $employeeId);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row) {
    $firstName = $row['FirstName'] ?? 'Not Provided';
    $lastName = $row['LastName'] ?? 'Not Provided';
    $email = $row['Email'] ?? 'Not Provided';
    $phone = $row['Phone'] ?? 'Not Provided';
    $address = $row['Address'] ?? 'Not Provided';
    $joiningDate = $row['JoiningDate'] ?? 'Not Provided';
    $departmentId = $row['Department_id'] ?? null;
    $designationId = $row['Designation_id'] ?? null;
    $imagePath = !empty($row['Picture']) ? "uploads/" . $row['Picture'] : "uploads/default.jpg";
} else {
    echo "No employee found!";
    exit();
}
$stmt->close();

// Fetch department name (if available)
$department = 'Not Provided';
if ($departmentId) {
    $deptQuery = "SELECT Name FROM department WHERE department_id = ?";
    $deptStmt = $conn->prepare($deptQuery);
    $deptStmt->bind_param("i", $departmentId);
    $deptStmt->execute();
    $deptResult = $deptStmt->get_result();
    $deptRow = $deptResult->fetch_assoc();
    if ($deptRow) {
        $department = $deptRow['Name'];
    }
    $deptStmt->close();
}

// Fetch designation name (if available)
$designation = 'Not Provided';
if ($designationId) {
    $desigQuery = "SELECT Name FROM designation WHERE designation_id = ?";
    $desigStmt = $conn->prepare($desigQuery);
    $desigStmt->bind_param("i", $designationId);
    $desigStmt->execute();
    $desigResult = $desigStmt->get_result();
    $desigRow = $desigResult->fetch_assoc();
    if ($desigRow) {
        $designation = $desigRow['Name'];
    }
    $desigStmt->close();
}

// Fetch user account details
$username = 'Not Provided';
$userRole = 'Not Provided';

$userQuery = "SELECT Username, UserRole FROM users WHERE User_id = ?";
$userStmt = $conn->prepare($userQuery);
$userStmt->bind_param("i", $employeeId);
$userStmt->execute();
$userResult = $userStmt->get_result();
$userData = $userResult->fetch_assoc();

if ($userData) {
    $username = $userData['Username'] ?? 'Not Provided';
    $userRole = $userData['UserRole'] ?? 'Not Provided';
}
$userStmt->close();

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
<body>
	<div style="display: flex; justify-content: center;">

	<div id="dashboard-content" class="container" style="margin-right:0px;margin-left:0px;padding-left:0px;padding-right:0px;">
        <div class="row" style="margin:9px;margin-left:-16px;">
            <div class="col" style="width:828px; padding-top:10px;">
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
									<h5 class="mb-1"><?php echo htmlspecialchars($firstName . ' ' . $lastName); ?></h5>
									<p class="text-muted mb-0" style="font-size: 14px;">
										<?php echo htmlspecialchars($department . ' - ' . $designation); ?>
									</p>
									<p class="text-muted mb-0" style="font-size: 14px;">Employee ID:
										<?php echo htmlspecialchars($employee_id_emp); ?>
									</p>
								</div>
							</div>


							<div class="row mb-2">
								<div class="col">
									<div style="padding-top: 7px;">
										<h6 class="mb-1" style="font-size: 14px; color: #555;">Phone Number</h6>
										<p style="font-size: 13px; color: #333; margin-bottom: 0;"><?php echo htmlspecialchars($phone); ?></p>
									</div>
								</div>
							</div>

							<div class="row mb-2">
								<div class="col">
									<div style="padding-top: 9px;">
										<h6 class="mb-1" style="font-size: 14px; color: #555;">Email Address</h6>
										<p style="margin-bottom: 0; font-size: 13px; color: #333;"><?php echo htmlspecialchars($email); ?></p>
									</div>
								</div>
							</div>

						<div class="row mb-2">
							<div class="col">
								<div style="padding-top: 9px;">
									<h6 class="mb-1" style="font-size: 14px; color: #555;">Address</h6>
									<p style="font-size: 13px; color: #333; margin-bottom: 0;">
										<?php echo htmlspecialchars($address); ?>
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
											if (!empty($joiningDate)) {
												echo date("d-m-Y", strtotime($joiningDate));
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
								<div id="payrollChartContainer_emp" style="position: relative; height: 300px;">
								<canvas id="leaveAreaChart_page" height="330" width="495" style="display: block;box-sizing: border-box;height: 300px;width: 450px;padding-left: 50px;padding-bottom: 20px;"></canvas>

								<!-- 👇 This is the message that will appear if all data is zero -->
								<div id="noDataMessage_emp"
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
								<h6 id="totalDays_emp" class="fw-bold text-dark mb-0">--</h6>
							  </div>
							</div>
						  </div>
						  <div class="col-md-6">
							<div class="d-flex align-items-center">
							<div style=" padding-bottom: 23px;  padding-right: 7px;">  <i class="fas fa-check-circle fa-lg me-3 text-success"></i></div>
							  <div>
								<p class="mb-1 fw-semibold text-muted">Present Days</p>
								<h6 id="presentDays_emp" class="fw-bold text-success mb-0">--</h6>
							  </div>
							</div>
						  </div>
						</div>

						<div class="mb-4">
						  <div class="d-flex align-items-center">
						   <div style="padding-bottom: 23px; padding-right: 7px;"> <i class="fas fa-times-circle fa-lg me-3 text-danger"></i></div>
							<div>
							  <p class="mb-1 fw-semibold text-muted">Absent Days</p>
							  <h6 id="absentDays_emp" class="fw-bold text-danger mb-0">--</h6>
							</div>
						  </div>
						</div>

						<div>
						  <p class="mb-2 fw-semibold text-muted">Attendance Rate</p>
						  <div class="progress rounded-pill" style="height: 20px;">
						  <div id="attendanceProgress_emp" class="progress-bar bg-success rounded-pill" role="progressbar"
							style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
						<span class="visually-hidden" id="attendanceProgress_emp">0% Complete</span>
						</div>

						  </div>
						  <small id="attendancePercentage_emp" class="text-muted mt-1">0%</small>
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
									<strong>Basic Salary:</strong> $<span id="basicSalary_emp">Loading...</span>
								</li>
								<li class="list-group-item">
									<i class="fas fa-gift"></i>
									<strong>Bonus:</strong> $<span id="bonus_emp">Loading...</span>
								</li>
								<li class="list-group-item">
									<i class="fas fa-percent"></i>
									<strong>Total Allowence:</strong> -$<span id="totalAllowance_emp">Loading...</span>
								</li>
								<li class="list-group-item">
									<i class="fas fa-minus-circle"></i>
									<strong>Other Deductions:</strong> -$<span id="deductions_emp">Loading...</span>
								</li>
								<li class="list-group-item">
									<strong>Total Salary:</strong> $<span id="netSalary_emp">Loading...</span>
								</li>
							</ul>
						</div>
					</div>

				</div>
            </div>
    </div>
	</div>
	</body>
	</html>