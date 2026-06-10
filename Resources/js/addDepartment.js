document.getElementById("add-department").addEventListener("input", function () {
    let departmentName = this.value.trim();
    let regex = /^[a-zA-Z\s]+$/;
    let errorMsg = document.getElementById("error-msg");
    let submitBtn = document.getElementById("submit-btn");
    errorMsg.style.display = "none";
    submitBtn.disabled = false;
    if (departmentName === "") {
        return;
    }
    // Check length
    if (departmentName.length > 50) {
        errorMsg.innerText = "Department name should not exceed 50 characters!";
        errorMsg.style.display = "block";
        submitBtn.disabled = true;
        return;
    }
    // Check invalid characters
    if (!regex.test(departmentName)) {
        errorMsg.innerText = "Only letters and spaces allowed!";
        errorMsg.style.display = "block";
        submitBtn.disabled = true;
        return;
    }
    // Check duplicate (AJAX)
    fetch('check_add_dep_name.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'departmentName=' + encodeURIComponent(departmentName)
    })
    .then(response => response.json())
    .then(data => {
        if (data.exists) {
            errorMsg.innerText = "This Department name already exists!";
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
document.getElementById("add-department-form").addEventListener("submit", function (e) {
    e.preventDefault();
    const formData = new FormData(this);

    fetch('add_department.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: data.message,
                showConfirmButton: false,
                timer: 2000
            });
            $('#addDepartmentModal').modal('hide');

            // Creating a new row with the same structure
            let newRow = `<tr data-id="${data.data.id}">
                <td style="font-family: Roboto;">${data.data.departmentId}</td> 
                <td class="department-name" style="font-family: Roboto;">${data.data.departmentName}</td> 
                <td style="font-family: Roboto;">${data.data.totalEmployees}</td> 
                <td style="width:204px;">                         
                    <button class="btn btn-primary editDep-btn" data-toggle="modal" data-target="#editDepartmentModal" type="submit"
                        style="margin-right: 13px; font-family: Roboto; color:White; background-color:rgba(0,123,255,0); border:none; outline:none; box-shadow:none; height:31px; padding-top:2px; width:23px; margin-left:-6px;"
                        data-id="${data.data.id}" 
                        data-name="${data.data.departmentName}"> 
                        <img src="../Resources/img/icons8-edit-23.png" alt="Icon 1">
                    </button>
                    
                    <button class="btn btn-secondary delete-department-btn" 
                        data-id="${data.data.id}"
                        data-name="${data.data.departmentName}" 
                        data-employees="${data.data.totalEmployees}" 
                        data-toggle='modal' data-target='#deleteModal' type="button" 
                        style="font-family: Roboto; background-color:rgba(0,123,255,0); border:none; outline:none; box-shadow:none; height:31px; width:50px; padding-top:2px; padding-left:8px; padding-right:11px;"> 
                        <img src="../Resources/img/icon2.png" alt="Icon 2">
                    </button>
                </td>
              </tr>`;
            // Append the new row inside the existing table body
            document.querySelector('#department-list').insertAdjacentHTML('beforeend', newRow);
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

document.addEventListener('click', function (event) {
    if (event.target.classList.contains('addDep-btn')) {
        let form = document.getElementById('add-department-form');
        form.reset();
        document.getElementById("error-msg").style.display = "none";
        document.getElementById("submit-btn").disabled = false;
        const modal = new bootstrap.Modal(document.getElementById('addDepartmentModal'));
        modal.show();
    }
});