<?php
include '../Database/db1.php';  

$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

$sql = "SELECT 
            e.Employee_id, e.Picture, e.FirstName, 
            e.LastName, CONCAT(e.FirstName, ' ', e.LastName) AS FullName, 
            e.Email, e.Address, e.Phone, e.Designation_id, 
            e.JoiningDate, e.Status, e.Department_id, 
            d.Name AS Department_Name,    
            des.Name AS Designation_Name,
            u.Username, u.UserRole 
        FROM employee e
        JOIN department d ON e.Department_id = d.Department_id
        JOIN designation des ON e.Designation_id = des.Designation_id
        JOIN users u ON e.Employee_id = u.User_id
        WHERE e.Status IN ('Active', 'Inactive')";

if (!empty($search)) {
    $sql .= " AND (
        e.FirstName LIKE '%$search%' 
        OR e.LastName LIKE '%$search%' 
        OR CONCAT(e.FirstName, ' ', e.LastName) LIKE '%$search%'
        OR e.Email LIKE '%$search%'
        OR e.Address LIKE '%$search%'
        OR e.Phone LIKE '%$search%' 
        OR e.JoiningDate LIKE '%$search%'
        OR e.Status LIKE '%$search%'
        OR d.Name LIKE '%$search%'
        OR des.Name LIKE '%$search%'
    )";
}

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($employee = $result->fetch_assoc()) {
        $imagePath = (!empty($employee['Picture']) && file_exists('uploads/' . $employee['Picture']))
            ? 'uploads/' . $employee['Picture']
            : 'uploads/default.jpg';
        ?>
        <tr data-id="<?= $employee['Employee_id']; ?>">
            <td style="font-family:Roboto; text-align: center;"><?= $employee['Employee_id']; ?></td>
            <td style="height:71px;font-family:Roboto;">
                <div class="col-3">
                    <img src="<?= $imagePath ?>" style="border-radius:50%; width:50px; height:50px;">
                </div>
                <h5><?= $employee['FullName']; ?></h5>
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
                    align-items: center; gap: 5px; font-size: 12px; min-width: 66px; justify-content: center; font-family: 'Roboto', sans-serif;">
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
                    style="margin-right: 13px;font-family: Roboto; background-color:rgba(0,123,255,0);border:none;outline:none;box-shadow:none;height:31px;padding-top:2px;width:23px;margin-left:-6px;">
                    <img src="../Resources/img/icons8-edit-23.png">
                </button>
                                      <!--Deactivate Button -->
                <button class="btn btn-primary deleteEmp-btn" title="Deactivate" data-toggle="modal" data-target="#deleteEmpModal" 
                    data-id="<?= $employee['Employee_id']; ?>" 
                    style="font-family: Roboto; background-color:rgba(0,123,255,0);border:none;outline:none;box-shadow:none;height:31px;width:50px;padding-top:2px;padding-left:8px;padding-right:11px;">
                    <img src="../Resources/img/icon2.png"> 
                </button>
            </td>
        </tr>
        <?php
    }
} else {
    echo "<tr><td colspan='9' class='text-center'>No matching records found</td></tr>";
}
$conn->close();
?>