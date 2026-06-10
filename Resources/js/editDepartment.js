
$(document).ready(function() {
    $(document).on('click', '.editDep-btn', function() {
        console.log("Edit Department Button Clicked!"); 
        // Extract data attributes
        var id = $(this).attr('data-id');
        var name = $(this).attr('data-name');
        console.log("ID:", id, "Name:", name);
        // Populate modal fields
        $('#editDepartmentID').val(id);
        $('#edit-department').val(name);
        $('#editDepartmentModal').modal('show');
    });
});
document.addEventListener('DOMContentLoaded', function () {
    const editForm = document.querySelector('#edit-department-form');

    if (editForm) {
        editForm.addEventListener('submit', function (e) {
            e.preventDefault(); 
            const formData = new FormData(editForm);

            fetch('edit_department.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
    if (data.status === 'success') {
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: 'Department Updated Successfully',
            showConfirmButton: false,
            timer: 2000
        });
        $('#editDepartmentModal').modal('hide');
        // Find and update the row in the table
        const row = document.querySelector(`tr[data-id="${data.data.id}"]`);
        if (row) {
            row.querySelector('.department-name').innerText = data.data.departmentName;
            //Also update the edit button’s data-name
            const editBtn = row.querySelector('.editDep-btn');
            if (editBtn) {
                editBtn.setAttribute('data-name', data.data.departmentName);
            }
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
// live Validation and existing name check
document.getElementById("edit-department").addEventListener("input", function () {
    let departmentName = this.value.trim();
    let regex = /^[a-zA-Z\s]+$/;
    let errorMsg = document.getElementById("edit-error-msg");
    let submitBtn = document.getElementById("edit-submit-btn");
    let departmentID = document.getElementById("editDepartmentID").value;
    errorMsg.style.display = "none";
    submitBtn.disabled = false;

    if (departmentName === "") {
        return;
    }
    if (departmentName.length > 50) {
        errorMsg.innerText = "Department name must be under 50 characters!";
        errorMsg.style.display = "block";
        submitBtn.disabled = true;
        return;
    }
    if (!regex.test(departmentName)) {
        errorMsg.innerText = "Only letters and spaces allowed!";
        errorMsg.style.display = "block";
        submitBtn.disabled = true;
        return;
    }
    // Check for existing department
    fetch('check_edit_dep_name.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'departmentName=' + encodeURIComponent(departmentName) + '&id=' + encodeURIComponent(departmentID)
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