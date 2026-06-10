<?php
include '../Database/db1.php'; // Include the MySQLi database connection file

// Get previous month and year (unchanged)
$currentDate = new DateTime();
$currentDate->modify('-1 month'); // Subtract one month to get previous month
$previousMonthName = strtolower($currentDate->format('F'));
$previousYear = $currentDate->format('Y');

// SQL query: Fetch previous month & year attendance (unchanged query)
$sql = "SELECT a.*, e.Picture, CONCAT(e.FirstName, ' ', COALESCE(e.LastName, '')) AS FullName, d.Name AS Department_Name  
        FROM attendance a  
        INNER JOIN employee e ON a.Employee_id = e.Employee_id  
        INNER JOIN department d ON e.Department_id = d.Department_id
        WHERE LOWER(a.Month) = ? AND a.Year = ?";  

$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $previousMonthName, $previousYear);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . $row["Attendance_id"] . '</td>';
        echo '<td>' . $row["Employee_id"] . '</td>';
        
        $imageDir = '../Controllers/uploads/';
        $imageFile = (!empty($row["Picture"]) && file_exists($imageDir . $row["Picture"]))
            ? $imageDir . $row["Picture"]
            : $imageDir . 'default.jpg';

        echo '<td>
                <div class="row">
                    <div class="col-3">
                        <img src="' . $imageFile . '" style="border-radius:50%; width:50px; height:50px;">
                    </div>
                    <div class="col-6">
                        <h5>' . htmlspecialchars($row["FullName"]) . '<br></h5>
                    ' . htmlspecialchars($row["Department_Name"]) . '<br>
                    </div>
                </div>
            </td>';

        echo '<td>' . $row["Present_days"] . '</td>';
        //echo '<td>' . $row["Absent_days"] . '</td>';
        // Show dash if Absent_days is NULL or 0
        echo '<td style="text-align: center;">' . 
                ((!empty($row["Absent_days"]) && $row["Absent_days"] != 0) 
                    ? htmlspecialchars($row["Absent_days"]) 
                    : '--') 
     . '</td>';
        // Show dash if Overtime_hours is NULL or 0
        echo '<td style="text-align: center;">' . 
                ((!empty($row["Overtime_hours"]) && $row["Overtime_hours"] != 0) 
                    ? htmlspecialchars($row["Overtime_hours"]) 
                    : '--') 
            . '</td>';
        echo '<td>' . ucfirst($row["Month"]) . '</td>';
        echo '<td>' . $row["Year"] . '</td>';
        
        echo '<td>
        <button class="btn btn-primary d-inline-flex btn p-0 edit-attendance-btn" type="button" 
                style="background-color:rgba(0,123,255,0); border:none; outline:none; box-shadow:none; width:23px; margin-left:27px;" 
                data-toggle="modal" data-target="#updateModal"
                data-id="' . htmlspecialchars($row["Employee_id"]) . '" 
                data-employee="' . htmlspecialchars($row["FullName"]) . '" 
                data-present="' . htmlspecialchars($row["Present_days"]) . '" 
                data-absent="' . htmlspecialchars($row["Absent_days"]) . '" 
                data-overtime="' . htmlspecialchars($row["Overtime_hours"]) . '" 
                data-month="' . htmlspecialchars($row["Month"]) . '"
                data-year="' . htmlspecialchars($row["Year"]) . '">
            <img src="../Resources/img/icons8-edit-23.png">
        </button>
      </td>';

        echo '</tr>';
    }
} else {
    echo '<tr><td colspan="9">No attendance records found for ' . ucfirst($previousMonthName) . ' ' . $previousYear . '.</td></tr>';
}

$stmt->close();
$conn->close();
?>