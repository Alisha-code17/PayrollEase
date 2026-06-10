document.addEventListener("change", function (e) {
    if (e.target && e.target.id === "year") {
        const selectedYear = e.target.value;
        // Update heading dynamically
    document.getElementById("attendanceTitle").textContent = "Attendance - " + selectedYear;
        fetch("get_emp_attendance.php?year=" + selectedYear)
            .then(response => response.json())
            .then(data => {
                // Update Summary Cards
                document.getElementById("empPresentDays").textContent = data.summary.present || "--";
                document.getElementById("empAbsentDays").textContent = data.summary.absent || "--";
                document.getElementById("overtimeHours").textContent = (data.summary.overtime || "--") + " hrs";
                // Update Attendance Table
                const tbody = document.querySelector("table tbody");
                tbody.innerHTML = "";

                if (data.attendance.length > 0) {
                    data.attendance.forEach(row => {
                        const monthName = new Date(`2024-${row.Month}-01`).toLocaleString('default', { month: 'long' });
                        tbody.innerHTML += `
                            <tr>
                                <td>${monthName}</td>
                                <td style="text-align:center;">${row.PresentDays}</td>
                                <td style="text-align:center;">${row.AbsentDays}</td>
                                <td style="text-align:center;">${row.OvertimeHours} hrs</td>
                            </tr>
                        `;
                    });
                } else {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="4" class="text-center text-danger">No Attendance Records Found</td>
                        </tr>
                    `;
                }
            })
            .catch(error => {
                console.error("Error loading attendance data:", error);
            });
    }
});