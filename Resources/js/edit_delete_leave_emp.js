    // script to display submit model to edit_leave.php
document.addEventListener('DOMContentLoaded', function () {
    const editForm = document.querySelector('#edit-leave-form');
    const monthInput = document.getElementById('edit-month');
    const monthError = document.getElementById('month-error');

    const validMonths = [
        'January', 'February', 'March', 'April', 'May', 'June',
        'July', 'August', 'September', 'October', 'November', 'December'
    ];

    let originalMonth = ''; // to track value when modal opens

    // Set original month value when modal is opened
    $('#editmodal').on('shown.bs.modal', function () {
        originalMonth = monthInput.value.trim().toLowerCase();
        monthError.style.display = 'none';
    });

    function capitalizeFirstLetter(str) {
        return str.charAt(0).toUpperCase() + str.slice(1).toLowerCase();
    }

    const currentMonthIndex = new Date().getMonth(); // 0 = Jan

    monthInput.addEventListener('input', function () {
        const inputValue = this.value.trim().toLowerCase();

        // Only validate if user changed the value
        if (inputValue === originalMonth) {
            monthError.style.display = 'none';
            return;
        }

        const matchedMonthIndex = validMonths.findIndex(
            month => month.toLowerCase() === inputValue
        );

        if (matchedMonthIndex === -1) {
            monthError.textContent = 'Please enter a valid month.';
            monthError.style.display = 'block';
        } else if (matchedMonthIndex < currentMonthIndex) {
            monthError.textContent = 'Please select the current or a future month only.';
            monthError.style.display = 'block';
        } else {
            monthError.style.display = 'none';
        }
    });

    editForm.addEventListener('submit', function (e) {
        e.preventDefault();

        const monthVal = monthInput.value.trim().toLowerCase();

        // Only validate if user changed the value
        if (monthVal !== originalMonth) {
            const monthIndex = validMonths.findIndex(
                month => month.toLowerCase() === monthVal
            );

            if (monthIndex === -1) {
                monthError.textContent = 'Please enter a valid month.';
                monthError.style.display = 'block';
                return;
            } else if (monthIndex < currentMonthIndex) {
                monthError.textContent = 'Please select the current or a future month only.';
                monthError.style.display = 'block';
                return;
            }
        }

        monthError.style.display = 'none'; // passed validation

        const formData = new FormData(editForm);

        // Debug
        for (let [key, value] of formData.entries()) {
            console.log(`${key}: ${value}`);
        }

        fetch('edit_leave.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            console.log('Data from PHP:', data);
            if (data.status === 'success') {
                const row = document.querySelector(`tr[data-id="${data.data.leave_id}"]`);
                if (row) {
                    const leaveTypeCell = row.querySelector('.leave-type');
                    const monthCell = row.querySelector('.leave-month');
                    const totalDaysCell = row.querySelector('.leave-days');
                    const descCell = row.querySelector('.leave-description');

                    if (leaveTypeCell) leaveTypeCell.textContent = getLeaveTypeName(data.data.leave_type);
                    if (monthCell) monthCell.textContent = data.data.month;
                    if (totalDaysCell) totalDaysCell.textContent = data.data.total_days;
                    if (descCell) descCell.textContent = data.data.description;
                }

                $('#editmodal').modal('hide');

                Swal.fire({
                    icon: 'success',
                    title: 'Leave Updated',
                    text: 'The leave has been updated successfully!',
                    timer: 7000,
                    showConfirmButton: false
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Update Failed',
                    text: data.message || 'Something went wrong.',
                    //timer: 1500,
                    showConfirmButton: true
                });
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Server Error',
                text: 'Could not connect to the server or something went wrong.',
                timer: 1500,
                showConfirmButton: false
            });
        });
    });

    function getLeaveTypeName(id) {
        switch (parseInt(id)) {
            case 1: return 'Sick';
            case 2: return 'Casual';
            case 3: return 'Other';
            default: return 'Unknown';
        }
    }
});
// <!--edit leave model another script to shwo prefilled fileds-->
$('#editmodal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);

    var leave_id = button.data('leave-id');
    var leave_type_id = button.data('leave-type-id'); // ✅ using ID now
    var total_days = button.data('total-days');

    var description = button.data('description');
    var month = button.data('month'); // format: '2025-08'

    // Convert 'YYYY-MM' to 'Month Name'
    var monthName = new Date(month + '-01').toLocaleString('default', { month: 'long' });

    var modal = $(this);
    modal.find('#edit-leave-id').val(leave_id);  // Use the updated data attribute
    modal.find('input[name="options"][value="' + leave_type_id + '"]').prop('checked', true);

    modal.find('#edit-total-days').val(total_days);

    modal.find('#edit_description').val(description);
    modal.find('#edit-month').val(monthName); // Show "August", etc.
});

$(document).ready(function () {
  // Handle open modal
  $(document).on('click', '.open-delete-modal', function () {
    const leaveId = $(this).data('del-leave-id');
    console.log("Leave ID fetched:", leaveId);
    $('#deleteLeaveId').val(leaveId);
  });

  // Handle delete confirm
  $('.delete-confirm-btn').on('click', function () {
    const leaveId = $('#deleteLeaveId').val();
    console.log("Sending Leave ID to the server:", leaveId);

    if (!leaveId) {
      Swal.fire('Error', 'Leave ID is missing!', 'error');
      return;
    }

    // AJAX call
    $.ajax({
      url: 'delete_leave.php',
      type: 'POST',
      data: { Leave_id: leaveId },
      dataType: 'json', // ✅ tell jQuery to parse JSON automatically

      success: function (parsedResponse) {
        console.log("Server Response:", parsedResponse);

        if (parsedResponse.status === 'success') {
          $('#deletemodal').modal('hide');
          Swal.fire({
            title: 'Deleted!',
            text: parsedResponse.message,
            icon: 'success',
            showConfirmButton: false,
            timer: 1500,
          }).then(() => {
            location.reload();
          });
        } else {
          Swal.fire('Error', parsedResponse.message, 'error');
        }
      },

      error: function (xhr, status, error) {
        console.error("AJAX Error:", status, error);
        Swal.fire('Error', 'Failed to connect to server.', 'error');
      }
    });
  });
});
