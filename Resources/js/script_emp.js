function initializeEmployeeDashboard() {
    console.log("my function is load");
    function drawEmployeeMonthlyEarningsChart() {
        const ctx = document.getElementById('leaveAreaChart_page')?.getContext('2d');
        const noDataMessage = document.getElementById('noDataMessage_emp');
        let chartInstance = null;

        if (!ctx) {
            console.warn("Chart canvas not found");
            return;
        }

        function createChart(data) {
            if (chartInstance) chartInstance.destroy();

            chartInstance = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 
                            'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                        label: 'Total Earnings',
                        data: data,
                        backgroundColor: 'rgba(54, 162, 235, 0.7)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: { y: { beginAtZero: true } },
                    plugins: {
                        legend: { display: false },
                        title: {
                            display: true,
                            text: 'Total Earnings (' + new Date().getFullYear() + ')',
                            font: { size: 16 }
                        }
                    }
                }
            });

            if (noDataMessage) {
                noDataMessage.style.display = data.every(val => val === 0) ? 'flex' : 'none';
            }
        }

        fetch('get_monthly_earnings.php')
            .then(response => response.json())
            .then(data => createChart(data))
            .catch(() => createChart(new Array(12).fill(0)));
    }

    // 2. Payroll Summary
    function loadEmployeePayrollSummary() {
        fetch('../Controllers/emp_payroll_dash.php')
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    document.getElementById('basicSalary_emp').textContent = "Not created yet";
                    document.getElementById('bonus_emp').textContent = "Not created yet";
                    document.getElementById('totalAllowance_emp').textContent = "Not created yet";
                    document.getElementById('deductions_emp').textContent = "Not created yet";
                    document.getElementById('netSalary_emp').textContent = "Not created yet";
                    return;
                }
                else{
                document.getElementById('basicSalary_emp').textContent = data.BasicSalary;
                document.getElementById('bonus_emp').textContent = data.Bonus;
                document.getElementById('totalAllowance_emp').textContent = data.TotalAllowance;
                document.getElementById('deductions_emp').textContent = data.TotalDeductions;
                document.getElementById('netSalary_emp').textContent = data.NetSalary;
                }
            })
            .catch(() => {
                document.getElementById('netSalary_emp').textContent = "Data unavailable";
            });
    }

    // 3. Attendance Summary
    function loadEmployeeAttendanceSummary() {
        fetch('../Controllers/att_emp_dash.php')
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then(data => {
                // Get all elements
                const totalDaysEl = document.getElementById('totalDays_emp');
                const presentDaysEl = document.getElementById('presentDays_emp');
                const absentDaysEl = document.getElementById('absentDays_emp');
                const progressBarEl = document.getElementById('attendanceProgress_emp'); // The progress bar div
                const percentageEl = document.getElementById('attendancePercentage_emp');
                const progressTextEl = progressBarEl.querySelector('span.visually-hidden'); // The hidden span inside progress bar
    
                // Handle "Not created yet" case
                if (data.TotalDays === 'Not created yet') {
                    if (totalDaysEl) totalDaysEl.textContent = 'Not created yet';
                    if (presentDaysEl) presentDaysEl.textContent = 'Not created yet';
                    if (absentDaysEl) absentDaysEl.textContent = 'Not created yet';
                    
                    if (progressBarEl) {
                        progressBarEl.style.width = '0%';
                        progressBarEl.setAttribute('aria-valuenow', 0);
                    }
                    
                    if (percentageEl) percentageEl.textContent = '0%';
                    if (progressTextEl) progressTextEl.textContent = '0% Complete';
                    return;
                }
    
                // Normal data case
                const totalDays = data.TotalDays || 0;
                const presentDays = data.PresentDays || 0;
                const absentDays = data.AbsentDays || 0;
                const attendanceRate = data.AttendanceRate || 0;
    
                // Update elements
                if (totalDaysEl) totalDaysEl.textContent = totalDays;
                if (presentDaysEl) presentDaysEl.textContent = presentDays;
                if (absentDaysEl) absentDaysEl.textContent = absentDays;
    
                if (progressBarEl) {
                    progressBarEl.style.width = `${attendanceRate}%`;
                    progressBarEl.setAttribute('aria-valuenow', attendanceRate);
                }
    
                if (percentageEl) percentageEl.textContent = `${attendanceRate}%`;
                if (progressTextEl) progressTextEl.textContent = `${attendanceRate}% Complete`;
            })
            .catch(error => {
                console.error('Error loading attendance data:', error);
                
                // Show error state
                const totalDaysEl = document.getElementById('totalDays_emp');
                if (totalDaysEl) totalDaysEl.textContent = 'Error';
                
                const percentageEl = document.getElementById('attendancePercentage_emp');
                if (percentageEl) percentageEl.textContent = '0%';
            });
    }
    // Execute all components
    drawEmployeeMonthlyEarningsChart();
    loadEmployeePayrollSummary();
    loadEmployeeAttendanceSummary();
    // Initialize when page loads (traditional)
document.addEventListener('DOMContentLoaded', initializeEmployeeDashboard);

// For dynamic content (call manually when new content loads)
// initializeEmployeeDashboard();
}