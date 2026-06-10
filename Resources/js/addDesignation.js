document.addEventListener('DOMContentLoaded', function () {
    const addForm = document.querySelector('#addDesignationForm');

    if (addForm) {
        addForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(addForm);
            fetch('add_designation.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
    if (data.status === 'success') {
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: 'Designation Added Successfully',
            showConfirmButton: false,
            timer: 2000
        })
        $('#addDesignationModal').modal('hide');

const table = document.querySelector('#designationTable tbody');
const newRow = document.createElement('tr'); 
newRow.style.textAlign = "center"; 
newRow.innerHTML = `
    <td style="font-family: Roboto;">${data.data.id}</td>    
    <td style="font-family: Roboto;">${data.data.designationName}</td>
    <td style="font-family: Roboto;">${data.data.salary}</td>
    <td style="font-family: Roboto;">0</td>
    <td>
    <div style="display: flex; justify-content: center; gap: 10px;">-->
            <!-- Edit Button -->
           <!-- <button class="btn btn-primary edit-btn" title="Edit" data-toggle="modal" data-target="#editDesignationModal"
                data-id="${data.data.id}" 
                data-name="${data.data.designationName}"
                data-salary="${data.data.salary}"
                 style="margin-right: 13px; background-color:rgba(0,123,255,0); border:none; outline:none; box-shadow:none; height:31px; padding-top:2px; width:23px; margin-left:-6px;">
                <img src="../Resources/img/icons8-edit-23.png" alt="Edit">
            </button>-->
            <!-- Delete Button -->
            <!--<button class="btn btn-secondary delete-btn" title="Delete"
                data-id="${data.data.id}" 
                data-employees="0"
                data-toggle='modal' data-target='#deleteDesignationModal'  
                type="button" style="font-family: Roboto; background-color:rgba(0,123,255,0);border:none;outline:none;box-shadow:none;height:31px;width:50px;padding-top:2px;padding-left:8px;padding-right:11px;"> 
                <img src="../Resources/img/icon2.png" alt="Delete">
            </button>
        </div>
    </td>
`;
table.appendChild(newRow);
        addForm.reset();
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
// For Designation Name validation
document.getElementById("designationNameInput").addEventListener("input", function () {
    let designationName = this.value.trim();
    let errorMsg = document.getElementById("designationNameError");
    let submitBtn = document.getElementById("addDesignationSubmitBtn");
    errorMsg.style.display = "none";
    submitBtn.disabled = false;
    if (designationName === "") {
        return;
    }
    if (designationName.length > 50) {
        errorMsg.innerText = "Designation name must be under 50 characters!";
        errorMsg.style.display = "block";
        submitBtn.disabled = true;
        return;
    }
    let regex = /^[a-zA-Z\s]+$/;
    if (!regex.test(designationName)) {
        errorMsg.innerText = "Only letters and spaces allowed!";
        errorMsg.style.display = "block";
        submitBtn.disabled = true;
        return;
    }
    // Check for existing designation (AJAX Request)
    fetch('check_add_designation_name.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'designationName=' + encodeURIComponent(designationName)
    })
    .then(response => {
        // Check if response is valid JSON
        return response.json().catch(err => {
            console.error('Error parsing JSON:', err);
            return {}; 
        });
    })
    .then(data => {
        if (data.exists) {
            errorMsg.innerText = "This Designation name already exists!";
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
//salary checks for Add Designation model
document.getElementById("salaryInput").addEventListener("input", function () {
    let salary = this.value.trim();
    let errorMsg = document.getElementById("salaryError");
    let submitBtn = document.getElementById("addDesignationSubmitBtn");
    errorMsg.style.display = "none";
    submitBtn.disabled = false;

    if (salary === "") return;
    let salaryRegex = /^(?!0\d)\d+(\.\d{1,2})?$/;
    if (!salaryRegex.test(salary)) {
        errorMsg.innerText = "Salary must be a valid number (up to 2 decimal places, no leading zeroes)!";
        errorMsg.style.display = "block";
        submitBtn.disabled = true;
        return;
    }
    let numericSalary = parseFloat(salary);
    if (numericSalary < 5000) {
        errorMsg.innerText = "Salary must be at least 5000!";
        errorMsg.style.display = "block";
        submitBtn.disabled = true;
        return;
    }
    errorMsg.style.display = "none";
    submitBtn.disabled = false;
});