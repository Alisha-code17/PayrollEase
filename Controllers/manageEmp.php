<?php
include '../Database/db1.php'; 
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
        $sql = "SELECT 
        e.Employee_id, e.Picture, e.FirstName, 
        e.LastName,
        CONCAT(COALESCE(e.FirstName, ''), ' ', COALESCE(e.LastName, '')) AS FullName,
        e.Email, e.Address, e.Phone, e.Designation_id, 
        e.JoiningDate, e.Status, e.Department_id, 
        d.Name AS Department_Name,
        des.Name AS Designation_Name,
        u.Username, u.UserRole
    FROM employee e
    LEFT JOIN department d ON e.Department_id = d.Department_id
    LEFT JOIN designation des ON e.Designation_id = des.Designation_id
    LEFT JOIN users u ON e.Employee_id = u.User_id
    
    WHERE e.Status = 'Active'
    ORDER BY e.Employee_id ASC";
$result = $conn->query($sql);
$employee = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $employee[] = [
            'Employee_id' => $row['Employee_id'],
            'Picture' => $row['Picture'],
            'FirstName' => $row['FirstName'],
            'LastName' => $row['LastName'],
            'FullName' => $row['FullName'], 
            'Email' => $row['Email'],
            'Phone' => $row['Phone'],
            'Address' => $row['Address'],
            'Designation_id' => $row['Designation_id'],
            'Designation_Name' => $row['Designation_Name'],
            'JoiningDate' => $row['JoiningDate'],
           // 'Status' => $row['Status'],
            'Department_id' => $row['Department_id'],
            'Department_Name' => $row['Department_Name'],
            'Username' => $row['Username'],         
            'UserRole' => $row['UserRole']          
        ];
    }
}
    // Card Statistics
/*$totalEmployees = count($employee);
$activeEmployees = count(array_filter($employee, fn($emp) => $emp['Status'] === 'Active'));
$inactiveEmployees = $totalEmployees - $activeEmployees;*/

$totalEmployees = count($employee);
// Department Count 
$deptQuery = "SELECT COUNT(*) AS totalDepartments FROM department";
$deptResult = $conn->query($deptQuery);
$totalDepartments = ($deptResult->num_rows > 0) ? $deptResult->fetch_assoc()['totalDepartments'] : 0;

// Designation Count 
$desgQuery = "SELECT COUNT(*) AS totalDesignations FROM designation";
$desgResult = $conn->query($desgQuery);
$totalDesignations = ($desgResult->num_rows > 0) ? $desgResult->fetch_assoc()['totalDesignations'] : 0;
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Emp</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Merriweather+Sans">
    <link rel="stylesheet" href="../Resources/css/manageEmp.css">
    <link rel="stylesheet" href="../Resources/bootstrap/css/bootstrap.min.css">
</head>
<body>
<div class="container" id="empList" style="margin-top:90px;">
        <h4>Manage Employee</h4>
        <h6>Employee &gt; Manage Employee</h6>
    </div>
    <!-- Cards Section -->
    <div class="container" style="margin-top:50px;">
        <!-- Total Employees -->
        <div class="card" style="border-radius:15px;width:250px;border:1px solid #6fbfed;height:95px;margin-bottom:33px;margin-left:20px;">         
        <div class="card-body">
                <h5 class="card-title">Active Employees</h5>          
            <h6 class="text-muted card-subtitle mb-0"><?= $totalEmployees; ?></h6>
            </div>
        </div>

        <!--<div class="card" style="border-radius:15px;width:250px;border:1px solid #6fbfed;height:95px;margin-bottom:33px;margin-left:398px;margin-top:-126px;">
            <div class="card-body">
                <h5 class="card-title">Active</h5>
                <h6 class="text-muted card-subtitle mb-2"><?= $activeEmployees; ?></h6>
            </div>
        </div>
        <div class="card" style="border-radius:15px;width:250px;border:1px solid #6fbfed;height:95px;margin-bottom:33px;margin-left:758px;margin-top:-127px;">
            <div class="card-body">
                <h5 class="card-title">Inactive</h5>
                <h6 class="text-muted card-subtitle mb-2"><?= $inactiveEmployees; ?></h6>
            </div>-->
        
    <!-- Departments -->
    <div class="card" style="border-radius:15px;width:250px;border:1px solid #6fbfed;height:95px;margin-bottom:33px;margin-left:398px;margin-top:-126px;">
            <div class="card-body">
            <h5 class="card-title">Departments</h5>
            <h6 class="text-muted card-subtitle mb-2"><?= $totalDepartments; ?></h6>
        </div>
    </div>
    <!-- Designations -->
    <div class="card" style="border-radius:15px;width:250px;border:1px solid #6fbfed;height:95px;margin-bottom:33px;margin-left:758px;margin-top:-127px;">
            <div class="card-body">
            <h5 class="card-title">Designations</h5>
            <h6 class="text-muted card-subtitle mb-2"><?= $totalDesignations; ?></h6>
        </div>
    </div>
    <!-- Search -->
    <form id="searchForm">
        <div class="input-group" style="width: 300px; margin-bottom: 30px; border: 1px solid #007bff; border-radius: 4px;">
        <input type="text" class="form-control" name="manageEmpsearch" id="manageEmp_search" placeholder="Search record by name and id" style="border: none;">
        <button class="btn btn-primary" type="button" style="border: none; border-left: 1px solid #007bff;">
        <i class="fas fa-search"></i>
        </button>
       </div>
   </form>
    </div>

    <div class="container">
    <div class="table-responsive" style="border-radius:7px;">     
        <table class="table" style="background-color:white;border:1px solid #007bff; border-radius: 10px;">
                <thead>
                    <tr style="background-color:rgb(0,115,230); color:white;">
                        <th style="width:600px; padding-bottom: 23px;">Employee ID</th>
                        <th style="width:150px; padding-bottom: 23px;">Name</th>
                        <th style="width:160px; padding-bottom: 23px;">Email</th>
                        <th style="width:1px;   padding-bottom: 23px;">Address</th>
                        <th style="width:100px; padding-bottom: 23px;">Phone</th>
                        <th style="width:140px; padding-bottom: 23px;">Designation</th>
                        <th style="width:990px; padding-bottom: 23px;">Joining Date</th>
                       <!--<th style="width:80px; padding-bottom: 23px;">Status</th>-->
                        <th style="width:890px; padding-bottom: 23px;">Action</th>
                    </tr>
                </thead>  
                <tbody id="employeeTableBody">
                    <?php foreach ($employee as $employee): ?>
                     <!--default image handling-->
                <?php
                    $imagePath = (!empty($employee['Picture']) && file_exists('uploads/' . $employee['Picture']))
                    ? 'uploads/' . $employee['Picture']
                    : 'uploads/default.jpg';
                ?>
                        <tr data-id="<?= $employee['Employee_id']; ?>">
                            <td style="font-family:Roboto;text-align: center;"><?= $employee['Employee_id']; ?></td>
                            <td style="height:71px;font-family:Roboto;">  
                            <div class="col-3">
                            <img src="<?= $imagePath ?>" style="border-radius:50%; width:50px; height:50px;">
                                </div>
                               <h5> <?= $employee['FullName']; ?> </h5> 
                               <?= $employee['Department_Name']; ?>                
                            </td>
                            <td style="font-family:Roboto;"><?= $employee['Email']; ?></td>
                            <td style="font-family:Roboto;"><?= $employee['Address']; ?></td>
                            <td style="font-family:Roboto;"><?= $employee['Phone']; ?></td>
                            <td style="font-family:Roboto;"><?= $employee['Designation_Name']; ?></td>
                            <td style="font-family:Roboto;"><?= $employee['JoiningDate']; ?></td>
                    <td>
                     <!--<span class="badge status-badge"
                     style="background-color: <?= ($employee['Status'] == 'Active') ? '#28a745' : '#dc3545'; ?>;
                     color: white; padding: 4px 8px; border-radius: 3px; display: inline-flex; 
                     align-items: center; gap: 5px; font-size: 12px; min-width: 66px; justify-content: center; font-family: 'Roboto', sans-serif;"">
                     <img src="<?= ($employee['Status'] == 'Active') ? '../Resources/img/active.png' : '../Resources/img/inactive.png'; ?>" 
                     style="width: 13px; height: 13px;">
                     <?= $employee['Status']; ?>               
                     </span>
                    </td>
                 <td>-->
                    
                        <!-- Edit Button -->
  <button class="btn btn-primary editEmp-btn" title="Edit" data-toggle="modal" data-target="#editEmpModal"
    data-id="<?= $employee['Employee_id']; ?>" 
    data-firstname="<?= $employee['FirstName']; ?>"
    data-lastname="<?= $employee['LastName']; ?>"
    data-email="<?= $employee['Email']; ?>"
    data-phone="<?= $employee['Phone']; ?>"
    data-address="<?= $employee['Address']; ?>"
    data-dept="<?= $employee['Department_id']; ?>"
    data-dept-name="<?= $employee['Department_Name']; ?>"  
    data-designation="<?= $employee['Designation_id']; ?>"
    data-designation-name="<?= $employee['Designation_Name']; ?>"  
    data-joining-date="<?= $employee['JoiningDate']; ?>"
    
    data-picture="<?= $employee['Picture']; ?>"
    data-username="<?= $employee['Username']; ?>"
    data-userrole="<?= $employee['UserRole']; ?>"
    style="margin-right: 13px; background-color:rgba(0,123,255,0);border:none;outline:none;box-shadow:none;height:31px;padding-top:2px;width:23px;margin-left:-6px;">
    <img src="../Resources/img/icons8-edit-23.png">
  </button>
                       <!--Deactivate btn -->
  <button class="btn btn-primary deleteEmp-btn" title="Deactivate" data-toggle="modal" data-target="#deleteEmpModal" 
    data-id="<?= $employee['Employee_id']; ?>" 
    style="font-family: Roboto; background-color:rgba(0,123,255,0);border:none;outline:none;box-shadow:none;height:31px;width:50px;padding-top:2px;padding-left:8px;padding-right:11px;">
    <img src="../Resources/img/icon2.png"> 
  </button>
                 </td>
                 </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    </div>
<script src="../Resources/js/jquery.min.js"></script>
<script src="../Resources/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>