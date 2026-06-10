<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
include('../Database/db1.php');
$query = "
    SELECT 
        department.Department_id, 
        department.Name, 
        COUNT(employee.Employee_id) AS TotalEmployees
    FROM 
        department
    LEFT JOIN 
        employee ON department.Department_id = employee.Department_id
    GROUP BY 
        department.Department_id
";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Department Management</title>
    <link rel="stylesheet" href="../Resources/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../Resources/css/department.css">
</head>
<div class="container" style="margin-top:90px;">
        <h4>Manage Department</h4>
        <h6>Department &gt; Manage Department</h6>
    </div>
    <div class="container d-flex justify-content-end" style="width:640px; margin-right:52px;">
        <div class="row">
            <div class="col-md-2">
        <button class="btn btn-primary addDep-btn" data-toggle="modal" data-target="#addDepartmentModal" type="button" style="font-size: 20px;margin-left:0px;height:32px;padding:11px;padding-top:0px;width:75px; background-position:top;margin-bottom:30px; background-color:rgb(0,115,230);color:white;">
    <strong>+Add</strong>
</button>
        </div>
        </div>
    </div>
    <div class="container" >
        <div class="table-responsive" >
            <table class="table" style="background-color: white; width:100%; border:1px solid #007bff; border-radius: 7px;">
                <thead>            
                    <tr style="background-color:rgb(0,115,230);color:white;">
                    <th style="width: 150px; text-align: center;">Department ID</th>
                    <th style="width: 241px; text-align: left; padding-left: 90px;">Department</th>
                    <th style="width: 211px; text-align: center;">Total Employees</th>
                    <th style="width: 211px; text-align: center;">Actions</th>
                </tr>
                </thead>
                <tbody id="department-list">
                    <?php while ($department = mysqli_fetch_assoc($result)): ?>
                        <tr data-id="<?= $department['Department_id']; ?>"> 
                            <td style="font-family: Roboto;text-align: center;"><?php echo $department['Department_id']; ?></td>
                            <td class="department-name" style="font-family: Roboto;text-align: left; padding-left: 90px;"><?php echo ucfirst($department['Name']); ?></td>
                            <td style="font-family: Roboto; text-align: center;"><?php echo $department['TotalEmployees']; ?></td>
                            <td style="width:204px;">                         
                            <div style="display: flex; justify-content: center; padding-left: 17px; gap: 5px;">
                                <!-- Edit Button -->
                                <button class="btn btn-primary editDep-btn" title="Edit" data-toggle="modal" data-target="#editDepartmentModal" type="submit" style="margin-right: 13px;font-family: Roboto;color:White; background-color:rgba(0,123,255,0);border:none;outline:none;box-shadow:none;height:31px;padding-top:2px;width:23px;margin-left:-6px;"
                                data-id="<?= $department['Department_id']; ?>" 
                                data-name="<?= $department['Name']; ?>"> 
                                <img src="../Resources/img/icons8-edit-23.png" alt="Icon 1">
                                </button>
                                <!--delete button-->     
                                <button class="btn btn-secondary delete-department-btn" title="Delete"
                                data-id="<?php echo $department['Department_id']; ?>"
                                data-name="<?php echo htmlspecialchars($department['Name']); ?>" 
                                data-employees="<?php echo $department['TotalEmployees']; ?>" 
                                data-toggle='modal' data-target='#deleteModal'  type="button" style="font-family: Roboto; background-color:rgba(0,123,255,0);border:none;outline:none;box-shadow:none;height:31px;width:50px;padding-top:2px;padding-left:8px;padding-right:11px;"> 
                                <img src="../Resources/img/icon2.png" alt="Icon 2">
                                </button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>       
    <script src="../Resources/js/jquery.min.js"></script>
    <script src="../Resources/Bootstrap/js/bootstrap.min.js"></script>
</body>
</html>