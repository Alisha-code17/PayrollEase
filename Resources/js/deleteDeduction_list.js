document.addEventListener('DOMContentLoaded', function() {
    document.body.addEventListener('click', function(event) {
        if (event.target.closest('.delete-deduction-btn')) {
            const button = event.target.closest('.delete-deduction-btn');
            const deductionID = button.getAttribute('data-id');
            const deductionName = button.getAttribute('data-name');

            document.getElementById('deductionID').value = deductionID;
            document.getElementById('deductionName').value = deductionName;
            
            // Check if deduction exists in payrollprofile table
            checkDeductionUsage(deductionName);
        }
    });
});

function checkDeductionUsage(deductionName) {
    $.ajax({
        url: 'check_deduction_usage.php',
        type: 'POST',
        data: { deductionName: deductionName },
        dataType: 'json',
        success: function(response) {
            const deleteButton = document.getElementById('confirmDeleteDeductionBtn');
            const errorMessage = document.querySelector('#deleteDeductionModal #deductionErrorMessage');

            if (response.isUsed) {
                deleteButton.disabled = true;
                errorMessage.style.display = 'block';
            } else {
                deleteButton.disabled = false;
                errorMessage.style.display = 'none';
            }
        },
        error: function() {
            console.error('Error checking deduction usage');
        }
    });
}

// Handle form submission (AJAX for deletion)
$("#deleteDeductionForm").submit(function(event) {
    event.preventDefault();

    let deductionID = $("#deductionID").val();

    $.ajax({
        url: "delete_deduction.php",
        type: "POST",
        data: { deductionID: deductionID },
        dataType: "json",
        success: function(response) {
            if (response.status === "success") {
                // Remove the deleted deduction row from the table
                $("#deduction-row-" + deductionID).remove();

                // Show success alert
                Swal.fire({
                    icon: "success",
                    title: "Success!",
                    text: "Deduction deleted successfully.",
                    showConfirmButton: false,
                    timer: 2000
                }).then(() => {
                    // Close modal after alert
                    $("#deleteDeductionModal").modal("hide");
                });
            } else {
                // Show error alert
                Swal.fire({
                    icon: "error",
                    title: "Error!",
                    text: response.message
                });
            }
        },    
        error: function() {
            Swal.fire({
                icon: "error",
                title: "Error!",
                text: "Something went wrong. Please try again!"
            });
        }
    });
});

// Reset modal when closed
$('#deleteDeductionModal').on('hidden.bs.modal', function() {
    $('#deductionID').val('');
    $('#deductionName').val('');
    $('#deductionErrorMessage').hide();
    $('#confirmDeleteDeductionBtn').prop('disabled', false);
});
