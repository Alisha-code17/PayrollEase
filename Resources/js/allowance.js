
$(document).ready(function() {
    // Function to validate only letters + spaces
function validateTitleInput(inputField, errorField) {
    inputField.addEventListener("input", function () {
        const value = this.value;
        if (!/^[A-Za-z\s]*$/.test(value)) {
            errorField.innerText = "Only letters and spaces are allowed!";
            document.getElementById("save-btn").disabled = true;
        } else {
            errorField.innerText = "";
            document.getElementById("save-btn").disabled = false;
        }
    });
}

// Function to validate description (letters, numbers, spaces only)
function validateDescriptionInput(inputField, errorField) {
    inputField.addEventListener("input", function () {
        const value = this.value;
        if (!/^[A-Za-z0-9\s]*$/.test(value)) {
            errorField.innerText = "Description cannot contain special characters!";
            document.getElementById("save-btn").disabled = true;
        } else {
            errorField.innerText = "";
            document.getElementById("save-btn").disabled = false;
        }
    });
}

// Function to validate amount (only numbers)
function validateAmountInput(inputField, errorField) {
    inputField.addEventListener("input", function () {
        const value = this.value;
        if (!/^\d*$/.test(value)) {
            errorField.innerText = "Only numbers are allowed!";
            document.getElementById("save-btn").disabled = true;
        } else {
            errorField.innerText = "";
            document.getElementById("save-btn").disabled = false;
        }
    });
}

// Initialize validation on page load
function initValidation(){

    const titleField = document.querySelector("input[name='name']");
    const descriptionField = document.querySelector("textarea[name='description']");
    const amountField = document.querySelector("input[name='amount']");

    const titleError = document.getElementById("titleError");
    const descriptionError = document.getElementById("descriptionError");
    const amountError = document.getElementById("amountError");

    if (titleField && titleError) validateTitleInput(titleField, titleError);
    if (descriptionField && descriptionError) validateDescriptionInput(descriptionField, descriptionError);
    if (amountField && amountError) validateAmountInput(amountField, amountError);
}
initValidation();
    $(document).on("submit", "#allowance-form", function(e) {
        e.preventDefault(); // Prevent default form submission
        
        var formData = $(this).serialize();
        var form = this; // Reference to the form
        
        $.post("../Controllers/insert_allowance.php", formData)
        .done(function(response) {
            // Show SweetAlert success message
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Record inserted successfully!',
                showConfirmButton: false,
                timer: 2000
            });
            
            // Reset the form after successful submission
            form.reset();
            
            // Optional: If you want to refresh part of the page
            // $("#some-container").load(" #some-container > *");
        })
        .fail(function(xhr, status, error) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Something went wrong: ' + error
            });
            console.error(xhr.responseText);
        });
    });
    

});