document.addEventListener("DOMContentLoaded", function () {
    // Function to Fetch Data
    function fetchData(url) {
        return fetch(url)
            .then(response => response.json())
            .catch(error => {
                console.error(`Fetch Error: ${url}`, error);
                return [];
            });
    }

    // Function to Populate Employee Dropdown with Unmarked IDs
    function populateEmployeeDropdown() {
    fetchData("fetch_unmarked_employee_ids.php").then(employeeData => {
        const empSelect = document.querySelector('#markAttendanceModal select[name="empid"]');
        const modal = document.getElementById("markAttendanceModal");
        const modalBody = modal.querySelector(".modal-body");
        const modalHeader = modal.querySelector(".inform");

        if (empSelect) {
            if (employeeData.length === 0) {
                modalHeader.innerHTML = `<p style="color: green; font-size: 16px; margin-top: 10px;">✅ All employees' attendance is marked.</p>`;
                modalBody.innerHTML = `<div style="text-align: center; font-size: 18px; color: green; margin-top: 20px;">
                                            ✅ All employees have already been marked for attendance.
                                        </div>`;
            } else {
                empSelect.innerHTML = `<option value="" selected disabled>Select Employee ID</option>` +
                    employeeData.map(emp => {
                        const empName = emp.EmployeeName && emp.EmployeeName.trim() !== "" ? emp.EmployeeName : "No Name Found";
                        return `<option value="${emp.Employee_id}" data-name="${empName}">ID ${emp.Employee_id}</option>`;
                    }).join("");
            }
        }
    });
}


    // Helper function to populate default month options
function populateDefaultMonthOptions() {
    const monthSelect = document.getElementById("month1");
    const yearSelect = document.getElementById("year1");
    
    if (!monthSelect || !yearSelect) return;

    const currentDate = new Date();
    
    // Get previous month (1 month back)
    const prevDate1 = new Date(currentDate);
    prevDate1.setMonth(currentDate.getMonth() - 1);
    const prevMonthName1 = prevDate1.toLocaleString('default', { month: 'long' }).toLowerCase();
    
    // Get previous-previous month (2 months back)
    /*const prevDate2 = new Date(currentDate);
    prevDate2.setMonth(currentDate.getMonth() - 2);
    const prevMonthName2 = prevDate2.toLocaleString('default', { month: 'long' }).toLowerCase();*/

    // Default to showing two previous months
    monthSelect.innerHTML = `
        <option value="${prevMonthName1}" selected>
            ${prevMonthName1.charAt(0).toUpperCase() + prevMonthName1.slice(1)}
        </option>
    `;

    yearSelect.innerHTML = `
        <option value="${currentDate.getFullYear()}" selected>
            ${currentDate.getFullYear()}
        </option>
    `;
}

    // Function to update month dropdown based on joining date
async function updateMonthDropdown(employeeId) {
    const monthSelect = document.getElementById("month1");
    const yearSelect = document.getElementById("year1");
    
    if (!employeeId || !monthSelect || !yearSelect) return;

    try {
        const response = await fetch(`get_employee_joining_date.php?empid=${employeeId}`);
        const data = await response.json();
        
        if (data.status !== 'success' || !data.joiningDate) {
            console.error("Error fetching joining date:", data.message || "Unknown error");
            populateDefaultMonthOptions();
            return;
        }

        const joiningDate = new Date(data.joiningDate);
        const currentDate = new Date();
        
        // Get previous month (1 month back)
        const prevDate1 = new Date(currentDate);
        prevDate1.setMonth(currentDate.getMonth() - 1);
        const prevMonthName1 = prevDate1.toLocaleString('default', { month: 'long' }).toLowerCase();
        
        // Get previous-previous month (2 months back)
        const prevDate2 = new Date(currentDate);
        prevDate2.setMonth(currentDate.getMonth() - 2);
        const prevMonthName2 = prevDate2.toLocaleString('default', { month: 'long' }).toLowerCase();

        // Check if employee joined in current month or previous month
        const isNewEmployee = (
            (joiningDate.getFullYear() === currentDate.getFullYear() && 
             joiningDate.getMonth() === currentDate.getMonth()) ||
            (joiningDate.getFullYear() === prevDate1.getFullYear() && 
             joiningDate.getMonth() === prevDate1.getMonth())
        );

        // Store this information on the select element for later use
        document.getElementById("empid").dataset.isNewEmployee = isNewEmployee;

        // Update month dropdown based on whether employee is new
        if (isNewEmployee) {
            // Only show previous month (1 month back) for new employees
            monthSelect.innerHTML = `
                <option value="${prevMonthName1}" selected>
                    ${prevMonthName1.charAt(0).toUpperCase() + prevMonthName1.slice(1)}
                </option>
            `;
        } else {
            // Always show only previous month (1 month back)
            monthSelect.innerHTML = `
                <option value="${prevMonthName1}" selected>
                    ${prevMonthName1.charAt(0).toUpperCase() + prevMonthName1.slice(1)}
                </option>
            `;
        }

        // Always set year to current year
        yearSelect.innerHTML = `
            <option value="${currentDate.getFullYear()}" selected>
                ${currentDate.getFullYear()}
            </option>
        `;

    } catch (error) {
        console.error("Error in updateMonthDropdown:", error);
        populateDefaultMonthOptions();
    }
}

    // Event listener for employee dropdown change
    document.addEventListener("change", function (event) {
        if (event.target.matches('select[name="empid"]')) {
            const selectedOption = event.target.selectedOptions[0];
            const empNameInput = document.querySelector('input[name="empname"]');
            
            if (empNameInput) {
                empNameInput.value = selectedOption.getAttribute("data-name") || "No Name Found";
            }

            // Get employee ID and update month dropdown
            const employeeId = event.target.value;
            if (employeeId) {
                updateMonthDropdown(employeeId);
            }
        }
    });

    // Function to Validate Numeric Input
    function validateNumberInput(inputField, errorField, maxLength = null) {
        inputField.addEventListener("input", function () {
            const value = this.value;
            if (!/^\d*$/.test(value)) {
                errorField.innerText = " Only numbers are allowed!";
                document.getElementById("markBtn").disabled = true;
            } else if (maxLength && value.length > maxLength) {
                errorField.innerText = ` Maximum length is ${maxLength} digits!`;
                document.getElementById("markBtn").disabled = true;
            } else {
                errorField.innerText = "";
                document.getElementById("markBtn").disabled = false;
            }
        });
    }

    // Function to Validate Total Days
    function validateTotalDays() {
        const presentDays = document.getElementById("presentdays1");
        const absentDays = document.getElementById("absentdays1");
        const totalDaysError = document.getElementById("daysError");
        const presentDaysError = document.getElementById("presentDaysError");

        const present = parseInt(presentDays.value) || 0;
        const absent = parseInt(absentDays.value) || 0;

        if (present + absent > 30) {
            totalDaysError.innerText = " Total days cannot exceed 30!";
            document.getElementById("markBtn").disabled = true;
        }else if (present < 15) {
            presentDaysError.innerText = " Present days cannot be less than 15!";
            document.getElementById("markBtn").disabled = true;
        } else {
            totalDaysError.innerText = "";
            document.getElementById("markBtn").disabled = false;
        }
    }

    // Initialize validation
    const presentDays = document.getElementById("presentdays1");
    const absentDays = document.getElementById("absentdays1");
    const overtimeHours = document.getElementById("overtimehours1");
    const presentError = document.getElementById("presentDaysError");
    const absentError = document.getElementById("absentDaysError");
    const overtimeError = document.getElementById("overtimeError");

    if (presentDays && presentError) validateNumberInput(presentDays, presentError);
    if (absentDays && absentError) validateNumberInput(absentDays, absentError);
    if (overtimeHours && overtimeError) validateNumberInput(overtimeHours, overtimeError, 2);

    if (presentDays && absentDays) {
        presentDays.addEventListener("input", validateTotalDays);
        absentDays.addEventListener("input", validateTotalDays);
    }

    // Initialize the form
    populateEmployeeDropdown();
    populateDefaultMonthOptions(); // Initialize month/year dropdowns
    // Function to update Mark button disabled state based on existing errors
    function updateMarkButtonDisabledState() {
        const markBtn = document.getElementById("markBtn");
        const presentError = document.getElementById("presentDaysError").innerText;
        const absentError = document.getElementById("absentDaysError").innerText;
        const overtimeError = document.getElementById("overtimeError").innerText;
        const daysError = document.getElementById("daysError").innerText;

        // Disable if any error exists
        if (presentError || absentError || overtimeError || daysError) {
            markBtn.disabled = true;
        } else {
            markBtn.disabled = false;
        }
    }

    // Attach to your existing input events
    if (presentDays) presentDays.addEventListener("input", updateMarkButtonDisabledState);
    if (absentDays) absentDays.addEventListener("input", updateMarkButtonDisabledState);
    if (overtimeHours) overtimeHours.addEventListener("input", updateMarkButtonDisabledState);

    // Call once on page load
    updateMarkButtonDisabledState();

    // Event Listener for Mark Attendance Button
    document.addEventListener("click", function (event) {
        if (event.target.id === "markBtn") {
            console.log("Mark Button Clicked!");

            setTimeout(() => {
                const empSelect = document.getElementById("empid");
                let empId = empSelect?.value || "";
                let presentDays = document.getElementById("presentdays1")?.value || "";
                let absentDays = document.getElementById("absentdays1")?.value || "";
                let overtimeHours = document.getElementById("overtimehours1")?.value || "";
                let month = document.getElementById("month1")?.value || "";
                let year = document.getElementById("year1")?.value || "";

                if (!empId || !presentDays || !month || !year) {
                    Swal.fire({
                        icon: "warning",
                        title: " Error!",
                        text: "All fields are required!",
                        confirmButtonColor: "#d33",
                    });
                    return;
                }

                // Check if this is a new employee (joined in current month)
                const isNewEmployee = empSelect.dataset.isNewEmployee === "true";

                let data = { 
                    empid: empId, 
                    presentdays: presentDays, 
                    absentdays: absentDays, 
                    overtimehours: overtimeHours, 
                    month: month, 
                    year: year,
                    isNewEmployee: isNewEmployee // Pass this flag to the server
                };

                fetch("insert_attendance.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify(data),
                })
                .then(response => response.json())
                .then(data => {
                    // Modify success/error messages for new employees
                    if (isNewEmployee && data.message.includes("previous month")) {
                        // For new employees, ignore "previous month" errors
                        Swal.fire({
                            icon: "success",
                            title: "Success!",
                            text: "Attendance marked successfully for new employee",
                            confirmButtonColor: "#28a745",
                        }).then(() => {
                            document.getElementById("markAttendanceForm").reset();
                            populateEmployeeDropdown();
                            location.reload(); // 🔄 Refresh page after OK button click

                        });
                    } else {
                        // Normal handling for other cases
                        Swal.fire({
                            icon: data.success ? "success" : "error",
                            title: data.success ? "Success!" : "Error!",
                            text: data.message,
                            confirmButtonColor: data.success ? "#28a745" : "#d33",
                        }).then(() => {
                            if (data.success) {
                                document.getElementById("markAttendanceForm").reset();
                                populateEmployeeDropdown();
                                location.reload(); // 🔄 Refresh page after OK button click

                            }
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: "error",
                        title: "❌ Error!",
                        text: "An error occurred while marking attendance.",
                        confirmButtonColor: "#d33",
                    });
                });
            }, 200);
        }
    });
});
