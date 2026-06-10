<?php
include '../Database/db1.php'; 
$allowanceQuery = "SELECT SalaryExtras_id, Name, Amount FROM salaryextras";
$deductionQuery = "SELECT SalaryDeductions_id, Name, Amount FROM salarydeductions";
$allowanceResult = $conn->query($allowanceQuery);
$deductionResult = $conn->query($deductionQuery);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payroll Profile</title>
    <link rel="stylesheet" href="../Resources/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../Resources/css/payrollprofile.css">
</head>
<body style="font-family:Roboto, sans-serif;">
    <div class="container" style="margin-top:90px;">
        <h4>Payroll Profile</h4>
        <h6>Payroll Generation &gt;&nbsp;Payroll Profile<br></h6>
        <p style="color: red; font-size: small;">
    1. Please select at least one allowance and one deduction.<br>
    2. You cannot select the same allowance or deduction more than once.<br>
    3. Amount fields will auto-fill once you select a valid option.<br>
    4. Do not leave all allowance and deduction fields as "Select".<br>
    5. Amount fields are read-only and fetched from the database.<br>
    </p>
    </div>
    <form id="payroll-profile-form" method="POST" action="payroll_profile.php">
        <div class="container" style="margin-top:35px;">
            <div class="row">
                <!-- Allowance Section -->
                <div class="col-sm-12 col-md-6" style="border-radius:10px; border:groove; background-color: white; padding: 15px;margin-right:25px;">
                    <h5>Allowance</h5>
                    <hr>
                    <div class="row">
                        <div class="col">
                            <div class="form-group"><label>Allowance 1</label>
                            <select class="form-control allowance-select" data-id="1" name="allowance1">
                                <option value="Select">Select</option>
                                <?php while($row = $allowanceResult->fetch_assoc()): ?>
                                    <option value="<?= $row['Name'] ?>" data-amount="<?= $row['Amount'] ?>"><?= $row['Name'] ?></option>
                                <?php endwhile; ?>
                            </select></div>
                        </div>
                        <div class="col">
                            <div class="form-group"><label>Amount</label>
                            <input type="text" class="form-control allowance-amount" data-id="1" name="allowance1_Amount" readonly></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group"><label>Allowance 2</label>
                            <select class="form-control allowance-select" data-id="2" name="allowance2">
                                <option value="Select">Select</option> 
                                <?php
                                $allowanceResult->data_seek(0); 
                                while($row = $allowanceResult->fetch_assoc()): ?>
                                    <option value="<?= $row['Name'] ?>" data-amount="<?= $row['Amount'] ?>"><?= $row['Name'] ?></option>
                                <?php endwhile; ?>
                            </select></div>
                        </div>                    
                        <div class="col">
                            <div class="form-group"><label>Amount</label>
                            <input type="text" class="form-control allowance-amount" data-id="2" name="allowance2_Amount" readonly></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group"><label>Allowance 3</label>
                            <select class="form-control allowance-select" data-id="3" name="allowance3">
                                <option value="Select">Select</option>
                                <?php
                                $allowanceResult->data_seek(0); 
                                while($row = $allowanceResult->fetch_assoc()): ?>
                                    <option value="<?= $row['Name'] ?>" data-amount="<?= $row['Amount'] ?>"><?= $row['Name'] ?></option>
                                <?php endwhile; ?>
                            </select></div>
                        </div>
                        <div class="col">
                            <div class="form-group"><label>Amount</label>
                            <input type="text" class="form-control allowance-amount" data-id="3" name="allowance3_Amount" readonly></div>
                        </div>
                    </div>                       
                    <hr>
                </div>
                <!-- Deduction Section -->                  
                <div class="col" style="border-radius:10px; border:groove; background-color: white; padding: 15px;">
                    <h5>Deduction</h5>
                    <hr>
                    <div class="row">
                        <div class="col">
                            <div class="form-group"><label>Deduction 1</label>
                            <select class="form-control deduction-select" id="deduction1" name="deduction1">
                                <option value="Select">Select</option> 
                                <?php while($row = $deductionResult->fetch_assoc()): ?>
                                    <option value="<?= $row['Name'] ?>" data-amount="<?= $row['Amount'] ?>"><?= $row['Name'] ?></option>
                                <?php endwhile; ?>
                            </select></div>
                        </div>
                        <div class="col">
                            <div class="form-group"><label>Amount</label>
                            <input type="text" readonly class="form-control" id="deduction1_Amount" name="deduction1_Amount"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="form-group"><label>Deduction 2</label>
                            <select class="form-control deduction-select" id="deduction2" name="deduction2">
                                <option value="Select">Select</option> 
                                <?php
                                $deductionResult->data_seek(0); 
                                while($row = $deductionResult->fetch_assoc()): ?>
                                    <option value="<?= $row['Name'] ?>" data-amount="<?= $row['Amount'] ?>"><?= $row['Name'] ?></option>
                                <?php endwhile; ?>
                            </select></div>
                        </div>                    
                        <div class="col">
                            <div class="form-group"><label>Amount</label>
                            <input type="text" readonly class="form-control" id="deduction2_Amount" name="deduction2_Amount"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="form-group"><label>Deduction 3</label>
                            <select class="form-control deduction-select" id="deduction3" name="deduction3">
                                <option value="Select">Select</option> 
                                <?php
                                $deductionResult->data_seek(0); 
                                while($row = $deductionResult->fetch_assoc()): ?>
                                    <option value="<?= $row['Name'] ?>" data-amount="<?= $row['Amount'] ?>"><?= $row['Name'] ?></option>
                                <?php endwhile; ?>
                            </select></div>
                        </div>
                        <div class="col">
                            <div class="form-group"><label>Amount</label>
                            <input type="text" readonly class="form-control" id="deduction3_Amount" name="deduction3_Amount"></div>
                        </div>
                    </div>        
                    <hr>
                </div>
            </div>
        </div>
        <div class="container">
            <hr>
        </div>
        <div class="container">
            <div class="form-row justify-content-end">
                <button class="btn btn-light" type="reset" style="margin-right:10px;width:80px;color:#2374c3;">CANCEL</button>
                <button class="btn btn-primary" type="submit" style="width:80px;background-color:#2374c3;" name="save">SAVE</button>            
            </div>
        </div>
    </form>
</div>
<script src="../Resources/js/jquery.min.js"></script>
<script src="../Resources/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>