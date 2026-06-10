<?php
include '../Database/db1.php';

$search = isset($_POST['attsearch']) ? trim($_POST['attsearch']) : '';
$currentDate = new DateTime();
$currentDate->modify('-1 month');
$previousMonthName = strtolower($currentDate->format('F'));
$previousYear = $currentDate->format('Y');

$sql = "SELECT 
            a.Attendance_id,
            a.Employee_id, 
            CONCAT(e.FirstName, ' ', COALESCE(e.LastName, '')) AS FullName,
            e.Picture, 
            d.Name AS Department_Name, 
            a.Present_days, 
            a.Absent_days,
            a.Overtime_hours, 
            a.Month, 
            a.Year
        FROM attendance a
        INNER JOIN employee e ON a.Employee_id = e.Employee_id
        INNER JOIN department d ON e.Department_id = d.Department_id
        WHERE LOWER(a.Month) = ? AND a.Year = ?";

if (!empty($search)) {
    $sql .= " AND (a.Employee_id LIKE ? OR 
                  e.FirstName LIKE ? OR 
                  e.LastName LIKE ? OR
                  CONCAT(e.FirstName, ' ', COALESCE(e.LastName, '')) LIKE ?)";
}
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("Error preparing statement: " . $conn->error);
}
if (!empty($search)) {
    $searchTerm = '%' . $search . '%';
    $stmt->bind_param("ssssss", 
        $previousMonthName, 
        $previousYear,
        $searchTerm,
        $searchTerm,
        $searchTerm,
        $searchTerm
    );
} else {
    $stmt->bind_param("ss", 
        $previousMonthName, 
        $previousYear
    );
}
if (!$stmt->execute()) {
    die("Error executing statement: " . $stmt->error);
}
$result = $stmt->get_result();
$output = '';

while ($row = $result->fetch_assoc()) {
    $imagePath = !empty($row["Picture"]) 
        ? '../Controllers/uploads/' . $row["Picture"] 
        : '../Controllers/uploads/default.jpg';
    $output .= '<tr>
        <td>'.$row["Attendance_id"].'</td>
        <td>'.$row["Employee_id"].'</td>
        <td>
            <div class="row">
                <div class="col-3">
                    <img src="'.$imagePath.'" style="border-radius:50%; width:50px; height:50px;">
                </div>
                <div class="col-6">
                    <h5>'.$row["FullName"].'<br></h5>
                    '.$row["Department_Name"].'<br>
                </div>
            </div>
        </td>
        <td>'.$row["Present_days"].'</td>
        <td>'.$row["Absent_days"].'</td>
        <td>'.$row["Overtime_hours"].'</td>
        <td>'.ucfirst($row["Month"]).'</td>
        <td>'.$row["Year"].'</td>
        <td>
            <button class="btn btn-primary d-inline-flex btn p-0 edit-attendance-btn" type="button" 
                    style="background-color:rgba(0,123,255,0); border:none; outline:none; box-shadow:none; width:23px; margin-left:27px;" 
                    data-toggle="modal" data-target="#updateModal"
                    data-id="'.htmlspecialchars($row["Employee_id"]).'" 
                    data-employee="'.htmlspecialchars($row["FullName"]).'" 
                    data-present="'.htmlspecialchars($row["Present_days"]).'" 
                    data-absent="'.htmlspecialchars($row["Absent_days"]).'" 
                    data-overtime="'.htmlspecialchars($row["Overtime_hours"]).'" 
                    data-month="'.htmlspecialchars($row["Month"]).'"
                    data-year="'.htmlspecialchars($row["Year"]).'">
                <img src="../Resources/img/icons8-edit-23.png">
            </button>
        </td>
    </tr>';
}

if (empty($output)) {
    $output = '<tr><td colspan="9">No matching records found for '.ucfirst($previousMonthName).' '.$previousYear.'</td></tr>';
}
echo $output;

$stmt->close();
$conn->close();
?>