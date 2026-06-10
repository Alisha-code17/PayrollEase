<?php
include '../Database/db1.php'; 
session_start();

$employeeID = isset($_SESSION['Employee_id']) ? intval($_SESSION['Employee_id']) : 0;
$currentYear = date('Y');
$selectedYear = isset($_GET['year']) && is_numeric($_GET['year']) ? $_GET['year'] : $currentYear;

$query = "SELECT e.*, d.Name AS DepartmentName, desig.Name AS DesignationName
          FROM employee e
          LEFT JOIN department d ON e.Department_id = d.Department_id
          LEFT JOIN designation desig ON e.Designation_id = desig.Designation_id
          WHERE e.Employee_id = $employeeID";
$result = mysqli_query($conn, $query);
$employee = mysqli_fetch_assoc($result);

$imagePath = (!empty($employee['Picture']) && file_exists('uploads/' . $employee['Picture']))
? 'uploads/' . $employee['Picture']
: 'uploads/default.jpg';

$yearQuery = "SELECT DISTINCT Year FROM attendance WHERE Employee_id = $employeeID ORDER BY Year DESC";
$yearResult = mysqli_query($conn, $yearQuery);

$attendanceQuery = "SELECT 
                        Month,
                        Year,
                        SUM(Present_days) AS PresentDays,
                        SUM(Absent_days) AS AbsentDays,
                        SUM(Overtime_hours) AS OvertimeHours
                    FROM attendance 
                    WHERE Employee_id = $employeeID AND Year = $selectedYear
                    GROUP BY Month, Year";
$attendanceResult = mysqli_query($conn, $attendanceQuery);
$attendanceData = [];
while ($row = mysqli_fetch_assoc($attendanceResult)) {
    $attendanceData[] = $row;
}
$totalPresent = 0;
$totalAbsent = 0;
$totalOvertime = 0;

foreach ($attendanceData as $row) {
    $totalPresent += $row['PresentDays'];
    $totalAbsent += $row['AbsentDays'];
    $totalOvertime += $row['OvertimeHours'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EmpAttendance</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Merriweather+Sans">
    <link rel="stylesheet" href="../Resources/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../Resources/css/EmpAttendance.css">
</head>
<body>
<div class="container" style="margin-top:90px;">
    <h4 id="attendanceTitle">Attendance - <?php echo $selectedYear; ?></h4>
    <h6>Attendance &gt; View Attendance</h6>
</div>
<div class="container" style="margin-top:50px;">
<div class="row">
<!--Emp Inf Card-->
<div class="col-md-3">
    <div class="card shadow-lg employee-card text-center" style="border-radius:15px;border:1px solid #6fbfed;">
        <?php if ($employee): ?> 
            <h5 class="mt-3">
                <?php echo htmlspecialchars($employee['FirstName'] . " " . $employee['LastName']); ?>
            </h5>
            <p>
                <?php echo htmlspecialchars($employee['DepartmentName'] . " - " . $employee['DesignationName']); ?>
            </p>
            <div>
                <img src="<?php echo $imagePath; ?>" 
                alt="Employee Image" 
                style="width:90px; height:90px; border-radius:50%; object-fit:cover;">
            </div>
            <hr>
            <h6>Joined on:</h6>
            <p><?php echo htmlspecialchars($employee['JoiningDate']); ?></p>
        <?php else: ?>
            <p class="text-danger">Employee Not Found!</p>
        <?php endif; ?>
    </div>
</div>
<!-- Summary Cards -->
<div class="col-md-9">
    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-lg small-card text-center">
                <div class="card-body">
                    <h6 class="card-title"><i class="fas fa-user-check"></i> Present</h6>
                    <h4 id="empPresentDays"><?php echo $totalPresent; ?></h4>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-lg small-card text-center">
                <div class="card-body">
                    <h6 class="card-title"><i class="fas fa-user-times"></i> Absent</h6>
                    <h4 id="empAbsentDays"><?php echo $totalAbsent; ?></h4>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-lg small-card text-center">
                <div class="card-body">
                    <h6 class="card-title"><i class="fas fa-clock"></i> Overtime</h6>
                    <h4 id="overtimeHours"><?php echo $totalOvertime . " hrs"; ?></h4>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Year Dropdown-->
<div class="container mt-4">
    <div class="d-flex justify-content-end align-items-center mb-3">
        <form method="GET" action="" class="d-flex align-items-center">
            <label for="year" style="font-family: Roboto;" class="me-2 mb-0">Select Year:</label>
            <select id="year" class="form-control" style="width: 150px;">          
            <?php while ($yearRow = mysqli_fetch_assoc($yearResult)) : ?>
                    <option value="<?php echo $yearRow['Year']; ?>" 
                        <?php echo ($yearRow['Year'] == $selectedYear) ? 'selected' : ''; ?>>
                        <?php echo $yearRow['Year']; ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </form>
    </div>
</div>
    <!-- Attendance Table -->
    <div class="container">
    <div class="table-responsive" style="border-radius: 7px;" >
        <table class="table" style="background-color: white;border:1px solid #007bff; border-radius: 10px;">
            <thead>
                <tr style="background-color:rgb(0,115,230);color:white;">
                    <th style="width:150px;">Month</th>
                    <th style="width:300px; text-align: center;">Present Days</th>
                    <th style="text-align: center;">Absent Days</th>
                    <th style="text-align: center;">&nbsp;Overtime</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($attendanceData)) : ?>
                    <?php foreach ($attendanceData as $row) : ?>
                        <tr>
                            <?php
                                $monthNum = date('n', strtotime($row['Month']));
                            ?>
                            <td><?php echo date("F", mktime(0, 0, 0, $monthNum, 1)); ?></td>
                            <td style="font-family: Roboto; text-align: center;"><?php echo $row['PresentDays']; ?></td>
                            <td style="font-family: Roboto; text-align: center;"><?php echo $row['AbsentDays']; ?></td>
                            <td style="font-family: Roboto; text-align: center;"><?php echo $row['OvertimeHours'] . " hrs"; ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="4" class="text-center text-danger">No Attendance Records Found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<!--<script src="../Resources/js/jquery.min.js"></script>
<script src="../Resources/bootstrap/js/bootstrap.min.js"></script>-->
</body>
</html>