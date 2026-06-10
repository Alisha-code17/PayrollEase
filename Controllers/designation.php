<?php
include '../Database/db1.php'; 
$query = "
    SELECT 
        d.Designation_id, 
        d.Name AS DesignationName, 
        d.Salary, 
        COUNT(e.Employee_id) AS TotalEmployees
    FROM 
        Designation d
    LEFT JOIN 
        Employee e 
    ON 
        d.Designation_id = e.Designation_id
    GROUP BY 
        d.Designation_id
";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Designation</title>
    <link rel="stylesheet" href="../Resources/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Merriweather+Sans">
    <link rel="stylesheet" href="../Resources/css/designation.css">
</head>
<body style="background-color:#f8f9fa;">
<div class="container" style="margin-top:90px;">
        <h4>Manage Designation</h4>
        <h6>Department &gt; Manage Designation</h6>
    </div>
<div class="container d-flex justify-content-end">
        <div class="row">
            <div class="col-md-2">
            <button class="btn btn-primary add-btn" data-toggle="modal" data-target="#addDesignationModal" type="button" style="font-size: 20px;height:32px;padding:11px;padding-top:0px;width:75px;background-position:top;padding-left:10px;margin-bottom:30px; background-color:rgb(0,115,230);color:white;">
            <strong>+Add </strong>
        </button>
            </div>
        </div> 
    </div>
    <div class="container">
        <div class="table-responsive">
            <table id="designationTable" class="table" style="background-color: white; border:1px solid #007bff; border-radius: 7px;">
                <thead>
                    <tr style="background-color:rgb(0,115,230); color:white;">
                    <th style="width: 130px; text-align: center;">Designation ID</th>
                    <th style="width: 250px; text-align: left; padding-left: 105px;">Designation</th>
                    <th style="width: 150px; text-align: center;">Salary</th>            
                    <th style="width: 200px; text-align: center;">Total Employees</th>
                    <th style="width: 150px; text-align: center;">Actions</th>
                </tr>
                </thead>
                <tbody id="designation-list">
                <?php while ($designation = mysqli_fetch_assoc($result)): ?>
                    <tr id="designation-row-<?php echo $designation['Designation_id']; ?>" style="text-align: center;">
                    <td style="font-family: Roboto;"><?php echo $designation['Designation_id']; ?></td>
                    <td style="font-family: Roboto; text-align: left; padding-left: 105px;"><?php echo $designation['DesignationName']; ?></td>
                    <td style="font-family: Roboto;"><?php echo $designation['Salary']; ?></td>
                    <td style="font-family: Roboto;"><?php echo $designation['TotalEmployees']; ?></td>
                            <td>
            
                               <div style="display: flex; justify-content: center; padding-left: 17px; gap: 5px;">
                                <!-- Edit Button --> 
                                <button class="btn btn-primary edit-btn" title="Edit" data-toggle="modal" data-target="#editDesignationModal"
                                data-id="<?= $designation['Designation_id']; ?>" 
                                data-name="<?= $designation['DesignationName']; ?>"
                                data-salary="<?= $designation['Salary']; ?>"
                                style="margin-right: 13px; background-color:rgba(0,123,255,0); border:none; outline:none; box-shadow:none; height:31px; padding-top:2px; width:23px; margin-left:-6px;">
                                <img src="../Resources/img/icons8-edit-23.png" alt="Edit">
                                </button>
                                <!-- Delete Button -->
                                <button class="btn btn-secondary delete-btn" title="Delete"
                                data-id="<?= $designation['Designation_id']; ?>" 
                                data-employees="<?php echo $designation['TotalEmployees']; ?>"
                                data-toggle='modal' data-target='#deleteDesignationModal'  type="button" style="font-family: Roboto; background-color:rgba(0,123,255,0);border:none;outline:none;box-shadow:none;height:31px;width:50px;padding-top:2px;padding-left:8px;padding-right:11px;"> 
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