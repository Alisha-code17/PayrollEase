document.addEventListener('DOMContentLoaded', function() {
    document.body.addEventListener('click', function(event) {
        if (event.target.closest('.delete-btn')) {
            const button = event.target.closest('.delete-btn');
            const designationID = button.getAttribute('data-id');
            const totalEmployees = button.getAttribute('data-employees');
            document.getElementById('designationID').value = designationID;
            const deleteButton = document.getElementById('confirmDeleteBtn');
            const errorMessage = document.querySelector('#deleteDesignationModal #errorMessage');

            if (parseInt(totalEmployees) > 0) {
                deleteButton.disabled = true;
                errorMessage.style.display = 'block'; 
            } else {
                deleteButton.disabled = false;
                errorMessage.style.display = 'none'; 
            }
        }
    });
});
// Handle form submission (AJAX for deletion)
$("#deleteDesignationForm").submit(function (event) {
        event.preventDefault(); 
        let designationID = $("#designationID").val();
        $.ajax({
            url: "delete_designation.php",
            type: "POST",
            data: { designationID: designationID },
            dataType: "json",
            success: function (response) {
                if (response.status === "success") {
                    // Remove the deleted designation row from the table
                    $("#designation-row-" + designationID).remove();

                    Swal.fire({
                        icon: "success",
                        title: "Success!",
                        text: "Designation deleted successfully.",
                        showConfirmButton: false,
                        timer: 2000
                    }).then(() => {
    $("#deleteDesignationModal").modal("hide");
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Error!",
                        text: response.message
                    });
                }
            },    
            error: function () {
                Swal.fire({
                    icon: "error",
                    title: "Error!",
                    text: "Something went wrong. Please try again!"
                });
            }
        });
    });
    // Reset modal when closed
    $('#deleteDesignationModal').on('hidden.bs.modal', function () {
        $('#designationID').val('');
    });