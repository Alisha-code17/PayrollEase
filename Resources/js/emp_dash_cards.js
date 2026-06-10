	// <!--script for payroll earnings  chart-->
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('leaveAreaChart').getContext('2d');
    const noDataMessage = document.getElementById('noDataMessage'); // This div should exist in your HTML
    let chartInstance = null;

    function createChart(data) {
        if (chartInstance) {
            chartInstance.destroy();
        }

        chartInstance = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
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
                scales: {
                    y: {
                        beginAtZero: true,
                        min: data.every(val => val === 0) ? 0 : undefined,
                        max: data.every(val => val === 0) ? 10 : undefined,
                        title: {
                            display: false,
                            text: 'Earnings'
                        }
                    },
                    x: {
                        title: {
                            display: false,
                            text: 'Month'
                        }
                        
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    title: {
                        display: true,
                        text: 'Total Earnings (' + new Date().getFullYear() + ')',

                        font: {
                            size: 16
                        },
                        padding: {
                            top: 10,
                            bottom: 15
                        }
                    }
                }
            }
        });

        // Show or hide the "no data" message
        if (data.every(val => val === 0)) {
            noDataMessage.style.display = 'flex';
        } else {
            noDataMessage.style.display = 'none';
        }
    }

    fetch('../Controllers/get_monthly_earnings.php')
        .then(response => response.json())
        .then(monthlyData => {
            if (Array.isArray(monthlyData)) {
                createChart(monthlyData);
            } else {
                console.error("Invalid data format received.");
                createChart([0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]);
            }
        })
        .catch(error => {
            console.error("Error fetching earnings data:", error);
            createChart([0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]);
        });
});
// <!--script for emp dashboard card-->

document.addEventListener('DOMContentLoaded', function () {
    fetch('../Controllers/emp_payroll_dash.php')
        .then(res => res.json())
        .then(data => {
            if (data.error) {
                document.getElementById('basicSalary').textContent = "Not created yet";
                document.getElementById('bonus').textContent = "Not created yet";
                document.getElementById('totalAllowance').textContent = "Not created yet";
                document.getElementById('deductions').textContent = "Not created yet";
                document.getElementById('netSalary').textContent = "Not created yet";
            } else {
                document.getElementById('basicSalary').textContent = data.BasicSalary;
                document.getElementById('bonus').textContent = data.Bonus;
                document.getElementById('totalAllowance').textContent = data.TotalAllowance;
                document.getElementById('deductions').textContent = data.TotalDeductions;
                document.getElementById('netSalary').textContent = data.NetSalary;
            }
        });
});
//script for attendence chart
document.addEventListener('DOMContentLoaded', () => {
    fetch('../Controllers/att_emp_dash.php')
        .then(response => response.json())
        .then(data => {
            const totalDays = document.getElementById('totalDays');
            const presentDays = document.getElementById('presentDays');
            const absentDays = document.getElementById('absentDays');
            const progress = document.getElementById('attendanceProgress');
            const percentage = document.getElementById('attendancePercentage');
            const progressText = document.getElementById('attendanceText'); // Hidden span inside progress bar
            
            if (data.TotalDays === 'Not created yet') {
                totalDays.textContent = 'Not created yet';
                presentDays.textContent = 'Not created yet';
                absentDays.textContent = 'Not created yet';
                progress.style.width = '0%';
                progress.setAttribute('aria-valuenow', 0);
                percentage.textContent = '0%';
                if (progressText) progressText.textContent = '0% Complete';
            } else {
                totalDays.textContent = data.TotalDays;
                presentDays.textContent = data.PresentDays;
                absentDays.textContent = data.AbsentDays;

                const rate = data.AttendanceRate || 0;
                progress.style.width = `${rate}%`;
                progress.setAttribute('aria-valuenow', rate);
                percentage.textContent = `${rate}%`;
                if (progressText) progressText.textContent = `${rate}% Complete`;
            }
        })
        .catch(error => {
            console.error('Error fetching attendance data:', error);
        });
});