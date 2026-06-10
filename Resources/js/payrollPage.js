
    // Function to Fetch Data (Replaces Async/Await)
    function fetchData(url) {
        return fetch(url)
            .then(response => response.json())
            .catch(error => {
                console.error(`Fetch Error: ${url}`, error);
                return [];
            });
    }

    // Function to Populate Employee Dropdown
    function populateEmployeeDropdown() {
        fetchData("fetch_employee_ids.php").then(employeeIds => {
            const empSelect = document.querySelector('select[name="empID"]');
            if (empSelect) {
                empSelect.innerHTML = `<option value="" selected disabled>Select Employee ID</option>` +
                    employeeIds.map(emp => `<option value="${emp.Employee_id}">ID ${emp.Employee_id}</option>`).join("");
            }
        });
    }

    // Function to Fetch Employee Details & Attendance
    function fetchEmployeeData(employeeId) {
         const empName = document.querySelector('input[name="empname"]');
        const empDept = document.querySelector('input[name="department"]');
        const empDesig = document.querySelector('input[name="designation"]');
        const empSalary = document.querySelector('input[name="salary"]');
        const basicSalary = document.querySelector('input[name="basicsalary"]');
        const empPicture = document.querySelector(".avatar-bg");
        const presentDays = document.querySelector('input[name="presentDays"]');
        const absentDays = document.querySelector('input[name="absentDays"]');
        const overtimeHours = document.querySelector('input[name="overtimeHours"]');
    // Default image path
    const defaultImage = '../Controllers/uploads/default.jpg';

        if (!employeeId) {
            // Clear fields if no employee is selected
            empName.value = empDept.value = empDesig.value = empSalary.value = basicSalary.value = "";
            //empPicture.style.backgroundImage = "";
            //empPicture.style.backgroundImage = "url('../Controllers/uploads/default.jpg')"; // Fallback
            empPicture.style.backgroundImage = `url('${defaultImage}')`;
            presentDays.value = absentDays.value = overtimeHours.value = "";
            return;
        }

        // Fetch Employee Details
        fetchData(`fetch_employee_details.php?employee_id=${employeeId}`).then(empDetails => {
            if (!empDetails.error) {
                empName.value = empDetails.FullName;
                empDept.value = empDetails.Department;
                empDesig.value = empDetails.Designation;
                empSalary.value = empDetails.Salary;
                basicSalary.value = empDetails.Salary;  // Copy salary to basic salary
                //empPicture.style.backgroundImage = `url('${empDetails.Picture}')`;
                // Set the employee picture (with fallback to default.jpg)
            const imageDir = '../Controllers/uploads/';
            const imageFile = (empDetails.Picture && empDetails.Picture !== '') 
                // Check if employee has a picture in database
            if (empDetails.Picture && empDetails.Picture.trim() !== '') {
                 const employeeImage = imageDir + empDetails.Picture;
                // First try to load the employee's image
                setEmployeeImage(employeeImage);
                // Verify if the image actually exists (fallback to default if not)
                checkImageExists(employeeImage)
                    .then(exists => {
                        if (!exists) {
                            setEmployeeImage(defaultImage);
                        }
                    })
                    .catch(() => setEmployeeImage(defaultImage));
            } else {
                // No picture in database -> use default
                setEmployeeImage(defaultImage);
            }

            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: empDetails.error,
                    confirmButtonColor: '#d33'
                });
            }
        });
    // Helper function to set image
    function setEmployeeImage(imagePath) {
        empPicture.style.backgroundImage = `url('${imagePath}')`;
        empPicture.style.backgroundSize = 'cover';
        empPicture.style.backgroundPosition = 'center';
    }

    // Helper function to check if image exists
    function checkImageExists(url) {
        return fetch(url, { method: 'HEAD' })
            .then(res => res.ok)
            .catch(() => false);
    }
        // Fetch Employee Attendance
    const month = document.querySelector('#payrollMonth')?.value;
    const year = document.querySelector('#payrollYear')?.value;

    fetchData(`fetch_total_attendance.php?employee_id=${employeeId}&payrollMonth=${encodeURIComponent(month)}&payrollYear=${encodeURIComponent(year)}`)
    .then(attendance => {
        if (attendance.error) {
            Swal.fire({
                icon: 'error',
                title: 'Attendance Data Missing',
                text: attendance.error,
                confirmButtonColor: '#d33'
            }).then(() => {
                disableAllFields();
            });
        } else {
            enableAllFields();
            presentDays.value = attendance.Present_days;
            absentDays.value = attendance.Absent_days;
            overtimeHours.value = attendance.Overtime_hours;
        }
    });

        }

    // Function to disable all fields
    function disableAllFields() {
        const fieldsToDisable = [
            /*'input[name="presentDays"]',
            'input[name="absentDays"]',
            'input[name="overtimeHours"]',
            'input[name="bonusamount"]',
            'input[name="allowance1"]',
            'input[name="allowance1_amount"]',
            'input[name="allowance2"]',
            'input[name="allowance2_amount"]',
            'input[name="allowance3"]',
            'input[name="allowance3_amount"]',
            'input[name="deduction1"]',
            'input[name="deduction1_amount"]',
            'input[name="deduction2"]',
            'input[name="deduction2_amount"]',
            'input[name="deduction3"]',
            'input[name="deduction3_amount"]',*/
            '#payrollProfileID',
            'select[name="payrollMonth"]',
            'button[type="submit"]'
        ];
        
        fieldsToDisable.forEach(selector => {
            const element = document.querySelector(selector);
            if (element) {
                element.disabled = true;
            }
        });
        
        // Also disable the employee dropdown to prevent changing
        /*const empSelect = document.querySelector('select[name="empID"]');
        if (empSelect) {
            empSelect.disabled = true;
        }*/
    }

    // Function to enable all fields
    function enableAllFields() {
        const fieldsToEnable = [
            'input[name="presentDays"]',
            'input[name="absentDays"]',
            'input[name="overtimeHours"]',
            'input[name="bonusamount"]',
            'input[name="allowance1"]',
            'input[name="allowance1_amount"]',
            'input[name="allowance2"]',
            'input[name="allowance2_amount"]',
            'input[name="allowance3"]',
            'input[name="allowance3_amount"]',
            'input[name="deduction1"]',
            'input[name="deduction1_amount"]',
            'input[name="deduction2"]',
            'input[name="deduction2_amount"]',
            'input[name="deduction3"]',
            'input[name="deduction3_amount"]',
            '#payrollProfileID',
            'button[type="submit"]'
        ];
        
        fieldsToEnable.forEach(selector => {
            const element = document.querySelector(selector);
            if (element) {
                element.disabled = false;
            }
        });
        
        // Re-enable the employee dropdown
        const empSelect = document.querySelector('select[name="empID"]');
        if (empSelect) {
            empSelect.disabled = false;
        }
    }

    // Event Delegation for Employee Selection
    document.addEventListener("change", function (event) {
        if (event.target.matches('select[name="empID"]')) {
            fetchEmployeeData(event.target.value);
        }
    });
    

    // Use MutationObserver to Populate Dropdown When Loaded
    const observer = new MutationObserver(() => {
        const empSelect = document.querySelector('select[name="empID"]');
        if (empSelect) {
            console.log("Employee dropdown found! Populating...");
            populateEmployeeDropdown();
            observer.disconnect(); // Stop observing once dropdown is found
        }
    });

    observer.observe(document.body, { childList: true, subtree: true });

    // to fetch month and year
    // Function to Fetch Data
    function fetchData(url) {
        return fetch(url)
            .then(response => response.json())
            .catch(error => {
                console.error(`Fetch Error: ${url}`, error);
                return [];
            });
    }

    // Function to set current month and year
    function setCurrentMonthAndYear() {
        console.log("✅ Running setCurrentMonthAndYear()..."); // Check if this appears in browser console (F12)
        
        const monthInput = document.getElementById('payrollMonth');
        const yearInput = document.getElementById('payrollYear');
        
        console.log("Month Input:", monthInput); // Should show the HTML element
        console.log("Year Input:", yearInput);   // Should show the HTML element
        
        if (!monthInput || !yearInput) {
            console.error("❌ Error: Could not find inputs!");
            return;
        }

        const months = ["January", "February", "March", "April", "May", "June",
                    "July", "August", "September", "October", "November", "December"];
        
        const currentDate = new Date();
        const currentMonth = months[currentDate.getMonth() - 1]; // -1 previous month
        const currentYear = currentDate.getFullYear();
        
        monthInput.value = currentMonth;
        yearInput.value = currentYear;
        
        console.log("✅ Set values - Month:", currentMonth, "Year:", currentYear);
    }

    // Function to Populate Payroll Profile Dropdown
    function populatePayrollProfiles() {
        const payrollProfileSelect = document.getElementById("payrollProfileID");

        if (!payrollProfileSelect) {
            console.error("Dropdown not found. Waiting for it to appear...");
            return; // Exit and let the interval keep checking
        }

        console.log("Dropdown found! Fetching profiles...");

        fetchData("fetch_payroll_profile_ids.php").then(payrollProfiles => {
            console.log("Fetched Payroll Profiles:", payrollProfiles);

            payrollProfileSelect.innerHTML = `<option value="" selected disabled>Select Payroll Profile ID</option>`;

            payrollProfiles.forEach(profile => {
                let option = document.createElement("option");
                option.value = profile.PayrollProfile_ID;
                option.textContent = `Profile ${profile.PayrollProfile_ID}`;
                payrollProfileSelect.appendChild(option);
            });

            console.log("Updated Payroll Profile Dropdown:", payrollProfileSelect.innerHTML);
        });
    }

    // Function to Populate Payroll Profile Details
    function fetchPayrollDetails(payrollProfileID) {
        fetchData(`fetch_payroll_profile_details.php?payrollProfileID=${payrollProfileID}`).then(payrollDetails => {
            if (payrollDetails.error) {
                alert(payrollDetails.error);
                return;
            }

            document.querySelector('input[name="allowance1"]').value = payrollDetails.Allowance1 || "";
            document.querySelector('input[name="allowance1_amount"]').value = Math.round(payrollDetails.Allowance1_Amount) || "";
            document.querySelector('input[name="allowance2"]').value = payrollDetails.Allowance2 || "";
            document.querySelector('input[name="allowance2_amount"]').value = Math.round(payrollDetails.Allowance2_Amount) || "";
            document.querySelector('input[name="allowance3"]').value = payrollDetails.Allowance3 || "";
            document.querySelector('input[name="allowance3_amount"]').value = Math.round(payrollDetails.Allowance3_Amount) || "";
            document.querySelector('input[name="deduction1"]').value = payrollDetails.Deduction1 || "";
            document.querySelector('input[name="deduction1_amount"]').value = Math.round(payrollDetails.Deduction1_Amount) || "";
            document.querySelector('input[name="deduction2"]').value = payrollDetails.Deduction2 || "";
            document.querySelector('input[name="deduction2_amount"]').value = Math.round(payrollDetails.Deduction2_Amount) || "";
            document.querySelector('input[name="deduction3"]').value = payrollDetails.Deduction3 || "";
            document.querySelector('input[name="deduction3_amount"]').value = Math.round(payrollDetails.Deduction3_Amount) || "";

            updateTotalAllowances();
            updateTotalDeductions();

             // ✅ Instantly recalc salaries after profile is fetched
        calculateGrossSalary();
        calculateNetSalary();
        });
    }

    // Function to Calculate Total Allowances
    function updateTotalAllowances() {
        const total = 
            (parseInt(document.querySelector('input[name="allowance1_amount"]').value) || 0) +
            (parseInt(document.querySelector('input[name="allowance2_amount"]').value) || 0) +
            (parseInt(document.querySelector('input[name="allowance3_amount"]').value) || 0);

        document.querySelector('input[name="totalallowance"]').value = Math.round(total);
        document.querySelector('input[name="extras"]').value = Math.round(total);
    }

    // Function to Calculate Total Deductions
    function updateTotalDeductions() {
        const total = 
            (parseInt(document.querySelector('input[name="deduction1_amount"]').value) || 0) +
            (parseInt(document.querySelector('input[name="deduction2_amount"]').value) || 0) +
            (parseInt(document.querySelector('input[name="deduction3_amount"]').value) || 0);

        document.querySelector('input[name="totaldeduction"]').value = Math.round(total);
        document.querySelector('input[name="substractions"]').value = Math.round(total);
    }

    // Event Delegation for Dropdown Selection
    document.addEventListener("change", function (event) {
        if (event.target && event.target.id === "payrollProfileID") {
            const selectedID = event.target.value;
            if (selectedID) {
                fetchPayrollDetails(selectedID);
            }
        }
    });

    // Initialize everything when the DOM is loaded
    document.addEventListener('DOMContentLoaded', function() {
        setCurrentMonthAndYear(); // Set current month and year
        
        // Check if dropdown exists every 500ms until found, then stop checking
        const checkDropdownInterval = setInterval(() => {
            const payrollProfileSelect = document.getElementById("payrollProfileID");
            if (payrollProfileSelect) {
                clearInterval(checkDropdownInterval); // Stop checking once found
                populatePayrollProfiles();
            }
        }, 500);
    });

    // Function to calculate gross salary
    function calculateGrossSalary() {
        const basicSalary = document.querySelector('input[name="basicsalary"]');
        const bonusAmount = document.querySelector('input[name="bonusamount"]');
        const extras = document.querySelector('input[name="extras"]');
        const grossSalary = document.querySelector('input[name="grosssalary"]');

        const basic = Math.floor(parseFloat(basicSalary?.value) || 0);
        const bonus = Math.floor(parseFloat(bonusAmount?.value) || 0);
        const extra = Math.floor(parseFloat(extras?.value) || 0);

        const gross = basic + extra + bonus;
        if (grossSalary) {
            grossSalary.value = gross; // Display as an integer
        }
        console.log("Gross Salary Calculated:", gross);
    }

    // Function to calculate net salary
    function calculateNetSalary() {
        const grossSalary = document.querySelector('input[name="grosssalary"]');
        const substractions = document.querySelector('input[name="substractions"]');
        const netSalary = document.querySelector('input[name="netsalary"]');

        const gross = Math.floor(parseFloat(grossSalary?.value) || 0);
        const subtraction = Math.floor(parseFloat(substractions?.value) || 0);

        const net = gross - subtraction;
        if (netSalary) {
            netSalary.value = net; // Display as an integer
        }
        console.log("Net Salary Calculated:", net);
    }

    // Attach input listeners and recalculate salaries
    function attachInputListeners() {
        const basicSalary = document.querySelector('input[name="basicsalary"]');
        const bonusAmount = document.querySelector('input[name="bonusamount"]');
        const extras = document.querySelector('input[name="extras"]');
        const substractions = document.querySelector('input[name="substractions"]');

        [basicSalary, bonusAmount, extras, substractions].forEach(input => {
            if (input) {
                input.addEventListener('input', () => {
                    console.log("Input Changed:", input.name, input.value);
                    calculateGrossSalary();
                    calculateNetSalary();
                });
            } else {
                console.error("Input Element Not Found:", input);
            }
        });

        // Trigger calculations once when the page loads
        calculateGrossSalary();
        calculateNetSalary();
    }

    // Handle form submission
    document.addEventListener('submit', function (event) {
        if (event.target && event.target.id === "payrollForm") {
            event.preventDefault(); // Prevent default form submission

            // Ensure calculations are performed before validation
            calculateGrossSalary();
            calculateNetSalary();

            let empID = document.getElementById("empID")?.value;
            let payrollProfileID = document.getElementById("payrollProfileID")?.value;
            let presentDays = document.getElementById("presentDays")?.value;
            let absentDays = document.getElementById("absentDays")?.value;
            let totalAllowance = document.getElementById("totalallowance")?.value;
            let totalDeduction = document.getElementById("totaldeduction")?.value;
            let basicSalary = document.getElementById("basicsalary")?.value;
            let bonusAmount = document.getElementById("bonusamount")?.value;
            let grossSalary = document.getElementById("grosssalary")?.value;
            let netSalary = document.getElementById("netsalary")?.value;
            let payrollMonth = document.getElementById("payrollMonth")?.value.trim();
            let payrollYear = document.getElementById("payrollYear")?.value.trim();

            // Debugging logs
            console.log("empID:", empID);
            console.log("payrollProfileID:", payrollProfileID);
            console.log("presentDays:", presentDays);
            console.log("absentDays:", absentDays);
            console.log("totalAllowance:", totalAllowance);
            console.log("totalDeduction:", totalDeduction);
            console.log("basicSalary:", basicSalary);
            console.log("bonusAmount:", bonusAmount);
            console.log("grossSalary:", grossSalary);
            console.log("netSalary:", netSalary);
            console.log("payrollMonth:", payrollMonth);
            console.log("payrollYear:", payrollYear);

            // Ensure all values are not empty
            if (!empID || !payrollProfileID || !presentDays || !absentDays ||
                !totalAllowance || !totalDeduction || !basicSalary ||
                !grossSalary || !netSalary || !payrollMonth || !payrollYear) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Missing Fields!',
                        text: '⚠️ Error: All fields are required!',
                        confirmButtonColor: '#f27474'
                    });
                return;
            }

            // Parse integers where required
            let payrollData = {
                empID: parseInt(empID),
                payrollProfileID: parseInt(payrollProfileID),
                presentDays: parseInt(presentDays),
                absentDays: parseInt(absentDays),
                totalAllowance: parseInt(totalAllowance),
                totalDeduction: parseInt(totalDeduction),
                basicSalary: parseInt(basicSalary),
                bonusAmount: bonusAmount ? parseInt(bonusAmount) : 0, // ✅ Default to 0 if empty
                grossSalary: parseInt(grossSalary),
                netSalary: parseInt(netSalary),
                payrollMonth: payrollMonth, // Keeping it as a string
                payrollYear: parseInt(payrollYear),
            };

            // Ensure parsing did not result in NaN
            for (let key in payrollData) {
                if (key !== "payrollMonth" && key !== "bonusAmount" && isNaN(payrollData[key])) {
                    Swal.fire({
                    icon: 'warning',
                    title: 'Invalid Input',
                    text: `⚠️ Error: ${key} must be a valid number.`,
                    confirmButtonColor: '#f8bb86'
                });
                    return;
                }
            }

            // AJAX request to save data using fetch
            fetch("save_Payroll.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(payrollData),
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Server responded with status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log("Parsed Response:", data);
                if (data.success) {
    Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: '✅ Payroll data saved successfully!',
        confirmButtonColor: '#28a745'
    }).then(() => {
        // ✅ Refresh page after OK
        location.reload();
    });
}else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Save Failed',
                        text: `❌ Failed to save payroll data: ${data.message || "Unknown error"}`,
                        confirmButtonColor: '#dc3545'
                    });
                }
            })
            .catch(error => {
                console.error("Fetch Error:", error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '❌ An error occurred while saving payroll data.',
                    confirmButtonColor: '#dc3545'
                });
            });
        }
    });

    // Initialize the script when the child page is loaded
    function initializePayrollPage() {
        console.log("Initializing Payroll Page...");
        attachInputListeners();
    }

    // Use MutationObserver to detect when the child page is loaded
    const observer2 = new MutationObserver((mutationsList, observer2) => {
        const payrollForm = document.getElementById("payrollForm");
        if (payrollForm) {
            console.log("Payroll Form Found in DOM");
            initializePayrollPage();
            observer2.disconnect(); // Stop observing once the form is found
        }
    });

    // Start observing the parent container where the child page is loaded
    const parentContainer = document.getElementById("dashboard-content"); // Replace with the actual ID of your parent container
    if (parentContainer) {
        console.log("Parent Container Found:", parentContainer);
        observer2.observe(parentContainer, { childList: true, subtree: true });
    } else {
        console.error("Parent Container Not Found. Please check the ID of the parent container.");
    }
