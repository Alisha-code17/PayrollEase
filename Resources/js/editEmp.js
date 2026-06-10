
document.addEventListener("DOMContentLoaded", function () {
    document.body.addEventListener('click', function (event) {
        if (event.target.closest('.editEmp-btn')) { 
            let button = event.target.closest('.editEmp-btn'); 
            console.log("Edit Button Clicked!");

            // Get Employee Data from button attributes
            let empID = button.getAttribute('data-id');
            let firstName = button.getAttribute('data-firstname');
            let lastName = button.getAttribute('data-lastname');
            let email = button.getAttribute('data-email');
            let phone = button.getAttribute('data-phone');
            let address = button.getAttribute('data-address');
            let department = button.getAttribute('data-dept');
            let departmentName = button.getAttribute('data-dept-name');
            let designation = button.getAttribute('data-designation');
            let designationName = button.getAttribute('data-designation-name');
            let joiningDate = button.getAttribute('data-joining-date');
           
            let picture = button.getAttribute('data-picture');
            let username = button.getAttribute('data-username');
            let userRole = button.getAttribute('data-userrole');

            // Debugging: Show values in console
            console.log("Editing Employee: ", { empID, firstName, lastName, email, phone, address, department, designation, joiningDate, /*status,*/ picture });
            // Check if attributes are undefined
            if (!empID) {
                console.error("Button attributes are missing! Check HTML.");
                return;
            }
            // Set values in modal inputs
            document.getElementById('editEmpID').value = empID;
            document.getElementById('editFirstName').value = firstName;
            document.getElementById('editLastName').value = lastName;
            document.getElementById('editEmail').value = email;
            document.getElementById('editPhone').value = phone;
            document.getElementById('editAddress').value = address;
            document.getElementById('editJoiningDate').value = joiningDate;
          
            document.getElementById('username').value = username;
            document.getElementById('user_role').value = userRole;
            // Department & Designation Handling (Dropdowns)
            let deptDropdown = document.getElementById('editDept');
            let desigDropdown = document.getElementById('editDesignation');
            if (deptDropdown) deptDropdown.value = department;
            if (desigDropdown) desigDropdown.value = designation;
            // Set Profile Picture
            let empPic = document.getElementById('editEmpPicture');
            if (empPic) empPic.src = 'uploads/' + picture;
        }
    });
});
document.addEventListener("DOMContentLoaded", function () {
    const editForm = document.querySelector('#editEmpForm');

    if (editForm) {
        editForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(editForm); 

            fetch('edit_emp.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Employee Updated!',
                        text: data.message,
                        showConfirmButton: false,
                        timer: 2000
                    });
                    $('#editEmpModal').modal('hide');
                    // Update the employee data in the table
                    const row = document.querySelector(`tr[data-id="${data.employee_id}"]`);
if (row) {
    row.querySelector('.employee-name').innerText = `${data.firstName} ${data.lastName}`;
    row.querySelector('.employee-department').innerText = data.department;
    row.querySelector('.employee-designation').innerText = data.designation;
    if (data.picture) {
        row.querySelector('.employee-picture').src = `uploads/${data.picture}`;
    }
    row.querySelector('.employee-email').innerText = data.email;
    row.querySelector('.employee-phone').innerText = data.phone;
    row.querySelector('.employee-address').innerText = data.address;
    row.querySelector('.employee-joining-date').innerText = data.joiningDate;
}
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: data.message
                    });
                }
            })
            .catch(error => console.error('Error:', error));
        });
    }
});
        //first/Last name check
document.getElementById("editFirstName").addEventListener("input", validateName);
document.getElementById("editLastName").addEventListener("input", validateName);
function validateName() {
    let firstName = document.getElementById("editFirstName").value.trim();
    let lastName = document.getElementById("editLastName").value.trim();
    let regex = /^[a-zA-Z\s]+$/;
    let firstNameErrorMsg = document.getElementById("editFirstName-error-msg");
    let lastNameErrorMsg = document.getElementById("editLastName-error-msg");
    let submitBtn = document.querySelector("#editEmpForm button[type='submit']");

    if (firstName && !regex.test(firstName)) { 
        firstNameErrorMsg.innerText = "Only letters and spaces allowed!";
        firstNameErrorMsg.style.display = "block";
        submitBtn.disabled = true;
    } else {
        firstNameErrorMsg.style.display = "none";
    }
   
    if (lastName.trim() !== "" && !regex.test(lastName)) {
        lastNameErrorMsg.innerText = "Only letters and spaces allowed!";
        lastNameErrorMsg.style.display = "block";
        submitBtn.disabled = true;
    } else {
        lastNameErrorMsg.style.display = "none";
    }
    // Enable Submit button if both are valid and not empty
    if (regex.test(firstName) && regex.test(lastName)) {
        submitBtn.disabled = false;
    }
    // Allow Submit button if fields are empty 
    if (!firstName || !lastName) {
        submitBtn.disabled = false;
    }
}
    // Address Validation
document.getElementById("editAddress").addEventListener("input", validateAddress);
function validateAddress() {
    const addressInput = document.getElementById("editAddress");
    const errorMsg = document.getElementById("editAddress-error-msg");
    const submitBtn = document.querySelector("#editEmpForm button[type='submit']");
    const address = addressInput.value.trim();
    // Regex: No @ or $, min 5 characters, allows letters, numbers, comma, dot, dash, space
    const regex = /^[a-zA-Z0-9\s.,-]{5,}$/;
    if (!regex.test(address)) {
        errorMsg.innerText = "Please enter a valid address (min 5 characters, no special symbols like @ or $)";
        errorMsg.style.display = "block";
        submitBtn.disabled = true;
    } else {
        errorMsg.style.display = "none";
        submitBtn.disabled = false;
    }
}
   // Joining Date Validation
document.getElementById("editJoiningDate").addEventListener("change", validateJoiningDate);
function validateJoiningDate() {
    const joiningDateInput = document.getElementById("editJoiningDate");
    const errorMsg = document.getElementById("editJoiningDate-error-msg");
    const submitBtn = document.querySelector("#editEmpForm button[type='submit']");
    const selectedDate = new Date(joiningDateInput.value);
    const today = new Date();
    today.setHours(0, 0, 0, 0); 
    if (selectedDate > today) {
        errorMsg.innerText = "Joining date cannot be in the future!";
        errorMsg.style.display = "block";
        submitBtn.disabled = true;
    } else {
        errorMsg.style.display = "none";
        submitBtn.disabled = false;
    }
}
// Phone Number Validation
/*document.getElementById("editPhone").addEventListener("input", validatePhoneNumber);
function validatePhoneNumber() {
    const phoneInput = document.getElementById("editPhone");
    const errorMsg = document.getElementById("editPhone-error-msg");
    const submitBtn = document.querySelector("#editEmpForm button[type='submit']");
    let cleaned = phoneInput.value.trim().replace(/[\s\-]/g, '');
    // Convert +92 to 0
    if (cleaned.startsWith("+92")) {
        cleaned = "0" + cleaned.slice(3);
    }
    const isValid = /^\d{11}$/.test(cleaned);
    if (phoneInput.value.trim() === "") {
        errorMsg.innerText = "";
        submitBtn.disabled = false; 
    } else if (!isValid) {
        errorMsg.innerText = "Please enter a valid phone number!";
        errorMsg.style.display = "block";
        submitBtn.disabled = true;
    } else {
        errorMsg.innerText = "";
        errorMsg.style.display = "none";
        submitBtn.disabled = false;
    }
}*/

// Phone Number Validation
document.getElementById("editPhone").addEventListener("input", validateEditPhoneNumber);

function validateEditPhoneNumber() {
    const phoneInput = document.getElementById("editPhone");
    const errorMsg = document.getElementById("editPhone-error-msg");
    const submitBtn = document.querySelector("#editEmpForm button[type='submit']");

    let cleaned = phoneInput.value.trim().replace(/[\s\-]/g, '');

    // Convert +92 to 0
    if (cleaned.startsWith("+92")) {
        cleaned = "0" + cleaned.slice(3);
    }

    // Final pattern: exactly 11 digits
    const phonePattern = /^\d{11}$/;

    if (phoneInput.value.trim() === "") {
        errorMsg.innerText = "";
        errorMsg.style.display = "none";
        submitBtn.disabled = false;  // Empty phone allowed
    } else if (!phonePattern.test(cleaned)) {
        errorMsg.innerText = "Please enter a valid number";
        errorMsg.style.display = "block";
        submitBtn.disabled = true;
    } else {
        errorMsg.innerText = "";
        errorMsg.style.display = "none";
        submitBtn.disabled = false;
    }
}


//username Validation
document.getElementById("username").addEventListener("input", validateUsername);
function validateUsername() {
    const usernameInput = document.getElementById("username");
    const errorMsg = document.getElementById("username-error-msg");
    const submitBtn = document.querySelector("#editEmpForm button[type='submit']");
    const username = usernameInput.value.trim();
    if (username === "") {
        errorMsg.innerText = "";
        submitBtn.disabled = false;
        return;
    }
    if (username.length < 3) {
        errorMsg.innerText = "Username must be at least 3 characters long.";
        errorMsg.style.display = "block";
        submitBtn.disabled = true;
        return;
    }
    // AJAX request to check if username exists
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "check_username.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            const response = xhr.responseText.trim();
            if (response !== "") {
                errorMsg.innerText = response;
                errorMsg.style.display = "block";
                submitBtn.disabled = true;
            } else {
                errorMsg.innerText = "";
                errorMsg.style.display = "none";
                submitBtn.disabled = false;
            }
        }
    };
    xhr.send("username=" + encodeURIComponent(username));
}
     //Email field checks
document.getElementById("editEmail").addEventListener("input", validateEmail);
function validateEmail() {
    let email = document.getElementById("editEmail").value.trim();
    let emailErrorMsg = document.getElementById("editEmail-error-msg");
    let submitBtn = document.querySelector("#editEmpForm button[type='submit']");
    // Regular Expression for email validation
    let emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    // Check if the email format is correct
    if (email && !emailRegex.test(email)) {
        emailErrorMsg.innerText = "Invalid Email Format";
        emailErrorMsg.style.display = "block";
        submitBtn.disabled = true;
        return; 
    } else {
        emailErrorMsg.style.display = "none";
    }
    // Check if the email already exists in the database
    checkEmailExistence(email, submitBtn, emailErrorMsg);
}
function checkEmailExistence(email, submitBtn, emailErrorMsg) {
    //AJAX call to check email existence
    fetch('check_email.php', {
        method: 'POST',
        body: new URLSearchParams({ email: email })
    })
    .then(response => response.text())
    .then(data => {
        if (data) { // If email exists
            emailErrorMsg.innerText = data; 
            emailErrorMsg.style.display = "block";
            submitBtn.disabled = true; // Disable the submit button
        } else {
            emailErrorMsg.style.display = "none"; // Hide error if email is valid
            submitBtn.disabled = false; // Enable submit button if valid
        }
    })
    .catch(error => console.error('Error:', error));
}

//<!--Live Image Preview for editEmpModel Using J.S-->

document.getElementById('editPicture').addEventListener('change', function (e) {
    const file = e.target.files[0];
    const preview = document.getElementById('editEmpPicture');

    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            preview.src = e.target.result; // Show live preview
        };
        reader.readAsDataURL(file);
    }
});
