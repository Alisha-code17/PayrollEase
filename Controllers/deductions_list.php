<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deductions List</title>
    <link rel="stylesheet" href="../Resources/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.15/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="../Resources/css/deduction_list.css"> 
</head>

<body>
    <div class="container header-container">
        <h4>Deductions List</h4>
        <h6>Adjustments &gt; Deductions List<br></h6>
    </div>

    <div class="container">
        <div class="table-responsive custom-table">
            <table class="table">
                <thead>
                    <tr class="table-header">
                        <th class="col-id">Deduction ID</th>
                        <th class="col-type">Deduction Type</th>
                        <th>Description</th>
                        <th class="col-amount">Amount</th>
                        <th class="col-action">Action</th>
                    </tr>
                </thead>
                <tbody class="table-body">
                    <?php include '../Controllers/deduction_fetch_data.php'; ?>
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
