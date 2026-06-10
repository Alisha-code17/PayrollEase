<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payroll Profile List</title>
    <link rel="stylesheet" href="../Resources/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.15/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="../Resources/css/payrollProfile_list.css"> 
</head>

<body>
    <div class="container page-header">
        <h4>Payroll Profile List</h4>
        <h6 class="subtitle">Payroll Generation &gt;&nbsp;Payroll Profile List<br></h6>
    </div>

    <div class="container">
        <div class="table-responsive custom-table">
            <table class="table custom-bordered">
                <thead>
                    <tr class="table-header">
                        <th class="table-margin">PP_ID</th>
                        <th class="table-margin">All_1</th>
                        <th>All_1 Amt</th>
                        <th class="table-margin">All_2</th>
                        <th>All_2 Amt</th>
                        <th class="table-margin">All 3</th>
                        <th>All_3 Amt</th>
                        <th class="table-margin">Ded_1</th>
                        <th>Ded_1 Amt</th>
                        <th class="table-margin">Ded_2</th>
                        <th>Ded_2 Amt</th>
                        <th class="table-margin">Ded_3</th>
                        <th>Ded_3 Amt</th>
                    </tr>
                </thead>
                <tbody>
                    <?php include '../Controllers/payrollProfile_fetch_data.php'; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="../Resources/js/jquery.min.js"></script>
    <script src="../Resources/bootstrap/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.15/js/dataTables.bootstrap.min.js"></script>
    <script src="../Resources/js/Table-with-search.js"></script>
</body>

</html>
