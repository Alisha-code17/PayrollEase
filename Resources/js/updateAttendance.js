
//  Handle Modal Pre-Filling 
$(document).on('click', '.edit-attendance-btn', function() { // Changed to '.edit-attendance-btn'
    console.log("Attendance Edit Button Clicked!");
    
    // Get data from the clicked button
    var id = $(this).data('id');
    var employee = $(this).data('employee');
    var present = $(this).data('present');
    var absent = $(this).data('absent');
    var overtime = $(this).data('overtime');
    var month = $(this).data('month').toLowerCase(); // Ensure lowercase match
    var year = $(this).data('year');

    console.log("Data:", {id, employee, present, absent, overtime, month, year}); // Debugging

    // Populate modal fields
    $('#updateModal #id').val(id);
    $('#updateModal #employee').val(employee);
    $('#updateModal #presentdays').val(present);
    $('#updateModal #absentdays').val(absent);
    $('#updateModal #overtimehours').val(overtime);
    $('#updateModal #month').val(month);
    $('#updateModal #year').val(year);
});

// Handle Form Submission 
$(document).on('click', '#updateModal .btn-primary', function() { // Attach to save button click
    var $modal = $('#updateModal');
    
    // Validate inputs
    var presentDays = $modal.find('#presentdays').val();
    var absentDays = $modal.find('#absentdays').val();
    var overtimeHours = $modal.find('#overtimehours').val();

    if (!presentDays || !absentDays ) {
        Swal.fire("Error!", "All fields are required!", "error");
        return;
    }

    // Prepare data
    var formData = {
        id: $modal.find('#id').val(),
        employee: $modal.find('#employee').val(),
        presentdays: presentDays,
        absentdays: absentDays,
        overtimehours: overtimeHours,
        month: $modal.find('#month').val(),
        year: $modal.find('#year').val()
    };

    // Submit via AJAX
    $.ajax({
        url: 'attendance_update_data.php',
        type: 'POST',
        data: formData,
        success: function(response) {
            if (response.trim() === 'success') { // Handle trailing whitespace
                Swal.fire({
                    icon: 'success',
                    title: 'Updated!',
                    text: 'Attendance record updated.',
                   iconColor: '#00a65a', // Custom success green
    confirmButtonColor: '#00a65a',
                    showConfirmButton: true,
        confirmButtonText: 'OK',
                }).then(() => {
                    $('#updateModal').modal('hide');
                    location.reload(); // Refresh the table
                });
            } else {
                Swal.fire("Error!", response, "error");
            }
        },
        error: function(xhr) {
            Swal.fire("Error!", "Connection failed: " + xhr.statusText, "error");
        }
    });
});
// Function to validate numeric input
function validateNumberInput(inputField, errorField, maxLength = null) {
    inputField.addEventListener("input", function () {
        const value = this.value;
        if (!/^\d*$/.test(value)) {
            errorField.innerText = " Only numbers are allowed!";
        } else if (maxLength && value.length > maxLength) {
            errorField.innerText = ` Maximum length is ${maxLength} digits!`;
        } else {
            errorField.innerText = "";
        }
        updateUpdateButtonState(); // 🔥 central check
    });
}

// Function to validate total days
function validateTotalDays() {
    const presentDays = document.getElementById("presentdays");
    const absentDays = document.getElementById("absentdays");
    const totalDaysError = document.getElementById("totalDaysError");
    const presentDaysError = document.getElementById("presentDaysError1");

    const present = parseInt(presentDays.value) || 0;
    const absent = parseInt(absentDays.value) || 0;

    if (present + absent > 30) {
        totalDaysError.innerText = " Total days cannot exceed 30!";
    } else {
        totalDaysError.innerText = "";
    }

    if (present < 15) {
        presentDaysError.innerText = " Present days cannot be less than 15!";
    } else {
        presentDaysError.innerText = "";
    }

    updateUpdateButtonState(); // 🔥 central check
}

// 🔥 Centralized button controller
function updateUpdateButtonState() {
    const updateBtn = document.getElementById("updateBtn");
    const presentError = document.getElementById("presentDaysError1").innerText;
    const absentError = document.getElementById("absentDaysError1").innerText;
    const overtimeError = document.getElementById("overtimeError1").innerText;
    const totalDaysError = document.getElementById("totalDaysError").innerText;

    if (presentError || absentError || overtimeError || totalDaysError) {
        updateBtn.disabled = true;
    } else {
        updateBtn.disabled = false;
    }
}

// Attach validation when modal opens
$(document).ready(function () {
    initValidation();
});

function initValidation() {
    const presentDays = document.getElementById("presentdays");
    const absentDays = document.getElementById("absentdays");
    const overtimeHours = document.getElementById("overtimehours");
    const presentError = document.getElementById("presentDaysError1");
    const absentError = document.getElementById("absentDaysError1");
    const overtimeError = document.getElementById("overtimeError1");

    if (presentDays && presentError) validateNumberInput(presentDays, presentError);
    if (absentDays && absentError) validateNumberInput(absentDays, absentError);
    if (overtimeHours && overtimeError) validateNumberInput(overtimeHours, overtimeError, 2);

    if (presentDays && absentDays) {
        presentDays.addEventListener("input", validateTotalDays);
        absentDays.addEventListener("input", validateTotalDays);
    }

    updateUpdateButtonState(); // set initial state
}


