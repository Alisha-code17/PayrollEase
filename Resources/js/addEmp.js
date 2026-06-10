let emailTimer, usernameTimer;
    function updateSaveButtonState() {
        const saveBtn = document.getElementById("AddEmpsaveBtn");
        const emailError = document.getElementById("emailError").innerText.trim();
        const usernameError = document.getElementById("usernameError").innerText.trim();
        const passwordStrength = document.getElementById("password-strength").innerText.trim();
        const confirmPasswordMsg = document.getElementById("confirmPasswordMsg").innerText.trim();
        const joiningDateError = document.getElementById("joiningDateError").innerText.trim();
        const addressError = document.getElementById("addressError").innerText.trim();
        const AddEmpfirstNameError = document.getElementById("AddEmpfirstNameError").innerText.trim();
        const AddEmplastNameError = document.getElementById("AddEmplastNameError").innerText.trim();
        const AddEmpPhoneError = document.getElementById("AddEmpPhoneError").innerText.trim();
        const hasError = (
            emailError !== "" ||
            usernameError !== "" ||
            passwordStrength.includes("must be") ||
            confirmPasswordMsg.includes("do not match") ||
            joiningDateError !== "" ||
            addressError !== "" ||
            AddEmpfirstNameError !== "" ||
            AddEmplastNameError !== "" ||
            AddEmpPhoneError !== "" 

        );
        saveBtn.disabled = hasError;
    }
    // Email check 
    function checkEmailFormat() {
    const email = document.getElementById("email").value.trim();
    const emailError = document.getElementById("emailError");
    if (email === "") {
        emailError.innerHTML = "";
        updateSaveButtonState();
        return;
    }
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email)) {
        emailError.innerHTML = "Invalid email format!";
        emailError.style.color = "red";
    } else {
        if (!emailError.innerHTML.includes("already")) {
            emailError.innerHTML = "";
        }
    }
    updateSaveButtonState();
}

function checkEmailAjax() {
    clearTimeout(emailTimer);
    const email = document.getElementById("email").value.trim();
    const emailError = document.getElementById("emailError");

    if (email === "") {
        emailError.innerHTML = "";
        updateSaveButtonState();
        return;
    }
    emailTimer = setTimeout(function () {
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "check_email.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                const response = xhr.responseText.trim();
                if (response !== "") {
                    emailError.innerHTML = response;
                    emailError.style.color = "red";
                } else {
                    emailError.innerHTML = "";
                }
                updateSaveButtonState();
            }
        };
        xhr.send("email=" + encodeURIComponent(email));
    }, 800);
}
    // username check 
    function checkUsername() {
        clearTimeout(usernameTimer);
        const username = document.getElementById("username").value.trim();
        const errorMsg = document.getElementById("usernameError");
        usernameTimer = setTimeout(function () {
            if (username === "") {
                errorMsg.innerHTML = "";
                updateSaveButtonState();
                return;
            }
            if (username.length < 3) {
                errorMsg.innerHTML = "Username must be at least 3 characters long.";
                errorMsg.style.color = "red";
                updateSaveButtonState();
                return;
            }
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "check_username.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    const response = xhr.responseText.trim();
                    if (response !== "") {
                        errorMsg.innerHTML = response;
                        errorMsg.style.color = "red";
                    } else {
                        errorMsg.innerHTML = "";
                    }
                    updateSaveButtonState();
                }
            };
            xhr.send("username=" + encodeURIComponent(username));
        }, 800);
    }
//Password strength + confirm password re-check on password input
document.addEventListener("input", function (e) {
    const password = document.getElementById("password").value;
    const confirmPassword = document.getElementById("confirm_password").value;
    const strengthMessage = document.getElementById("password-strength");
    const confirmPasswordMsg = document.getElementById("confirmPasswordMsg");
    if (e.target && e.target.id === "password") {
        const lengthCheck = password.length >= 5;
        const uppercaseCheck = /[A-Z]/.test(password);
        const numberCheck = /[0-9]/.test(password);
        if (password === "") {
            strengthMessage.innerHTML = "";
        } else if (lengthCheck && uppercaseCheck && numberCheck) {
            strengthMessage.innerHTML = "Strong Password";
            strengthMessage.style.color = "green";
        } else {
            strengthMessage.innerHTML = "Password must be at least 5 characters long and contain at least 1 uppercase letter and 1 number.";
            strengthMessage.style.color = "red";
        }
    }
    // Confirm password logic (run in both password & confirm fields)
    if (confirmPassword === "") {
        confirmPasswordMsg.innerHTML = "";
    } else if (password !== confirmPassword) {
        confirmPasswordMsg.innerHTML = "Passwords do not match!";
        confirmPasswordMsg.style.color = "red";
    } else {
        confirmPasswordMsg.innerHTML = "Passwords match!";
        confirmPasswordMsg.style.color = "green";
    }
    updateSaveButtonState();
});
        //Joining Date check 
    document.addEventListener("change", function (e) {
    if (e.target && e.target.id === "joining_date") {
        const joiningDate = new Date(e.target.value);
        const today = new Date();
        const error = document.getElementById("joiningDateError");

        if (e.target.value && joiningDate > today) {
        error.innerText = "Joining date cannot be in the future!";
        } else {
        error.innerText = "";
        }

        updateSaveButtonState();
    }
});
    // Address check 
    function checkAddressFormat() {
    const address = document.getElementById("address").value.trim();
    const addressError = document.getElementById("addressError");
    if (address === "") {
        addressError.innerHTML = ""; 
        updateSaveButtonState();
        return;
    }
    const addressPattern = /^[a-zA-Z0-9\s,.\-\/#]{5,}$/;
    if (!addressPattern.test(address)) {
        addressError.innerHTML = "Please enter a valid address (min 5 characters, no special symbols like @ or $)";
        addressError.style.color = "red";
    } else {
        addressError.innerHTML = "";
    }
    updateSaveButtonState();
}
    //1st and last Name check 
function validateNameField(fieldId, errorId) {
    const input = document.getElementById(fieldId).value.trim();
    const errorMsg = document.getElementById(errorId);
    const pattern = /^[A-Za-z\s]+$/;
    if (input === "") {
        errorMsg.innerText = "";
    } else if (!pattern.test(input)) {
        errorMsg.innerText = "Only letters and spaces allowed!";
    } else {
        errorMsg.innerText = "";
    }
    updateSaveButtonState();
}
// Attach real-time validation to first and last name
document.addEventListener("input", function (e) {
    if (e.target.id === "first_name") {
        validateNameField("first_name", "AddEmpfirstNameError");
    }
    if (e.target.id === "last_name") {
        validateNameField("last_name", "AddEmplastNameError");
    }
});
    // Phone No check
  document.addEventListener("input", function (e) {
  if (e.target && e.target.id === "phone") {
    const phoneInputRaw = e.target.value.trim();
    const phoneError = document.getElementById("AddEmpPhoneError");
    let cleaned = phoneInputRaw.replace(/[\s\-]/g, '');

    if (cleaned.startsWith("+92")) {
      cleaned = "0" + cleaned.slice(3);
    }
    // Final pattern: exactly 11 digits, starting with any number
    const phonePattern = /^\d{11}$/;
    if (phoneInputRaw === "") {
      phoneError.innerText = "";
    } else if (!phonePattern.test(cleaned)) {
      phoneError.innerText = "Please enter a valid number";
    } else {
      phoneError.innerText = "";
    }
    updateSaveButtonState(); 
  }
});
    //Image preview
    document.addEventListener('change', function (event) {
        if (event.target && event.target.id === 'avatar') {
            const reader = new FileReader();
            reader.onload = function () {
                document.getElementById('preview').src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    });

    
document.addEventListener("submit", function (e) {
    if (e.target && e.target.id === "add-employee-form") {
        e.preventDefault(); 
        const form = e.target;
        const formData = new FormData(form);
        fetch("add.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: data.message,
                    showConfirmButton: false,
                    timer: 2000
                });
                form.reset();
                // Clear image preview
                const preview = document.getElementById("preview");
                if (preview) preview.src = "uploads/default.jpg";
                document.getElementById("AddEmpsaveBtn").disabled = false;
                document.getElementById("password-strength").innerHTML = '';               
                document.getElementById("confirmPasswordMsg").innerHTML = '';               
                document.getElementById("password").classList.remove("is-valid", "is-invalid");
                document.getElementById("confirm_password").classList.remove("is-valid", "is-invalid");
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: data.message || "Something went wrong."
                });
            }
        })
        .catch(error => {
            console.error("Error:", error);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Failed to submit form. Try again later.'
            });
        });
    }
});

//<!--Handle Cancel Button Click-->

document.addEventListener("click", function (e) {
    if (e.target && e.target.id === "cancelBtn") {
        e.preventDefault(); 
        resetFormState();  
    }
});
function resetFormState() {
    const form = document.getElementById("add-employee-form");
    form.reset();  
    ["emailError", "usernameError", "password-strength", "confirmPasswordMsg", "joiningDateError", "addressError", "AddEmpfirstNameError" , "AddEmplastNameError", "AddEmpPhoneError"].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.innerHTML = "";
    });
    // Clear image preview and show default image
    const preview = document.getElementById("preview");
    if (preview) preview.src = "uploads/default.jpg";
    const avatar = document.getElementById("avatar");
    if (avatar) avatar.value = "";
    // Enable Save button
    const saveBtn = document.getElementById("AddEmpsaveBtn");
    if (saveBtn) saveBtn.disabled = false;
    ["password", "confirm_password"].forEach(id => {
        const input = document.getElementById(id);
        if (input) input.classList.remove("is-valid", "is-invalid");
    });
}

