<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>l-admin</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Merriweather+Sans">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.1.1/aos.css">
</head>

<body style="font-family:'Merriweather Sans', sans-serif;font-style:normal;">
    <div class="container">
        <div class="row" style="margin:9px;margin-left:-16px;">
            <div class="col" style="width:828px;">
                <h3 style="margin-top:60px;/*color:rgb(0,123,255);*/">Leaves List</h3>
            </div>
        </div>
        <div>
            <h5>Dashboard &gt; Leaves Management</h5>
           
        </div>
        <div style="width:1080px;padding-right:0px;background-color:white;padding-left:0px;">
            <div class="table-responsive" style="border:1px solid #007bff;border-radius:7px;margin-top:27px;width:1080px;">
                <table class="table">
                    <thead>
                        <tr style="background-color:#3786D5;color:white;">
                            <th style="width:104px;padding-left:12px;">Employee_ID</th>
							<th style="width:104px;padding-left:12px;">Employee</th>
                            <th style="/*border-radius:11px;*//*border:1px solid #007BFF;*/width:231px;padding-right:0;">Leave Type</th>
                            <th style="width:187px;padding-left:10px;padding-right:0;">Month</th>
                            <th style="width:170px;padding-left:12px;padding-right:0;">Year</th>
                            <th class="d-flex justify-content-center" style="width:107px;height:68px;margin-top:0px;padding-top:29px;padding-right:0;">Total days</th>
                            <th style="margin-left:0px;width:160px;padding-left:100px;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
					
                    <?php
// Database connection
include '../Database/db1.php';

// Handle status update when admin selects a status
if (isset($_POST['update_status'])) {
    $leaveId = $_POST['leave_id'];   // Get the leave ID from the form
    $status = $_POST['status'];      // Get the selected status from the form

    // Update the status in the 'leave' table
    $sql = "UPDATE `leave` SET Status = ? WHERE Leave_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $leaveId);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'status' => $status]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Error updating status: ' . $conn->error]);
    }

    $stmt->close();
}


// Fetch all leave records to display in admin view
$sql = "SELECT 
    e.Employee_id, 
    e.FirstName AS Employee_Name, 
    e.Picture AS Employee_Picture, 
    d.Name AS Department_Name, 
    lt.TypeName AS Leave_Type, 
    l.Month, 
    l.Year, 
    l.Totaldays, 
    l.Status,
    l.Leave_id
FROM `leave` l 
JOIN employee e ON l.Employee_id = e.Employee_id
JOIN department d ON e.Department_id = d.Department_id
JOIN leavetype lt ON l.LeaveType_id = lt.LeaveType_id";

$result = $conn->query($sql);

// Check if there are records
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $imagePath = "";

        if ($row['Status'] == 'Pending') {
            $imagePath = "../Resources/img/icons8-select-16.png";
        } elseif ($row['Status'] == 'Approved') {
            $imagePath = "../Resources/img/icons8-select-32 (2).png";
        } elseif ($row['Status'] == 'Rejected') {
            $imagePath = "../Resources/img/icons8-select-32 (1).png";
        } else {
            $imagePath = "../Resources/img/icons8-select-32.png"; // Default Image
        }

        echo "
        <tr>
            <td class='justify-content-center' style='padding-left:25px;padding-right:0;height:81px;width:112px;'>" . $row['Employee_id'] . "</td>
            <td style='padding-right:0;padding-left:11px;width:px;'>
                <div class='d-flex' style='width:155px;'>
                    <img src='../Controllers/uploads/" . $row['Employee_Picture'] . "' style='width:50px;height:50px;border-radius:50%;'>
                    <div>
                        <p style='margin-bottom:-2px;padding-left:13px;'>" . $row['Employee_Name'] . "</p>
                        <p style='padding-left:13px;color:rgb(52,60,67);font-size:12px;'>" . $row['Department_Name'] . "</p>
                    </div>
                </div>
            </td>
            <td class='justify-content-center' style='padding-left:25px;padding-right:0;height:81px;width:112px;'>" . $row['Leave_Type'] . "</td>
            <td style='padding-left:10px;padding-right:0;width:207px;height:81px;'>" . $row['Month'] . "</td>
            <td style='padding-left:12px;padding-right:0;width:140px;'>" . $row['Year'] . "</td>
            <td class='d-flex justify-content-center' style='padding-right:0;height:81px;width:107px;'>" . $row['Totaldays'] . "</td>
            
            <td style='width:281px;padding-left:80px;padding-right:0;height:81px;'>
                <div class='dropdown'>
                    <button class='btn btn-primary dropdown-toggle' data-toggle='dropdown' aria-expanded='false' type='button' style='color:rgb(33,37,41);background-color:rgb(255,255,255);'>
                        <img id='status-img-" . $row['Leave_id'] . "' src='" . $imagePath . "' style='width:20px;padding-right:3px;'>
                        <span id='status-" . $row['Leave_id'] . "'>" . $row['Status'] . "</span>
                    </button>

                    <form method='POST'>
                        <input type='hidden' name='leave_id' value='" . $row['Leave_id'] . "'>

                        <div class='dropdown-menu my-custom-dropdown' role='menu'>
                            <a class='dropdown-item' href='#' onclick='updateStatus(" . $row['Leave_id'] . ", \"Pending\")'>
                                <img src='../Resources/img/icons8-select-16.png' style='width:20px;height:17px;'> Pending
                            </a>
                            <a class='dropdown-item' href='#' onclick='updateStatus(" . $row['Leave_id'] . ", \"Approved\")'>
                                <img src='../Resources/img/icons8-select-32 (2).png' style='width:20px;'> Approved
                            </a>
                            <a class='dropdown-item' href='#' onclick='updateStatus(" . $row['Leave_id'] . ", \"Rejected\")'>
                                <img src='../Resources/img/icons8-select-32 (1).png' style='width:20px;'> Rejected
                            </a>
                        </div>
                    </form>
                </div>
            </td>
        </tr>";
    }
} else {
    echo "<tr><td colspan='8' style='text-align:center;'>No leave records found!</td></tr>";
}
?>


    </tbody>
    </table>
    </div>
    </div>
    </div>
				
    <!--<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/bs-animation.js"></script>
    <script src="assets/js/dropdown.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.1.1/aos.js"></script>-->
</body>

</html>