function initPayslipPage() {
    console.log("Payslip script is initializing...");

   // const current_Year = new Date().getFullYear();
    const year_Dropdown = document.getElementById('year_Dropdown');
    const year_Button = document.getElementById('year_Button');
    const empSelect = document.getElementById("emp_ID_payslip");
    const monthSelect = document.getElementById("month_Select");
    const printButton = document.getElementById('printButton');
	const emailButton = document.getElementById('emailButton');

    printButton.disabled = true;
	emailButton.disabled = true; 

    // Helper: Fetch wrapper
    function fetchData_payslip(url) {
        return fetch(url).then(res => res.json()).catch(err => {
            console.error("Error fetching data:", err);
            return [];
        });
    }

    // Fetch available years and populate dropdown

// Fetch available years and populate dropdown
function populateYearDropdown() {
    fetchData_payslip("../Controllers/fetch_payslip_years.php")
        .then(years => {
            // Clear existing items
            year_Dropdown.innerHTML = "";

            // Filter out any null or invalid years
            const validYears = years.filter(yearObj => yearObj.Year !== null);

            if (validYears.length > 0) {
                validYears.forEach(yearObj => {
                    const yearItem = document.createElement('a');
                    yearItem.className = 'dropdown-item';
                    yearItem.href = '#';
                    yearItem.textContent = yearObj.Year;
                    yearItem.dataset.year = yearObj.Year;

                    yearItem.addEventListener('click', function (e) {
                        e.preventDefault();

                        const selectedYear = this.dataset.year;
                        year_Button.innerHTML = `${selectedYear}`; // Update the button text
                        document.getElementById('selectedYearHidden').value = selectedYear;

                        const emp_ID_payslip = empSelect.value;
                        const month = monthSelect.value;

                        // Only proceed if all required fields are selected
                        if (emp_ID_payslip && month && selectedYear) {
                            fetch(`../Controllers/check_payroll_sequence.php?emp_ID_payslip=${emp_ID_payslip}&month=${month}&year=${selectedYear}`)
                                .then(res => res.json())
                                .then(data => {
                                    if (data.fullRecordExists) {
                                        // Fetch employee, attendance, and payroll details
                                        getEmployeeDetails(emp_ID_payslip, month, selectedYear);
                                        getAttendanceDetails(emp_ID_payslip, month, selectedYear);
                                        getPayrollDetails(emp_ID_payslip, month, selectedYear);
                                        printButton.disabled = false;
										emailButton.disabled = false;

                                    } else {
                                        // If no record exists, show alert and disable print button
                                        printButton.disabled = true;
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Payroll not created yet',
                                            text: 'Payroll for the selected year does not exist.'
                                        });
                                    }
                                })
                                .catch(error => {
                                    console.error('Error checking payroll sequence:', error);
                                    printButton.disabled = true;
									emailButton.disabled = true;

                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: 'An error occurred while fetching payroll data.'
                                    });
                                });
                        } else {
                            // Disable print button if not all fields are selected
                            printButton.disabled = true;
                        }
                    });

                    // Append the year item to the dropdown
                    year_Dropdown.appendChild(yearItem);
                });
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'No Payroll Data Available',
                    text: 'No payroll data found for the selected employee and month.'
                });
            }

            // If no year is selected, reset the button text to "Select Year"
            if (!document.getElementById('selectedYearHidden').value) {
                year_Button.innerHTML = "Select Year";
            }
        })
        .catch(error => {
            console.error('Error fetching years:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'An error occurred while fetching years for the payroll.'
            });
        });
}



    function checkPayrollExists(emp_ID_payslip, month, year) {
        const url = `../Controllers/check_payroll_sequence.php?emp_ID_payslip=${emp_ID_payslip}&month=${month}&year=${year}`;
        return fetch(url)
            .then(res => res.json())
            .then(data => data.fullRecordExists)
            .catch(error => {
                console.error("Error checking payroll record:", error);
                return false;
            });
    }

    monthSelect.addEventListener("change", () => {
        const month = monthSelect.value;
        if (month !== "") {
            fetch(`../Controllers/check_payroll_sequence.php?month=${month}`)
                .then(res => res.json())
                .then(data => {
                    if (data.monthExists) {
                      //  year_Button.disabled = true;
                        printButton.disabled = true;
                        empSelect.disabled = false;
                        empSelect.focus(); // Move focus to employee dropdown
                    } else {
                       // year_Button.disabled = true;
                        printButton.disabled = true;
                        empSelect.disabled = false;
                        empSelect.focus(); // Still move focus but show warning

                       /* Swal.fire({
                            icon: 'error',
                            title: 'Payroll not created yet',
                            text: 'No payroll data exists for this month.'
                        });*/
                    }
                });
        } else {
           // year_Button.disabled = true;
            printButton.disabled = true;
			emailButton.disabled = true;

        }
    });

    empSelect.addEventListener("change", () => {
        const emp_ID_payslip = empSelect.value;
        const month = monthSelect.value;

        if (month && emp_ID_payslip) {
            fetch(`../Controllers/check_payroll_sequence.php?month=${month}&emp_ID_payslip=${emp_ID_payslip}`)
                .then(res => res.json())
                .then(data => {
                    if (data.empExists) {
                        year_Button.disabled = false;
                       // Apply both focus styles programmatically
                        year_Button.style.outline = "1px solid #7ac5e4";
                        year_Button.style.boxShadow = "0 0 0 0.25rem rgba(13, 110, 253, 0.25)";
                        year_Button.style.borderColor = "#86b7fe";
                        year_Button.focus();

                        console.log("Disabled?", year_Button.disabled); // Should log "false"
                        year_Button.focus();

                        setTimeout(() => year_Button.focus(), 100);
                        printButton.disabled = true;
                    } else {
                        //year_Button.disabled = true;
                        printButton.disabled = true;
                        Swal.fire({
                            icon: 'error',
                            title: 'Payroll not created yet',
                            text: 'No payroll data exists for this employee in the selected month.'
                        });
                    }
                });
        }
    });

    // Fetch employee list for dropdown
    function populateEmployeeDropdown_payslip() {
        fetchData_payslip("../Controllers/fetch_employee_ids_payslip.php").then(employeeIds => {
            empSelect.innerHTML = `<option value="" selected disabled>Select Employee ID</option>` +
                employeeIds.map(emp => `<option value="${emp.Employee_id}">${emp.Employee_id}</option>`).join("");
                console.log("called");
        });
    }

    // Populate attendance data
    function getAttendanceDetails(emp_ID_payslip, month, year) {
        fetchData_payslip(`../Controllers/fetch_attendance_payslip.php?emp_ID_payslip=${emp_ID_payslip}&month=${month}&year=${year}`).then(att => {
            document.getElementById('payslipTotalDays').value = att.Total_Days;
            document.getElementById('presentdays').value = att.Present_Days;
            document.getElementById('absentdays').value = att.Absent_Days;
        });
    }

    // Populate payroll data
    function getPayrollDetails(emp_ID_payslip, month, year) {
        fetchData_payslip(`../Controllers/fetch_payroll_record.php?emp_ID_payslip=${emp_ID_payslip}&month=${month}&year=${year}`).then(payroll => {
            document.getElementById('basic_salary').value = payroll.BasicSalary;
            document.getElementById('total_allowance').value = payroll.TotalAllowance;
            document.getElementById('total_deductions').value = payroll.TotalDeductions;
            document.getElementById('bonus').value = payroll.Bonus;
            document.getElementById('gross_salary').value = payroll.GrossSalary;
            document.getElementById('net_salary').value = payroll.NetSalary;
        });
    }

    // Populate employee info
    function getEmployeeDetails(emp_ID_payslip, month, year) {
        fetchData_payslip(`../Controllers/fetch_employee_details_payslip.php?emp_ID_payslip=${emp_ID_payslip}`).then(emp => {
            document.getElementById('name').value = (emp.FirstName || '') + (emp.LastName ? ' ' + emp.LastName : '');
            document.getElementById('department').value = emp.Department_Name;
            document.getElementById('designation').value = emp.Designation_Name;
            document.getElementById('address').value = emp.Address;
            document.getElementById('email').value = emp.Email;
            document.getElementById('phone').value = emp.Phone;
            document.getElementById('joining_date').value = emp.JoiningDate;
        });
    }

    // Final form submission for print
    function submitFormForPrint() {
        const empID = empSelect.value;
        const month = monthSelect.value;
        const year = document.getElementById('selectedYearHidden').value;
        const totalDays = document.getElementById('totaldays').value;

        document.getElementById('selectedEmpID').value = empID;
        document.getElementById('selectedMonthHidden').value = month;
        document.getElementById('selectedYearHidden').value = year;
        document.getElementById('total_days').value = totalDays;

        const url = `print_payslip.php?emp_ID=${empID}&payrollMonth=${month}&year=${year}&TotalDays=${totalDays}`;
        window.open(url, '_blank');
        document.getElementById('printForm').submit();
    }
	// Then modify your final button handlers section:
if (printButton && emailButton) {
    console.log("Print and Email buttons found.");

    printButton.addEventListener("click", function (e) {
        e.preventDefault();
        if (printButton.disabled) return; // Don't proceed if button is disabled
        
        const emp_ID_payslip = empSelect.value;
        const month = monthSelect.value;
        const year = document.getElementById('selectedYearHidden').value;
        
        if (!emp_ID_payslip || !month || !year) {
            Swal.fire({
                icon: 'error',
                title: 'Incomplete Data',
                text: 'Please select employee, month, and year before printing.'
            });
            return;
        }
        
        console.log("Print button clicked");
        const form = document.getElementById("payslipForm");
        if (form) {
            form.action = "../Controllers/payslip_printed.php";
            form.target = "_blank";
            console.log("Submitting form to payslip_printed.php...");
            form.submit();
        }
    });
emailButton.addEventListener("click", function (e) {
    e.preventDefault();
    if (emailButton.disabled) return; // Don't proceed if button is disabled
    
    // Get values from form elements
    const emp_ID_payslip = empSelect.value;
    const payrollMonth = monthSelect.value;
    const selectedYearHidden = document.getElementById('selectedYearHidden').value;
    
    // Set the hidden form values
    document.getElementById('selectedEmpID').value = emp_ID_payslip;
    document.getElementById('selectedMonthHidden').value = payrollMonth;
    document.getElementById('selectedYearHidden').value = selectedYearHidden;
    
    if (!emp_ID_payslip || !payrollMonth || !selectedYearHidden) {
        Swal.fire({
            icon: 'error',
            title: 'Incomplete Data',
            text: 'Please select employee, month, and year before sending email.'
        });
        return;
    }
    
    console.log("Email button clicked");
    Swal.fire({
        title: 'Send Payslip via Email?',
        text: "This will email the payslip to the employee's registered email address.",
        icon: 'question',
        showCancelButton: true, 
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, send it!'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById("payslipForm");
            if (form) {
                // Show loading indicator
                Swal.fire({
                    title: 'Sending Email',
                    html: 'Please wait while we process your request...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Debug: Log form data before submission
                const formData = new FormData(form);
                // Debug: Log form data
                console.log("Form data being submitted:");
                for (let pair of formData.entries()) {
                    console.log(pair[0] + ': ' + pair[1]);
                }
                // AJAX request
                fetch("../Controllers/sendmail.php", {
                    method: "POST",
                    body: formData
                })
                
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    Swal.close();
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: data.message || 'Payslip has been sent successfully',
                            confirmButtonColor: '#3085d6',
                            timer: 3000,
                            timerProgressBar: true
                        });
                    } else {
                        throw new Error(data.message || 'Failed to send email');
                    }
                })
                .catch(error => {
                    Swal.close();
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Failed to Send Email',
                        text: error.message || 'An error occurred while sending the email',
                        confirmButtonColor: '#d33'
                    });
                });
            }
        }
    });
});
}
    // Initialize the page
    populateEmployeeDropdown_payslip();
    populateYearDropdown(); // ✅ Dynamic year dropdown
}
