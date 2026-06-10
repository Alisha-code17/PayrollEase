<?php
ob_start();
header('Content-Type: application/json');
include_once realpath(dirname(__FILE__) . '/../Database/db1.php');

if (!$conn) {
    echo json_encode(["error" => "Database connection failed"]);
    exit();
}
$data = [];

if (isset($_GET['type'])) {
    if ($_GET['type'] == 'allowance') {
        $query = "SELECT SalaryExtras_id, Name, Amount FROM salaryextras";
    } elseif ($_GET['type'] == 'deduction') {
        $query = "SELECT SalaryDeductions_id, Name, Amount FROM salarydeductions";
    } else {
        echo json_encode(["error" => "Invalid type"]);
        exit();
    }

    $result = $conn->query($query);
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

$conn->close();
ob_end_clean(); 
echo json_encode($data);
?>