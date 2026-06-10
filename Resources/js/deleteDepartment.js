$(document).ready(function () {
    // Handle delete department button click
    $(document).on("click", ".delete-department-btn", function () {
        let departmentID = $(this).data("id");
        let departmentName = $(this).data("name");
        let totalEmployees = $(this).data("employees");
        // Reset modal data before setting new values
        $("#department_id").val("");  
        $("#deptName").text("");  
        $("#deptErrorMessage").hide().html("");  
        $("#confirmDeleteDeptBtn").prop("disabled", false);  
        // Now set new values
        $("#department_id").val(departmentID);
        $("#deptName").text(`${departmentName} Department`);

        if (parseInt(totalEmployees) > 0) {
            $("#deptErrorMessage").html(`Cannot delete <strong>${departmentName} Department</strong>, as it has <strong>${totalEmployees}</strong> employee(s) assigned to it.`).show();
            $("#confirmDeleteDeptBtn").prop("disabled", true);
        }
        $("#deleteModal").modal("show");
    });

    // Prevent default form submission & use AJAX
    $("#deleteDepartmentForm").submit(function (event) {
        event.preventDefault(); 
        let departmentID = $("#department_id").val();
        $.ajax({
            url: "delete_department.php",
            type: "POST",
            data: { department_id: departmentID },
            dataType: "json",
            success: function (response) {
                if (response.status === "success") {
                    Swal.fire({
                        icon: "success",
                        title: "Success!",
                        text: "Department deleted successfully.",
                        showConfirmButton: false,
                        timer: 2000
                    });
                    // Remove department row from table
                    $(`tr[data-id='${departmentID}']`).remove();
                    $("#deleteModal").modal("hide");
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
    // When modal is closed, reset the values
    $("#deleteModal").on("hidden.bs.modal", function () {
        $("#department_id").val("");
        $("#deptName").text("");
        $("#deptErrorMessage").hide().html("");
        $("#confirmDeleteDeptBtn").prop("disabled", false);
    });
});