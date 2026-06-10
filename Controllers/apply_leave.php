<!-- <?php
// Har origin ko allow karo (yeh sirf development mein theek hai)
header("Access-Control-Allow-Origin: *");

// Allow karo specific HTTP methods (GET, POST, etc.)
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

// Agar yeh OPTIONS request hai, toh 200 OK return karo
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}
?> -->

<?php
// Start session only if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include MySQLi database connection
include '../Database/db1.php';

// Check if Employee_id exists in session
if (!isset($_SESSION['Employee_id'])) {
    die("Error: Employee ID is missing in the session.");
}

$employee_id = $_SESSION['Employee_id']; // Correct session variable

// SQL query to fetch leave data for the logged-in employee
$query = "SELECT 
    e.Employee_id, 
    e.FirstName AS Employee_Name, 
    COALESCE(l.Leave_id, '-') AS Leave_id,
    d.Name AS Department_Name, 
    COALESCE(l.Year, '-') AS Year, 
    COALESCE(l.Month, '-') AS Month, 
    COALESCE(l.Totaldays, '0') AS Totaldays,
    COALESCE(l.Description, '-') AS Description, 
    COALESCE(l.Status, 'No Leave') AS Status,
    COALESCE(lt.TypeName, 'No Leave') AS TypeName,
    lt.LeaveType_id AS LeaveType_id  -- Don't use COALESCE here if it's an INT
FROM employee e
JOIN department d ON e.Department_id = d.Department_id
LEFT JOIN `leave` l ON e.Employee_id = l.Employee_id
LEFT JOIN leavetype lt ON l.LeaveType_id = lt.LeaveType_id
WHERE e.Employee_id = ?";

// Prepare and execute the query
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $employee_id);
$stmt->execute();
$result = $stmt->get_result();

// Fetch all leave records for the logged-in employee
//create emppty array
$leave_data = array();
while ($row = $result->fetch_assoc()) {
    $leave_data[] = $row;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave_form_for_leave</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Merriweather+Sans">
	
</head>

<body style="background-color:white;font-family:'Merriweather Sans', sans-serif;background-color:#f8f9fa;">
	<div style="display: flex; justify-content: center;">

    <div class="container">
        <div class="row" style="margin:9px;margin-left:-16px;">
            <div class="col" style="width:828px;">
                <h3 style="margin-top:9px;/*color:rgb(0,123,255);*/">Leaves Requests</h3>
            </div>
			<!--add leave model button-->
			<div class="col d-flex justify-content-end align-items-end">
				<button class="btn btn-info" type="button" data-toggle="modal" 
				data-target="#leaveModal" 
				style="margin-left:1px; margin-bottom:0px; background-color:#3786D5; margin-right:5px; color: white; border-radius: 5px; transition: all 0.3s ease;" 
				onmouseover="this.style.color='#3786D5'; this.style.backgroundColor='white';" 
				onmouseout="this.style.color='white'; this.style.backgroundColor='#3786D5';">
				+ Add Leave
				</button>
			</div>
        </div>
        <div>
            <h5>Dashboard &gt; Leaves</h5>
          
        </div>
        <div style="width:1080px;padding-right:0px;background-color:white;">
            <div class="table-responsive" style="border:1px solid #007bff;border-radius:7px;margin-top:27px;width:1080px;">
               <table class="table">
                    <thead>
						<tr style="background-color:#3786D5;color:white;">
                           
                            <th style="/*border-radius:11px;*//*border:1px solid #007BFF;*/width:136px;padding-right:0;">Leave Type</th>
                            <th style="width:166px;padding-left:40px;padding-right:0;">Month</th>
                            <th style="width:120px;padding-left:12px;padding-right:0;">Year</th>
                            <th style="width:107px;height:68px;margin-top:0px;padding-top:29px;padding-right:0;">Total days</th>
                            
                            <th style="margin-left:0px;width:160px;padding-left:43px;">Status</th>
                            <th style="width:30px;padding:12px;padding-left:84px;margin-left:0px;padding-right:0;">Action</th>
                        </tr>  
                    </thead>
					<tbody>
             <?php
			if (count($leave_data) > 0) {
    foreach ($leave_data as $row) {
        // ✅ This keeps the ID for JS, without showing it to users
        echo "<tr data-id='" . htmlspecialchars($row['Leave_id']) . "'>";
        echo "<td  class='leave-type' style='padding-left:23px;padding-right:0;height:81px;width:112px;'>" . htmlspecialchars($row['TypeName']) . "</td>";
        echo "<td class='leave-month' style='padding-left:41px;padding-right:0;width:207px;height:81px;'>" . htmlspecialchars($row['Month']) . "</td>";
        echo "<td class='leave-year' style='padding-left:12px;padding-right:0;width:140px;'>" . htmlspecialchars($row['Year']) . "</td>";
        echo "<td class='leave-days' style='padding-left:30px;height:81px;width:107px;'>" . htmlspecialchars($row['Totaldays']) . "</td>";
        echo "<td class='leave-status' style='width:87px; padding-left:43px; padding-right:0;'>" . htmlspecialchars($row['Status']) . "</td>";

           // Action Buttons Column
        echo "<td style='padding-left:0;padding-right:0;'>";
        echo "<div class='btn-group' role='group' style='margin-left:46px;'>";

        // Edit Button
        echo "<button 
            type='button' 
            class='btn btn-primary' 
           data-leave-id='" . htmlspecialchars($row['Leave_id']) . "' 
            data-leave-type-id='" . htmlspecialchars($row['LeaveType_id']) . "' 
            data-leave-type='" . htmlspecialchars($row['TypeName']) . "' 
            data-year='" . htmlspecialchars($row['Year']) . "' 
			data-month='" . htmlspecialchars($row['Year']) . "-" . date('m', strtotime($row['Month'])) . "' 
            data-total-days='" . htmlspecialchars($row['Totaldays']) . "'
			data-description='" . htmlspecialchars($row['Description']) . "'	
            data-status='" . htmlspecialchars($row['Status']) . "'
            data-toggle='modal' 
            data-target='#editmodal'
            style='background-color:transparent; border:none;'> 
            <img src='../Resources/img/icons8-edit-23.png'>
        </button>";

        // Delete Button
        echo "<button 
            class='btn btn-primary open-delete-modal' 
            type='button' 
           data-del-leave-id='" . htmlspecialchars($row['Leave_id']) . "'

            data-toggle='modal' 
            data-target='#deletemodal' 
            style='margin-left:13px; background-color:transparent; border:none;'> 
            <img src='../Resources/img/icon2.png'>
        </button>";

        echo "</div></td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='6'>No leave records found.</td></tr>";
}
            ?>
        </tbody>


                </table>
            </div>
        </div>
    </div>
	</div>
	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>

	
	
</body>

</html>