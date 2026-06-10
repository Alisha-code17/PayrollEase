function updateStatus(leaveId, status) {
        $.post('../Controllers/update_status.php', { leave_id: leaveId, status: status }, function(response) {
            $('#status-' + leaveId).text(status); // Update the status in the UI
            
            // Update Status Image Dynamically
            var imgPath = '';
            if (status == 'Pending') {
                imgPath = '../Resources/img/icons8-select-16.png';
            } else if (status == 'Approved') {
                imgPath = '../Resources/img/icons8-select-32 (2).png';
            } else if (status == 'Rejected') {
                imgPath = '../Resources/img/icons8-select-32 (1).png';
            }

            $('#status-img-' + leaveId).attr('src', imgPath);
            
            // Show SweetAlert on success
            Swal.fire({
                title: 'Success!',
                text: 'Leave status updated to ' + status,
                icon: 'success',
                confirmButtonText: 'OK'
            });
            
        }).fail(function() {
            // Show SweetAlert on error
            Swal.fire({
                title: 'Error!',
                text: 'Error updating status. Please try again later.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        });
    }

