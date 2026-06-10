//  Function to Draw Employee Chart 
		let employeeChart; // Global variable for reuse

function drawEmployeeLollipopChart() {
    console.log("Initializing Employee Lollipop Chart...");

    const canvas = document.getElementById("employeeLollipopChart");
    if (!canvas) {
        console.error("Chart canvas not found!");
        return;
    }

    const ctx = canvas.getContext("2d");

    // Destroy previous chart if it exists
    if (employeeChart) {
        employeeChart.destroy();
    }

    // Fetch data from server
    fetch('get_employee_status_data.php')
        .then(response => {
            if (!response.ok) {
                throw new Error("Network response was not ok");
            }
            return response.json();
        })
        .then(data => {
            const activeCount = data.active || 0;
            const inactiveCount = data.inactive || 0;

            const employeeStatus = ["Active Employees", "Deactive Employees"];
            const employeeCount = [activeCount, inactiveCount];
            const colors = ["rgba(54, 162, 235, 0.7)", "rgba(255, 99, 132, 0.7)"];

            const dataPoints = employeeCount.map((value, index) => ({
                x: employeeStatus[index],
                y: value
            }));

            employeeChart = new Chart(ctx, {
                type: "scatter",
                data: {
                    datasets: [{
                        label: "",
                        data: dataPoints,
                        pointBackgroundColor: colors,
                        pointBorderColor: colors,
                        pointRadius: 8,
                        pointHoverRadius: 10,
                        showLine: false
                    }, {
                        type: "bar",
                        data: employeeCount.map((value, index) => ({
                            x: employeeStatus[index],
                            y: value
                        })),
                        backgroundColor: colors.map(c => c.replace("0.7", "0.3")),
                        borderColor: colors,
                        borderWidth: 2,
                        barThickness: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            type: "category",
                            labels: employeeStatus
                        },
                        y: {
                            beginAtZero: true,
                            max: Math.max(...employeeCount) + 10
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });

            console.log("✅ Chart drawn with live data:", employeeCount);
        })
        .catch(error => {
            console.error("❌ Error loading employee chart data:", error);
        });
}

				//attendance chart
// Attendance chart
let attendanceChart;

function drawAttendanceLineChart() {
    const canvas = document.getElementById("Attendance_lineChart");
    if (!canvas) {
        console.error("❌ Canvas with ID 'Attendance_lineChart' is missing!");
        return;
    }

    setTimeout(function () {
        canvas.width = 400;
        canvas.height = 300;

        const ctx = canvas.getContext("2d");
        if (!ctx) {
            console.error("❌ Cannot get 2D context!");
            return;
        }

        // ✅ Fetch data from backend
        $.ajax({
            url: "get_attendance_report_data.php", // This must return percentage values like [78.5, 88, 90.3, ...]
            method: "GET",
            dataType: "json",
            success: function (attendanceData) {
                const months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
                                "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

                if (attendanceChart) {
                    attendanceChart.destroy();
                }

                attendanceChart = new Chart(ctx, {
                    type: "line",
                    data: {
                        labels: months,
                        datasets: [{
                            label: "Monthly Attendance (2025)",
                            data: attendanceData, // should be percentage values like 25, 45.5, 76
                            borderColor: "blue",
                            backgroundColor: "rgba(0, 0, 255, 0.2)",
                            fill: true,
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: false,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 100,
                                ticks: {
                                    callback: function(value) {
                                        return value + "%";
                                    },
                                    stepSize: 25 // To show 0%, 25%, 50%, 75%, 100%
                                },
                                title: {
                                    display: true,
                                    text: "Attendance (%)"
                                }
                            }
                        },
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return context.dataset.label + ": " + context.parsed.y + "%";
                                    }
                                }
                            }
                        }
                    }
                });

                console.log("✅ Chart rendered with percentage data.");
            },
            error: function (xhr, status, error) {
                console.error("❌ Failed to load attendance data:", error);
            }
        });
    }, 500);
}

$(document).ready(function () {
    drawAttendanceLineChart();
    console.log("Chart successfully drawn!");
});


	console.log("Chart successfully drawn!");
    //leave chart
	let leaveChart;

function drawLeaveReportChart() {
    const canvas = document.getElementById("leave_barChart");
    if (!canvas) {
        console.error("❌ Canvas with ID 'leave_barChart' is missing!");
        return;
    }

    const ctx = canvas.getContext("2d");
    if (!ctx) {
        console.error("❌ Unable to get 2D context for canvas!");
        return;
    }

    // Destroy previous chart
    if (leaveChart) {
        leaveChart.destroy();
    }

    fetch("get_monthly_leave_data.php")
        .then(response => {
            if (!response.ok) {
                throw new Error("Failed to fetch leave data.");
            }
            return response.json();
        })
        .then(leaveData => {
            const months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
                            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

            const barColors = [
                "rgba(255, 99, 132, 0.8)", "rgba(54, 162, 235, 0.8)", "rgba(75, 192, 192, 0.8)",
                "rgba(255, 206, 86, 0.8)", "rgba(153, 102, 255, 0.8)", "rgba(255, 159, 64, 0.8)",
                "rgba(255, 99, 255, 0.8)", "rgba(102, 255, 204, 0.8)", "rgba(255, 204, 153, 0.8)",
                "rgba(204, 255, 102, 0.8)", "rgba(102, 178, 255, 0.8)", "rgba(192, 192, 192, 0.8)"
            ];

            const maxY = Math.max(...leaveData, 10) + 5;

            leaveChart = new Chart(ctx, {
                type: "bar",
                data: {
                    labels: months,
                    datasets: [{
                        data: leaveData,
                        backgroundColor: barColors,
                        borderColor: "rgba(0, 0, 0, 0.1)",
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: maxY,
                            ticks: {
                                stepSize: 1,
                                callback: value => Number.isInteger(value) ? value : ''
                            }
                        }
                    }
                }
            });

            console.log("✅ Dynamic Leave Chart drawn:", leaveData);
        })
        .catch(error => {
            console.error("❌ Error loading leave chart data:", error);
        });
}

// ✅ Only run after the page is loaded
$(document).ready(function () {
    drawLeaveReportChart();
});

    console.log("Chart successfully drawn!");
	
	// ✅ Function to Initialize att- Search
function initialize_emp_Search() {
    console.log("🔍 Employee Report Search Loaded");

    function fetchData() {
        var searchValue = $("#search").val();  // Capture text search value
        var selectedDate = $("#joiningDate").val(); // Capture selected date
        var selectedYear = "", selectedMonth = "";

        // Extract year and month from selected date
        if (selectedDate) {
            var dateParts = selectedDate.split("-");
            selectedYear = dateParts[0];  // Extract Year
            selectedMonth = dateParts[1]; // Extract Month
        }

        $.ajax({
            url: "emp-search.php",
            type: "POST",
            data: { 
                search: searchValue, 
                year: selectedYear, 
                month: selectedMonth 
            },
            success: function (response) {
                $("tbody").html(response); // Updates table without reload
            }
        });
    }

    // ✅ Attach event listeners only when `employee-report.php` is loaded
    $("#search").on("focus", fetchData); // Trigger when user enters search field
    $("#search").on("keyup", fetchData); // Trigger search on typing
    $("#joiningDate").on("change", fetchData); // Trigger search on date change
}

//to initializa-leave-search
function initialize_leave_Search() {
    console.log("🔍 leave Report Search Loaded");
    function fetchData() {
        var searchValue = $("#search").val();  // Capture text search value
        var selectedDate = $("#joiningDate").val(); // Capture selected date
        var selectedYear = "", selectedMonth = "";

        // Extract year and month from selected date
        if (selectedDate) {
            var dateParts = selectedDate.split("-");
            selectedYear = dateParts[0];  // Extract Year
            selectedMonth = dateParts[1]; // Extract Month
        }

        $.ajax({
            url: "leave-search.php",
            type: "POST",
            data: { 
                search: searchValue, 
                year: selectedYear, 
                month: selectedMonth 
            },
            success: function (response) {
                $("tbody").html(response); // Updates table without reload
            }
        });
    }

    $("#search").on("keyup", fetchData); // When user types, fetch data
    $("#joiningDate").on("change", fetchData); // When date changes, fetch data
};
//reprts charts
//leave chart
function initialize_att_Search() {
    console.log("🔍 att Report Search Loaded");
    function fetchData() {
        var searchValue = $("#search").val();  // Capture text search value
        var selectedDate = $("#joiningDate").val(); // Capture selected date
        var selectedYear = "", selectedMonth = "";

        // Extract year and month from selected date
        if (selectedDate) {
            var dateParts = selectedDate.split("-");
            selectedYear = dateParts[0];  // Extract Year
            selectedMonth = dateParts[1]; // Extract Month
        }

        $.ajax({
            url: "att-search.php",
            type: "POST",
            data: { 
                search: searchValue, 
                year: selectedYear, 
                month: selectedMonth 
            },
            success: function (response) {
                $("tbody").html(response); // Updates table without reload
            }
        });
    }

    $("#search").on("keyup", fetchData); // When user types, fetch data
    $("#joiningDate").on("change", fetchData); // When date changes, fetch data
};