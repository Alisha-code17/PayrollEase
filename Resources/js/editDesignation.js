$(document).ready(function() {
    $(document).on('click', '.edit-btn', function() {
        console.log("Edit Button Clicked!");
        var id = $(this).attr('data-id');
        var name = $(this).attr('data-name');
        var salary = $(this).attr('data-salary');
        console.log("ID:", id, "Name:", name, "Salary:", salary); 
        // Populate modal fields
        $('#editDesignationID').val(id);
        $('#editDesignationName').val(name);
        $('#editSalary').val(salary);
    });
});
$(document).on('submit', '#editDesignationForm', function(e) {
    e.preventDefault(); 
    $.ajax({
        url: 'edit_designation.php',
        type: 'POST',
        data: $(this).serialize(),
        success: function(response) {
        var data = JSON.parse(response);
            if (data.status === 'success') {
                // Update the table dynamically
                var row = $('#designation-row-' + data.data.id);
                row.find('td:nth-child(2)').text(data.data.designationName);
                row.find('td:nth-child(3)').text(data.data.salary);  
                Swal.fire({
                    icon: 'success',
                    title: 'Designation Updated Successfully!',
                    showConfirmButton: false,
                    timer: 2000
                });
                // Close modal
                $('#editDesignationModal').modal('hide');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: data.message
                });
            }
        },
        error: function(xhr, status, error) {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Something went wrong. Please try again later.'
            });
        }
    });
});

// Validation for Designation Name
document.getElementById("editDesignationName").addEventListener("input", function () {
    let designationName = this.value.trim();
    let regex = /^[a-zA-Z\s]+$/;
    let errorMsg = document.getElementById("edit-name-error"); 
    let submitBtn = document.getElementById("edit-designation-submit-btn");
    let designationID = document.getElementById("editDesignationID").value;
    errorMsg.style.display = "none";
    submitBtn.disabled = false;
    if (designationName === "") {
        return;
    }

    if (designationName.length > 50) {
        errorMsg.innerText = "Designation name must be under 50 characters!";
        errorMsg.style.display = "block";
        submitBtn.disabled = true;
        return;
    }
    if (!regex.test(designationName)) {
        errorMsg.innerText = "Only letters and spaces allowed!";
        errorMsg.style.display = "block";
        submitBtn.disabled = true;
        return;
    }
    // Check for existing designation (AJAX Request)
    fetch('check_edit_designation_name.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'designationName=' + encodeURIComponent(designationName) + '&id=' + encodeURIComponent(designationID)
    })
    .then(response => response.json())
    .then(data => {
        if (data.exists) {
            errorMsg.innerText = "This Designation name already exists!";
            errorMsg.style.display = "block";
            submitBtn.disabled = true;
        } else {
            errorMsg.style.display = "none";
            submitBtn.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
});
// Validation for Salary
document.getElementById("editSalary").addEventListener("input", function () {
    let salary = this.value.trim();
    let errorMsg = document.getElementById("edit-salary-error"); 
    let submitBtn = document.getElementById("edit-designation-submit-btn");
    errorMsg.style.display = "none";
    submitBtn.disabled = false;
    if (salary === "") {
        return; 
    }
    // Check if salary is a valid number (only digits & optional decimals allowed)
    let salaryRegex = /^(?!0\d)\d+(\.\d{1,2})?$/;
    if (!salaryRegex.test(salary)) {
        errorMsg.innerText = "Salary must be a valid number (up to 2 decimal places, no leading zeroes)!";
        errorMsg.style.display = "block";
        submitBtn.disabled = true;
        return;
    }

/*// Check minimum 5000 condition
    if (numericSalary < 5000) {
        errorMsg.innerText = "Salary must be at least 5000!";
        errorMsg.style.display = "block";
        submitBtn.disabled = true;
        return;
    }*/

    // Check if salary is within a reasonable range
    let numericSalary = parseFloat(salary);
    if (numericSalary < 5000) {
        errorMsg.innerText = "Salary must be at least 5000!";
        errorMsg.style.display = "block";
        submitBtn.disabled = true;
        return;
    }
    // If all checks pass, enable the submit button
    errorMsg.style.display = "none";
    submitBtn.disabled = false;
});