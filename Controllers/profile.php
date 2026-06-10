<?php
// Include the database connection file
include '../Database/db1.php'; // Replace with the actual path to your connection file

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstname = $conn->real_escape_string($_POST['firstname']);
    $lastname = $conn->real_escape_string($_POST['lastname']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);

    // Insert the data into the database
    $sql = "INSERT INTO profile (firstname, lastname, email, phone) VALUES ('$firstname', '$lastname', '$email', '$phone')";

    if ($conn->query($sql) === TRUE) {
        $message = "Profile saved successfully!";
    } else {
        $message = "Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>adProfile</title>
    <link rel="stylesheet" href="../Resources/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Merriweather+Sans">
    <link rel="stylesheet" href="../Resources/css/Profile-Edit-Form.css">
    <link rel="stylesheet" href="../Resources/css/Profile-Edit-Form.css">
    <!--<link rel="stylesheet" href="../Resources/css/styles.css">-->
</head>

<body style="font-family:'Merriweather Sans', sans-serif;">
    <div class="container profile profile-view" id="profile" style="padding-top:0px;">
        <div class="row">
            <div class="col-md-12 alert-col relative">
                <h6 style="margin-top:20px;">Pages &gt; Profile</h6>
            </div>
            <div class="col">
                <h3 style="margin-top:10px;">Profile</h3>
                <hr>
            </div>
        </div>
        <form method="POST" action="">

        <form>
            <div class="form-row profile-row">
                <div class="col-md-4 relative">
                    <div class="avatar">
                        <div class="avatar-bg center" style="height:175px;width:175px;"></div>
                    </div><input type="file" class="form-control" name="avatar-file" style="height:38px;margin-top:5px;padding-top:3px;"></div>
                <div class="col-md-8">
                    <h4 style="color:#3786d5;margin-top:10px;">Basic Information</h4>
                    <div class="form-row" style="margin-top:24px;">
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group"><label>Firstname </label><input class="form-control" type="text" name="firstname"></div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group"><label>Lastname </label><input class="form-control" type="text" name="lastname"></div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group"><label>Email </label><input class="form-control" type="email" autocomplete="off" required="" name="email"></div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group"><label>Phone</label><input class="form-control" type="text" name="phone" autocomplete="off" required=""></div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <hr>
        <form style="margin-top:30px;">
            <h4 style="color:#3786d5;">Address Information</h4>
            <div class="form-row" style="margin-top:20px;">
                <div class="col">
                    <div class="form-group"><label>Address</label><input class="form-control" type="text" autocomplete="off" required="" name="address"></div>
                </div>
            </div>
            <div class="form-row">
                <div class="col-sm-12 col-md-6">
                    <div class="form-group"><label>City</label><input class="form-control" type="text" name="city"></div>
                </div>
                <div class="col-sm-12 col-md-6">
                    <div class="form-group"><label>Country</label><input class="form-control" type="text" name="country"></div>
                </div>
            </div>
        </form>
        <hr>
        <form style="margin-top:30px;">
            <h4 style="color:#3786d5;">Change Password</h4>
            <div class="form-row" style="margin-top:20px;">
                <div class="col-sm-12 col-md-4">
                    <div class="form-group" style="width:348px;"><label>Current Password</label><input class="form-control" type="password"></div>
                </div>
                <div class="col-sm-12 col-md-4" style="width:310px;padding-left:12px;">
                    <div class="form-group" style="width:348px;"><label>New Password</label><input class="form-control" type="password"></div>
                </div>
                <div class="col-sm-12 col-md-4" style="width:310px;padding-left:12px;">
                    <div class="form-group" style="width:348px;"><label>Confirm Password</label><input class="form-control" type="password"></div>
                </div>
            </div>
        </form>
        <hr>
        <div class="row">
            <div class="col-md-12 content-right"><button class="btn btn-primary form-btn" type="submit">SAVE </button><button class="btn btn-danger form-btn" type="reset" style="background-color:rgb(255,255,255);color:rgb(0,0,0);/*box-shadow:0 0 0 .2rem rgba(55, 134, 213, .5);*/outline:2px solid #3786D5;border-color:#3786D5;">CANCEL </button></div>
        </div>
</form>
    </div>
    <script src="../Resources/js/jquery.min.js"></script>
    <script src="../Resources/bootstrap/js/bootstrap.min.js"></script>
    <script src="../Resources/js/Profile-Edit-Form.js"></script>
</body>

</html>