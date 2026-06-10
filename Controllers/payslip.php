<?php
include '../Database/db1.php';
?>


<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payslip</title>
    <link rel="stylesheet" href="../Resources/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Merriweather+Sans">
    <style>
        #year_Button:disabled {
    background-color: #e9ecef;
    color: #667480;
    border-color: #ced4da;
    cursor: not-allowed;
}

        </style>
</head>

<body style="font-family:'Merriweather Sans', sans-serif;">
    <div class="container" style="margin-top:90px;">
        <div style ="padding-bottom: 70px;">
            <div class="row">
                <div class="col">
                    <h4>Payslip Log</h4>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <h6>Payroll Generation &gt; Payslip Log</h6>
                </div>
            </div>
        </div>
        <!--<div class="row">
            <div class="col d-flex justify-content-end">
                <p>Date:</p>
            </div>
        </div>-->
        <div class="row">
            <div class="col">
                <h6>Select To Get Record:</h6>
            </div>
        </div>
		<form id="payslipForm" method="POST" target="_blank">

        <div style="border-radius:10px;border:groove;padding:16px; background-color:white;">
            <div class="row">
                
                <div class="col" style="padding-left: 90px;">
                    <div class="form-group" style="margin-top:7px;">
                            <label for="payrollMonth">Select Month<span style="color: red;">&nbsp;&nbsp;*</span></label>
                            <select class="form-control" id="month_Select" name="payrollMonth" class="form-control"  tabindex="1" required autofocus>
                                <option value="" selected="">Select Month</option>
                                <option value="January">January</option>
                                <option value="February">February</option>
                                <option value="March">March</option>
                                <option value="April">April</option>
                                <option value="May">May</option>
                                <option value="June">June</option>
                                <option value="July">July</option>
                                <option value="August">August</option>
                                <option value="September">September</option>
                                <option value="October">October</option>
                                <option value="November">November</option>
                                <option value="December">December</option>
                            </select>
                    </div>
				 <input type="hidden" name="selectedMonthHidden" id="selectedMonthHidden">

                </div>
                <div class="col">
                    <div style="padding-left:30px;padding-top:7px;">
                     <label>Employee ID:<span style="color: red;">&nbsp;&nbsp;*</span></label>
                     <select name="emp_ID_payslip" id="emp_ID_payslip" class="form-control" tabindex="2"  required>
                         <option value="" selected >Select Employee ID</option>
                     </select>
                     </div>
                 </div>
                <div class="col">
                    <div style="padding-left:132px; padding-top:7px;">
						<label>Year:<span style="color: red;">&nbsp;&nbsp;*</span></label>
						<div class="dropdown">
							<button id="year_Button"
                            class="btn btn-primary dropdown-toggle"
                            data-toggle="dropdown"
                            aria-expanded="false"
                            type="button"
                            tabindex="3"
                            disabled
                            style="background-color:white ; color: #667480; border-color: #ced4da;">
                            Select Year&nbsp;
                            </button>

							<div class="dropdown-menu custom_dropdown_payslip" style="position: absolute !important;" role="menu" id="year_Dropdown">
								<!-- Year will be added dynamically here -->
							</div>
						</div>
					</div>
					
					<!--fom form-->
					<input type="hidden" id="selectedEmpID" name="selectedEmpID">
					<input type="hidden" name="selectedMonthHidden" id="selectedMonthHidden">
					<input type="hidden" id="selectedYearHidden" name="selectedYearHidden">
					<input type="hidden" id="payslip_totaldays" name="payslip_TotalDays">
                   

                </div>
            </div>
        </div>
        <div style="padding-top:19px;">
            <div class="row">
                <div class="col">
                    <h6>Employee Record:</h6>
                </div>
            </div>
        </div>
        <div style="border-radius:10px;border:groove;padding:16px; background-color:white;">
            <div class="row" style="width:1098px;">
                <div class="col">
                    <div><label> Name:</label><br><input type="text" id="name"  class="form-control" disabled=""></div>
                </div>
                <div class="col">
                    <div style="padding-left:34px;"><label>Department:</label><input type="text" id="department" class="form-control" disabled=""></div>
                </div>
                <div class="col">
                    <div style="padding-left:66px;"><label>Designation:</label><input type="text" id="designation" class="form-control" disabled=""></div>
                </div>
				<div class="col">
                    <div style="padding-left:18px;"><label>Joining Date:</label><input type="text" id="joining_date" style="width:180px;" class="form-control" disabled=""></div>
                </div>
                
            </div>
            <div class="row" style="width:850px;padding-top:10px;">
                <div class="col" style="width:209px;">
                    <div style="width:220px;padding-right:0px;"><label>Email:</label><br><input style="width:245px" type="text" id="email" class="form-control" disabled=""></div>
                </div>
                <div class="col">
                    <div style="padding-left:34px;width:230px;"><label>Phone No:</label><input style="width:210px" type="text" id="phone" class="form-control" disabled=""></div>
                </div>
                <div class="col">
                    <div style="padding-left:66px;width:266px;"><label>Address:</label><br><input style="width:400px;" type="text" id="address" class="form-control" disabled=""></div>
                </div>
            </div>
        </div>
        <div class="col">
            <h6 style="padding-top:17px;">Attendance Record:</h6>
        </div>
        <div style="border-radius:10px;border:groove;padding:16px;background-color:white;">
            <div class="row" style="width:818px;">
                <div class="col">
                    <div><label>Total Days:</label><input type="text" id="payslipTotalDays"  class="form-control" readonly></div>
                </div>
                <div class="col">
                    <div style="padding-left:34px;"><label>Present Days:</label><input type="text" id="presentdays" class="form-control" disabled=""></div>
                </div>
                <div class="col">
                    <div style="padding-left:66px;"><label>Absent Days:</label><input type="text" id="absentdays" class="form-control" disabled=""></div>
                </div>
            </div>
        </div>
        <div class="col" style="padding-top:1px;">
            <h6 style="padding-top:17px;">Payroll Record:</h6>
        </div>
        <div style="border-radius:10px;border:groove;padding:16px;background-color:white;">
            <div class="row">
                <div class="col">
                    <div><label>Basic Salary:</label><input type="text" style="width:210px"  class="form-control" id="basic_salary" disabled=""></div>
                </div>
                <div class="col" style="padding-right:0px">
                    <div style="padding-left:30px;"><label>Total Allowence:</label><input type="text" style="width:210px"  class="form-control"  id="total_allowance" disabled=""></div>
                </div>
                <div class="col">
                    <div style="padding-left:66px;"><label>Total Deduction:</label><input type="text"  class="form-control" id="total_deductions" disabled=""></div>
                </div>
                <div class="col">
                    <div style="padding-left:66px;padding-right: 40px;"><label>Bonus</label><input type="text"  class="form-control" id="bonus"  disabled=""></div>
                </div>
            </div>
            <div class="row" style="padding-top:10px;width:600px;">
                <div class="col">
                    <div><label>Gross Salary:</label><br><input type="text" style="width:210px"  class="form-control" id="gross_salary" disabled=""></div>
                </div>
                <div class="col">
                    <div style="padding-left:10px;"><label>Net Salary:</label><input type="text" style="width:210px"  class="form-control" id="net_salary" disabled=""></div>
                </div>
            </div>
        </div>
        <div>



            

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr></tr>
                    </thead>
                    <tbody>
                        <tr>
                           <!--<td style="width:93px;">
                                <button id="printEmailButton" class="btn btn-primary" type="button" name="action" value="print-email" disabled>Print & Email</button>
                            </td>-->
                            <td style="width:100px;padding-left:900px;">
							<button class="btn btn-success" type="button" id="emailButton">Email</button>

                            </td>
                           <td style="width:93px;padding-right:0px;">
							<button class="btn btn-primary" type="button" id="printButton" disabled>Print</button>

                            </td>
                        </tr>
                        
                        <tr></tr>
                    </tbody>
                </table>

              
                

                <div class="row" style="padding-top: 20px;">
                <div class="col text-center">
    <label>QR Code:</label><br>
    <img id="qrImage" style="display:none; width:200px; height:200px;" />
    <div id="qrStatus" style="color:red;"></div>
     </div>       
       </div>

            </div>
        </div>
		</form>
    </div>
<!--<script>
const currentYear = new Date().getFullYear();
    const yearDropdown = document.getElementById('yearDropdown');
    const yearButton = document.getElementById('yearButton');

    // Create year item
    const yearItem = document.createElement('a');
    yearItem.className = 'dropdown-item';
    yearItem.href = '#';
    yearItem.textContent = currentYear;

    // On click update button text
    yearItem.addEventListener('click', function () {
        yearButton.innerHTML = currentYear;
    });

    // Append year item to dropdown
    yearDropdown.appendChild(yearItem);
// Generic Fetch Function
function fetchData(url) {
    return fetch(url)
        .then(response => response.json()) // Direct JSON Parsing
        .catch(error => console.error("Error fetching data:", error));
}


// Populate Dropdown with Employee IDs
function populateEmployeeDropdown() {
    fetchData("fetch_employee_ids_payslip.php").then(employeeIds => {
        const empSelect = document.querySelector('select[name="empID"]');
        if (empSelect) {
            empSelect.innerHTML = `<option value="" selected disabled>Select Employee ID</option>` +
                employeeIds.map(emp => `<option value="${emp.Employee_id}">${emp.Employee_id}</option>`).join("");
        }
    });
}

// Call it on page load
populateEmployeeDropdown();

// Get Employee Details
function getEmployeeDetails(empID) {
    fetchData(`fetch_employee_details_payslip.php?empID=${empID}`).then(emp => {
        document.getElementById('name').value = 
    (emp.FirstName ? emp.FirstName : '') + 
    (emp.LastName ? ' ' + emp.LastName : '');

        document.getElementById('department').value = emp.Department_Name;
        document.getElementById('designation').value = emp.Designation_Name;
        document.getElementById('address').value = emp.Address;
        document.getElementById('email').value = emp.Email;
        document.getElementById('phone').value = emp.Phone;
        document.getElementById('joining_date').value = emp.joiningDate;
    });
}

// Get Attendance Data
function getAttendanceDetails(empID, month, year) {
    fetchData(`fetch_attendance_payslip.php?empID=${empID}&month=${month}&year=${year}`).then(att => {
        document.getElementById('totaldays').value = att.Total_Days;
        document.getElementById('presentdays').value = att.Present_Days;
        document.getElementById('absentdays').value = att.Absent_Days;
    });
}

// Get Payroll Data
function getPayrollDetails(empID, month, year) {
    fetchData(`fetch_payroll_record.php?empID=${empID}&month=${month}&year=${year}`).then(payroll => {

        document.getElementById('basic_salary').value = payroll.BasicSalary;
        document.getElementById('total_allowance').value = payroll.TotalAllowance;
        document.getElementById('total_deductions').value = payroll.TotalDeductions;
        document.getElementById('bonus').value = payroll.Bonus;
        document.getElementById('gross_salary').value = payroll.GrossSalary;
        document.getElementById('net_salary').value = payroll.NetSalary;
    });
}

// On Employee Select -> Show Employee Details
document.querySelector('select[name="empID"]').addEventListener('change', function () {
    const empID = this.value;
    getEmployeeDetails(empID);

    const month = document.getElementById('monthSelect').value;
    const year = document.getElementById('selectedYearHidden').value;

    if (empID && month && year) {
        getAttendanceDetails(empID, month, year);
        getPayrollDetails(empID, month, year); // Fetch Payroll Data
    }
});

// On Month Change -> Fetch Attendance & Payroll Data
document.getElementById('').addEventListener('change', function () {
    const empID = document.querySelector('select[name="empID"]').value;
    const month = this.value;
    const year = document.getElementById('selectedYearHidden').value;

    if (empID && month && year) {
        getAttendanceDetails(empID, month, year);
        getPayrollDetails(empID, month, year); // Fetch Payroll Data
    }
});

// On Year Change -> Fetch Attendance & Payroll Data
document.querySelectorAll('#yearDropdown a').forEach(item => {
    item.addEventListener('click', function () {
        const year = this.textContent;
        document.getElementById('selectedYearHidden').value = year;

        const empID = document.querySelector('select[name="empID"]').value;
        const month = document.getElementById('monthSelect').value;

        if (empID && month && year) {
            getAttendanceDetails(empID, month, year);
            getPayrollDetails(empID, month, year); // Fetch Payroll Data
        }
    });
});
</script>-->

    <script src="../Resources/js/jquery.min.js"></script>
    <script src="../Resources/bootstrap/js/bootstrap.min.js"></script>
	<!--<script src="../Resources/js/payslip_dynamic.js"></script>-->

</body>

</html>