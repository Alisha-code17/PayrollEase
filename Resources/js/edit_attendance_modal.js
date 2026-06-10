// 1. First, ensure jQuery is loaded
if (typeof jQuery == 'undefined') {
    console.error('jQuery is not loaded!');
}

// 2. Create a dedicated modal controller
function initEditAttendanceModal() {
    console.log('Initializing edit attendance modal...');
    
    // Clean up previous bindings
    $(document).off('show.bs.modal.editAttendance');
    $('#updateModal .btn-primary').off('click.saveAttendance');
    
    // Modal show handler with event delegation
    $(document).on('show.bs.modal.editAttendance', '#updateModal', function(event) {
        console.log('Edit modal triggered');
        
        const button = $(event.relatedTarget);
        console.log('Button data:', button.data());
        
        // Safely get data with defaults
        const data = {
            id: button.data('id') || '',
            employee: button.data('employee') || '',
            present: button.data('present') || 0,
            absent: button.data('absent') || 0,
            overtime: button.data('overtime') || 0,
            month: (button.data('month') || 'january').toString().toLowerCase(),
            year: button.data('year') || new Date().getFullYear()
        };
        
        // Set values with explicit selectors
        $('#updateModal #id').val(data.id);
        $('#updateModal #employee').val(data.employee);
        $('#updateModal #presentdays').val(data.present);
        $('#updateModal #absentdays').val(data.absent);
        $('#updateModal #overtimehours').val(data.overtime);
        $('#updateModal #month').val(data.month);
        $('#updateModal #year').val(data.year);
    });
    
    // Save button handler
    $('#updateModal .btn-primary').on('click.saveAttendance', function() {
        console.log('Save button clicked');
        
        const data = {
            id: $('#updateModal #id').val(),
            employee: $('#updateModal #employee').val(),
            presentdays: $('#updateModal #presentdays').val(),
            absentdays: $('#updateModal #absentdays').val(),
            overtimehours: $('#updateModal #overtimehours').val(),
            month: $('#updateModal #month').val(),
            year: $('#updateModal #year').val()
        };
        
        // Validation
        if (!data.id || !data.employee) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Missing required fields!',
                showConfirmButton: true, // Keeps the alert open until user interacts
                timer: null // Disables auto-close
            });
            return;
        }
        
        // AJAX call
        $.ajax({
            url: 'attendance_update_data.php',
            type: 'POST',
            data: data,
            success: function(response) {
                if (response === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Attendance updated successfully!',
                        confirmButtonColor: '#28a745',
                        timer: null // Disables auto-close
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Update failed: ' + response,
                        showConfirmButton: true, // Keeps the alert open until user interacts
                        timer: null // Disables auto-close
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Server request failed',
                    showConfirmButton: true, // Keeps the alert open until user interacts
                    timer: null // Disables auto-close
                });
            }
        });
    });
}

// 3. Initialize when page loads and after AJAX
$(document).ready(function() {
    initEditAttendanceModal();
    
    // Reinitialize after AJAX loads
    $(document).ajaxComplete(function() {
        setTimeout(initEditAttendanceModal, 100);
    });
});
