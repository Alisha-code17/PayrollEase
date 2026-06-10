
document.addEventListener('DOMContentLoaded', function() {
    document.body.addEventListener('click', function(event) {
        if (event.target.closest('.delete-allowance-btn')) {
            const button = event.target.closest('.delete-allowance-btn');
            const allowanceID = button.getAttribute('data-id');
            const allowanceName = button.getAttribute('data-name');

            document.getElementById('allowanceID').value = allowanceID;
            document.getElementById('allowanceName').value = allowanceName;
            
            // Check if allowance exists in payrollprofile table
            checkAllowanceUsage(allowanceName);
        }
    });
});

function checkAllowanceUsage(allowanceName) {
    $.ajax({
        url: 'check_allowance_usage.php',
        type: 'POST',
        data: { allowanceName: allowanceName },
        dataType: 'json',
        success: function(response) {
            const deleteButton = document.getElementById('confirmDeleteAllowanceBtn');
            const errorMessage = document.querySelector('#deleteAllowanceModal #allowanceErrorMessage');

            if (response.isUsed) {
                deleteButton.disabled = true;
                errorMessage.style.display = 'block';
            } else {
                deleteButton.disabled = false;
                errorMessage.style.display = 'none';
            }
        },
        error: function() {
            console.error('Error checking allowance usage');
        }
    });
}

// Handle form submission (AJAX for deletion)
$("#deleteAllowanceForm").submit(function(event) {
    event.preventDefault();

    let allowanceID = $("#allowanceID").val();

    $.ajax({
        url: "delete_allowance.php",
        type: "POST",
        data: { allowanceID: allowanceID },
        dataType: "json",
        success: function(response) {
            if (response.status === "success") {
                // Remove the deleted allowance row from the table
                $("#allowance-row-" + allowanceID).remove();

                // Show success alert
                Swal.fire({
                    icon: "success",
                    title: "Success!",
                    text: "Allowance deleted successfully.",
                    showConfirmButton: false,
                    timer: 2000
                }).then(() => {
                    // Close modal after alert
                    $("#deleteAllowanceModal").modal("hide");
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
$('#deleteAllowanceModal').on('hidden.bs.modal', function() {
    $('#allowanceID').val('');
    $('#allowanceName').val('');
    $('#allowanceErrorMessage').hide();
    $('#confirmDeleteAllowanceBtn').prop('disabled', false);
});