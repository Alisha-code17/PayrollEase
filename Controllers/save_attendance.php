<?php
/*include '../Database/db.php'; // Include your database connection file

// Get data from the POST request
$empID = $_POST['empID'];
$empName = $_POST['empName'];
$presentDays = $_POST['presentDays'];
$absentDays = $_POST['absentDays'];
$month = $_POST['month'];

// Prepare the SQL INSERT query
$sql = "INSERT INTO attendance (Employee_id, Present_days, Absent_days, Month) 
        VALUES (:empID, :presentDays, :absentDays, :month)";

// Prepare and bind the parameters
$stmt = $conn->prepare($sql);
$stmt->bindParam(':empID', $empID);
$stmt->bindParam(':presentDays', $presentDays);
$stmt->bindParam(':absentDays', $absentDays);
$stmt->bindParam(':month', $month);

// Execute the query
if ($stmt->execute()) {
    echo "success"; // Return success message
} else {
    $errorInfo = $stmt->errorInfo();
    echo "error: " . $errorInfo[2]; // Return error message
}*/
?>
<?php
include '../Database/db.php'; // Include your database connection file

// Get data from the POST request
$empID = $_POST['empID']; // Fixed variable name (was 'empid' in the form)
$empName = $_POST['empName'];
$presentDays = $_POST['presentDays'];
$absentDays = $_POST['absentDays'];
$overtimeHours = $_POST['overtimeHours']; // Fixed variable name (was 'overtimehours' in form)
$month = $_POST['month'];
$year1 = $_POST['year1']; // Get the 'year1' value

// Prepare the SQL INSERT query
$sql = "INSERT INTO attendance (Employee_id, Present_days, Absent_days, Overtime_hours, Month, Year) 
        VALUES (:empID, :presentDays, :absentDays, :overtimeHours, :month, :year1)";

// Prepare and bind the parameters
$stmt = $conn->prepare($sql);
$stmt->bindParam(':empID', $empID);
$stmt->bindParam(':presentDays', $presentDays);
$stmt->bindParam(':absentDays', $absentDays);
$stmt->bindParam(':overtimeHours', $overtimeHours); // Corrected Overtime binding
$stmt->bindParam(':month', $month);
$stmt->bindParam(':year1', $year1); 

// Execute the query and check for success
if ($stmt->execute()) {
    echo "success"; // Return success message
} else {
    $errorInfo = $stmt->errorInfo();
    echo "error: " . $errorInfo[2]; // Return error message for debugging
}
?>
