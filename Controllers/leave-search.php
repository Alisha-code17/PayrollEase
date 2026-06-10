<?php
include '../Database/db1.php';
$search = isset($_POST['search']) ? $_POST['search'] : '';
$year = isset($_POST['year']) ? $_POST['year'] : '';
$month = isset($_POST['month']) ? $_POST['month'] : '';

$sql = "SELECT 
    e.Employee_id, 
    e.FirstName AS Employee_Name, 
    e.Picture AS Picture, 
    d.Name AS Department_Name, 
    des.Name AS Designation_Name, 
    COALESCE(l.Year, '-') AS Year, 
    COALESCE(l.Month, '-') AS Month, 
    COALESCE(l.Totaldays, '0') AS Totaldays, 
    COALESCE(l.Status, 'No Leave') AS Status,
    COALESCE(lt.TypeName, 'No Leave') AS TypeName
	FROM employee e
	JOIN department d ON e.Department_id = d.Department_id
	JOIN designation des ON e.Designation_id = des.Designation_id
	LEFT JOIN `leave` l ON e.Employee_id = l.Employee_id
	LEFT JOIN leavetype lt ON l.LeaveType_id = lt.LeaveType_id
        WHERE 1"; // Placeholder to append conditions dynamically

$params = [];
$types = "";

// If search term is provided (searching by name or ID)
if (!empty($search)) {
    $sql .= " AND (e.Employee_id = ? OR e.FirstName LIKE ? OR e.LastName LIKE ?)";
    $params[] = $search;
    $params[] = "%" . $search . "%";
    $params[] = "%" . $search . "%";
    $types .= "iss";
}

// If date is selected (searching by month & year)
if (!empty($year) && !empty($month)) {
    $sql .= " AND YEAR(e.JoiningDate) = ? AND MONTH(e.JoiningDate) = ?";
    $params[] = $year;
    $params[] = $month;
    $types .= "ii";
}

$stmt = $conn->prepare($sql);

// Bind parameters dynamically
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

$output = '';

while ($row = $result->fetch_assoc()) {
    $output .= '<tr>
        <td>
            <input type="checkbox" class="subCheckbox" name="employees[]" value="' . htmlspecialchars($row['Employee_id']) . '" style="width:15px;height:15px;">
        </td>
        <td>' . $row['Employee_id'] . '</td>
        <td>
            <div class="row" style="height:60px;width:200px;">
                <div class="col" style="padding-right:0px;">
                    <div style="width:50px;">
                        <img src="../Controllers/uploads/' . $row['Picture'] . '" style="width:50px;height:50px;border-radius:50%;">
                    </div>
                </div>
                <div class="col d-flex align-items-center align-content-center" style="margin-top:4px;">
                    <div>
                        <p style="margin-bottom:1px;color:#212529;">' . $row['Employee_Name'].'</p>
                    </div>
                </div>
            </div>
        </td>
        <td>' . $row['Department_Name'] . '</td>
        <td>' . $row['Designation_Name'] . '</td>
        <td>' . $row['TypeName'] . '</td>
		<td>' . $row['Month'] . '</td>
        <td>' . $row['Year'] . '<br></td>
        <td>' . $row['Totaldays'] . '<br></td>
        <td>' . $row['Status'] . '<br></td>
    </tr>';
}

echo $output;

$stmt->close();
$conn->close();
?>
