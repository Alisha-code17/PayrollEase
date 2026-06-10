$(document).ready(function() {
    $(document).on("submit", "#deduction-form", function(e) {
        e.preventDefault(); // Prevent default form submission
        
        var formData = $(this).serialize();
        var form = this; // Reference to the form
        
        $.post("../Controllers/insert_deduction.php", formData)
        .done(function(response) {
            // Show SweetAlert success message
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Deduction added successfully!',
                showConfirmButton: false,
                timer: 2000
            });
            
            // Reset the form after successful submission
            form.reset();
            
            // Optional: Refresh the form content if needed
            $("#deduction-form").load("../Views/deduction.html #deduction-form > *");
        })
        .fail(function(xhr, status, error) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to add deduction: ' + error
            });
            console.error(xhr.responseText);
        });
    });
});