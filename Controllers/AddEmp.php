<?php
include '../Database/db1.php';
$employeeId = isset($_GET['id']) ? $_GET['id'] : null;
$imagePath = "uploads/default.jpg"; 

if ($employeeId) {
    $query = "SELECT picture FROM employee WHERE User_id = '$employeeId'";
    $result = mysqli_query($conn, $query);

    if ($row = mysqli_fetch_assoc($result)) {
        $imagePath = "uploads/" . $row['picture'];
    }
}

$departmentQuery = "SELECT department_id, Name FROM department";
$departmentResult = mysqli_query($conn, $departmentQuery);
$departments = [];
while ($row = mysqli_fetch_assoc($departmentResult)) {
    $departments[] = $row;
}

$designationQuery = "SELECT designation_id, Name FROM designation";
$designationResult = mysqli_query($conn, $designationQuery);
$designations = [];
while ($row = mysqli_fetch_assoc($designationResult)) {
    $designations[] = $row;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AddEmp</title>
    <link rel="stylesheet" href="../Resources/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Merriweather+Sans">
    <link rel="stylesheet" href="../Resources/css/Profile-Edit-Form.css">
    <link rel="stylesheet" href="../Resources/css/Profile-Edit-Form.css">
    <link rel="stylesheet" href="../Resources/css/styles.css">
</head>
<body style="font-family:'Merriweather Sans', sans-serif;">
    <div class="container profile profile-view" id="profile" style="padding-top:0px;">
        <div class="row">
            <div class="col-md-12 alert-col relative">
            <h4 style="margin-top:20px;">Add Employee</h4>
            </div>
            <div class="col">
            <h6>Employee &gt; Add Employee</h6>
                <hr>
            </div>
        </div>
        <form id="add-employee-form" action="add.php" method="POST" enctype="multipart/form-data">
    <div class="form-row profile-row">
        <div class="col-md-4 relative">
            <div class="avatar">
                <div class="avatar-bg center" style="height:175px;width:175px; overflow:hidden; border-radius:50%; border:2px solid #ccc;">
                <img id="preview" src="<?php echo $imagePath; ?>" alt="Employee Image" style="height:100%;width:100%; object-fit: cover; border-radius: 50%;">
                </div>
            </div> 

            <!--<input type="file" class="form-control" name="avatar" id="avatar" 
       style="height:38px;margin-top:5px;padding-top:3px;" accept=".jpg,.jpeg,.png">
       <small id="avatarError" style="color:red;"></small>-->

            <input type="file" class="form-control" name="avatar" id="avatar" 
            style="height:38px;margin-top:5px;padding-top:3px;"accept=".jpg, .jpeg, .png">
            <!--<small id="avatarError" style="color:red;"></small>-->
        </div>
        <div class="col-md-8">
            <h4 style="color:#3786d5;margin-top:10px;">Personal Details</h4>
            <div class="form-row" style="margin-top:24px;">
                <div class="col-sm-12 col-md-6">
                <div class="form-group"> 
                <label for="first_name">First Name</label>
                <input class="form-control" type="text" id="first_name" name="first_name" autocomplete="given-name" required pattern="[A-Za-z ]+" title="Only letters allowed" placeholder="Enter First Name">
                <small id="AddEmpfirstNameError" style="color:red;"></small>
            </div>
        </div>
                <div class="col-sm-12 col-md-6">
                <div class="form-group">
                <label for="last_name">Last Name</label>
                <input class="form-control" type="text" id="last_name" name="last_name" autocomplete="family-name" pattern="[A-Za-z ]+" title="Only letters allowed" placeholder="Enter Last Name">
                <small id="AddEmplastNameError" style="color:red;"></small>
            </div>
        </div>
    </div>
         <div class="form-row">
                <div class="col-sm-12 col-md-6">
                <div class="form-group">
                <label for="email">Email</label>
                <input class="form-control" type="email" id="email" name="email" autocomplete="email" placeholder="Enter Email"
                onkeyup="checkEmailAjax()" onblur="checkEmailFormat()" required>
                <small id="emailError" style="color: red;"></small>
            </div>
        </div>
                <div class="col-sm-12 col-md-6">
                <div class="form-group">
                <label for="phone">Phone</label>
                <input class="form-control" type="text" id="phone" name="phone" autocomplete="tel" 
                title="Enter 11-digit phone number" placeholder="e.g. 03001234567">
                <small id="AddEmpPhoneError" style="color:red;"></small>
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
        <input class="form-control" type="date" id="joining_date" name="joining_date" required>
        <small id="joiningDateError" style="color:red;"></small>
    </div>
    <div class="col-md-4">
        <label for="department">Department</label>
        <select class="form-control" id="department" name="department" autocomplete="organization" required>
            <option value="" disabled selected>Select Department</option>
            <?php foreach ($departments as $dept) { ?>
                <option value="<?= $dept['department_id']; ?>"><?= $dept['Name']; ?></option>
            <?php } ?>    
        </select>     
    </div>
    <div class="col-md-4">
        <label for="designation">Designation</label>
        <select class="form-control" id="designation" name="designation" required>
            <option value="" disabled selected>Select Designation</option>
            <?php foreach ($designations as $desig) { ?>
                <option value="<?= $desig['designation_id']; ?>"><?= $desig['Name']; ?></option>
            <?php } ?>
        </select>      
    </div>
    <div class="col-md-4">
        <label for="address">Address</label>
        <input class="form-control" type="text" id="address" name="address" autocomplete="street-address" onblur="checkAddressFormat()" required>
        <small id="addressError" style="color:red;"></small>
    </div>
</div>
<hr>

    <h4 style="color:#3786d5;">Account Details</h4>
    <div class="form-row">
    <div class="col-md-3">
        <label for="username">Username</label>
        <input class="form-control" type="text" id="username" name="username" autocomplete="username" required onkeyup="checkUsername()">
        <small id="usernameError" style="color: red;"></small>
    </div>
    <div class="col-md-3">
        <label for="user_role">User Role</label>
        <select class="form-control" id="user_role" name="user_role" autocomplete="user_role" required>
            <option value="" disabled selected>Select User Role</option>
            <option value="Admin">Admin</option>
            <option value="Employee">Employee</option>
        </select>
    </div>
    <div class="col-md-3">
        <label for="password">Password</label>
        <input class="form-control" type="password" id="password" name="password" autocomplete="new-password" required>
        <small id="password-strength" style="color: red;"></small>
    </div>
    <div class="col-md-3">
        <label for="confirm_password">Confirm Password</label>
        <input class="form-control" type="password" id="confirm_password" name="confirm_password" autocomplete="new-password" required>
        <small id="confirmPasswordMsg" style="color: red;"></small>
    </div>
</div>
<hr>
    <div class="row">
        <div class="col-md-12 content-right">
       <button id="AddEmpsaveBtn" class="btn btn-primary form-btn" type="submit">SAVE</button>       
        <button class="btn btn-danger form-btn" id="cancelBtn" type="reset" style="background-color:rgb(255,255,255);color:rgb(0,0,0);/*box-shadow:0 0 0 .2rem rgba(55, 134, 213, .5);*/outline:2px solid #3786D5;border-color:#3786D5;">Cancel</button>
        </div>
    </div>
</form>

<script>
    document.querySelector('form').addEventListener('submit', function (e) {
        console.log("Form submitted to: " + this.action);
    });
</script>
    <script src="../Resources/js/jquery.min.js"></script>
   <!-- <script src="../Resources/js/AddEmp.js"></script>-->
    <script src="../Resources/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>