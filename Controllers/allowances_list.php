<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Allowances List</title>
    <link rel="stylesheet" href="../Resources/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.15/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="../Resources/css/allowance_list.css"> 
</head>

<body>
    <div class="container page-header">
        <h4>Allowances List</h4>
        <h6 class="subtitle">Adjustments &gt; Allowances List<br></h6>
    </div>

    <div class="container">
        <div class="table-responsive custom-table">
            <table class="table custom-bordered">
                <thead>
                    <tr class="table-header">
                        <th style="width:150px;">Allowance ID</th>
                        <th>Allowance Type</th>
                        <th>Description</th>
                        <th>Amount</th>
                        <th class="col-action">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php include '../Controllers/allowance_fetch_data.php'; ?>
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
