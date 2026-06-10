<?php
session_start();
include '../Database/db1.php';

if (!isset($_SESSION['user_id'])) {
    echo "User not logged in!";
    exit();
}

$employeeId = $_SESSION['user_id'];
$imagePath = "uploads/default.jpg";
// Fetch employee data
$query = "SELECT * FROM employee WHERE User_id = '$employeeId'";
$result = mysqli_query($conn, $query);

if ($row = mysqli_fetch_assoc($result)) {
    $firstName = $row['FirstName'] ?? 'Not Provided';
    $lastName = $row['LastName'] ?? 'Not Provided';
    $email = $row['Email'] ?? 'Not Provided';
    $phone = $row['Phone'] ?? 'Not Provided';
    $address = $row['Address'] ?? 'Not Provided';
    $joiningDate = $row['JoiningDate'] ?? 'Not Provided';
    $departmentId = $row['Department_id'] ?? null;
    $designationId = $row['Designation_id'] ?? null;
    $imagePath = $row['Picture'] ? "uploads/" . $row['Picture'] : "uploads/default.jpg";
} else {
    echo "No employee found!";
    exit();
}
// Fetch department name 
$department = 'Not Provided';
if ($departmentId) {
    $departmentQuery = "SELECT Name FROM department WHERE department_id = '$departmentId'";
    $departmentResult = mysqli_query($conn, $departmentQuery);
    if ($deptRow = mysqli_fetch_assoc($departmentResult)) {
        $department = $deptRow['Name'];
    }
}
// Fetch designation name 
$designation = 'Not Provided';
if ($designationId) {
    $designationQuery = "SELECT Name FROM designation WHERE designation_id = '$designationId'";
    $designationResult = mysqli_query($conn, $designationQuery);
    if ($desigRow = mysqli_fetch_assoc($designationResult)) {
        $designation = $desigRow['Name'];
    }
}
// Fetch user account details
$userQuery = "SELECT Username, UserRole FROM users WHERE User_id = '$employeeId'";
$userResult = mysqli_query($conn, $userQuery);
$userData = mysqli_fetch_assoc($userResult) ?? [];
$username = $userData['Username'] ?? 'Not Provided';
$userRole = $userData['UserRole'] ?? 'Not Provided';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Profile</title>
    <link rel="stylesheet" href="../Resources/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Merriweather+Sans">
    <link rel="stylesheet" href="../Resources/css/Profile-Edit-Form.css">
</head>
<body style="font-family:'Merriweather Sans', sans-serif;">
    <div class="container profile profile-view" id="profile" style="padding-top:0px;">
        <div class="row">
            <div class="col-md-12 alert-col relative">
                <h4 style="margin-top:20px;">View Profile</h4>
            </div>
            <div class="col">
                <h6>Profile &gt; View Profile</h6>
                <hr>
            </div>
        </div>
        <form id="view-profile-form" method="POST">
            <div class="form-row profile-row">
                <div class="col-md-4 relative">
                    <div class="avatar">
                        <div class="avatar-bg center" style="height:175px;width:175px; overflow:hidden; border-radius:50%; border:2px solid #ccc;">
                            <img id="preview" src="<?php echo $imagePath; ?>" alt="Employee Image" style="height:100%;width:100%; object-fit: cover; border-radius: 50%;">
                        </div>
                    </div>
                </div>           
                <div class="col-md-8">
                    <h4 style="color:#3786d5;margin-top:10px;">Personal Details</h4>
                    <div class="form-row" style="margin-top:24px;">
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group">
                                <label for="first_name">First Name</label>
                                <input class="form-control" type="text" id="first_name" name="first_name" value="<?php echo $firstName; ?>" readonly>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group">
                                <label for="last_name">Last Name</label>
                                <input class="form-control" type="text" id="last_name" name="last_name" value="<?php echo $lastName; ?>" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input class="form-control" type="email" id="email" name="email" value="<?php echo $email; ?>" readonly>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group">
                                <label for="phone">Phone</label>
                                <input class="form-control" type="text" id="phone" name="phone" value="<?php echo $phone; ?>" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <h4 style="color:#3786d5;">Job Details</h4>
            <div class="form-row">
                <div class="col-md-4">
                    <label for="joining_date">Joining Date</label>
                    <input class="form-control" type="date" id="joining_date" name="joining_date" value="<?php echo $joiningDate; ?>" readonly>
                </div>
                <div class="col-md-4">
                    <label for="department">Department</label>
                    <input class="form-control" type="text" id="department" name="department" value="<?php echo $department; ?>" readonly>
                </div>
                <div class="col-md-4">
                    <label for="designation">Designation</label>
                    <input class="form-control" type="text" id="designation" name="designation" value="<?php echo $designation; ?>" readonly>
                </div>
                <div class="col-md-4">
                    <label for="address">Address</label>
                    <input class="form-control" type="text" id="address" name="address" value="<?php echo $address; ?>" readonly>
                </div>
            </div>
            <hr>
            <h4 style="color:#3786d5;">Account Details</h4>
            <div class="form-row">
                <div class="col-md-3">
                    <label for="username">Username</label>
                    <input class="form-control" type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" readonly>
                </div>
                <div class="col-md-3">
                    <label for="user_role">User Role</label>
                    <input class="form-control" type="text" id="user_role" name="user_role" value="<?php echo htmlspecialchars($userRole); ?>" readonly>
                </div>
            <div class="col-md-3 d-flex align-items-end">
             <button type="button" class="btn btn-primary w-100" data-toggle="modal" data-target="#changePasswordModal" type="button" style="background-color:rgb(0,115,230);color:white;">
              Change Password
             </button>
            </div>
            </div>
            <hr>
        </form>
    </div>
</body>
</html>