$(document).ready(function () {

 // Password validation for 'New Password'
 $('#new_password').on('input', function () {
        const password = $(this).val();
        const errorMsg = $('#newPaswordError');
        const submitBtn = $('#changePasswordBtn');
        // Password must be at least 5 characters, 1 uppercase letter, and 1 number
        const isValid = /^(?=.*[A-Z])(?=.*\d).{5,}$/.test(password);
        if (!isValid) {
            errorMsg.text('Password must be at least 5 characters long and contain at least 1 uppercase letter and 1 number.');
            errorMsg.show();
            submitBtn.prop('disabled', true); // Disable submit button
        } else {
            errorMsg.hide();
            submitBtn.prop('disabled', false); // Enable submit button
        }
        // Trigger confirm password validation to check if both passwords match
        checkPasswordMatch();
    });
    // Confirm Password validation
    $('#confirm_password').on('input', function () {
        checkPasswordMatch();
    });

    // Function to check if passwords match
    function checkPasswordMatch() {
        const newPassword = $('#new_password').val();
        const confirmPassword = $('#confirm_password').val();
        const confirmErrorMsg = $('#confirmNewPasswordError');
        const submitBtn = $('#changePasswordBtn');

        // Check if both password fields are filled before checking for match
        if (newPassword && confirmPassword) {
            if (confirmPassword !== newPassword) {
                confirmErrorMsg.text('Passwords do not match.');
                confirmErrorMsg.show();
                submitBtn.prop('disabled', true); // Disable submit button
            } else {
                confirmErrorMsg.hide();
                // Check if the new password validation passed and enable submit button
                if (/^(?=.*[A-Z])(?=.*\d).{5,}$/.test(newPassword)) {
                    submitBtn.prop('disabled', false);
                }
            }
        } else {
            // Hide the error message if either of the fields is empty
            confirmErrorMsg.hide();
            submitBtn.prop('disabled', true); // Disable submit button until both fields are filled
        }
    }
        // Form submission with AJAX
    $('#changePasswordForm').on('submit', function (e) {
        e.preventDefault(); // stop page reload
        const formData = $(this).serialize();
        $.ajax({
            url: 'change_password.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    $('#changePasswordModal').modal('hide');

                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 2000
                    });

                    $('#changePasswordForm')[0].reset();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops!',
                        text: response.message
                    });
                }
            },
            error: function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Something went wrong!'
                });
            }
        });
    });
});