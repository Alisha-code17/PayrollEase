$(document).ready(function() {
    $(document).on('click', '.edit-deduction-btn', function() {
        console.log("Edit Deduction Button Clicked!");

        // Extract data attributes
        var id = $(this).attr('data-id');
        var name = $(this).attr('data-name');
        var description = $(this).attr('data-description');
        var amount = $(this).attr('data-amount');

        console.log("ID:", id, "Name:", name, "Description:", description, "Amount:", amount);

        // Populate modal fields
        $('#editDeductionID').val(id);
        $('#editDeductionName').val(name);
        $('#editDescription1').val(description);
        $('#editAmount1').val(amount);
    });
});

$(document).on('submit', '#editDeductionForm', function(e) {
    e.preventDefault(); // Prevent form submission

    $.ajax({
        url: 'edit_deduction.php',
        type: 'POST',
        data: $(this).serialize(),
        success: function(response) {
            var data = JSON.parse(response);

            if (data.status === 'success') {
                // Update the table dynamically
                var row = $('#deduction-row-' + data.data.SalaryDeductions_id);
                row.find('td:nth-child(2)').text(data.data.Name);  // Update Deduction Name
                row.find('td:nth-child(3)').text(data.data.Description);  // Update Description
                row.find('td:nth-child(4)').text(data.data.Amount);  // Update Amount

                // Show success message
                Swal.fire({
                    icon: 'success',
                    title: 'Deduction Updated Successfully!',
                    showConfirmButton: false,
                    timer: 2000
                });
                
                // Close modal
                $('#editDeductionModal').modal('hide');
            } else {
                // Show error message
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

// Validation for Deduction Name
document.getElementById("editDeductionName").addEventListener("input", function () {
    let deductionName = this.value.trim();
    let regex = /^[a-zA-Z\s]+$/;
    let errorMsg = document.getElementById("edit-name-error");
    let submitBtn = document.getElementById("edit-deduction-submit-btn");
    let deductionID = document.getElementById("editDeductionID").value;

    // Reset
    errorMsg.style.display = "none";
    submitBtn.disabled = false;

    if (deductionName === "") {
        return;
    }

    if (deductionName.length > 50) {
        errorMsg.innerText = "Deduction name must be under 50 characters!";
        errorMsg.style.display = "block";
        submitBtn.disabled = true;
        return;
    }

    if (!regex.test(deductionName)) {
        errorMsg.innerText = "Only letters and spaces allowed!";
        errorMsg.style.display = "block";
        submitBtn.disabled = true;
        return;
    }
    
    // Check for existing deduction (AJAX Request)
    fetch('check_edit_deduction_name.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'deductionName=' + encodeURIComponent(deductionName) + '&id=' + encodeURIComponent(deductionID)
    })
    .then(response => response.json())
    .then(data => {
        if (data.exists) {
            errorMsg.innerText = "This Deduction name already exists!";
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

// Validation for Amount
document.getElementById("editAmount1").addEventListener("input", function () {
    let amount = this.value.trim();
    let errorMsg = document.getElementById("edit-amount-error");
    let submitBtn = document.getElementById("edit-deduction-submit-btn");

    // Reset error message and button
    errorMsg.style.display = "none";
    submitBtn.disabled = false;

    // Check if amount is empty
    if (amount === "") {
        return; 
    }

    // Check if amount is a valid number
    let amountRegex = /^(?!0\d)\d+(\.\d{1,2})?$/;
    if (!amountRegex.test(amount)) {
        errorMsg.innerText = "Amount must be a valid number (up to 2 decimal places, no leading zeroes)!";
        errorMsg.style.display = "block";
        submitBtn.disabled = true;
        return;
    }

    // Check if amount is positive
    let numericAmount = parseFloat(amount);
    if (numericAmount <= 0) {
        errorMsg.innerText = "Amount must be a positive number!";
        errorMsg.style.display = "block";
        submitBtn.disabled = true;
        return;
    }

    // If all checks pass
    errorMsg.style.display = "none";
    submitBtn.disabled = false;
});