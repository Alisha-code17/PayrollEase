<?php
include '../Database/db1.php';  

$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

$sql = "SELECT 
            e.Employee_id, e.Picture, e.`FirstName`, 
            e.`LastName`, 
            CONCAT(e.`FirstName`, ' ', e.`LastName`) AS `FullName`, 
            e.Email, e.Address, e.Phone, e.Designation_id, 
            e.JoiningDate, e.Status, e.Department_id, 
            d.Name AS DepartmentName, 
            des.Name AS DesignationName
        FROM employee e
        JOIN department d ON e.Department_id = d.Department_id
        JOIN designation des ON e.Designation_id = des.Designation_id
        WHERE e.Status IN ('Active', 'Inactive')";

if (!empty($search)) {
    $sql .= " AND (e.FirstName LIKE '%$search%' 
                  OR e.LastName LIKE '%$search%' 
                  OR CONCAT(e.FirstName, ' ', e.LastName) LIKE '%$search%'
                  OR e.Email LIKE '%$search%'
                  OR e.Address LIKE '%$search%'
                  OR e.Phone LIKE '%$search%' 
                  OR e.JoiningDate LIKE '%$search%'
                  OR e.Status LIKE '%$search%')";
}

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        ?>
        <tr>
            <td style="font-family:Roboto;"><?= $row['Employee_id']; ?></td>
            <td style="height:71px;font-family:Roboto;">
                <img src="<?= $row['Picture']; ?>" style="border-radius:50%; width:36px;height:36px;">
                &nbsp;<?= $row['FullName']; ?>
                <p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <?= $row['DepartmentName']; ?></p>
            </td>
            <td style="font-family:Roboto;"><?= $row['Email']; ?></td>
            <td style="font-family:Roboto;"><?= $row['Address']; ?></td>
            <td style="font-family:Roboto;"><?= $row['Phone']; ?></td>
            <td>
                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false" type="button" style="color:rgb(58,57,57);background-color:rgba(239,243,248,0);font-family:Roboto;">
                        <?= $row['DesignationName']; ?>
                    </button>
                    <div class="dropdown-menu" role="menu">
                        <a class="dropdown-item" role="presentation" href="#">Admin Manager</a>
                        <a class="dropdown-item" role="presentation" href="#">HR Manager</a>
                        <a class="dropdown-item" role="presentation" href="#">Developer</a>
                        <a class="dropdown-item" role="presentation" href="#">Accountant</a>
                        <a class="dropdown-item" role="presentation" href="#">Analyst</a>
                    </div>
                </div>
            </td>
            <td style="font-family:Roboto;"><?= $row['JoiningDate']; ?></td>
            <td style="font-family:Roboto;">
                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false" type="button" style="background-color:rgba(0,98,204,0.03);color:rgb(13,13,13);">
                        <?= $row['Status']; ?>
                    </button>
                    <div class="dropdown-menu" role="menu">
                        <a class="dropdown-item" role="presentation" href="#">InActive</a>
                    </div>
                </div>
            </td>
            <td>
                <!-- Edit Button -->
                <button class="btn btn-primary edit-btn" data-toggle="modal" data-target="#editEmpModal" 
                    data-id="<?= $row['Employee_id']; ?>" 
                    data-firstname="<?= $row['FirstName']; ?>"
                    data-lastname="<?= $row['LastName']; ?>"
                    data-email="<?= $row['Email']; ?>"
                    data-phone="<?= $row['Phone']; ?>"
                    data-address="<?= $row['Address']; ?>"
                    data-dept="<?= $row['Department_id']; ?>"
                    data-dept-name="<?= $row['DepartmentName']; ?>" 
                    data-designation="<?= $row['Designation_id']; ?>"
                    data-designation-name="<?= $row['DesignationName']; ?>" 
                    data-joining-date="<?= $row['JoiningDate']; ?>"
                    data-status="<?= $row['Status']; ?>"
                    data-picture="<?= $row['Picture']; ?>"  
                    style="margin-right: 13px;font-family: Roboto; background-color:rgba(0,123,255,0);border:none;outline:none;box-shadow:none;height:31px;padding-top:2px;width:23px;margin-left:-6px;">
                    <img src="assets/img/icons8-edit-23.png">
                </button>
                <!--Deactivate Button -->
                <button class="btn btn-primary delete-btn" data-toggle="modal" data-target="#deleteEmpModal" 
                    data-id="<?= $row['Employee_id']; ?>" 
                    style="font-family: Roboto; background-color:rgba(0,123,255,0);border:none;outline:none;box-shadow:none;height:31px;width:50px;padding-top:2px;padding-left:8px;padding-right:11px;">
                    <img src="assets/img/icon2.png"> 
                </button> 
            </td>
        </tr>
        <?php
    }
} else {
    echo "<tr><td colspan='9'>No matching records found</td></tr>";
}

$conn->close();
?>
