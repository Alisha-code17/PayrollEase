<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../Resources/bootstrap/css/bootstrap.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.15/css/dataTables.bootstrap.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../Resources/css/attendance.css">
</head>

<body>
    <div class="container header-container">
        <h4>Employee Attendance</h4>
        <h6>Attendance &gt; Employee Attendance</h6>
    </div>
    
    <div class="container">
        <div class="row top-row">
            <!-- Search form -->
            <div class="col-md-6">
                <form id="searchForm" class="d-flex">
                    <div class="input-group search-box">
                        <input type="text" class="form-control" name="attsearch" id="searchAttendance"
                            placeholder="Search record by name and id">
                        <button class="btn btn-primary" type="button" id="searchBtn">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Mark Attendance button -->
            <div class="col-md-6 text-right">
                <button class="btn btn-primary mark-btn" type="button" data-toggle="modal"
                    data-target="#markAttendanceModal">
                    Mark Attendance
                </button>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="container">
        <div class="table-responsive table-box">
            <table class="table custom-table">
                <thead>
                    <tr>
                        <th>Attendance ID</th>
                        <th>Employee ID</th>
                        <th>Employee</th>
                        <th>Present Days</th>
                        <th>Absent Days</th>
                        <th>Overtime Hours</th>
                        <th>Month</th>
                        <th>Year</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php include 'attendance_fetch_data.php'; ?>  
                </tbody>
            </table>
        </div>
    </div>

    <!-- Scripts -->
    <script src="../Resources/js/jquery.min.js"></script>
    <script src="../Resources/bootstrap/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.15/js/dataTables.bootstrap.min.js"></script>
    <script src="../Resources/js/Table-with-search.js"></script>
</body>
</html>
