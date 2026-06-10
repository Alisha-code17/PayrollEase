document.getElementById('addLeaveForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);

    const totalDaysInput = document.getElementById('total_days');
    const monthInput = document.getElementById('month');

    const leaveDays = parseInt(totalDaysInput.value, 10);
    const selectedMonthStr = monthInput.value; // Format: YYYY-MM

    if (!selectedMonthStr || isNaN(leaveDays)) {
        Swal.fire({
            icon: 'warning',
            title: 'Incomplete Form',
            text: 'Please select a valid month and enter total leave days.',
        });
        return;
    }

    const [selectedYear, selectedMonth] = selectedMonthStr.split('-').map(Number); // month is 1-based here
    const today = new Date();
    const currentYear = today.getFullYear();
    const currentMonth = today.getMonth() + 1; // convert to 1-based for comparison
    const currentDay = today.getDate();

    if (selectedYear === currentYear && selectedMonth === currentMonth) {
        // Get days in current month
        const daysInMonth = new Date(currentYear, currentMonth, 0).getDate();
        const remainingDays = daysInMonth - currentDay;

        if (leaveDays > remainingDays) {
            Swal.fire({
                icon: 'warning',
                title: 'Too Many Leave Days',
                text: `You can only apply for up to ${remainingDays} day(s) for the rest of this month.`,
                showConfirmButton: true
            });
            return;
        }
    }

    // ✅ Proceed with AJAX if all checks pass
    fetch('add_leave.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        Swal.fire({
            icon: data.icon || 'info',
            title: data.title || 'Alert',
            text: data.text || 'Something happened.',
            //timer: 1500,
            showConfirmButton: true
        });

        if (data.icon === 'success') {
            form.reset();
            $('#leaveModal').modal('hide');
            // Optional reload: loadLeaveSummary();
        }
    })
    .catch(error => {
        console.error('AJAX Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'AJAX Failed',
            text: 'Unable to submit leave. Please try again.',
            timer: 1500,
            showConfirmButton: false
        });
    });
});