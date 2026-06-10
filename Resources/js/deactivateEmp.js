document.addEventListener('DOMContentLoaded', function () {
        document.body.addEventListener('click', function (event) {
            let target = event.target;
            if (target.tagName === 'IMG') {
                target = target.closest('.deleteEmp-btn');
            }
            if (target && target.classList.contains('deleteEmp-btn')) {
                const empId = target.getAttribute('data-id');
                document.getElementById('deleteEmpID').value = empId;
                $('#deleteEmpModal').modal('show'); 
            }
        });
    });
$(document).ready(function () {
    $("#deleteEmpForm").submit(function (event) {
        event.preventDefault(); 
        let empID = $("#deleteEmpID").val();
        $.ajax({
            url: "delete_emp.php",
            type: "POST",
            data: { employee_id: empID },
            dataType: "json",
            success: function (response) {
                if (response.status === "success") {
                    Swal.fire({
                        icon: "success",
                        title: "Success!",
                        text: "Employee has been deactivated.",
                        showConfirmButton: false,
                        timer: 2000
                    });
                    //remove employee row from table
                    $(`tr[data-id='${empID}']`).remove();
                    $("#deleteEmpModal").modal("hide");
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
});