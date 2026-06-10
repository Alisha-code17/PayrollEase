$(document).ready(function() {
    $(document).on('click', '.edit-allowance-btn', function() {
        console.log("Edit Allowance Button Clicked!");

        // Extract data attributes
        var id = $(this).attr('data-id');
        var name = $(this).attr('data-name');
        var description = $(this).attr('data-description');
        var amount = $(this).attr('data-amount');

        console.log("ID:", id, "Name:", name, "Description:", description, "Amount:", amount);

        // Populate modal fields
        $('#editAllowanceID').val(id);
        $('#editAllowanceName').val(name);
        $('#editDescription').val(description);
        $('#editAmount').val(amount);
    });
});

$(document).on('submit', '#editAllowanceForm', function(e) {
    e.preventDefault(); // Prevent form submission

    $.ajax({
        url: 'edit_allowance.php',
        type: 'POST',
        data: $(this).serialize(),
        success: function(response) {
            var data = JSON.parse(response);

            if (data.status === 'success') {
                // Update the table dynamically
                var row = $('#allowance-row-' + data.data.SalaryExtras_id);
                row.find('td:nth-child(2)').text(data.data.Name);  // Update Allowance Name
                row.find('td:nth-child(3)').text(data.data.Description);  // Update Description
                row.find('td:nth-child(4)').text(data.data.Amount);  // Update Amount

                // Show success message
                Swal.fire({
                    icon: 'success',
                    title: 'Allowance Updated Successfully!',
                    showConfirmButton: false,
                    timer: 2000
                });
                
                // Close modal
                $('#editAllowanceModal').modal('hide');
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

// Validation for Allowance Name
document.getElementById("editAllowanceName").addEventListener("input", function () {
    let allowanceName = this.value.trim();
    let regex = /^[a-zA-Z\s]+$/;
    let errorMsg = document.getElementById("edit-name-error");
    let submitBtn = document.getElementById("edit-allowance-submit-btn");
    let allowanceID = document.getElementById("editAllowanceID").value;

    // Reset
    errorMsg.style.display = "none";
    submitBtn.disabled = false;

    if (allowanceName === "") {
        return;
    }

    if (allowanceName.length > 50) {
        errorMsg.innerText = "Allowance name must be under 50 characters!";
        errorMsg.style.display = "block";
        submitBtn.disabled = true;
        return;
    }

    if (!regex.test(allowanceName)) {
        errorMsg.innerText = "Only letters and spaces allowed!";
        errorMsg.style.display = "block";
        submitBtn.disabled = true;
        return;
    }
    
    // Check for existing allowance (AJAX Request)
    fetch('check_edit_allowance_name.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'allowanceName=' + encodeURIComponent(allowanceName) + '&id=' + encodeURIComponent(allowanceID)
    })
    .then(response => response.json())
    .then(data => {
        if (data.exists) {
            errorMsg.innerText = "This Allowance name already exists!";
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
document.getElementById("editAmount").addEventListener("input", function () {
    let amount = this.value.trim();
    let errorMsg = document.getElementById("edit-amount-error");
    let submitBtn = document.getElementById("edit-allowance-submit-btn");

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
